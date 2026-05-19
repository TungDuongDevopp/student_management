<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class TeacherHomeController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;

        $stats = [
            'assigned_classes' => 0,
            'total_students' => 0,
            'ungraded_schedules' => 0,
            'pending_tickets' => 0,
        ];

        $todaySchedules = [];

        if ($teacher) {
            $classRooms = ClassRoom::where('teacher_id', $teacher->id)->get();
            $schedules = Schedule::where('teacher_id', $teacher->id)->get();

            $stats['assigned_classes'] = $classRooms->count() + $schedules->count();
            $stats['total_students'] = $classRooms->sum('quantity') + $schedules->sum('current_capacity');
            $stats['ungraded_schedules'] = Schedule::where('teacher_id', $teacher->id)
                ->whereHas('enrollments', function($q) {
                    $q->doesntHave('grade');
                })->count();

            // Lịch hôm nay: day_of_week khớp với thứ hiện tại (PHP: 0=CN, 1=T2... -> +1 hoặc = 8 cho CN)
            $todayDow = (int) now()->format('N') + 1; // ISO: 1=Mon -> +1 = 2..., CN=7->8
            if (now()->dayOfWeek === 0) $todayDow = 8; // Chủ nhật

            $todaySchedules = Schedule::with(['subject', 'room', 'sessions'])
                ->where('teacher_id', $teacher->id)
                ->whereHas('sessions', function ($q) use ($todayDow) {
                    $q->where('day_of_week', $todayDow);
                })
                ->get()
                ->map(function ($s) use ($todayDow) {
                    $session = $s->sessions->firstWhere('day_of_week', $todayDow);
                    return [
                        'subject_name'     => $s->subject?->name ?? '—',
                        'group_code'       => $s->group_code ?? '',
                        'room'             => $s->room ? (($s->room->block ? $s->room->block.'.' : '').$s->room->name) : '—',
                        'current_capacity' => $s->current_capacity ?? 0,
                        'start_time'       => substr($session?->start_time ?? '', 0, 5),
                        'end_time'         => substr($session?->end_time ?? '', 0, 5),
                    ];
                })
                ->sortBy('start_time')
                ->values()
                ->toArray();
        }

        $news = News::where('is_published', true)
            ->whereIn('target_audience', ['teacher', 'all'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('user.Teacher.home', compact('stats', 'todaySchedules', 'news'));
    }

    public function info()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher.faculty');
        $teacher = $account->teacher;

        $stats = [
            'assigned_classes' => 0,
            'total_students' => 0,
            'ungraded_schedules' => 0,
            'pending_tickets' => 0,
        ];

        if ($teacher) {
            $classRooms = ClassRoom::where('teacher_id', $teacher->id)->get();
            $schedules = Schedule::where('teacher_id', $teacher->id)->get();

            $stats['assigned_classes'] = $classRooms->count() + $schedules->count();
            $stats['total_students'] = $classRooms->sum('quantity') + $schedules->sum('current_capacity');
            
            $stats['ungraded_schedules'] = Schedule::where('teacher_id', $teacher->id)
                ->whereHas('enrollments', function($q) {
                    $q->where('status', 0);
                })->count();
        }

        if ($account) {
            $stats['pending_tickets'] = \App\Models\Feedback::where('account_id', $account->id)
                ->where('status', 0)->count();
        }

        return view('user.Teacher.info', compact('teacher', 'stats'));
    }

    public function schedule()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;

        $semesters = Semester::orderByDesc('id')->get();

        $schedules = [];
        if ($teacher) {
            $schedules = Schedule::with([
                'subject',
                'room',
                'semester',
                'sessions',
                'enrollments',
            ])
            ->where('teacher_id', $teacher->id)
            ->get()
            ->map(function ($s) {
                $hasUngraded = $s->enrollments->where('status', 0)->count() > 0;
                return [
                    'id'               => $s->id,
                    'semester_id'      => $s->semester_id,
                    'subject_name'     => $s->subject?->name ?? '—',
                    'group_code'       => $s->group_code ?? '',
                    'room'             => $s->room ? (($s->room->block ? $s->room->block.'.' : '').$s->room->name) : '—',
                    'current_capacity' => $s->current_capacity ?? 0,
                    'max_capacity'     => $s->max_capacity ?? 0,
                    'has_ungraded'     => $hasUngraded,
                    'sessions'         => $s->sessions->map(fn($ss) => [
                        'day_of_week' => $ss->day_of_week,
                        'start_time'  => substr($ss->start_time ?? '', 0, 5),
                        'end_time'    => substr($ss->end_time ?? '', 0, 5),
                    ])->values()->toArray(),
                ];
            })->values()->toArray();
        }

        $stats = [
            'assigned_classes'   => 0,
            'total_students'     => 0,
            'ungraded_schedules' => 0,
        ];
        if ($teacher) {
            $classRooms = ClassRoom::where('teacher_id', $teacher->id)->get();
            $allSchedules = Schedule::where('teacher_id', $teacher->id)->get();
            $stats['assigned_classes']   = $classRooms->count() + $allSchedules->count();
            $stats['total_students']     = $classRooms->sum('quantity') + $allSchedules->sum('current_capacity');
            $stats['ungraded_schedules'] = Schedule::where('teacher_id', $teacher->id)
                ->whereHas('enrollments', fn($q) => $q->where('status', 0))->count();
        }

        return view('user.Teacher.schedule', compact('teacher', 'semesters', 'schedules', 'stats'));
    }

    public function classes()
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;

        $classRooms = [];
        $schedules = [];

        if ($teacher) {
            $classRooms = ClassRoom::with('faculty')
                ->where('teacher_id', $teacher->id)
                ->get();

            $schedules = Schedule::with([
                'subject',
                'room',
                'semester',
                'sessions',
                'enrollments'
            ])
            ->where('teacher_id', $teacher->id)
            ->get();
        }

        return view('user.Teacher.class_list', compact('teacher', 'classRooms', 'schedules'));
    }

    public function students(\Illuminate\Http\Request $request)
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;

        $classId = $request->query('class_id');
        $scheduleId = $request->query('schedule_id');

        $classRooms = [];
        $schedules = [];
        $students = collect();
        $currentClass = null;
        $currentSchedule = null;
        $title = 'Danh sách Sinh viên';

        if ($teacher) {
            $classRooms = ClassRoom::where('teacher_id', $teacher->id)->get();
            $schedules = Schedule::with('subject')->where('teacher_id', $teacher->id)->get();

            if ($classId) {
                $currentClass = $classRooms->firstWhere('id', $classId);
                if ($currentClass) {
                    $title = "Lớp hành chính: " . $currentClass->name;
                    $students = \App\Models\Student::with('account')
                        ->where('classroom_id', $classId)
                        ->get()
                        ->map(function($student) use ($currentClass) {
                            return (object) [
                                'id' => $student->id,
                                'name' => $student->name,
                                'code' => $student->student_code,
                                'email' => $student->account->email ?? '',
                                'class_name' => $currentClass->name,
                                'status' => 'Đang học',
                            ];
                        });
                }
            } elseif ($scheduleId) {
                $currentSchedule = $schedules->firstWhere('id', $scheduleId);
                if ($currentSchedule) {
                    $title = "Lớp học phần: " . ($currentSchedule->subject->name ?? '') . " (Nhóm " . $currentSchedule->group_code . ")";
                    $enrollments = \App\Models\Enrollment::with(['student.account', 'student.classroom'])
                        ->where('schedule_id', $scheduleId)
                        ->get();
                    
                    $students = $enrollments->map(function($enrollment) {
                        $student = $enrollment->student;
                        return (object) [
                            'id' => $student->id,
                            'name' => $student->name,
                            'code' => $student->student_code,
                            'email' => $student->account->email ?? '',
                            'class_name' => $student->classroom->name ?? '—',
                            'status' => 'Đang học',
                        ];
                    });
                }
            } else {
                // Default to first class room if none selected
                if ($classRooms->count() > 0) {
                    return redirect()->route('teacher.students', ['class_id' => $classRooms->first()->id]);
                } elseif ($schedules->count() > 0) {
                    return redirect()->route('teacher.students', ['schedule_id' => $schedules->first()->id]);
                }
            }
        }

        return view('user.Teacher.student_list', compact(
            'teacher', 'classRooms', 'schedules', 'students', 'currentClass', 'currentSchedule', 'title'
        ));
    }

    public function attendances(\Illuminate\Http\Request $request)
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;
        
        $scheduleId = $request->query('schedule_id');
        $schedules = Schedule::with('subject')->where('teacher_id', $teacher->id)->get();
        
        $students = collect();
        $currentSchedule = null;
        
        if ($scheduleId) {
            $currentSchedule = $schedules->firstWhere('id', $scheduleId);
            if ($currentSchedule) {
                $enrollments = \App\Models\Enrollment::with(['student.account', 'student.classroom'])
                    ->where('schedule_id', $scheduleId)
                    ->get();
                
                $students = $enrollments->map(function($enrollment) {
                    $student = $enrollment->student;
                    return (object) [
                        'id' => $student->id,
                        'name' => $student->name,
                        'code' => $student->student_code,
                        'class_name' => $student->classroom->name ?? '—',
                        // In a real app, you'd fetch today's attendance record.
                        'is_present' => true 
                    ];
                });
            }
        } elseif ($schedules->count() > 0) {
            return redirect()->route('teacher.attendances', ['schedule_id' => $schedules->first()->id]);
        }
        
        return view('user.Teacher.attendance', compact('teacher', 'schedules', 'students', 'currentSchedule'));
    }

    public function grades(\Illuminate\Http\Request $request)
    {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher');
        $teacher = $account->teacher;
        
        $scheduleId = $request->query('schedule_id');
        $schedules = Schedule::with('subject')->where('teacher_id', $teacher->id)->get();
        
        $students = collect();
        $currentSchedule = null;
        
        if ($scheduleId) {
            $currentSchedule = $schedules->firstWhere('id', $scheduleId);
            if ($currentSchedule) {
                $enrollments = \App\Models\Enrollment::with(['student.account', 'student.classroom'])
                    ->where('schedule_id', $scheduleId)
                    ->get();
                
                $students = $enrollments->map(function($enrollment) {
                    $student = $enrollment->student;
                    return (object) [
                        'id' => $student->id,
                        'enrollment_id' => $enrollment->id,
                        'name' => $student->name,
                        'code' => $student->student_code,
                        'class_name' => $student->classroom->name ?? '—',
                        'score' => $enrollment->score ?? '', // Assuming there is a score field or similar. If not, it will be empty.
                    ];
                });
            }
        } elseif ($schedules->count() > 0) {
            return redirect()->route('teacher.grades', ['schedule_id' => $schedules->first()->id]);
        }
        
        return view('user.Teacher.grade', compact('teacher', 'schedules', 'students', 'currentSchedule'));
    }
}
