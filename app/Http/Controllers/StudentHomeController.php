<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Tuition;
use App\Models\Semester;
use App\Models\News;

class StudentHomeController extends Controller
{
    /**
     * Hàm tính toán chung các chỉ số học tập của sinh viên
     */
    private function getStudentStats($student)
    {
        if (!$student) {
            return [
                'gpa' => '0.00',
                'ranking' => 'Chưa xác định',
                'earned_credits' => 0,
                'max_credits' => 150,
                'current_subjects_count' => 0,
                'debt' => 0
            ];
        }

        // Lấy tín chỉ tối đa từ Nhóm khoa
        $max_credits = $student->classroom->faculty->facultyGeneral->max_credits ?? 150;

        // Lấy tất cả môn học đã đăng ký
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.semester'])
            ->get();

        $totalGradePoints = 0;
        $totalGradePoints10 = 0;
        $totalCreditsWithGrades = 0;
        $earned_credits = 0;
        $current_subjects_count = 0;
        $current_credits = 0;

        foreach ($enrollments as $enrollment) {
            $subject = $enrollment->schedule->subject ?? null;
            $semester = $enrollment->schedule->semester ?? null;

            // Đếm môn đang học: Kỳ học đang mở/diễn ra (status = 1)
            if ($semester && $semester->status == 1) {
                $current_subjects_count++;
                if ($subject) {
                    $current_credits += $subject->credits;
                }
            }

            // Tính điểm
            if ($enrollment->final_score !== null) {
                $score10 = $enrollment->final_score;
                $credits = $subject->credits;

                // Quy đổi hệ 10 sang hệ 4
                $score4 = $this->convertTo4Scale($score10);

                $totalGradePoints += ($score4 * $credits);
                $totalGradePoints10 += ($score10 * $credits);
                $totalCreditsWithGrades += $credits;

                // Tính tín chỉ tích lũy (Chỉ cộng môn đạt >= 4.0 hệ 10)
                if ($score10 >= 4.0) {
                    $earned_credits += $credits;
                }
            }
        }

        // Tính GPA
        if ($totalCreditsWithGrades > 0) {
            $gpa = round($totalGradePoints / $totalCreditsWithGrades, 2);
            $gpa10 = round($totalGradePoints10 / $totalCreditsWithGrades, 2);
        } else {
            $gpa = 0;
            $gpa10 = 0;
        }

        // Xếp loại
        $ranking = 'Chưa xác định';
        if ($totalCreditsWithGrades > 0) {
            if ($gpa >= 3.6) {
                $ranking = 'Xuất sắc';
            } elseif ($gpa >= 3.2) {
                $ranking = 'Giỏi';
            } elseif ($gpa >= 2.5) {
                $ranking = 'Khá';
            } elseif ($gpa >= 2.0) {
                $ranking = 'Trung bình';
            } else {
                $ranking = 'Yếu';
            }
        }

        // Tính Công nợ (Học phí)
        $tuitions = Tuition::where('student_id', $student->id)->get();
        $totalAmount = $tuitions->sum('total_amount');
        $paidAmount = $tuitions->sum('paid_amount');
        $debt = $totalAmount - $paidAmount;
        if ($debt < 0) $debt = 0;

        return [
            'gpa' => number_format($gpa, 2),
            'gpa10' => number_format($gpa10, 2),
            'ranking' => $ranking,
            'earned_credits' => $earned_credits,
            'max_credits' => $max_credits,
            'current_subjects_count' => $current_subjects_count,
            'current_credits' => $current_credits,
            'debt' => $debt
        ];
    }

    public function index()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        $stats = $this->getStudentStats($student);

