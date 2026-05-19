# Shoji v2 – Task List (Màn hình & Chức năng)

> Cập nhật: 2026-03-23
> Ký hiệu: ✅ Đã thực hiện | ❌ Chưa thực hiện | ⚠️ Mapping không chắc chắn

## Quy tắc tham chiếu code cũ

Mỗi chức năng trong `shoji_old` thường gồm **3 file** (thay `{Ref}` bằng tên trong cột "shoji_old Ref"):
- `shoji_old/{Ref}.php` — view / controller
- `shoji_old/{Ref}_Data.php` — data / backend logic
- `shoji_old/js/{Ref}.js` — frontend ExtJS

Khi nhận yêu cầu "tham khảo logic cũ", đọc cả 3 file này trước khi implement.

---

## 1. HR Management (Quản lý Nhân sự)

### 1.1 Employee Management (Quản lý Nhân viên)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| HR-01 | Employees List (Danh sách nhân viên) | `admin.user.index` | ✅ | `Employee` |
| HR-02 | Terminated Employees (Nhân viên nghỉ việc) | `admin.user.terminate` | ✅ | `Employee` |
| HR-03 | Import New Employee (Nhập nhân viên mới) | `admin.user.import` | ✅ | `ImportEmployeeInformation` |
| HR-04 | Insurance (Bảo hiểm) | `admin.insurance.index` | ✅ | `EmployeeInsuranceEditor` |
| HR-05 | Contract Management (Quản lý hợp đồng) | `admin.labour_contracts.index` | ✅ | `LaborContractManagement` |
| HR-06 | Change Employee Code (Đổi mã nhân viên) | `admin.change_employee_code.index` | ✅ | `ChangeEmployeeCode` |
| HR-07 | Employee Email (Email nhân viên) | — | ❌ | `ImportEmployeeEmail` |
| HR-08 | Login History (Lịch sử đăng nhập) | — | ❌ | `LoginHistory` |
| HR-09 | Import Team History (Lịch sử nhập nhóm) | — | ❌ | `ImportTeamHistories` |

### 1.2 HR Reports (Báo cáo Nhân sự)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| HR-10 | Export Employee List (Xuất danh sách NV) | — | ❌ | `ExportEmployee` |
| HR-11 | Export Terminated Employees (Xuất NV nghỉ việc) | — | ❌ | `ExportEmployeeTerminated` |
| HR-12 | Export Insurance Changes Monthly (Xuất biến động BH tháng) | — | ❌ | `ExportInsMonth` |
| HR-13 | Export Insurance Changes Yearly (Xuất biến động BH năm) | — | ❌ | `ExportInsYear` |
| HR-14 | Export Labour Contracts (Xuất hợp đồng lao động) | — | ❌ | `ExportContracts` |

---

## 2. Attendance Management (Quản lý Chấm công)

### 2.1 Attendance (Chấm công)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| AT-01 | Monthly Summary Timesheet (Bảng tổng hợp tháng) | — | ❌ | `FinalProcessedTimesheet` |
| AT-02 | Modify Overtime (Điều chỉnh tăng ca) | — | ❌ | `OverTimeList` |
| AT-03 | Monthly Timesheet (Bảng chấm công tháng) | — | ❌ | `ProcessTimeSheet` ⚠️ |
| AT-04 | Modify Early/Late (Điều chỉnh sớm/muộn) | — | ❌ | `ShiftDurationCheck` ⚠️ |
| AT-05 | Modify Leave (Điều chỉnh nghỉ phép) | `admin.leave.index` | ✅ | `UserLeaves` |
| AT-06 | Reprocess Data (Xử lý lại dữ liệu) | — | ❌ | `PreprocessData` |
| AT-07 | Process Late/Early Data (Xử lý dữ liệu sớm/muộn) | — | ❌ | `ProcessShiftDurationCheck` |
| AT-08 | Split Late/Early Data (Tách dữ liệu sớm/muộn) | — | ❌ | `SplitUserLeave` |
| AT-09 | Public Holidays (Ngày lễ) | — | ❌ | `HolidayEditor` |
| AT-10 | Annual Leave Management (Quản lý nghỉ phép năm) | `admin.annual_leave.index` | ✅ | `AnnualLeaveManager` |
| AT-11 | Process Annual Leave (Xử lý nghỉ phép năm) | — | ❌ | `ProcessUserLeave` |
| AT-12 | Special Leave Management (Quản lý loại nghỉ đặc biệt) | `admin.leave_type.index` | ✅ | `UserLeaves` |
| AT-13 | Split Leave Data (Tách dữ liệu nghỉ phép) | — | ❌ | `SplitLeave` |

### 2.2 Attendance Reports (Báo cáo Chấm công)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| AT-14 | Overtime Report (Báo cáo tăng ca) | — | ❌ | `ExportOverTimeInMonth` |
| AT-15 | Late/Early Report (Báo cáo sớm/muộn) | — | ❌ | `ExportLateEarlyReport` |
| AT-16 | Leave Information Report (Báo cáo thông tin nghỉ) | — | ❌ | `ExportMonthInLeave` |
| AT-17 | Wrong Shift Report (Báo cáo sai ca) | — | ❌ | `ExportWrongShiftReport` |
| AT-18 | Wrong Card Sweeping (Quẹt thẻ sai) | — | ❌ | `ExportWrongCardSweeping` |
| AT-19 | Monthly TA Summary (Tổng hợp chấm công tháng) | — | ❌ | `ExportTableTimekeeper` |
| AT-20 | Annual Leave Report (Báo cáo nghỉ phép năm) | — | ❌ | `ExportAnnualLeave` |

