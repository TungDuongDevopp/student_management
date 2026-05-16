<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Tuition;
use App\Models\Semester;

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
        $totalCreditsWithGrades = 0;
        $earned_credits = 0;
        $current_subjects_count = 0;

        foreach ($enrollments as $enrollment) {
            $subject = $enrollment->schedule->subject;
            $semester = $enrollment->schedule->semester;

            // Đếm môn đang học: Kỳ học đang mở/diễn ra (status = 1)
            if ($semester && $semester->status == 1) {
                $current_subjects_count++;
            }

            // Tính điểm
            if ($enrollment->final_score !== null) {
                $score10 = $enrollment->final_score;
                $credits = $subject->credits;

                // Quy đổi hệ 10 sang hệ 4
                $score4 = $this->convertTo4Scale($score10);

                $totalGradePoints += ($score4 * $credits);
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
        } else {
            $gpa = 0;
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
            'ranking' => $ranking,
            'earned_credits' => $earned_credits,
            'max_credits' => $max_credits,
            'current_subjects_count' => $current_subjects_count,
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

        return view('user.Student.home', array_merge($stats, ['todaySchedules' => $todaySchedules]));
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
}
