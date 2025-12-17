<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->nullable()->comment('companies::id');
            $table->string('code', 50);
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();

            // Thêm các trường hình ảnh từ tblusers
            $table->string('picture_type', 50)->nullable()->comment('Loại file hình ảnh (jpeg, png, gif...)');
            $table->unsignedInteger('picture_size')->nullable()->comment('Kích thước file hình ảnh tính bằng bytes');

            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->string('main_manager_code', 50)->nullable()->comment('Người mà người dùng này báo cáo tới');
            $table->string('sub_manager_code', 50)->nullable()->comment('Người mà người dùng này báo cáo tới');
            $table->unsignedInteger('department_id')->nullable()->comment('ID phòng ban của người dùng (departments::id)');

            // Thêm division_id từ tblusers
            $table->unsignedInteger('division_id')->nullable()->comment('ID bộ phận/chi nhánh (divisions::id)');

            $table->unsignedInteger('office_id')->nullable()->comment('ID văn phòng nơi làm việc của người dùng (offices::id)');
            $table->string('regional', 200)->nullable()->comment('Khu vực làm việc được giao (khác với văn phòng)');
            $table->unsignedInteger('transportation_id')->nullable()->comment('ID phụ cấp đi lại của người dùng (transportations::id)');
            $table->unsignedInteger('group_id')->nullable()->comment('ID nhóm người dùng (groups::id)');

            // Thêm group_action_id từ tblusers
            $table->unsignedInteger('group_action_id')->nullable()->comment('ID nhóm hành động/quyền hạn (group_actions::id)');

            $table->unsignedInteger('tax_scheme_id')->nullable()->comment('ID trỏ đến thuế thu nhập cá nhân chung (tax_schemes::id)');
            $table->string('nickname', 50)->nullable()->comment('Tên ngắn gọn của người dùng');

            // Thêm employee_code và acc_code từ tblusers
            $table->string('employee_code', 15)->nullable()->comment('Mã nhân viên do công ty cấp phát');
            $table->string('acc_code', 10)->nullable()->comment('Mã kế toán/tài khoản nội bộ');

            $table->string('nationality')->nullable()->comment('Quốc tịch');
            $table->string('religion', 80)->nullable()->comment('Tôn giáo');
            $table->integer('is_married')->nullable()->comment('Tình trạng hôn nhân: 0 - Chưa kết hôn, 1 - Đã kết hôn');
            $table->integer('gender')->nullable()->comment('Giới tính: 0 - Nữ, 1 - Nam, 2 - Không xác định');
            $table->date('birthday')->nullable()->comment('Ngày sinh của người dùng');
            $table->string('birth_place')->nullable()->comment('Nơi sinh');

            // Cập nhật id_card và thêm các trường liên quan
            $table->string('id_card', 200)->nullable()->comment('Số căn cước công dân');
            $table->date('id_card_issue_date')->nullable()->comment('Ngày cấp căn cước công dân');
            $table->string('id_card_issue_place', 100)->nullable()->comment('Nơi cấp căn cước công dân');

            // Cập nhật passport và thêm các trường liên quan
            $table->string('passport', 200)->nullable()->comment('Số hộ chiếu');
            $table->date('passport_issue_date')->nullable()->comment('Ngày cấp hộ chiếu');
            $table->date('passport_expiry_date')->nullable()->comment('Ngày hết hạn hộ chiếu');
            $table->string('passport_issue_place', 100)->nullable()->comment('Nơi cấp hộ chiếu');

            $table->string('timekeeper_card_id', 40)->nullable()->comment('Mã thẻ chấm công, duy nhất');
            $table->string('contract_number', 40)->nullable()->comment('Số hợp đồng lao động');
            $table->string('contract_type_id', 50)->nullable()->comment('contract_types::id | Loại hợp đồng (không xác định thời hạn, thời hạn...)');
            $table->string('home_address', 200)->nullable()->comment('Địa chỉ thường trú');
            $table->string('temporary_address', 200)->nullable()->comment('Địa chỉ tạm trú');
            $table->string('extension', 15)->nullable()->comment('Số máy lẻ của người dùng');
            $table->string('phone', 50)->nullable()->comment('Số điện thoại di động');
            $table->string('home_phone', 15)->nullable()->comment('Số điện thoại bàn');

            // Thêm office_phone từ tblusers
            $table->string('office_phone', 15)->nullable()->comment('Số điện thoại văn phòng');

            $table->string('fax_number', 50)->nullable()->comment('Số fax (nếu có)');
            $table->string('company_email', 55)->nullable()->comment('Email công ty');
            $table->string('private_email', 55)->nullable()->comment('Email cá nhân');
            $table->date('join_date')->nullable()->comment('Ngày gia nhập công ty');
            $table->integer('probation_period')->nullable()->comment('Thời gian thử việc (tính bằng đơn vị bên dưới)');
            $table->string('probation_period_unit', 5)->nullable()->comment('Đơn vị thử việc: tháng (m), ngày (d), năm (y)');
            $table->date('probation_start')->nullable()->comment('Ngày bắt đầu thử việc');
            $table->date('probation_end')->nullable()->comment('Ngày kết thúc thử việc');
            $table->double('probation_salary_percentage', 5, 2)->nullable()->comment('Phần trăm lương trong thời gian thử việc');

            // Thêm senior_date từ tblusers
            $table->date('seniority_date')->nullable()->comment('Ngày tính thâm niên');

            $table->date('termination_date')->nullable()->comment('Ngày nghỉ việc chính thức (nếu có)');

            // Thêm termination_date_registered từ tblusers
            $table->date('termination_date_registered')->nullable()->comment('Ngày đăng ký nghỉ việc');

            $table->date('status_from_date')->nullable()->comment('Ngày bắt đầu trạng thái hiện tại');
            $table->string('im_id', 30)->nullable()->comment('ID nhắn tin tức thời');
            $table->string('tax_code', 30)->nullable()->comment('Mã số thuế');
            $table->string('bank_account_number', 24)->nullable()->comment('Số tài khoản ngân hàng');
            $table->string('bank_account_type', 45)->nullable()->comment('Loại tài khoản ngân hàng');
            $table->string('bank_name')->nullable()->comment('Tên ngân hàng');
            $table->string('bank_branch')->nullable()->comment('Chi nhánh ngân hàng');
            $table->string('bank_address')->nullable()->comment('Địa chỉ ngân hàng');
            $table->string('insurance_number', 24)->nullable()->comment('Số bảo hiểm xã hội');
            $table->string('health_insurance_number', 24)->nullable()->comment('Số thẻ bảo hiểm y tế');

            // Thêm các trường bảo hiểm từ tblusers
            $table->date('social_insurance_date')->nullable()->comment('Ngày tham gia bảo hiểm xã hội');
            $table->string('social_insurance_place', 100)->nullable()->comment('Nơi tham gia bảo hiểm xã hội');

            $table->integer('access_level')->nullable()->comment('Mức độ truy cập: 1 - Mặc định');

            // Thêm last_visit_date từ tblusers
            $table->datetime('last_visit_date')->nullable()->comment('Lần truy cập cuối cùng');

            // Thêm page_size từ tblusers
            $table->string('page_size', 20)->nullable()->comment('Kích thước trang in mặc định');

            $table->string('modules_allowed')->nullable()->comment('Danh sách module được phép truy cập');
            $table->integer('monthly_timesheet')->nullable()->comment('Yêu cầu khai báo bảng công hàng tháng: 0 - Không, 1 - Có');
            $table->integer('is_blocked')->nullable()->comment('Người dùng có bị khóa không: 0 - Không, 1 - Có');

            // Thêm display_records_max từ tblusers
            $table->integer('display_records_max')->nullable()->comment('Số bản ghi tối đa hiển thị trên một trang');

            $table->string('theme', 30)->nullable()->comment('Giao diện mặc định');
            $table->string('language', 5)->nullable()->comment('Ngôn ngữ giao diện');

            // Thêm on_mouse_move từ tblusers
            $table->integer('on_mouse_move')->nullable()->comment('Hiệu ứng khi di chuột qua dòng dữ liệu');

            $table->string('title', 50)->nullable()->comment('Danh xưng | Ms Mr Dr | Ông Bà');
            $table->integer('is_system_user')->nullable()->comment('Người dùng hệ thống: 0 - Không, 1 - Có');
            $table->integer('overnight_shift_allowed')->nullable()->comment('Cho phép làm ca đêm: 0 - Không, 1 - Có');
            $table->integer('is_union')->nullable()->comment('Thành viên công đoàn: 0 - Không, 1 - Có');
            $table->bigInteger('country_id')->nullable()->comment('ID quốc gia của người dùng (countries::id)');

            // Thêm is_foreigner từ tblusers
            $table->integer('is_foreigner')->nullable()->comment('Người nước ngoài: 0 - Không, 1 - Có');

            // Thêm is_office từ tblusers
            $table->integer('is_office')->nullable()->comment('Nhân viên văn phòng: 0 - Không, 1 - Có');

            // Thêm is_lunch_allow từ tblusers
            $table->integer('is_lunch_allow')->nullable()->comment('Được hưởng phụ cấp ăn trưa: 0 - Không, 1 - Có');

            $table->double('first_basic_salary', 10, 2)->nullable()->comment('Lương cơ bản ban đầu');
            $table->double('increase_rate', 5, 2)->nullable()->comment('Tỉ lệ tăng lương mỗi năm');
            $table->double('total_contract_duration', 5, 2)->nullable()->comment('Tổng thời gian làm việc trong các hợp đồng');

            // Thêm menu_system từ tblusers
            $table->string('menu_system', 12)->nullable()->comment('Kiểu hiển thị menu: horizontal/vertical');

            // Thêm flag từ tblusers
            $table->unsignedInteger('flag')->nullable()->comment('Cờ đánh dấu để xử lý nội bộ');

            $table->text('province_id')->nullable()->comment('provinces::id');
            $table->text('is_direct')->nullable()->comment('0: Indirect, 1: Direct');
            $table->text('comments')->nullable()->comment('Ghi chú thêm');

            $table->integer('user_status_id')->nullable()->comment('ID trạng thái người dùng (user_statuses::id)');
            $table->integer('role')->nullable()->comment('1: Admin : 2: Customer');
            $table->integer('is_login')->nullable()->comment('1: Allow login : 0: Denied');
            $table->dateTime('lasted_login')->nullable();

            // Thêm notes từ tblusers (khác với comments)
            $table->string('notes', 200)->nullable()->comment('Ghi chú ngắn gọn');

            // Thêm is_have_baby từ tblusers
            $table->integer('is_have_baby')->nullable()->comment('Có con nhỏ cần chăm sóc: 0 - Không, 1 - Có');

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Thêm các index cho performance
            $table->index('department_id', 'idx_users_department');
            $table->index('division_id', 'idx_users_division');
            $table->index('office_id', 'idx_users_office');
            $table->index('group_id', 'idx_users_group');
            $table->index('group_action_id', 'idx_users_group_action');
            $table->index('employee_code', 'idx_users_employee_code');
            $table->index('user_status_id', 'idx_users_status');
            $table->index('is_blocked', 'idx_users_blocked');
            $table->index('flag', 'idx_users_flag');
            $table->index('country_id', 'idx_users_country');
            $table->index('email', 'idx_users_email');
            $table->index('timekeeper_card_id', 'idx_users_timekeeper');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};