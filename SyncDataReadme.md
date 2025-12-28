# 📚 HỆ THỐNG ĐỒNG BỘ DỮ LIỆU - HƯỚNG DẪN HOÀN CHỈNH

## 📋 MỤC LỤC

1. [Tổng quan](#tổng-quan)
2. [Kiến trúc hệ thống](#kiến-trúc-hệ-thống)
3. [Method 1: Import File Excel](#method-1-import-file-excel)
4. [Method 2: Sync Nhập Tay](#method-2-sync-nhập-tay)
5. [Backend Service](#backend-service)
6. [Cài đặt](#cài-đặt)
7. [Debug & Troubleshooting](#debug--troubleshooting)
8. [Best Practices](#best-practices)

---

## 🎯 TỔNG QUAN

Hệ thống đồng bộ dữ liệu từ database cũ sang database mới với 2 phương thức:

### Method 1: Import File Excel
- **Mục đích:** Đồng bộ nhiều tables cùng lúc
- **Input:** File Excel (.xlsx, .xls, .csv)
- **Use case:** Production, migration hàng loạt, lặp lại nhiều lần

### Method 2: Sync Nhập Tay
- **Mục đích:** Đồng bộ từng table một
- **Input:** Form nhập thông tin trực tiếp
- **Use case:** Test, debug, sync nhanh 1 table

---

## 🏗️ KIẾN TRÚC HỆ THỐNG

### Database Setup

```
┌─────────────────┐         ┌─────────────────┐
│  Database Cũ    │         │  Database Mới   │
│  (mysql_old)    │  ────>  │  (mysql)        │
│                 │         │                 │
│  tblskills      │         │  skills         │
│  tblemployees   │         │  employees      │
│  ...            │         │  ...            │
└─────────────────┘         └─────────────────┘
```

### Flow Chart

```
┌─────────────────────────────────────────────────┐
│              USER INTERFACE                     │
├──────────────────────┬──────────────────────────┤
│  📁 Form Import      │  ⚙️ Form Manual Sync     │
│  - Upload Excel      │  - Origin Table          │
│  - Submit            │  - Origin Columns        │
│                      │  - Sync Table            │
│                      │  - Sync Columns          │
│                      │  - Submit                │
└──────────┬───────────┴──────────┬───────────────┘
           │                      │
           ▼                      ▼
    ┌──────────────┐      ┌──────────────┐
    │ AJAX Request │      │ AJAX Request │
    │ /import      │      │ /sync-manual │
    └──────┬───────┘      └──────┬───────┘
           │                      │
           ▼                      ▼
    ┌─────────────────────────────────────┐
    │     SyncDataController              │
    │  - import()                         │
    │  - syncManual()                     │
    └──────────────┬──────────────────────┘
                   │
                   ▼
    ┌─────────────────────────────────────┐
    │     SyncDataService                 │
    │  - importAndSync()                  │
    │  - syncData()                       │
    │    1. Check tables exist            │
    │    2. Truncate sync table           │
    │    3. Begin transaction             │
    │    4. Fetch origin data             │
    │    5. Insert to sync table          │
    │    6. Commit transaction            │
    └─────────────────────────────────────┘
```

### File Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Admin/
│           └── SyncDataController.php
└── Services/
    └── SyncDataService.php

resources/
└── views/
    └── admin/
        └── sync-data/
            └── index.blade.php

routes/
└── web.php

config/
└── database.php
```

---

## 📁 METHOD 1: IMPORT FILE EXCEL

### 🎯 Tổng quan

Import file Excel chứa mapping nhiều tables để đồng bộ cùng lúc.

### 📋 Excel Template Format

```
| STT | Origin Table  | Origin Columns    | Sync Table | Sync Columns      |
|-----|--------------|-------------------|------------|-------------------|
| 1   | tblskills    | skill,allowance   | skills     | name,allowance    |
| 2   | tblemployees | emp_id,emp_name   | employees  | id,name           |
| 3   | tbldepts     | dept_id,dept_name | departments| id,name           |
```

**Lưu ý:**
- Row 1: Header (bắt buộc)
- Dữ liệu bắt đầu từ row 2
- Columns phân cách bằng dấu phẩy (,)
- Số lượng Origin Columns = Số lượng Sync Columns
- Thứ tự columns sẽ được map theo thứ tự khai báo

### 🖥️ Frontend - Form Import

**File:** `index_with_manual_sync.blade.php`

**HTML:**
```html
<form id="importForm" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="import_file">Chọn file Excel <span class="text-danger">*</span></label>
        <input type="file" class="form-control" id="import_file" name="import_file"
               accept=".xlsx,.xls,.csv" required>
        <small class="form-text text-muted">File Excel (.xlsx, .xls, .csv) - Tối đa 10MB</small>
    </div>
    
    <div class="form-group">
        <a href="{{ route('admin.sync-data.download-template') }}" class="btn btn-info" target="_blank">
            <i class="fas fa-download"></i> Tải Template
        </a>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-file-import"></i> Import & Sync
        </button>
    </div>
</form>
```

**JavaScript - AJAX Handler:**
```javascript
$('#importForm').on('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    // Validate file
    var fileInput = document.getElementById('import_file');
    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'Vui lòng chọn file để import!'
        });
        return false;
    }
    
    var file = fileInput.files[0];
    var fileSize = file.size / 1024 / 1024; // MB
    var fileName = file.name;
    var fileExt = fileName.split('.').pop().toLowerCase();
    
    // Validate size (max 10MB)
    if (fileSize > 10) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'File quá lớn! Tối đa 10MB (File: ' + fileSize.toFixed(2) + ' MB)'
        });
        return false;
    }
    
    // Validate extension
    if (!['xlsx', 'xls', 'csv'].includes(fileExt)) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi',
            text: 'File không đúng định dạng! Chỉ chấp nhận .xlsx, .xls, .csv'
        });
        return false;
    }
    
    // Create FormData
    var formData = new FormData(this);
    
    // Show loading
    Swal.fire({
        title: 'Đang import và đồng bộ dữ liệu...',
        html: 'Quá trình này có thể mất vài phút<br><small>File: ' + fileName + '</small>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // AJAX request
    $.ajax({
        url: '{{ route("admin.sync-data.import") }}',
        type: 'POST',
        data: formData,
        processData: false,  // Quan trọng: Không xử lý FormData
        contentType: false,  // Quan trọng: Để browser tự set Content-Type
        cache: false,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            // Upload progress
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = (evt.loaded / evt.total) * 100;
                    console.log('Upload:', percentComplete.toFixed(2) + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            console.log('Import success:', response);
            handleSyncResponse(response);
        },
        error: function(xhr, status, error) {
            console.error('Import error:', xhr);
            handleAjaxError(xhr, status, error);
        }
    });
    
    return false;
});
```

### 🔧 Backend - Controller Method

**File:** `SyncDataController.php`

**Method:** `import(Request $request)`

```php
/**
 * Import file Excel và sync nhiều tables
 */
