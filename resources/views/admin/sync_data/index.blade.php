@extends('layouts.app')

@section('content')

    <style>
        label{
            margin-bottom: 5px;
            margin-top: 5px;
        }
    </style>

    <div class="container-fluid">
        <div class="row">
            <!-- ========== FORM 1: IMPORT FILE EXCEL ========== -->
            <div class="col-12 col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Import File Excel</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Import nhiều tables một lúc:</strong> Tải lên file Excel theo template để đồng bộ nhiều tables cùng lúc.
                        </div>

                        <!-- Form import Excel -->
                        <form id="importForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="import_file">Chọn file Excel <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="import_file" name="import_file"
                                       accept=".xlsx,.xls,.csv" required>
                                <small class="form-text text-muted">File Excel (.xlsx, .xls, .csv) - Tối đa 10MB</small>
                            </div>

                            <div class="form-group mt-3">
                                <a href="{{ route('admin.sync-data.download-template') }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-download"></i> Tải Template
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-file-import"></i> Import & Sync
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ========== FORM 2: SYNC NHẬP TAY ========== -->
            <div class="col-12 col-lg-6">
                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title"> Manual</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Sync từng table:</strong> Nhập thông tin table và columns để đồng bộ từng table một.
                        </div>

                        <!-- Form sync manual -->
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

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-sync"></i> Sync Now
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- ========== KẾT QUẢ HIỂN THỊ ========== -->
            <div class="col-12">
                <div id="importResult" class="mt-4" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Kết quả</h4>
                        </div>
                        <div class="card-body">
                            <div id="importResultAlert"></div>
                            <div id="importDetails"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        $(document).ready(function() {

            console.log('=== Document ready fired ===');

            // Kiểm tra jQuery
            if (typeof jQuery === 'undefined') {
                console.error('❌ jQuery CHƯA được load!');
                alert('CRITICAL ERROR: jQuery chưa được load!');
                return;
            }
            console.log('✅ jQuery version:', jQuery.fn.jquery);

            // Kiểm tra Swal
            if (typeof Swal === 'undefined') {
                console.warn('⚠️ SweetAlert2 CHƯA được load!');
            } else {
                console.log('✅ SweetAlert2 đã load');
            }

            // Kiểm tra CSRF token
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            if (!csrfToken) {
                console.error('❌ CSRF token KHÔNG tồn tại!');
                alert('ERROR: CSRF token không tồn tại!');
                return;
            }
            console.log('✅ CSRF token OK');

            // ========== HANDLER: FORM IMPORT EXCEL ==========
            $('#importForm').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('🔥 IMPORT FORM SUBMITTED!');

                try {
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
                    var fileSize = file.size / 1024 / 1024;
                    var fileName = file.name;
                    var fileExt = fileName.split('.').pop().toLowerCase();

                    // Validate size
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
                        processData: false,
                        contentType: false,
                        cache: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            console.log('✅ Import AJAX SUCCESS!', response);
                            handleSyncResponse(response);
                        },
                        error: function(xhr, status, error) {
                            console.error('❌ Import AJAX ERROR!', xhr);
                            handleAjaxError(xhr, status, error);
                        }
                    });

                } catch (err) {
                    console.error('💥 EXCEPTION in import form:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi JavaScript',
                        html: '<strong>Error:</strong> ' + err.message
                    });
                }

                return false;
            });

            // ========== HANDLER: FORM SYNC MANUAL ==========
            $('#manualSyncForm').on('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                console.log('🔥 MANUAL SYNC FORM SUBMITTED!');

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
                            console.log('✅ Manual sync AJAX SUCCESS!', response);

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
                                    timer: 2000,
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
                            console.error('❌ Manual sync AJAX ERROR!', xhr);
                            handleAjaxError(xhr, status, error);
                        }
                    });

                } catch (err) {
                    console.error('💥 EXCEPTION in manual sync form:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi JavaScript',
                        html: '<strong>Error:</strong> ' + err.message
                    });
                }

                return false;
            });

            // ========== HELPER: XỬ LÝ RESPONSE CHUNG ==========
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
                        icon = 'success';
                        title = 'Import thành công!';
                        alertClass = 'alert-success';
                    } else if (failedTables > 0 && successTables > 0) {
                        icon = 'warning';
                        title = 'Import hoàn tất với một số lỗi';
                        alertClass = 'alert-warning';
                    } else if (failedTables > 0 && successTables === 0) {
                        icon = 'error';
                        title = 'Import thất bại';
                        alertClass = 'alert-danger';
                    } else {
                        icon = 'info';
                        title = 'Import hoàn tất';
                        alertClass = 'alert-info';
                    }

                    // Summary HTML
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

                    // Details table
                    var detailsHtml = '';
                    if (data.details && data.details.length > 0) {
                        detailsHtml = `
                    <div class="table-responsive mt-3">
                        <h6>Chi tiết:</h6>
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">Row</th>
                                    <th width="50">STT</th>
                                    <th>Origin Table</th>
                                    <th>Sync Table</th>
                                    <th width="100">Status</th>
                                    <th width="80">Total</th>
                                    <th width="80">Inserted</th>
                                    <th width="80">Failed</th>
                                    <th>Message</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                        data.details.forEach(function(detail) {
                            var rowClass = detail.status === 'success' ? 'table-success' : 'table-danger';
                            var statusIcon = detail.status === 'success' ? 'check' : 'times';
                            var statusBadge = detail.status === 'success' ? 'badge-success' : 'badge-danger';

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
                            <td class="text-right text-success"><strong>${detail.inserted || '-'}</strong></td>
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

                    // Display results
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

                    // SweetAlert
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
                    <p><strong>${insertedRecords}</strong> records đã được insert</p>
                    <hr>
                    <small>Xem chi tiết bên dưới</small>
                `;
                    } else {
                        swalMessage = `
                    <p class="text-danger">Tất cả ${failedTables} tables đều thất bại!</p>
                    <p>Vui lòng kiểm tra lại dữ liệu và thử lại</p>
                    <hr>
                    <small>Xem chi tiết bên dưới</small>
                `;
                    }

                    Swal.fire({
                        icon: icon,
                        title: title,
                        html: swalMessage,
                        timer: failedTables > 0 ? null : 2000,
                        timerProgressBar: failedTables === 0,
                        showConfirmButton: failedTables > 0
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: response.message || 'Có lỗi xảy ra'
                    });
                }
            }

            // ========== HELPER: XỬ LÝ LỖI AJAX ==========
            function handleAjaxError(xhr, status, error) {
                Swal.close();

                var message = 'Có lỗi xảy ra';

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON && xhr.responseJSON.errors;
                    if (errors) {
                        message = 'Lỗi validation:\n' + Object.values(errors).flat().join('\n');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                } else if (xhr.status === 500) {
                    message = 'Lỗi server (500). Vui lòng kiểm tra log Laravel.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message += '\n\n' + xhr.responseJSON.message;
                    }
                } else if (xhr.status === 413) {
                    message = 'File quá lớn! Server không chấp nhận.';
                } else if (xhr.status === 419) {
                    message = 'CSRF token hết hạn. Vui lòng refresh trang (F5).';
                } else if (xhr.status === 404) {
                    message = 'Route không tồn tại (404).';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi (' + xhr.status + ')',
                    html: '<div style="white-space: pre-wrap;">' + message + '</div>',
                    width: 600
                });
            }

            console.log('✅ All event handlers attached');
        });
    </script>
@endsection