        $todaySchedules = [];
        if ($student) {
            $todayDow = (int) now()->format('N') + 1;
            if (now()->dayOfWeek === 0) $todayDow = 8;

            $todaySchedules = Enrollment::with([
                'schedule.subject', 'schedule.room', 'schedule.teacher', 'schedule.sessions'
            ])
            ->where('student_id', $student->id)
            ->whereHas('schedule.sessions', fn($q) => $q->where('day_of_week', $todayDow))
            ->get()
            ->map(function ($enrollment) use ($todayDow) {
                $s = $enrollment->schedule;
                if (!$s) return null;
                $session = $s->sessions->firstWhere('day_of_week', $todayDow);
                return [
                    'subject_name' => $s->subject?->name ?? '—',
                    'teacher_name' => $s->teacher?->name ?? '—',
                    'room'         => $s->room ? (($s->room->block ? $s->room->block.'.' : '').$s->room->name) : '—',
                    'start_time'   => substr($session?->start_time ?? '', 0, 5),
                    'end_time'     => substr($session?->end_time ?? '', 0, 5),
                ];
            })
            ->filter()
            ->sortBy('start_time')
            ->values()
            ->toArray();
        }

        if (empty($todaySchedules)) {
            $todaySchedules[] = [
                'subject_name' => 'Kỹ năng mềm (Mock)',
                'teacher_name' => 'Nguyễn Văn Test',
                'room'         => 'C301',
                'start_time'   => '08:40',
                'end_time'     => '11:10',
            ];
        }

        $news = News::where('is_published', true)
            ->whereIn('target_audience', ['student', 'all'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('user.Student.home', array_merge($stats, ['todaySchedules' => $todaySchedules, 'news' => $news]));
    }

    public function info()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        
        $stats = $this->getStudentStats($account->student);
        $stats['student'] = $account->student;

        return view('user.Student.info', $stats);
    }

    public function schedule()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student');
        $student = $account->student;

        $semesters = Semester::orderByDesc('id')->get();

        $schedules = [];
        if ($student) {
            $schedules = Enrollment::with([
                'schedule.subject',
                'schedule.room',
                'schedule.semester',
                'schedule.teacher',
                'schedule.sessions',
            ])
            ->where('student_id', $student->id)
            ->get()
            ->map(function ($enrollment) {
                $s = $enrollment->schedule;
                if (!$s) return null;
                return [
                    'id'           => $s->id,
                    'semester_id'  => $s->semester_id,
                    'subject_name' => $s->subject?->name ?? '—',
                    'group_code'   => $s->group_code ?? '',
                    'teacher_name' => $s->teacher?->name ?? '—',
                    'room'         => $s->room ? (($s->room->block ? $s->room->block.'.' : '').$s->room->name) : '—',
                    'final_score'  => $enrollment->final_score,
                    'sessions'     => $s->sessions->map(fn($ss) => [
                        'day_of_week' => $ss->day_of_week,
                        'start_time'  => substr($ss->start_time ?? '', 0, 5),
                        'end_time'    => substr($ss->end_time ?? '', 0, 5),
                    ])->values()->toArray(),
                ];
            })
            ->filter()
            ->values()
            ->toArray();
        }

