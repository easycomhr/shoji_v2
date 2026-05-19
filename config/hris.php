<?php

/**
 * HRIS Business Constants Configuration
 *
 * Migrated from legacy constant.php.
 * Contains all business rule constants used across the HRIS system,
 * including office IDs, employee statuses, leave type groupings,
 * shift mappings, and payroll-related identifiers.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Offices
    |--------------------------------------------------------------------------
    | Mapping tên văn phòng → office_id trong bảng offices
    */
    'offices' => [
        'HCM' => 4, // Văn phòng Hồ Chí Minh
        'BD'  => 5, // Văn phòng Bình Dương
        'HN'  => 6, // Văn phòng Hà Nội
        'VTP' => 7, // Văn phòng Vũng Tàu / Tiền Phong
        'DL9' => 8, // Văn phòng Đà Lạt 9
    ],

    /*
    |--------------------------------------------------------------------------
    | User Status
    |--------------------------------------------------------------------------
    | Mapping trạng thái nhân viên → user_status_id trong bảng user_statuses
    */
    'user_status' => [
        'working'        => 8, // Đang làm việc
        'terminated'     => 9, // Đã nghỉ việc
        'maternity'      => 1, // Nghỉ thai sản
        'post_maternity' => 2, // Sau thai sản (trở lại làm việc)
    ],

    /*
    |--------------------------------------------------------------------------
    | Leave Types
    |--------------------------------------------------------------------------
    | Phân nhóm các loại phép theo nghiệp vụ tính lương và công
    */
    'leave_types' => [
        'annual'            => [1, 5, 14],              // Phép năm (tính vào ngày phép năm)
        'unpaid'            => [3, 4, 6],               // Nghỉ không lương
        'no_max_calc'       => [7, 8, 9, 10, 11, 12, 25], // Loại phép không giới hạn / không tính tối đa
        'limited'           => [14, 15],                // Phép có giới hạn số ngày sử dụng
        'violate_limit'     => [2],                     // Phép vi phạm giới hạn (tính trừ lương)
        'unexpected_annual' => 5,                       // Phép năm đột xuất (ID đơn lẻ)
        'unexpected'        => 6,                       // Phép đột xuất không lương (ID đơn lẻ)
    ],

    /*
    |--------------------------------------------------------------------------
    | Shift by Office
    |--------------------------------------------------------------------------
    | Mapping office_id → shift_id mặc định của văn phòng đó
    */
    'shift_by_office' => [
        4 => 1, // HCM → Ca 1
        5 => 3, // BD  → Ca 3
        7 => 3, // VTP → Ca 3
        6 => 2, // HN  → Ca 2
        8 => 5, // DL9 → Ca 5
    ],

    /*
    |--------------------------------------------------------------------------
    | Overtime After Minutes
    |--------------------------------------------------------------------------
    | Số phút làm thêm tối thiểu sau giờ tan tầm để được tính OT
    */
    'ot_after_minutes' => 15,

    /*
    |--------------------------------------------------------------------------
    | Positions
    |--------------------------------------------------------------------------
    | Mapping tên chức vụ đặc thù → position_id (dùng cho rule tính lương riêng)
    */
    'positions' => [
        'cleaner'   => 21, // Nhân viên vệ sinh
        'driver_sg' => 20, // Tài xế Sài Gòn
        'driver_xt' => 22, // Tài xế Xuyên Tỉnh
        'driver_bd' => 23, // Tài xế Bình Dương
    ],

    /*
    |--------------------------------------------------------------------------
    | Probation Contract ID
    |--------------------------------------------------------------------------
    | contract_type_id tương ứng với hợp đồng thử việc
    */
    'probation_contract_id' => 3,

    /*
    |--------------------------------------------------------------------------
    | Driver Shift ID
    |--------------------------------------------------------------------------
    | shift_id dành cho tài xế (ca đặc thù, không áp dụng rule giờ chuẩn)
    */
    'driver_shift_id' => 4,

    /*
    |--------------------------------------------------------------------------
    | Day Off Shift ID
    |--------------------------------------------------------------------------
    | shift_id đại diện cho ngày nghỉ (không tính công)
    */
    'day_off_shift_id' => 0,

    /*
    |--------------------------------------------------------------------------
    | Holiday Shift ID
    |--------------------------------------------------------------------------
    | shift_id đại diện cho ngày lễ / nghỉ lễ chính thức
    */
    'holiday_shift_id' => 10,

];