public function import(Request $request)
{
    // Validate request
    $request->validate([
        'import_file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // 10MB max
    ]);

    try {
        $file = $request->file('import_file');

        Log::info("SyncDataController@import - Start", [
            'file' => $file->getClientOriginalName(),
            'size' => $file->getSize()
        ]);

        // Import và sync
        $result = $this->syncDataService->importAndSync($file);

        if ($result['success']) {
            Log::info("SyncDataController@import - Success", [
                'total_tables' => $result['total_tables'],
                'success_tables' => $result['success_tables'],
                'failed_tables' => $result['failed_tables']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Import và đồng bộ hoàn tất',
                'data' => $result
            ]);
        } else {
            Log::error("SyncDataController@import - Failed", [
                'message' => $result['message']
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

    } catch (\Exception $e) {
        Log::error("SyncDataController@import - Exception", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi import: ' . $e->getMessage()
        ], 500);
    }
}
```

### 📊 Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Import và đồng bộ hoàn tất",
    "data": {
        "total_tables": 3,
        "success_tables": 2,
        "failed_tables": 1,
        "total_records": 100,
        "inserted_records": 95,
        "failed_records": 5,
        "details": [
            {
                "row": 2,
                "stt": 1,
                "origin_table": "tblskills",
                "sync_table": "skills",
                "status": "success",
                "total": 50,
                "inserted": 50,
                "failed": 0,
                "message": "Đồng bộ thành công"
            },
            {
                "row": 3,
                "stt": 2,
                "origin_table": "tblemployees",
                "sync_table": "employees",
                "status": "failed",
                "message": "Origin table 'tblemployees' không tồn tại"
            }
        ]
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Lỗi validation: File không đúng định dạng"
}
```

### 🎨 UI Display Logic

**JavaScript - Handle Response:**
```javascript
function handleSyncResponse(response) {
    Swal.close();
    
    if (response.success && response.data) {
        var data = response.data;
        
        var totalTables = data.total_tables || 0;
        var successTables = data.success_tables || 0;
        var failedTables = data.failed_tables || 0;
        var insertedRecords = data.inserted_records || 0;
        
        // Xác định loại thông báo
        var icon, title, alertClass;
        
        if (failedTables === 0 && successTables > 0) {
            // Tất cả thành công
            icon = 'success';
            title = 'Import thành công!';
            alertClass = 'alert-success';
        } else if (failedTables > 0 && successTables > 0) {
            // Một phần thành công
            icon = 'warning';
            title = 'Import hoàn tất với một số lỗi';
            alertClass = 'alert-warning';
        } else if (failedTables > 0 && successTables === 0) {
            // Tất cả thất bại
            icon = 'error';
            title = 'Import thất bại';
            alertClass = 'alert-danger';
        } else {
            icon = 'info';
            title = 'Import hoàn tất';
            alertClass = 'alert-info';
        }
        
        // Hiển thị summary
        var summaryHtml = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Tổng số tables:</strong> ${totalTables}</p>
                    <p><strong>Thành công:</strong> <span class="text-success">${successTables}</span></p>
                    <p><strong>Thất bại:</strong> <span class="text-danger">${failedTables}</span></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tổng số records:</strong> ${data.total_records || 0}</p>
                    <p><strong>Đã insert:</strong> <span class="text-success">${insertedRecords}</span></p>
                    <p><strong>Failed:</strong> <span class="text-danger">${data.failed_records || 0}</span></p>
                </div>
            </div>
        `;
        
        // Hiển thị details table
        var detailsHtml = '';
        if (data.details && data.details.length > 0) {
            detailsHtml = `
                <div class="table-responsive mt-3">
                    <h6>Chi tiết:</h6>
                    <table class="table table-bordered table-sm table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Row</th>
                                <th>STT</th>
                                <th>Origin Table</th>
                                <th>Sync Table</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Inserted</th>
                                <th>Failed</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
            `;
            
            data.details.forEach(function(detail) {
                var rowClass = detail.status === 'success' ? 'table-success' : 'table-danger';
                var statusBadge = detail.status === 'success' ? 'badge-success' : 'badge-danger';
                var statusIcon = detail.status === 'success' ? 'check' : 'times';
                
                detailsHtml += `
                    <tr class="${rowClass}">
                        <td>${detail.row}</td>
                        <td>${detail.stt || '-'}</td>
                        <td><strong>${detail.origin_table}</strong></td>
                        <td><strong>${detail.sync_table}</strong></td>
                        <td>
                            <span class="badge ${statusBadge}">
                                <i class="fas fa-${statusIcon}"></i> ${detail.status}
                            </span>
                        </td>
                        <td class="text-right">${detail.total || '-'}</td>
                        <td class="text-right text-success">${detail.inserted || '-'}</td>
                        <td class="text-right text-danger">${detail.failed || '-'}</td>
                        <td><small>${detail.message}</small></td>
                    </tr>
                `;
            });
            
            detailsHtml += `
                        </tbody>
                    </table>
                </div>
            `;
        }
        
        // Display on page
        $('#importResultAlert').html(`
            <div class="alert ${alertClass}">
                <h5><i class="fas fa-check-circle"></i> ${title}</h5>
                ${summaryHtml}
            </div>
        `);
        $('#importDetails').html(detailsHtml);
        $('#importResult').slideDown();
        
        // Reset form
        $('#importForm')[0].reset();
        
        // SweetAlert notification
        var swalMessage = '';
        if (failedTables === 0 && successTables > 0) {
            swalMessage = `
                <p><strong>${successTables}/${totalTables}</strong> tables đã được đồng bộ thành công</p>
                <p><strong>${insertedRecords}</strong> records đã được insert</p>
            `;
        } else if (failedTables > 0 && successTables > 0) {
            swalMessage = `
                <p><strong>${successTables}/${totalTables}</strong> tables thành công</p>
                <p><strong class="text-danger">${failedTables}/${totalTables}</strong> tables thất bại</p>
                <hr>
                <small>Xem chi tiết bên dưới</small>
            `;
        } else {
            swalMessage = `
                <p class="text-danger">Tất cả ${failedTables} tables đều thất bại!</p>
                <p>Vui lòng kiểm tra lại dữ liệu</p>
                <hr>
                <small>Xem chi tiết bên dưới</small>
            `;
        }
        
        Swal.fire({
            icon: icon,
            title: title,
            html: swalMessage,
            timer: failedTables > 0 ? null : 5000,
            timerProgressBar: failedTables === 0,
            showConfirmButton: failedTables > 0
        });
    }
}
```

---

## ⚙️ METHOD 2: SYNC NHẬP TAY

### 🎯 Tổng quan

Nhập thông tin mapping trực tiếp vào form để đồng bộ 1 table.

### 🖥️ Frontend - Form Manual Sync

**File:** `index_with_manual_sync.blade.php`

**HTML:**
```html
<form id="manualSyncForm">
    @csrf
    
    <div class="form-group">
        <label for="manual_origin_table">Origin Table <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="manual_origin_table" name="origin_table" 
               placeholder="vd: tblskills" required>
        <small class="form-text text-muted">Tên table trong database cũ</small>
    </div>

    <div class="form-group">
        <label for="manual_origin_columns">Origin Columns <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="manual_origin_columns" name="origin_columns" 
               placeholder="vd: skill,allowance" required>
        <small class="form-text text-muted">Các cột phân cách bằng dấu phẩy (,)</small>
    </div>

    <div class="form-group">
        <label for="manual_sync_table">Sync Table <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="manual_sync_table" name="sync_table" 
               placeholder="vd: skills" required>
        <small class="form-text text-muted">Tên table trong database mới</small>
    </div>

    <div class="form-group">
        <label for="manual_sync_columns">Sync Columns <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="manual_sync_columns" name="sync_columns" 
               placeholder="vd: name,allowance" required>
        <small class="form-text text-muted">Các cột phân cách bằng dấu phẩy (,)</small>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">
            <i class="fas fa-sync"></i> Sync Ngay
        </button>
    </div>
