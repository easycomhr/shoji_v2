<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
// Mục đích: Lưu kết quả tính lương chi tiết của từng nhân viên theo tháng
        Schema::create('user_monthly_salary_details', function (Blueprint $table) {
            $table->id(); // ID tự động tăng
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ID nhân viên
            $table->string('username'); // Tên nhân viên (snapshot tại thời điểm tính lương)
            $table->string('department'); // Tên phòng ban (snapshot tại thời điểm tính lương)
            $table->integer('month'); // Tháng tính lương (1-12)
            $table->integer('year'); // Năm tính lương

            // === THÀNH PHẦN LƯƠNG CƠ BẢN ===
            $table->decimal('basic_salary', 12, 2); // Lương cơ bản đã tính theo ngày làm việc thực tế
            $table->decimal('position_allowance', 12, 2)->default(0); // Phụ cấp chức vụ
            $table->decimal('evaluation_allowance', 12, 2)->default(0); // Phụ cấp đánh giá/KPI
            $table->decimal('seniority_allowance', 12, 2)->default(0); // Phụ cấp thâm niên/OHS
            $table->decimal('adjustment_allowance', 12, 2)->default(0); // Phụ cấp điều chỉnh/cứu hỏa
            $table->decimal('transportation_allowance', 12, 2)->default(0); // Phụ cấp đi lại theo ngày làm việc
            $table->decimal('night_shift_allowance', 12, 2)->default(0); // Phụ cấp ca đêm
            $table->decimal('slippage_allowance', 12, 2)->default(0); // Phụ cấp chăm sóc con nhỏ
            $table->decimal('harmful_allowance', 12, 2)->default(0); // Phụ cấp độc hại
            $table->decimal('regular_allowance', 12, 2)->default(0); // Phụ cấp thường xuyên khác
            $table->decimal('seniority_worker_allowance', 12, 2)->default(0); // Phụ cấp thâm niên công nhân
            $table->decimal('skill_allowance', 12, 2)->default(0); // Phụ cấp kỹ năng
            $table->decimal('salary_allowance', 12, 2)->default(0); // Phụ cấp lương đặc biệt tháng này

            // === GIỜ TĂNG CA ===
            $table->decimal('ot_day_hours', 8, 2)->default(0); // Giờ tăng ca ngày thường
            $table->decimal('ot_night_hours', 8, 2)->default(0); // Giờ tăng ca đêm
            $table->decimal('ot_dayoff_hours', 8, 2)->default(0); // Giờ tăng ca ngày nghỉ
            $table->decimal('ot_dayoff_night_hours', 8, 2)->default(0); // Giờ tăng ca đêm ngày nghỉ
            $table->decimal('ot_special_day_hours', 8, 2)->default(0); // Giờ tăng ca đặc biệt ngày
            $table->decimal('ot_special_night_hours', 8, 2)->default(0); // Giờ tăng ca đặc biệt đêm

            // === TIỀN TĂNG CA (CHỊU THUẾ) ===
            $table->decimal('ot_day_amount', 12, 2)->default(0); // Tiền tăng ca ngày (hệ số 1.5)
            $table->decimal('ot_night_amount', 12, 2)->default(0); // Tiền tăng ca đêm (hệ số 1.5)
            $table->decimal('ot_dayoff_amount', 12, 2)->default(0); // Tiền tăng ca ngày nghỉ (hệ số 2.0)
            $table->decimal('ot_dayoff_night_amount', 12, 2)->default(0); // Tiền tăng ca đêm nghỉ (hệ số 2.0)
            $table->decimal('ot_special_day_amount', 12, 2)->default(0); // Tiền tăng ca đặc biệt ngày (hệ số 3.0)
            $table->decimal('ot_special_night_amount', 12, 2)->default(0); // Tiền tăng ca đặc biệt đêm (hệ số 3.0)

            // === TIỀN TĂNG CA (KHÔNG CHỊU THUẾ) ===
            $table->decimal('ot_day_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT ngày (50%)
            $table->decimal('ot_night_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT đêm
            $table->decimal('ot_dayoff_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT ngày nghỉ (100%)
            $table->decimal('ot_dayoff_night_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT đêm nghỉ
            $table->decimal('ot_special_day_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT đặc biệt ngày (200%)
            $table->decimal('ot_special_night_non_tax_amount', 12, 2)->default(0); // Phần không thuế OT đặc biệt đêm

            // === BẢO HIỂM ===
            $table->decimal('health_insurance', 12, 2)->default(0); // BHYT (1.5% lương đóng bảo hiểm)
            $table->decimal('social_insurance', 12, 2)->default(0); // BHXH (8% lương đóng bảo hiểm)
            $table->decimal('employment_insurance', 12, 2)->default(0); // BHTN (1% lương đóng bảo hiểm)
            $table->decimal('union_fee', 12, 2)->default(0); // Phí công đoàn

            // === KHẤU TRỪ ===
            $table->decimal('late_deduction', 12, 2)->default(0); // Trừ lương do đi muộn
            $table->decimal('leave_deduction', 12, 2)->default(0); // Trừ lương do nghỉ không phép

            // === PHỤ CẤP & KHẤU TRỪ KHÁC ===
            $table->decimal('non_taxable_allowance', 12, 2)->default(0); // Phụ cấp không chịu thuế tháng này
            $table->decimal('non_taxable_deduction', 12, 2)->default(0); // Khấu trừ không chịu thuế tháng này
            $table->decimal('taxable_allowance', 12, 2)->default(0); // Phụ cấp chịu thuế tháng này
            $table->decimal('taxable_deduction', 12, 2)->default(0); // Khấu trừ chịu thuế tháng này

            // === CÁC KHOẢN ĐẶC BIỆT ===
            $table->decimal('day_off_70_amount', 12, 2)->default(0); // Tiền nghỉ hưởng 70% lương
            $table->decimal('day_off_fixed_amount', 12, 2)->default(0); // Tiền nghỉ hưởng lương cố định
            $table->decimal('day_off_100_amount', 12, 2)->default(0); // Tiền nghỉ hưởng 100% lương
            $table->decimal('perfect_attendance_bonus', 12, 2)->default(0); // Thưởng chuyên cần (300,000 VND)

            // === NGÀY CÔNG & GIỜ LÀM ===
            $table->decimal('working_days', 8, 2)->default(0); // Số ngày làm việc thực tế
            $table->decimal('day_off_70_days', 8, 2)->default(0); // Số ngày nghỉ hưởng 70% lương
            $table->decimal('day_off_fixed_days', 8, 2)->default(0); // Số ngày nghỉ hưởng lương cố định
            $table->decimal('day_off_100_days', 8, 2)->default(0); // Số ngày nghỉ hưởng 100% lương
            $table->decimal('paid_leave_days', 8, 2)->default(0); // Số ngày nghỉ phép có lương
            $table->decimal('unpaid_leave_days', 8, 2)->default(0); // Số ngày nghỉ không lương
            $table->decimal('late_hours', 8, 2)->default(0); // Số giờ đi muộn
            $table->decimal('night_shift_hours', 8, 2)->default(0); // Tổng số giờ làm ca đêm

            // === TÍNH TOÁN LƯƠNG ===
            $table->decimal('payable_salary', 12, 2)->default(0); // Lương thực trả (sau trừ ngày nghỉ)
            $table->decimal('taxable_income', 12, 2)->default(0); // Thu nhập chịu thuế
            $table->decimal('pit_deduction', 12, 2)->default(0); // Giảm trừ gia cảnh (11M + 4.4M*người phụ thuộc)
            $table->decimal('after_pit_deduction', 12, 2)->default(0); // Thu nhập sau giảm trừ gia cảnh
            $table->decimal('personal_income_tax', 12, 2)->default(0); // Thuế thu nhập cá nhân
            $table->decimal('net_payment', 12, 2)->default(0); // Lương thực lãnh (sau thuế)

            // === TRƯỜNG TÙY CHỈNH ===
            $table->decimal('seniority_allowance_original', 12, 2)->default(0); // Phụ cấp thâm niên gốc (chưa tính tỷ lệ)
            $table->boolean('is_custom_day_off')->default(false); // Có sử dụng số ngày nghỉ tùy chỉnh không

            $table->timestamps();

            $table->unique(['user_id', 'month', 'year']); // Mỗi nhân viên chỉ có 1 bản ghi lương/tháng
            $table->index(['month', 'year']); // Tìm kiếm theo tháng/năm
            $table->index('net_payment'); // Tìm kiếm theo mức lương
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_monthly_salary_details');
    }
};
