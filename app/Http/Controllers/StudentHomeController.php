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
            ->with(['schedule.subject', 'schedule.semester', 'grade'])
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
            if ($enrollment->grade?->final_score !== null) {
                $score10 = $enrollment->grade->final_score;
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
                'schedule.subject',
                'schedule.sessions.room',
                'schedule.teacher',
                'schedule.sessions'
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
                        'room'         => $session?->room ? (($session->room->block ? $session->room->block . '.' : '') . $session->room->name) : '—',
                        'start_time'   => substr($session?->start_time ?? '', 0, 5),
                        'end_time'     => substr($session?->end_time ?? '', 0, 5),
                    ];
                })
                ->filter()
                ->sortBy('start_time')
                ->values()
                ->toArray();
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
                'schedule.semester',
                'schedule.teacher',
                'schedule.sessions.room',
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
                        'final_score'  => $enrollment->grade?->final_score,
                        'sessions'     => $s->sessions->map(fn($ss) => [
                            'day_of_week' => $ss->day_of_week,
                            'start_time'  => substr($ss->start_time ?? '', 0, 5),
                            'end_time'    => substr($ss->end_time ?? '', 0, 5),
                            'room'        => $ss->room ? (($ss->room->block ? $ss->room->block . '.' : '') . $ss->room->name) : '—',
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
        $activeSemester = \App\Models\Semester::where('status', 1)->first() ?? \App\Models\Semester::latest()->first();
        $dbSchedules = \App\Models\Schedule::with(['subject', 'sessions.room', 'teacher', 'sessions'])
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id))
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
                    $startPeriod = $this->parseTimeSlot($session->start_time);
                    $endPeriod = $session->end_time ? $this->parseTimeSlot($session->end_time, true) : ($startPeriod + 1);

                    $mappedSessions[] = [
                        'day' => $session->day_of_week,
                        'period' => $startPeriod,
                        'duration' => max(1, $endPeriod - $startPeriod + 1)
                    ];
                }
            }

            $subjects[] = [
                $s->id,                    // 0: schedule_id
                $sub->name,                // 1: name
                $sub->credits,             // 2: credits
                $type,                     // 3: type (bb, tc, cn)
                $mappedSessions,           // 4: sessions
                $s->max_capacity,          // 5: max
                $s->current_capacity,      // 6: cur
                $s->group_code ?? 'N01',   // 7: group_code
                $sub->code ?? $sub->id     // 8: subject_code
            ];
        }



        // Fetch student's already enrolled schedules for active semester (status = 1)
        $enrolledSchedules = \App\Models\Enrollment::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.semester', 'schedule.teacher', 'schedule.sessions.room', 'schedule.sessions'])
            ->get()
            ->filter(fn($e) => $e->schedule?->semester?->status == 1)
            ->map(fn($e) => [
                'id' => $e->schedule->id,
                'subject_code' => $e->schedule->subject->code ?? $e->schedule->subject->id ?? '',
                'subject_name' => $e->schedule->subject->name ?? '',
                'credits' => $e->schedule->subject->credits ?? 0,
                'teacher_name' => $e->schedule->teacher->name ?? '—',
                'room' => $e->schedule->room_names,
                'sessions' => $e->schedule->sessions->map(fn($ss) => [
                    'day_of_week' => $ss->day_of_week,
                    'start_time' => substr($ss->start_time ?? '', 0, 5),
                    'end_time' => substr($ss->end_time ?? '', 0, 5)
                ])->values()->toArray()
            ])
            ->values()
            ->toArray();

        // Determine registration availability from Database SystemConfig
        $registrationOpen = (\App\Models\SystemConfig::getValue('is_registration_open', '1') === '1');

        return view('user.Student.enrollment', array_merge($stats, [
            'student' => $student,
            'subjects' => $subjects,
            'enrolledSchedules' => $enrolledSchedules,
            'registrationOpen' => $registrationOpen
        ]));
    }

    public function submitEnrollment(Request $request)
    {
        // Check if enrollment feature is enabled from Database SystemConfig
        $enabled = (\App\Models\SystemConfig::getValue('is_registration_open', '1') === '1');
        if (!$enabled) {
            return response()->json(['success' => false, 'message' => 'Đăng ký môn học đã đóng. Vui lòng thử lại sau.'], 403);
        }

        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Lỗi xác thực sinh viên.'], 403);
        }

        $scheduleIds = $request->input('schedule_ids', []);

        if (empty($scheduleIds)) {
            return response()->json(['success' => false, 'message' => 'Chưa chọn học phần nào.'], 400);
        }

        // Lấy học kỳ hiện tại đang active
        $activeSemester = Semester::where('status', 1)->first() ?? Semester::latest()->first();
        if (!$activeSemester) {
            return response()->json(['success' => false, 'message' => 'Không có học kỳ nào đang mở.'], 400);
        }

        // Tự động sửa cấu hình cột tuition_id thành nullable đề phòng lỗi DB schema
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE enrollments MODIFY tuition_id BIGINT UNSIGNED NULL DEFAULT NULL");
        } catch (\Exception $e) {
            // Bỏ qua nếu lỗi
        }

        // 1. Tạo phiếu học phí (Tuition) cho học kỳ này vì sinh viên đã bắt đầu đăng ký môn học
        $tuition = Tuition::firstOrCreate([
            'student_id'  => $student->id,
            'semester_id' => $activeSemester->id,
        ], [
            'total_amount' => 0,
            'paid_amount'  => 0
        ]);

        // 2. Tạo bản ghi Enrollment cho từng môn
        foreach ($scheduleIds as $scheduleId) {
            // Kiểm tra xem đã đăng ký chưa
            $exists = Enrollment::where('student_id', $student->id)
                ->where('schedule_id', $scheduleId)
                ->exists();

            if (!$exists) {
                $schedule = \App\Models\Schedule::find($scheduleId);
                if ($schedule) {
                    // Tự động đếm sĩ số thực tế để sửa lỗi lệch sĩ số nếu có
                    $actualEnrolledCount = Enrollment::where('schedule_id', $scheduleId)->count();
                    
                    if ($actualEnrolledCount < $schedule->max_capacity) {
                        Enrollment::create([
                            'student_id'  => $student->id,
                            'schedule_id' => $scheduleId,
                            'tuition_id'  => $tuition->id, // Gán để lưu đúng công nợ
                            'status'      => 1
                        ]);
                        
                        $schedule->current_capacity = $actualEnrolledCount + 1;
                        $schedule->save();
                    } else {
                        // Rollback everything or just return error immediately
                        return response()->json([
                            'success' => false,
                            'message' => 'Lớp học phần "' . ($schedule->subject->name ?? 'Không xác định') . '" đã đạt số lượng đăng ký tối đa. Vui lòng chọn môn khác.'
                        ], 400);
                    }
                }
            } else {
                // Nếu đã đăng ký nhưng cột tuition_id bị null thì cập nhật lại
                Enrollment::where('student_id', $student->id)
                    ->where('schedule_id', $scheduleId)
                    ->whereNull('tuition_id')
                    ->update(['tuition_id' => $tuition->id]);
            }
        }

        // 3. Lấy thông tin học phí mỗi tín chỉ một cách chắc chắn trực tiếp từ database
        $feePerCredit = 480000; // Mặc định
        if ($student->classroom_id) {
            $classroom = \App\Models\Classroom::with('faculty.facultyGeneral')->find($student->classroom_id);
            if ($classroom && $classroom->faculty && $classroom->faculty->facultyGeneral) {
                $feePerCredit = $classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
            }
        }

        // 4. Lấy tất cả môn học đã đăng ký trong học kỳ active này
        $allEnrollments = Enrollment::where('student_id', $student->id)
            ->whereHas('schedule', function ($q) use ($activeSemester) {
                $q->where('semester_id', $activeSemester->id);
            })
            ->with(['schedule.subject'])
            ->get();

        $totalCredits = $allEnrollments->sum(fn($e) => $e->schedule->subject->credits ?? 0);
        
        $tuition->total_amount = $totalCredits * $feePerCredit;
        $tuition->save();

        // Ghi log để chẩn đoán nếu cần
        \Illuminate\Support\Facades\Log::info('HocPhiDebug: ', [
            'student_id' => $student->id,
            'fee_per_credit' => $feePerCredit,
            'total_credits' => $totalCredits,
            'total_amount' => $tuition->total_amount
        ]);

        // 5. Đồng bộ tất cả enrollment khác của kỳ này nếu còn thiếu tuition_id hoặc không khớp
        Enrollment::where('student_id', $student->id)
            ->where(function($query) use ($tuition) {
                $query->whereNull('tuition_id')
                      ->orWhere('tuition_id', 0)
                      ->orWhere('tuition_id', '!=', $tuition->id);
            })
            ->whereHas('schedule', function ($q) use ($activeSemester) {
                $q->where('semester_id', $activeSemester->id);
            })
            ->update(['tuition_id' => $tuition->id]);

        return response()->json(['success' => true, 'message' => 'Đăng ký thành công!']);
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
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return redirect()->route('user.login')->with('error', 'Tài khoản chưa được cấu hình thông tin sinh viên.');
        }

        $stats = $this->getStudentStats($student);
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.semester', 'grade'])
            ->get();

        $enrollmentsBySemester = $enrollments->groupBy(function ($e) {
            $sem = $e->schedule?->semester;
            return $sem ? $sem->name . ($sem->academic_year ? ' – ' . $sem->academic_year : '') : 'Khác';
        });

        return view('user.Student.grade', array_merge($stats, [
            'enrollmentsBySemester' => $enrollmentsBySemester,
            'enrollments' => $enrollments
        ]));
    }

    public function attendance()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return redirect()->route('user.login')->with('error', 'Tài khoản chưa được cấu hình thông tin sinh viên.');
        }

        $stats = $this->getStudentStats($student);
        $enrollments = Enrollment::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.teacher', 'attendances.scheduleSession'])
            ->get();

        return view('user.Student.attendance_list', array_merge($stats, [
            'enrollments' => $enrollments
        ]));
    }

    public function tuition()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return redirect()->route('user.login')->with('error', 'Tài khoản chưa được cấu hình thông tin sinh viên.');
        }

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

        // Tìm bản ghi học phí thực tế
        $tuition = Tuition::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        // Tự động sửa/đồng bộ nếu đã đăng ký môn nhưng chưa có phiếu học phí hoặc tiền bị lệch
        if ($enrollments->count() > 0) {
            if (!$tuition) {
                $tuition = Tuition::create([
                    'student_id'   => $student->id,
                    'semester_id'  => $activeSemester->id,
                    'total_amount' => 0,
                    'paid_amount'  => 0
                ]);
            }

            // Lấy học phí mỗi tín từ DB một cách chắc chắn
            $feePerCredit = 480000;
            if ($student->classroom_id) {
                $classroom = \App\Models\Classroom::with('faculty.facultyGeneral')->find($student->classroom_id);
                if ($classroom && $classroom->faculty && $classroom->faculty->facultyGeneral) {
                    $feePerCredit = $classroom->faculty->facultyGeneral->tuition_fee_per_credit ?? 480000;
                }
            }

            $totalCredits = $enrollments->sum(fn($e) => $e->schedule->subject->credits ?? 0);
            $expectedTotalAmount = $totalCredits * $feePerCredit;

            if ($tuition->total_amount != $expectedTotalAmount) {
                $tuition->total_amount = $expectedTotalAmount;
                $tuition->save();
            }

            // Gán tuition_id cho các bản ghi đăng ký nếu bị null hoặc không khớp
            Enrollment::where('student_id', $student->id)
                ->where(function($query) use ($tuition) {
                    $query->whereNull('tuition_id')
                          ->orWhere('tuition_id', 0)
                          ->orWhere('tuition_id', '!=', $tuition->id);
                })
                ->whereHas('schedule', function ($q) use ($activeSemester) {
                    $q->where('semester_id', $activeSemester->id);
                })
                ->update(['tuition_id' => $tuition->id]);
        }

        // Lấy lịch sử giao dịch từ bảng payments liên kết
        $payments = $tuition ? \App\Models\Payment::where('tuition_id', $tuition->id)->orderByDesc('id')->get() : collect();

        // Lấy tất cả phiếu học phí qua các học kỳ để hiển thị lịch sử
        $allTuitions = Tuition::where('student_id', $student->id)
            ->with(['semester', 'payments'])
            ->orderByDesc('id')
            ->get();

        return view('user.Student.tuition_fee', array_merge($stats, [
            'student'        => $student,
            'activeSemester' => $activeSemester,
            'enrollments'    => $enrollments,
            'tuition'        => $tuition,
            'payments'       => $payments,
            'allTuitions'    => $allTuitions,
        ]));
    }

    public function feedback()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return redirect()->route('user.login')->with('error', 'Tài khoản chưa được cấu hình thông tin sinh viên.');
        }

        $stats = $this->getStudentStats($student);
        return view('user.Student.feedback', $stats);
    }

    public function payment()
    {
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE payments ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'completed'");
        } catch (\Exception $e) {
            // Already added
        }
        try {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE payments ADD COLUMN proof_image VARCHAR(255) NULL");
        } catch (\Exception $e) {
            // Already added
        }

        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty.facultyGeneral');
        $student = $account->student;

        if (!$student) {
            return redirect()->route('user.login')->with('error', 'Tài khoản chưa được cấu hình thông tin sinh viên.');
        }

        $stats = $this->getStudentStats($student);
        $activeSemester = Semester::where('status', 1)->first() ?? Semester::latest()->first();

        $tuition = Tuition::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id ?? 1)
            ->first();

        // Nếu chưa có tuition, redirect về trang học phí
        if (!$tuition) {
            return redirect()->route('student.tuition')->with('info', 'Vui lòng xem lại thông tin học phí trước khi thanh toán.');
        }

        $payments = \App\Models\Payment::where('tuition_id', $tuition->id)->orderByDesc('id')->get();

        return view('user.Student.payment', array_merge($stats, [
            'student'        => $student,
            'activeSemester' => $activeSemester,
            'tuition'        => $tuition,
            'payments'       => $payments,
        ]));
    }

    public function searchSchedules(Request $request)
    {
        $code = $request->input('code', '');
        $name = $request->input('name', '');
        
        $activeSemester = Semester::where('status', 1)->first() ?? Semester::latest()->first();
        
        $query = \App\Models\Schedule::with(['subject', 'sessions.room', 'teacher', 'sessions'])
            ->when($activeSemester, fn($q) => $q->where('semester_id', $activeSemester->id));
            
        if ($code || $name) {
            $query->whereHas('subject', function($q) use ($code, $name) {
                if ($code) {
                    $q->where(function($q1) use ($code) {
                        $q1->where('code', 'like', '%' . $code . '%')
                           ->orWhere('id', 'like', '%' . $code . '%');
                    });
                }
                if ($name) {
                    $q->where('name', 'like', '%' . $name . '%');
                }
            });
        }
        
        $dbSchedules = $query->get();
        
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
                    $startPeriod = $this->parseTimeSlot($session->start_time);
                    $endPeriod = $session->end_time ? $this->parseTimeSlot($session->end_time, true) : ($startPeriod + 1);

                    $mappedSessions[] = [
                        'day' => $session->day_of_week,
                        'period' => $startPeriod,
                        'duration' => max(1, $endPeriod - $startPeriod + 1)
                    ];
                }
            }

            $subjects[] = [
                $s->id,                    // 0: schedule_id
                $sub->name,                // 1: name
                $sub->credits,             // 2: credits
                $type,                     // 3: type (bb, tc, cn)
                $mappedSessions,           // 4: sessions
                $s->max_capacity,          // 5: max
                $s->current_capacity,      // 6: cur
                $s->group_code ?? 'N01',   // 7: group_code
                $sub->code ?? $sub->id     // 8: subject_code
            ];
        }
        
        return response()->json(['success' => true, 'subjects' => $subjects]);
    }

    private function parseTimeSlot($timeStr, $isEnd = false)
    {
        if (!$timeStr) return 1;
        $slots = [
            ['start' => '06:45', 'end' => '07:35'],
            ['start' => '07:45', 'end' => '08:35'],
            ['start' => '08:45', 'end' => '09:35'],
            ['start' => '09:45', 'end' => '10:35'],
            ['start' => '10:45', 'end' => '11:35'],
            ['start' => '12:30', 'end' => '13:20'],
            ['start' => '13:30', 'end' => '14:20'],
            ['start' => '14:30', 'end' => '15:20'],
            ['start' => '15:30', 'end' => '16:20'],
            ['start' => '16:30', 'end' => '17:20'],
            ['start' => '17:30', 'end' => '18:20'],
            ['start' => '18:30', 'end' => '19:20'],
            ['start' => '19:30', 'end' => '20:20'],
        ];

        $p = explode(':', substr($timeStr, 0, 5));
        $tm = (intval($p[0] ?? 0) * 60) + intval($p[1] ?? 0);

        if (!$isEnd) {
            foreach ($slots as $idx => $slot) {
                $sp = explode(':', $slot['end']);
                $endTm = (intval($sp[0]) * 60) + intval($sp[1]);
                if ($tm <= $endTm + 5) {
                    return $idx + 1;
                }
            }
            return 1;
        } else {
            for ($i = count($slots) - 1; $i >= 0; $i--) {
                $sp = explode(':', $slots[$i]['end']);
                $endTm = (intval($sp[0]) * 60) + intval($sp[1]);
                if ($endTm <= $tm + 5) {
                    return $i + 1;
                }
            }
            return 1;
        }
    }
}