</form>
```

**JavaScript - AJAX Handler:**
```javascript
$('#manualSyncForm').on('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    console.log('Manual sync form submitted');
    
    try {
        // Get form data
        var originTable = $('#manual_origin_table').val().trim();
        var originColumns = $('#manual_origin_columns').val().trim();
        var syncTable = $('#manual_sync_table').val().trim();
        var syncColumns = $('#manual_sync_columns').val().trim();
        
        // Validate
        if (!originTable || !originColumns || !syncTable || !syncColumns) {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Vui lòng điền đầy đủ thông tin!'
            });
            return false;
        }
        
        // Show loading
        Swal.fire({
            title: 'Đang đồng bộ dữ liệu...',
            html: `
                <p>Origin: <strong>${originTable}</strong> → Sync: <strong>${syncTable}</strong></p>
                <small>Vui lòng chờ...</small>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Prepare data
        var formData = {
            origin_table: originTable,
            origin_columns: originColumns,
            sync_table: syncTable,
            sync_columns: syncColumns,
            _token: csrfToken
        };
        
        console.log('Manual sync data:', formData);
        
        // AJAX request
        $.ajax({
            url: '{{ route("admin.sync-data.sync-manual") }}',
            type: 'POST',
            data: formData,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            success: function(response) {
                console.log('Manual sync success:', response);
                
                Swal.close();
                
                if (response.success) {
                    var data = response.data || response;
                    
                    // Hiển thị kết quả
                    var summaryHtml = `
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> Sync thành công!</h5>
                            <hr>
                            <p><strong>Origin Table:</strong> ${originTable}</p>
                            <p><strong>Sync Table:</strong> ${syncTable}</p>
                            <hr>
                            <p><strong>Tổng số records:</strong> ${data.total || 0}</p>
                            <p><strong>Đã insert:</strong> <span class="text-success">${data.inserted || 0}</span></p>
                            <p><strong>Failed:</strong> <span class="text-danger">${data.failed || 0}</span></p>
                        </div>
                    `;
                    
                    $('#importResultAlert').html(summaryHtml);
                    $('#importDetails').html('');
                    $('#importResult').slideDown();
                    
                    // Reset form
                    $('#manualSyncForm')[0].reset();
                    
                    // SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Sync thành công!',
                        html: `
                            <p><strong>${data.inserted || 0}</strong> records đã được insert</p>
                            ${data.failed > 0 ? '<p class="text-danger"><strong>' + data.failed + '</strong> records thất bại</p>' : ''}
                        `,
                        timer: 5000,
                        timerProgressBar: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sync thất bại',
                        text: response.message || 'Có lỗi xảy ra khi sync dữ liệu'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Manual sync error:', xhr);
                handleAjaxError(xhr, status, error);
            }
        });
        
    } catch (err) {
        console.error('Exception in manual sync:', err);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi JavaScript',
            html: '<strong>Error:</strong> ' + err.message
        });
    }
    
    return false;
});
```

### 🔧 Backend - Controller Method

**File:** `SyncDataController.php`

**Method:** `syncManual(Request $request)`

```php
/**
 * Sync manual - đồng bộ từng table một
 */
public function syncManual(Request $request)
{
    // Validate request
    $request->validate([
        'origin_table' => 'required|string',
        'origin_columns' => 'required|string',
        'sync_table' => 'required|string',
        'sync_columns' => 'required|string',
    ]);

    try {
        Log::info("SyncDataController@syncManual - Start", [
            'origin_table' => $request->origin_table,
            'sync_table' => $request->sync_table
        ]);

        // Sync data
        $result = $this->syncDataService->syncData($request);

        if ($result['success']) {
            Log::info("SyncDataController@syncManual - Success", [
                'origin_table' => $request->origin_table,
                'sync_table' => $request->sync_table,
                'total' => $result['total'],
                'inserted' => $result['inserted'],
                'failed' => $result['failed']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đồng bộ thành công',
                'data' => $result
            ]);
        } else {
            Log::error("SyncDataController@syncManual - Failed", [
                'message' => $result['message']
            ]);

            return response()->json([
                'success' => false,
                'message' => $result['message']
            ], 400);
        }

    } catch (\Exception $e) {
        Log::error("SyncDataController@syncManual - Exception", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Lỗi khi sync: ' . $e->getMessage()
        ], 500);
    }
}
```

### 📊 Response Format

**Success Response:**
```json
{
    "success": true,
    "message": "Đồng bộ thành công",
    "data": {
        "total": 50,
        "inserted": 50,
        "failed": 0,
        "errors": []
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Origin table 'tblskills' không tồn tại trong database 'old_database'"
}
```

---

## 🛠️ BACKEND SERVICE

### SyncDataService - Core Logic

**File:** `SyncDataService.php`

#### Method: `syncData(Request $request)`

**Flow:**

```
1. Validate columns count
   ↓
2. Check origin table exists (INFORMATION_SCHEMA)
   ↓
3. Check sync table exists (Schema::hasTable)
   ↓
4. TRUNCATE sync table (clear old data)
   ↓
5. Begin Transaction
   ↓
6. Fetch data from origin table
   ↓
7. Loop through rows:
   - Map columns
   - Add timestamps (created_at, updated_at)
   - Add company_id
   - Insert to sync table
   ↓
8. Commit Transaction
   ↓
9. Return result
```

**Code:**

```php
public function syncData($request)
{
    $originTable = trim($request->origin_table);
    $syncTable = trim($request->sync_table);
    
    $originColumns = array_map('trim', explode(',', $request->origin_columns));
    $syncColumns = array_map('trim', explode(',', $request->sync_columns));
    
    // Validate columns count
    if (count($originColumns) !== count($syncColumns)) {
        return [
            'success' => false,
            'message' => 'Số lượng cột Origin và Sync phải bằng nhau'
        ];
    }
    
    $transactionStarted = false;
    
    try {
        // ========== STEP 1: Check tables exist ==========
        
        $database = DB::connection('mysql_old')->getDatabaseName();
        
        // Check origin table
        $originExists = DB::connection('mysql_old')
            ->table('information_schema.tables')
            ->where('table_schema', $database)
            ->where('table_name', $originTable)
            ->exists();
        
        if (!$originExists) {
            return [
                'success' => false,
                'message' => "Origin table '{$originTable}' không tồn tại"
            ];
        }
        
        // Check sync table
        if (!Schema::hasTable($syncTable)) {
            return [
                'success' => false,
                'message' => "Sync table '{$syncTable}' không tồn tại"
            ];
        }
        
        Log::info("Tables checked - OK");
        
        // ========== STEP 2: TRUNCATE (clear old data) ==========
        
        try {
            DB::table($syncTable)->truncate();
            Log::info("Table truncated successfully", ['table' => $syncTable]);
        } catch (\Exception $e) {
            Log::warning("Truncate failed, continuing", ['error' => $e->getMessage()]);
        }
        
        // ========== STEP 3: TRANSACTION + INSERT ==========
        
        DB::beginTransaction();
        $transactionStarted = true;
        
        Log::info("Transaction started");
        
        // Fetch origin data
        $originData = DB::connection('mysql_old')
            ->table($originTable)
            ->select($originColumns)
            ->get();
        
        $total = $originData->count();
        $inserted = 0;
        $failed = 0;
        $errors = [];
        
        Log::info("Fetched data", ['total_rows' => $total]);
        
        // Loop and insert
        foreach ($originData as $index => $row) {
            try {
                // Map data
                $insertData = [
                    'company_id' => config('constants.COMPANY_ID'),
                ];
                
                foreach ($originColumns as $colIndex => $originCol) {
                    $syncCol = $syncColumns[$colIndex];
                    
                    if (!property_exists($row, $originCol)) {
                        throw new \Exception("Column '{$originCol}' không tồn tại");
                    }
                    
                    $insertData[$syncCol] = $row->$originCol;
                }
                
                // Add timestamps
                if (Schema::hasColumn($syncTable, 'created_at') && !isset($insertData['created_at'])) {
                    $insertData['created_at'] = now();
                }
                if (Schema::hasColumn($syncTable, 'updated_at') && !isset($insertData['updated_at'])) {
                    $insertData['updated_at'] = now();
                }
                
                // Insert
                DB::table($syncTable)->insert($insertData);
                $inserted++;
                
            } catch (\Exception $e) {
                $failed++;
                $errors[] = sprintf("Row %d: %s", $index + 1, $e->getMessage());
                
                Log::error("Failed to insert row", [
                    'row' => $index + 1,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Commit
        if ($transactionStarted) {
            DB::commit();
            $transactionStarted = false;
            
            Log::info("Transaction committed", [
                'total' => $total,
                'inserted' => $inserted,
                'failed' => $failed
            ]);
        }
        
        return [
            'success' => true,
            'total' => $total,
            'inserted' => $inserted,
            'failed' => $failed,
            'errors' => $errors
        ];
        
    } catch (\Exception $e) {
        // Rollback if needed
        if ($transactionStarted) {
            try {
                DB::rollBack();
                Log::info("Transaction rolled back");
            } catch (\Exception $rollbackError) {
                Log::error("Error rolling back", ['error' => $rollbackError->getMessage()]);
            }
        }
        
        Log::error("Exception in syncData", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
```

#### Method: `importAndSync($file)`

**Flow:**

```
1. Load Excel file (PhpSpreadsheet)
   ↓
2. Read highest row
   ↓
3. Loop from row 2 (skip header):
   - Read columns: STT, Origin Table, Origin Columns, Sync Table, Sync Columns
   - Skip if empty
   - Create Request object
   - Call syncData()
   - Collect results
   ↓
4. Return summary + details
```

**Code:**

```php
public function importAndSync($file)
{
    try {
        Log::info("importAndSync - Start", [
            'file' => $file->getClientOriginalName(),
            'size' => $file->getSize()
        ]);
        
        // Load Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();
        
        $totalTables = 0;
        $successTables = 0;
        $failedTables = 0;
        $totalRecords = 0;
        $insertedRecords = 0;
        $failedRecords = 0;
        $details = [];
        
        // Loop from row 2 (row 1 is header)
        for ($row = 2; $row <= $highestRow; $row++) {
            // Read data
            $stt = $sheet->getCell('A' . $row)->getValue();
            $originTable = trim($sheet->getCell('B' . $row)->getValue());
            $originColumns = trim($sheet->getCell('C' . $row)->getValue());
            $syncTable = trim($sheet->getCell('D' . $row)->getValue());
            $syncColumns = trim($sheet->getCell('E' . $row)->getValue());
            
            // Skip empty row
            if (empty($originTable) || empty($syncTable)) {
                Log::info("Skipping empty row", ['row' => $row]);
                continue;
            }
            
            $totalTables++;
            
            Log::info("Processing table", [
                'row' => $row,
                'origin_table' => $originTable,
                'sync_table' => $syncTable
            ]);
            
            // Create request
            $request = new \Illuminate\Http\Request([
                'origin_table' => $originTable,
                'sync_table' => $syncTable,
                'origin_columns' => $originColumns,
                'sync_columns' => $syncColumns,
            ]);
            
            // Sync data
            $result = $this->syncData($request);
            
            if ($result['success']) {
                $successTables++;
                $totalRecords += $result['total'];
                $insertedRecords += $result['inserted'];
                $failedRecords += $result['failed'];
                
                $details[] = [
                    'row' => $row,
                    'stt' => $stt,
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable,
                    'status' => 'success',
                    'total' => $result['total'],
                    'inserted' => $result['inserted'],
                    'failed' => $result['failed'],
                    'message' => 'Đồng bộ thành công'
                ];
            } else {
                $failedTables++;
                
                $details[] = [
                    'row' => $row,
                    'stt' => $stt,
                    'origin_table' => $originTable,
                    'sync_table' => $syncTable,
                    'status' => 'failed',
                    'message' => $result['message'] ?? 'Lỗi không xác định'
                ];
                
                Log::warning("Table sync failed", [
                    'row' => $row,
                    'message' => $result['message'] ?? 'Unknown error'
                ]);
            }
        }
        
        Log::info("importAndSync - Completed", [
            'total_tables' => $totalTables,
            'success_tables' => $successTables,
            'failed_tables' => $failedTables
        ]);
        
        return [
            'success' => true,
            'total_tables' => $totalTables,
            'success_tables' => $successTables,
            'failed_tables' => $failedTables,
            'total_records' => $totalRecords,
            'inserted_records' => $insertedRecords,
            'failed_records' => $failedRecords,
            'details' => $details
        ];
        
    } catch (\Exception $e) {
        Log::error("importAndSync - Exception", [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'success' => false,
            'message' => $e->getMessage()
        ];
    }
}
```

---

## 🚀 CÀI ĐẶT

### 1. Database Config

**File:** `config/database.php`

```php
'connections' => [
    
    // Database mới (mặc định)
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'new_database'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        // ...
    ],
    
    // Database cũ
    'mysql_old' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST_OLD', '127.0.0.1'),
        'port' => env('DB_PORT_OLD', '3306'),
        'database' => env('DB_DATABASE_OLD', 'old_database'),
        'username' => env('DB_USERNAME_OLD', 'root'),
        'password' => env('DB_PASSWORD_OLD', ''),
        // ...
    ],
    
],
```

**.env File:**

```env
# Database mới
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=new_database
DB_USERNAME=root
DB_PASSWORD=

# Database cũ
DB_CONNECTION_OLD=mysql
DB_HOST_OLD=127.0.0.1
DB_PORT_OLD=3306
DB_DATABASE_OLD=old_database
DB_USERNAME_OLD=root
DB_PASSWORD_OLD=
```

### 2. Routes

**File:** `routes/web.php`

```php
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    
    Route::prefix('sync-data')->name('sync-data.')->group(function () {
        
        // Trang chính
        Route::get('/', [SyncDataController::class, 'index'])
            ->name('index');
        
        // Import file Excel
        Route::post('/import', [SyncDataController::class, 'import'])
            ->name('import');
        
        // Sync manual
        Route::post('/sync-manual', [SyncDataController::class, 'syncManual'])
            ->name('sync-manual');
        
        // Download template
        Route::get('/download-template', [SyncDataController::class, 'downloadTemplate'])
            ->name('download-template');
    });
    
});
```

### 3. Install PhpSpreadsheet

```bash
composer require phpoffice/phpspreadsheet
```

### 4. Create Constant

**File:** `config/constants.php`

```php
return [
    'COMPANY_ID' => 1, // Hoặc lấy từ Auth::user()->company_id
];
```

### 5. Layout Requirements

**File:** `resources/views/layouts/app.blade.php`

```html
<head>
    <!-- CSRF Token - REQUIRED -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Other meta tags -->
</head>

<body>
    <!-- Content -->
    
    <!-- Scripts - ORDER IS CRITICAL -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>  <!-- 1. jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   <!-- 2. SweetAlert2 -->
    
    @yield('pagescript')  <!-- 3. Page scripts -->
</body>
```

---

## 🐛 DEBUG & TROUBLESHOOTING

### Xem Log

```bash
# Real-time log
tail -f storage/logs/laravel.log

# Clear log
> storage/logs/laravel.log
```

### Common Errors & Solutions

#### 1. "There is no active transaction"

**Nguyên nhân:** Transaction bị phá vỡ hoặc không tồn tại

**Fix:** Đã fix trong code - track `$transactionStarted` và safe rollback

#### 2. "SQL Syntax error: SHOW TABLES LIKE ?"

**Nguyên nhân:** `SHOW TABLES LIKE` không hỗ trợ parameter binding

**Fix:** Dùng `INFORMATION_SCHEMA` thay vì `SHOW TABLES LIKE`

#### 3. "Origin table không tồn tại"

**Nguyên nhân:** Table không có trong database cũ hoặc tên sai (case-sensitive)

**Check:**
```sql
SELECT table_name 
FROM information_schema.tables 
WHERE table_schema = 'old_database';
```

#### 4. "CSRF token hết hạn"

**Nguyên nhân:** Session timeout

**Fix:** Refresh trang (F5) hoặc thêm vào layout:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

#### 5. "Column không tồn tại"

**Nguyên nhân:** Tên column sai hoặc không có trong table

**Check:**
```sql
SHOW COLUMNS FROM tblskills;
```

#### 6. "Cannot truncate table referenced in foreign key"

**Nguyên nhân:** Table có foreign key constraint

**Fix:** Disable foreign key check:
```php
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table($syncTable)->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
```

Hoặc dùng DELETE thay vì TRUNCATE:
```php
DB::table($syncTable)->delete();
```

### Debug Checklist

```
□ jQuery loaded? (Check console: typeof jQuery)
□ SweetAlert2 loaded? (Check console: typeof Swal)
□ CSRF token exists? (Check <meta name="csrf-token">)
□ Form ID correct? (#importForm, #manualSyncForm)
□ Route exists? (php artisan route:list | grep sync)
□ Database connection OK? (php artisan tinker → DB::connection('mysql_old')->getPdo())
□ Tables exist? (SHOW TABLES)
□ Columns exist? (SHOW COLUMNS FROM table_name)
```

---

## ✅ BEST PRACTICES

### 1. Testing Strategy

**Development:**
1. Test với 1 table nhỏ (<10 rows) trước
2. Dùng Manual Sync để test nhanh
3. Kiểm tra log sau mỗi lần sync
4. Verify data trong database

**Production:**
1. Backup database trước khi sync
2. Test trên staging environment trước
3. Sync từng nhóm tables nhỏ
4. Monitor log real-time

### 2. Performance

**Optimization:**
- Dùng batch insert thay vì insert từng row (nếu có nhiều data)
- Index các columns dùng để join
- Tăng `max_execution_time` trong php.ini nếu cần

**Batch Insert Example:**
```php
// Instead of:
foreach ($data as $row) {
    DB::table($table)->insert($row);
}

// Use:
$batch = [];
foreach ($data as $row) {
    $batch[] = $row;
    if (count($batch) >= 1000) {
        DB::table($table)->insert($batch);
        $batch = [];
    }
}
if (!empty($batch)) {
    DB::table($table)->insert($batch);
}
```

### 3. Security

**Validation:**
- Validate file upload (type, size)
- Validate table names (avoid SQL injection)
- Validate column names
- Check user permissions

**SQL Injection Prevention:**
- Dùng parameter binding
- Dùng INFORMATION_SCHEMA
- Không concatenate SQL strings

### 4. Data Integrity

**Transaction:**
- Luôn dùng transaction cho insert
- Rollback nếu có lỗi
- Log chi tiết errors

**Validation:**
- Check table exists
- Check column exists
- Validate data types
- Check foreign key constraints

### 5. Monitoring

**Logging:**
- Log mỗi step quan trọng
- Log errors với stack trace
- Log success với metrics
- Use structured logging (JSON)

**Metrics:**
- Track success rate
- Track execution time
- Track error types
- Monitor database load

---

## 📊 SO SÁNH 2 PHƯƠNG THỨC

| Feature | Import Excel | Sync Manual |
|---------|--------------|-------------|
| **Input** | File Excel | Form nhập tay |
| **Số tables** | Nhiều | 1 table |
| **Tốc độ setup** | Chậm (phải tạo file) | Nhanh (nhập trực tiếp) |
| **Tốc độ sync** | Nhanh (batch) | Tương tự |
| **Lặp lại** | ✅ Dễ (upload lại file) | ⚠️ Phải nhập lại |
| **Production** | ✅ Khuyến nghị | ⚠️ Chỉ test |
| **Debug** | ⚠️ Phải edit file | ✅ Dễ dàng |
| **Template** | ✅ Có | ❌ Không |
| **Validation** | File + Data | Data only |
| **Best for** | Migration hàng loạt | Test & Debug |

**Kết luận:**
- **Import Excel:** Dùng cho production, migration project
- **Sync Manual:** Dùng cho development, testing, debugging

---

## 📚 TÀI LIỆU THAM KHẢO

### Dependencies

- [PhpSpreadsheet Documentation](https://phpspreadsheet.readthedocs.io/)
- [SweetAlert2 Documentation](https://sweetalert2.github.io/)
- [Laravel Database Documentation](https://laravel.com/docs/database)
- [jQuery AJAX Documentation](https://api.jquery.com/jquery.ajax/)

### Laravel Features Used

- Query Builder
- Transactions
- Schema Builder
- File Upload
- Request Validation
- Logging
- Service Pattern

---

## ✅ CHECKLIST HOÀN CHỈNH

### Setup

```
□ Cấu hình database (config/database.php)
□ Thêm connection mysql_old vào .env
□ Install PhpSpreadsheet (composer require)
□ Tạo constant COMPANY_ID (config/constants.php)
□ Thêm CSRF meta tag vào layout
□ Load jQuery và SweetAlert2 đúng thứ tự
```

### Files

```
□ Copy SyncDataService.php
□ Copy SyncDataController.php
□ Copy index_with_manual_sync.blade.php
□ Update routes/web.php
```

### Testing

```
□ Test database connection (mysql_old)
□ Test download template
□ Test import Excel (1 table)
□ Test import Excel (nhiều tables)
□ Test manual sync
□ Test error cases (table không tồn tại, column sai, etc.)
□ Check Laravel log
□ Verify data trong database
```

### Production

```
□ Backup database
□ Test trên staging
□ Prepare rollback plan
□ Monitor log real-time
□ Verify data sau sync
□ Document mapping tables
```

---

**Good luck! 🚀**

---

**Version:** 1.0
**Last Updated:** {{ now() }}
**Author:** AI Assistant