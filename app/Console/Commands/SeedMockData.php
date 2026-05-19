<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SeedMockData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:seed-mock-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Đang tạo dữ liệu mẫu (Mock Data) cho Frontend UI...');

        // 1. Tạo các Khối nhóm ngành (Faculty Generals) của trường HUMG (Update 2026)
        $this->info('Đang khởi tạo các nhóm Khoa (Khối Kỹ thuật & Kinh tế)...');
        
        $techBlock = \App\Models\FacultyGeneral::updateOrCreate(
            ['name' => 'Khối Kỹ thuật'],
            ['max_credits' => 155, 'tuition_fee_per_credit' => 480000]
        );

        $econBlock = \App\Models\FacultyGeneral::updateOrCreate(
            ['name' => 'Khối Kinh tế'],
            ['max_credits' => 135, 'tuition_fee_per_credit' => 400000]
        );

        // Gán khoa tương ứng vào khối
        $techFaculties = ['Khoa Công nghệ thông tin', 'Khoa Mỏ', 'Khoa Dầu khí', 'Khoa Xây dựng', 'Khoa Cơ điện', 'Khoa Địa chất', 'Khoa Trắc địa - Bản đồ'];
        $econFaculties = ['Khoa Kinh tế - QTKD'];
        
        foreach (\App\Models\Faculty::all() as $faculty) {
            if (in_array($faculty->name, $techFaculties) || strpos(strtolower($faculty->name), 'kỹ thuật') !== false || strpos(strtolower($faculty->name), 'công nghệ') !== false) {
                $faculty->update(['faculty_general_id' => $techBlock->id]);
            } else {
                $faculty->update(['faculty_general_id' => $econBlock->id]);
            }
        }

        // Tạo một vài Session mẫu cho Schedules hiện có (nếu chưa có)
        $schedules = \App\Models\Schedule::all();
        if ($schedules->count() > 0 && \App\Models\ScheduleSession::count() == 0) {
            foreach ($schedules as $schedule) {
                // Random 1-2 buổi học cho mỗi môn
                for ($i = 0; $i < rand(1, 2); $i++) {
                    \App\Models\ScheduleSession::create([
                        'schedule_id' => $schedule->id,
                        'day_of_week' => rand(2, 7), // Thứ 2 đến thứ 7
                        'start_time' => ['07:00', '09:35', '13:00', '15:35'][rand(0, 3)],
                        'end_time' => ['09:25', '12:00', '15:25', '18:00'][rand(0, 3)],
                    ]);
                }
            }
            $this->info('Đã tạo xong Schedule Sessions (Lịch học/Thời khóa biểu).');
        }

        // Tạo điểm thi ngẫu nhiên cho bảng grades để tính GPA
        $enrollments = \App\Models\Enrollment::doesntHave('grade')->get();
        if ($enrollments->count() > 0) {
            foreach ($enrollments as $enrollment) {
                $scoreC = rand(50, 100) / 10;
                $scoreB = rand(50, 100) / 10;
                $scoreA = rand(40, 100) / 10;
                
                \App\Models\Grade::create([
                    'enrollment_id' => $enrollment->id,
                    'score_c' => $scoreC,
                    'score_b' => $scoreB,
                    'score_a' => $scoreA
                ]);
            }
            $this->info('Đã cập nhật điểm (Điểm A, B, C và Tổng kết) cho các môn học.');
        }

        // Tạo công nợ học phí mẫu dựa vào Khối ngành và Tín chỉ đăng ký
        $students = \App\Models\Student::with('classroom.faculty.facultyGeneral')->get();
        foreach ($students as $student) {
            $facultyGeneral = $student->classroom->faculty->facultyGeneral ?? null;
            $feePerCredit = $facultyGeneral ? $facultyGeneral->tuition_fee_per_credit : 400000;
            
            // Tính tổng tín chỉ đã đăng ký
            $enrollments = \App\Models\Enrollment::where('student_id', $student->id)
                ->with('schedule.subject')
                ->get();
            
            $totalCredits = 0;
            foreach ($enrollments as $enrollment) {
                if ($enrollment->schedule && $enrollment->schedule->subject) {
                    $totalCredits += $enrollment->schedule->subject->credits;
                }
            }

            $totalAmount = $totalCredits * $feePerCredit;
            // Nếu sinh viên không có môn nào thì demo 15 tín
            if ($totalAmount == 0) $totalAmount = 15 * $feePerCredit;

            $tuition = \App\Models\Tuition::firstOrNew([
                'student_id' => $student->id,
                'semester_id' => 1,
            ]);
            
            $tuition->total_amount = $totalAmount;
            // Đã nộp 50% hoặc nộp đủ
            if (!$tuition->exists) {
                $tuition->paid_amount = rand(0, 1) == 1 ? $totalAmount : $totalAmount / 2;
            }
            $tuition->save();
        }
        $this->info('Đã tạo và cập nhật dữ liệu Công nợ Học phí tự động dựa trên Khối ngành.');

        // Mock News
        if (\App\Models\News::count() == 0) {
            \App\Models\News::insert([
                [
                    'title' => 'Thông báo đăng ký tín chỉ học kỳ 1 năm học 2026-2027',
                    'slug' => 'thong-bao-dang-ky-tin-chi-hk1-2026-2027',
                    'content' => 'Nhà trường thông báo đến toàn thể sinh viên về lịch đăng ký học phần học kỳ 1 năm học 2026-2027...',
                    'thumbnail' => 'storage/images/news/dang-ky-tin-chi.jpg',
                    'target_audience' => 'student',
                    'category' => 'Đào tạo',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Giấy mời họp giao ban khoa Công nghệ Thông tin tháng 5',
                    'slug' => 'giay-moi-hop-giao-ban-khoa-cntt-thang-5',
                    'content' => 'Kính mời toàn thể cán bộ, giảng viên khoa CNTT tham dự buổi họp giao ban định kỳ để tổng kết công tác giảng dạy giữa kỳ...',
                    'thumbnail' => 'storage/images/news/Anhhopkhoa.jpg',
                    'target_audience' => 'teacher',
                    'category' => 'Công tác',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'title' => 'Thông báo nghỉ Lễ Quốc khánh 2/9 năm 2026',
                    'slug' => 'thong-bao-nghi-le-quoc-khanh-2026',
                    'content' => 'Trường Đại học thông báo lịch nghỉ Lễ Quốc khánh 2/9 cho toàn thể cán bộ, giảng viên và sinh viên...',
                    'thumbnail' => 'storage/images/news/nghi-le.jpg',
                    'target_audience' => 'all',
                    'category' => 'Chung',
                    'is_published' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
            $this->info('Đã tạo dữ liệu Tin tức & Thông báo mẫu.');
        }

        $this->info('Hoàn tất! Bây giờ bạn có thể F5 lại giao diện để xem dữ liệu (GPA, Tín chỉ, Thời khóa biểu, Tin tức) hoạt động.');
    }
}
