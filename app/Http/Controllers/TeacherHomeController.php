<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Schedule;
use App\Models\Semester;
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
        ];

        $todaySchedules = [];

        if ($teacher) {
            $classRooms = ClassRoom::where('teacher_id', $teacher->id)->get();
            $schedules = Schedule::where('teacher_id', $teacher->id)->get();

            $stats['assigned_classes'] = $classRooms->count() + $schedules->count();
            $stats['total_students'] = $classRooms->sum('quantity') + $schedules->sum('current_capacity');

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

        return view('user.Teacher.home', compact('stats', 'todaySchedules'));
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
}