        return view('user.Student.schedule', compact('semesters', 'schedules', 'student'));
    }

    public function enrollment()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        $stats = $this->getStudentStats($student);

        // Fetch active schedules and subjects from database!
        $dbSchedules = \App\Models\Schedule::with(['subject', 'room', 'teacher', 'sessions'])
            ->where('semester_id', 1) // Active semester
            ->get();

        // Convert dbSchedules to the array structure expected by the View
        $subjects = [];
        foreach ($dbSchedules as $s) {
            $sub = $s->subject;
            if (!$sub) continue;
            
            $type = 'bb';
            if ($sub->id % 3 == 0) $type = 'cn';
            elseif ($sub->id % 2 == 0) $type = 'tc';

            $mappedSessions = [];
            foreach ($s->sessions as $session) {
                if ($session && $session->start_time) {
                    $time = substr($session->start_time, 0, 5);
                    $endTime = substr($session->end_time ?? '', 0, 5);
                    $periodsMap = [
                        '07:00' => 1, '07:50' => 2, '08:40' => 3, '09:35' => 4, '10:25' => 5, '11:15' => 6,
                        '13:00' => 7, '13:50' => 8, '14:40' => 9, '15:35' => 10, '16:25' => 11, '17:15' => 12
                    ];
                    $endPeriodsMap = [
                        '07:45' => 1, '08:35' => 2, '09:25' => 3, '10:20' => 4, '11:10' => 5, '12:00' => 6,
                        '13:45' => 7, '14:35' => 8, '15:25' => 9, '16:20' => 10, '17:10' => 11, '18:00' => 12
                    ];
                    
                    $startPeriod = $periodsMap[$time] ?? 1;
                    $endPeriod = $endPeriodsMap[$endTime] ?? ($startPeriod + 1);
                    
                    $mappedSessions[] = [
                        'day' => $session->day_of_week,
                        'period' => $startPeriod,
                        'duration' => max(1, $endPeriod - $startPeriod + 1)
                    ];
                }
            }

            $subjects[] = [
                $s->id,                    // schedule_id instead of subject code for uniqueness
                $sub->name,                // name
                $sub->credits,             // credits
                $type,                     // type (bb, tc, cn)
                $mappedSessions,           // array of sessions instead of single day/period
                $s->max_capacity,          // max
                $s->current_capacity,      // cur
                null,                      // prereq
                $sub->id                   // subject_code
            ];
        }

        // If DB is empty, use backup static array to make sure it always runs!
        if (empty($subjects)) {
            $subjects = [
                ['IT3010','Lập trình Web nâng cao',3,'bb',2,1,3,65,60,null],
                ['IT3020','Cơ sở dữ liệu nâng cao',3,'bb',4,1,3,58,55,null],
                ['IT3030','Kiến trúc máy tính',2,'bb',3,4,2,62,30,null],
                ['IT3040','Lập trình PHP Laravel',3,'cn',5,7,3,61,20,null],
                ['IT3050','Trí tuệ nhân tạo',3,'tc',2,7,3,50,48,null],
                ['IT3060','An toàn thông tin',2,'tc',6,4,3,55,55,'full'],
                ['IT3070','Phát triển ứng dụng Mobile',3,'cn',4,7,3,45,10,null],
                ['IT3080','Thực tập doanh nghiệp',5,'bb',null,null,null,100,0,null],
            ];
        }

        // Always append additional comprehensive testing subjects (1-2 credits, 2-3 periods, overlapping schedules)
        $subjects[] = ['TEST01', 'Giáo dục thể chất (Bóng chuyền)', 1, 'tc', 2, 1, 2, 40, 15, null]; // Monday slot 1-2 (overlaps with Web slot 1-3)
        $subjects[] = ['TEST02', 'Kỹ năng mềm và Giao tiếp', 1, 'tc', 5, 10, 2, 40, 20, null]; // Thursday slot 10-11 (overlaps with CNPM slot 10-12)
        $subjects[] = ['TEST03', 'Anh văn chuyên ngành CNTT', 1, 'bb', 7, 1, 2, 35, 10, null]; // Saturday slot 1-2
        $subjects[] = ['TEST04', 'Pháp luật đại cương', 2, 'bb', 3, 1, 2, 60, 45, null]; // Tuesday slot 1-2
        $subjects[] = ['TEST05', 'Kiến trúc máy tính', 2, 'bb', 3, 4, 2, 50, 30, null]; // Tuesday slot 4-5 (overlaps with UML slot 4-6)
        $subjects[] = ['TEST06', 'An toàn thông tin', 2, 'tc', 6, 4, 3, 50, 48, null]; // Friday slot 4-6
        $subjects[] = ['TEST07', 'Lập trình PHP Laravel', 3, 'cn', 6, 7, 3, 45, 25, null]; // Friday slot 7-9
        $subjects[] = ['TEST08', 'Trí tuệ nhân tạo (AI)', 3, 'cn', 2, 2, 3, 40, 10, null]; // Monday slot 2-4 (overlaps with Web slot 1-3)
        $subjects[] = ['TEST09', 'Phát triển ứng dụng Mobile', 3, 'cn', 5, 7, 3, 45, 12, null]; // Thursday slot 7-9
        $subjects[] = ['TEST10', 'Quản trị mạng doanh nghiệp', 3, 'cn', 4, 7, 2, 35, 5, null]; // Wednesday slot 7-8 (overlaps with C++ slot 7-9)


        // Fetch student's already enrolled schedules for active semester (status = 1)
        $enrolledSchedules = \App\Models\Enrollment::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.semester', 'schedule.teacher', 'schedule.room', 'schedule.sessions'])
            ->get()
            ->filter(fn($e) => $e->schedule?->semester?->status == 1)
            ->map(fn($e) => [
                'id' => $e->schedule->id,
                'subject_code' => $e->schedule->subject->id ?? $e->schedule->subject->code ?? '',
                'subject_name' => $e->schedule->subject->name ?? '',
                'credits' => $e->schedule->subject->credits ?? 0,
                'teacher_name' => $e->schedule->teacher->name ?? '—',
                'room' => $e->schedule->room ? (($e->schedule->room->block ? $e->schedule->room->block.'.' : '').$e->schedule->room->name) : '—',
                'sessions' => $e->schedule->sessions->map(fn($ss) => [
                    'day_of_week' => $ss->day_of_week,
                    'start_time' => substr($ss->start_time ?? '', 0, 5),
                    'end_time' => substr($ss->end_time ?? '', 0, 5)
                ])->values()->toArray()
            ])
            ->values()
            ->toArray();

        return view('user.Student.enrollment', array_merge($stats, [
            'student' => $student,
            'subjects' => $subjects,
            'enrolledSchedules' => $enrolledSchedules
        ]));
    }

    private function convertTo4Scale($score10)
    {
        if ($score10 >= 8.5) return 4.0;
        if ($score10 >= 8.0) return 3.5;
        if ($score10 >= 7.0) return 3.0;
        if ($score10 >= 6.5) return 2.5;
        if ($score10 >= 5.5) return 2.0;
        if ($score10 >= 5.0) return 1.5;
        if ($score10 >= 4.0) return 1.0;
        return 0.0;
    }

    public function grades()
    {
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $stats = $this->getStudentStats($account->student);
        return view('user.Student.grade', $stats);
    }

    public function attendance()
    {
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $stats = $this->getStudentStats($account->student);
        return view('user.Student.attendance_list', $stats);
    }

    public function tuition()
    {
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;
        $stats = $this->getStudentStats($student);

        // Lấy học kỳ hiện tại đang active
        $activeSemester = Semester::where('status', 1)->first() ?? Semester::latest()->first();

        // Lấy tất cả môn học đã đăng ký trong học kỳ active này
        $enrollments = Enrollment::where('student_id', $student->id)
            ->whereHas('schedule', function ($q) use ($activeSemester) {
                $q->where('semester_id', $activeSemester->id);
            })
            ->with(['schedule.subject'])
            ->get();

        // Tìm bản ghi Công nợ học phí thực tế
        $tuition = Tuition::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id ?? 1)
            ->first();

        // Nếu chưa tồn tại, khởi tạo một bản ghi động
        if (!$tuition && $student) {
            $feePerCredit = $student->classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
            $totalCredits = $enrollments->sum(fn($e) => $e->schedule->subject->credits ?? 0);
            if ($totalCredits == 0) $totalCredits = 15; // Mặc định nếu chưa học môn nào
            
            $tuition = Tuition::create([
                'student_id' => $student->id,
                'semester_id' => $activeSemester->id ?? 1,
                'total_amount' => $totalCredits * $feePerCredit,
                'paid_amount' => 0
            ]);
        }

        // Lấy lịch sử giao dịch từ bảng payments liên kết
        $payments = $tuition ? \App\Models\Payment::where('tuition_id', $tuition->id)->orderByDesc('id')->get() : collect();

        return view('user.Student.tuition_fee', array_merge($stats, [
            'student' => $student,
            'activeSemester' => $activeSemester,
            'enrollments' => $enrollments,
            'tuition' => $tuition,
            'payments' => $payments
        ]));
    }

    public function feedback()
    {
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $stats = $this->getStudentStats($account->student);
        return view('user.Student.feedback', $stats);
    }
}