### 2.3 Time Recorder Data (Dữ liệu máy chấm công)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| AT-21 | Raw Time Recorder Data (Dữ liệu thô máy chấm công) | — | ❌ | `TimeAttendantRecord` |
| AT-22 | Import Work Hours (Nhập giờ làm việc) | — | ❌ | `ImportTimeRecorderLog` |
| AT-23 | Import Accumulated Leave Hours (Nhập giờ nghỉ tích lũy) | — | ❌ | `ImportAccumulation` |
| AT-24 | Export Accumulated Leave Template (Xuất mẫu nghỉ tích lũy) | — | ❌ | `ExportAccumulationTemplate` |

---

## 3. Salary Management (Quản lý Lương)

### 3.1 Salary (Lương)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SA-01 | Salary History (Lịch sử lương) | — | ❌ | `SalaryHistories` |
| SA-02 | Import Salary Info (Nhập thông tin lương) | — | ❌ | `ImportSalaryHistory` |
| SA-03 | Allowance Management (Quản lý phụ cấp) | `admin.allowance_types.index` | ✅ | `AllowanceManagement` |
| SA-04 | Work Summary (Tổng hợp công) | — | ❌ | `FinalProcessedTimesheet` ⚠️ |
| SA-05 | Input Addition (Nhập thu nhập thêm) | — | ❌ | `InputAllowance` |
| SA-06 | Input Deduction (Nhập khấu trừ) | — | ❌ | `InputDeductions` |
| SA-07 | Import Addition/Deduction (Nhập hàng loạt thu nhập/khấu trừ) | — | ❌ | `ImportAdditionDeduction` |
| SA-08 | Import 13th Month Salary Rate (Nhập tỉ lệ lương tháng 13) | — | ❌ | `ImportRateSalary13thMonth` |
| SA-09 | Import Advance (Nhập tạm ứng) | — | ❌ | `ImportAdvance` |
| SA-10 | Import Employee Allowances (Nhập phụ cấp nhân viên) | — | ❌ | `ImportAllowance` |
| SA-11 | Payslip Email History (Lịch sử email phiếu lương) | — | ❌ | `SendMailPayslipManagement` |
| SA-12 | Send Payslip Email (Gửi email phiếu lương) | — | ❌ | `SendMailPayroll` |
| SA-13 | Send Timesheet Email (Gửi email bảng công) | — | ❌ | `SendMailTableTimekeeper` |
| SA-14 | Manage Timesheet Email (Quản lý email bảng công) | — | ❌ | `SendMailTableTimekeeperMgt` |

### 3.2 Calculate Salary (Tính lương)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SA-15 | Calculate Salary (Tính lương) | `admin.calculate_salary.index` | ✅ | `CalculateSalary` |

### 3.3 Salary Reports (Báo cáo Lương)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SA-16 | Payroll (Bảng lương) | `admin.payroll.index` | ✅ | `Payroll` |
| SA-17 | Export Salary to Bank (Xuất lương ngân hàng) | — | ❌ | `ExportSalaryToBank` |
| SA-18 | Export Tax Finalization (Xuất quyết toán thuế) | — | ❌ | `ExportTaxFinalization` |
| SA-19 | Export Advance to Bank (Xuất tạm ứng ngân hàng) | — | ❌ | `ExportAdvanceToBank` |

---

## 4. System Settings (Cài đặt Hệ thống)

### 4.1 Company Structure (Cơ cấu Công ty)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SYS-01 | Organization (Tổ chức / Phòng ban) | `admin.department.index` | ✅ | `Organization` |
| SYS-02 | Offices (Chi nhánh / Văn phòng) | `admin.office.index` | ✅ | `Organization` ⚠️ |

### 4.2 System Configuration (Cấu hình Hệ thống)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SYS-03 | Company Info (Thông tin công ty) | `admin.company.index` | ✅ | `CompanyInfo` |
| SYS-04 | System Parameters (Tham số hệ thống) | — | ❌ | `ApplicationParameter` |
| SYS-05 | Salary Parameters (Tham số lương) | — | ❌ | `Settings` ⚠️ |
| SYS-06 | Sync Data (Đồng bộ dữ liệu) | `admin.sync-data.index` | ✅ | — |
| SYS-07 | DB Migration (Chuyển đổi CSDL) | `admin.db-migration.index` | ✅ | — |

### 4.3 Security (Bảo mật / Phân quyền)

| Mã | Màn hình / Chức năng | Route | Trạng thái | shoji_old Ref |
|----|----------------------|-------|-----------|---------------|
| SYS-08 | Application Modules (Module ứng dụng) | — | ❌ | `ApplicationModuleEditor` |
| SYS-09 | Application Resources (Tài nguyên ứng dụng) | — | ❌ | `ApplicationResource` |
| SYS-10 | Group Access (Phân quyền nhóm) | — | ❌ | `ApplicationEditor` |
| SYS-11 | Employee Access (Phân quyền nhân viên) | — | ❌ | `EmployeActionEditor` |

---

## Tóm tắt

| Nhóm | Tổng | Đã xong | Chưa xong |
|------|------|---------|-----------|
| HR Management | 14 | 5 | 9 |
| Attendance Management | 24 | 3 | 21 |
| Salary Management | 19 | 3 | 16 |
| System Settings | 11 | 6 | 5 |
| **Tổng** | **68** | **17** | **51** |
