# 🗺️ SITEMAP - HỆ THỐNG QUẢN TRỊ NHÂN SỰ (HRM)

> **Dự án:** HRM System  
> **Phiên bản:** 1.1  
> **Ngày cập nhật:** 29/12/2025  
> **Trạng thái:** 🟢 Đã phê duyệt cấu trúc

---

## 📑 MỤC LỤC
1. [Quản Lý Nhân Sự](#1-quản-lý-nhân-sự-human-resources)
2. [Quản Lý Chấm Công](#2-quản-lý-chấm-công-timekeeping)
3. [Quản Lý Lương](#3-quản-lý-lương-payroll)
4. [Cài Đặt Hệ Thống](#4-cài-đặt-system-settings)
5. [Sơ Đồ Cây Tổng Quan](#5-sơ-đồ-cây-tổng-quan-tree-view)
6. [Biểu Đồ Hệ Thống](#6-biểu-đồ-hệ-thống-mermaid)

---

## 1. 👥 QUẢN LÝ NHÂN SỰ (HUMAN RESOURCES)

### 1.1. Quản lý hồ sơ
- [ ] **Danh sách nhân viên** (Trang chính quản lý hồ sơ)
- [ ] **Danh sách nhân viên đã nghỉ việc** (Kho lưu trữ)
- [ ] **Import nhân viên mới** (Tính năng nhập liệu Excel)
- [ ] **Bảo hiểm** (Theo dõi quá trình đóng BHXH/BHYT)
- [ ] **Quản Lý hợp đồng** (Theo dõi hạn HĐ)
- [ ] **Thay đổi mã nhân viên**
- [ ] **Nhập Email Nhân Viên**
- [ ] **Lịch sử đăng nhập** (Audit Log)

### 1.2. Báo cáo nhân sự
- [ ] Export danh sách nhân viên
- [ ] Export danh sách nhân viên đã nghỉ việc
- [ ] Export biến động BHXH (Tháng)
- [ ] Export biến động BHXH (Năm)
- [ ] Xuất hợp đồng lao động (Mail merge/Template)

---

## 2. 🕒 QUẢN LÝ CHẤM CÔNG (TIMEKEEPING)

### 2.1. Nghiệp vụ chấm công
- [ ] **Chấm công tổng hợp** (Màn hình chính xử lý công)
- [ ] **Chỉnh sửa giờ tăng ca** (Duyệt OT)
- [ ] **Tháng chấm công** (Cấu hình chu kỳ)
- [ ] **Chỉnh sửa giờ đi trễ về sớm**
- [ ] **Chỉnh sửa ngày phép**
- [ ] **Xử lý lại dữ liệu công** (Re-calculate)
- [ ] **Tách dữ liệu đi trễ/về sớm**
- [ ] **Danh sách các ngày nghỉ lễ** (Cấu hình lịch năm)
- [ ] **Quản Lý phép năm** (Theo dõi quỹ phép)
- [ ] **Xử lý dữ liệu phép năm** (Chốt phép)
- [ ] **Quản lý phép chế độ, bù, khác**
- [ ] **Tách dữ liệu nghỉ phép**

### 2.2. Dữ liệu máy chấm công
- [ ] Dữ liệu gốc máy chấm công (Raw log)
- [ ] Import giờ công
- [ ] Import lũy kế giờ nghỉ
- [ ] Export Template Lũy Kế Giờ Nghỉ

### 2.3. Báo cáo chấm công
- [ ] Báo cáo giờ tăng ca
- [ ] Báo cáo giờ đi trễ về sớm
- [ ] Báo cáo thông tin nghỉ phép
- [ ] Báo cáo sai ca
- [ ] Báo cáo quẹt thẻ sai
- [ ] Báo cáo bảng tổng hợp công
- [ ] Báo cáo phép năm

---

## 3. 💰 QUẢN LÝ LƯƠNG (PAYROLL)

### 3.1. Nghiệp vụ lương
- [ ] **Lịch sử thông tin lương**
- [ ] **Import thông tin lương**
- [ ] **Quản Lý phụ cấp** (Gán phụ cấp cho nhân viên)
- [ ] **Tổng hợp công** (Đồng bộ từ Module Chấm công)
- [ ] **Khoản cộng / Khoản trừ** (Thưởng/Phạt)
- [ ] **Import dữ liệu lương:**
    - [ ] Import khoản trừ / khoản cộng
    - [ ] Import tỉ lệ lương tháng 13
    - [ ] Import tạm ứng
    - [ ] Import các khoảng trợ cấp
- [ ] **Email bảng lương:**
    - [ ] Quản lý lịch sử gửi mail lương
    - [ ] Gửi Email Bảng Lương
    - [ ] Gửi email bảng công
    - [ ] Quản lý email bảng công (Cấu hình Template)

### 3.2. Tính lương
- [ ] **Quản Lý tính lương** (Engine xử lý tính toán)

### 3.3. Báo cáo lương
- [ ] **Bảng lương** (Phiếu lương chi tiết)
- [ ] Xuất bảng lương chuyển Ngân Hàng
- [ ] Xuất quyết toán thuế
- [ ] Xuất tạm ứng chuyển ngân hàng

---

## 4. ⚙️ CÀI ĐẶT (SYSTEM SETTINGS)

> **Lưu ý cho Dev:** Các mục đánh dấu `[Tab]` nằm chung trong một màn hình cha, chuyển đổi qua lại bằng Tabs UI.

### 4.1. Cấu trúc công ty
#### 📂 Màn hình: Tổ chức (Organization)
Màn hình này chứa các Tabs quản lý dữ liệu sau:
- [ ] `[Tab]` **Nhân viên** (Phân nhóm/Cây tổ chức)
- [ ] `[Tab]` **Nhóm nghỉ**
- [ ] `[Tab]` **Loại nghỉ** (Có lương/Không lương...)
- [ ] `[Tab]` **Loại tăng ca** (Hệ số OT)
- [ ] `[Tab]` **Ca làm việc** (Shift definitions)
- [ ] `[Tab]` **Tháng tính lương** (Cấu hình kỳ lương)
- [ ] `[Tab]` **Tháng chấm công** (Cấu hình kỳ công)
- [ ] `[Tab]` **Quản Lý Team Parent**
- [ ] `[Tab]` **Team Management**

### 4.2. Thiết lập hệ thống
#### 📂 Màn hình: Cài đặt tham số (System Parameters)
Màn hình này chứa các Tabs quản lý danh mục (Master Data):
- [ ] `[Tab]` **Loại phụ cấp** (Cơm, Xăng, Điện thoại...)
- [ ] `[Tab]` **Quốc gia**
- [ ] `[Tab]` **Kỹ năng**
- [ ] `[Tab]` **Chuyên môn**
- [ ] `[Tab]` **Loại hợp đồng**
- [ ] `[Tab]` **Quan hệ gia đình**
- [ ] `[Tab]` **Học vấn**
- [ ] `[Tab]` **Cấp độ** (Level/Rank)
- [ ] `[Tab]` **Tình trạng nhân viên**
- [ ] `[Tab]` **Nhóm nghỉ** (Tham số bổ sung)

#### 📂 Các màn hình cấu hình khác:
- [ ] **Thông tin công ty** (Tên, Logo, Địa chỉ, MST)
- [ ] **Các tham số tính lương** (Công thức lương)

### 4.3. Bảo mật (Security)
- [ ] **Các Module của phần mềm** (Bật/Tắt module)
- [ ] **Các chức năng của phần mềm** (Phân quyền chi tiết)
- [ ] **Phân quyền cho nhóm** (Roles)
- [ ] **Phân quyền nhân viên** (User specific permissions)

---

## 5. 🌳 SƠ ĐỒ CÂY TỔNG QUAN (TREE VIEW)

Cấu trúc phân cấp giúp nhìn nhanh toàn bộ hệ thống.

```text
HỆ THỐNG HRM (ROOT)
├── 1. QUẢN LÝ NHÂN SỰ
│   ├── Quản lý hồ sơ
│   │   ├── Danh sách nhân viên
│   │   ├── Danh sách nhân viên đã nghỉ việc
│   │   ├── Import nhân viên mới
│   │   ├── Bảo hiểm
│   │   ├── Quản lý hợp đồng
│   │   ├── Thay đổi mã nhân viên
│   │   ├── Nhập Email Nhân Viên
│   │   └── Lịch sử đăng nhập
│   └── Báo cáo
│       ├── Export danh sách nhân viên
│       ├── Export danh sách nghỉ việc
│       ├── Export biến động BHXH (Tháng/Năm)
│       └── Xuất hợp đồng lao động
│
├── 2. QUẢN LÝ CHẤM CÔNG
│   ├── Nghiệp vụ chấm công
│   │   ├── Chấm công tổng hợp
│   │   ├── Chỉnh sửa giờ tăng ca
│   │   ├── Tháng chấm công
│   │   ├── Chỉnh sửa giờ đi trễ/về sớm
│   │   ├── Chỉnh sửa ngày phép
│   │   ├── Xử Lý lại dữ liệu
│   │   ├── Tách dữ liệu đi trễ/về sớm
│   │   ├── Danh sách ngày nghỉ lễ
│   │   ├── Quản Lý phép năm (Xử lý dữ liệu phép)
│   │   ├── Quản lý phép chế độ, bù, khác
│   │   └── Tách dữ liệu nghỉ phép
│   ├── Dữ liệu máy chấm công
│   │   ├── Dữ liệu gốc / Import giờ công
│   │   └── Import/Export lũy kế giờ nghỉ
│   └── Báo cáo chấm công
│       ├── BC giờ tăng ca / đi trễ về sớm
│       ├── BC nghỉ phép / sai ca / quẹt thẻ sai
│       └── BC tổng hợp công / phép năm
│
├── 3. QUẢN LÝ LƯƠNG
│   ├── Nghiệp vụ lương
│   │   ├── Thông tin lương (Lịch sử/Import)
│   │   ├── Quản lý phụ cấp
│   │   ├── Tổng hợp công / Khoản cộng / Khoản trừ
│   │   ├── Import các khoản (trừ, cộng, trợ cấp, tạm ứng...)
│   │   └── Email bảng lương (Gửi/Lịch sử/Cấu hình)
│   ├── Tính toán
│   │   └── Quản lý tính lương
│   └── Báo cáo lương
│       ├── Bảng lương
│       ├── Xuất bảng lương chuyển NH
│       └── Xuất quyết toán thuế / Tạm ứng
│
└── 4. CÀI ĐẶT (SYSTEM SETTINGS)
    ├── A. Cấu trúc công ty > Tổ chức [Tabs]
    │   ├── Nhân viên | Nhóm nghỉ | Loại nghỉ
    │   ├── Loại tăng ca | Ca làm việc
    │   ├── Tháng tính lương | Tháng chấm công
    │   └── Quản Lý Team Parent | Team Management
    │
    ├── B. Thiết lập hệ thống
    │   ├── Thông tin công ty & Tham số tính lương
    │   └── Cài đặt tham số [Tabs]
    │       ├── Loại phụ cấp | Quốc gia | Kỹ năng
    │       ├── Chuyên môn | Loại hợp đồng | Quan hệ GĐ
    │       └── Học vấn | Cấp độ | Tình trạng NV | Nhóm nghỉ
    │
    └── C. Bảo mật
        ├── Modules & Chức năng
        └── Phân quyền (Nhóm/Nhân viên)