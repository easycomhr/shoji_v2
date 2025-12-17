Ext.onReady(function () {

    var mainForm;

    // =============================================================================
    // STORES DEFINITION
    // =============================================================================

    var storeYears = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.YEARS_URL,
            reader: {
                type: 'json',
                rootProperty: 'data',
                totalProperty: 'results'
            }
        },
        autoLoad: true,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('storeYears loaded:', store.getCount(), 'items');
                    // Set default year to current year
                    var currentYear = new Date().getFullYear();
                    var yearCombo = mainForm ? mainForm.down('[name=year]') : null;
                    if (yearCombo) {
                        yearCombo.setValue(currentYear);
                    }
                } else {
                    console.error('Error loading storeYears');
                }
            }
        }
    });

    var storeMonths = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.MONTHS_URL,
            reader: {
                type: 'json',
                rootProperty: 'data',
                totalProperty: 'results'
            }
        },
        autoLoad: true,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('storeMonths loaded:', store.getCount(), 'items');
                    // Set default month to current month
                    var currentMonth = new Date().getMonth() + 1;
                    var monthCombo = mainForm ? mainForm.down('[name=month]') : null;
                    if (monthCombo) {
                        monthCombo.setValue(currentMonth);
                    }
                } else {
                    console.error('Error loading storeMonths');
                }
            }
        }
    });

    var storeUsers = Ext.create('Ext.data.Store', {
        fields: ['id', 'code', 'name', 'custom_name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.USERS_URL,
            reader: {
                type: 'json',
                rootProperty: 'data',
                totalProperty: 'results'
            }
        },
        autoLoad: true,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('storeUsers loaded:', store.getCount(), 'items');
                } else {
                    console.error('Error loading storeUsers');
                }
            }
        }
    });

    // Additional stores for departments and areas
    var storeDepartments = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.DEPARTMENT_URL, // Add your endpoint
            reader: {
                type: 'json',
                rootProperty: 'data',
                totalProperty: 'results'
            }
        },
        autoLoad: true,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('storeDepartments loaded:', store.getCount(), 'items');
                } else {
                    console.error('Error loading storeDepartments');
                }
            }
        }
    });

    // =============================================================================
    // EXPORT BY HANDLER FUNCTIONS
    // =============================================================================

    function handleExportByChange(radioField, newValue) {
        // Safety check
        if (!newValue || !mainForm) return;

        console.log('Export by changed to:', radioField.inputValue);

        // Get all combo references
        var employeeCombo = mainForm.down('[name=byEmployeeID]');
        var departmentCombo = mainForm.down('[name=byDepartment]');
        var officeCombo = mainForm.down('[name=byOffice]');

        // Reset all combos first
        [employeeCombo, departmentCombo, officeCombo].forEach(function(combo) {
            if (combo) {
                combo.setValue(null);
                combo.setDisabled(true);
                combo.setFieldStyle('background-color: #f5f5f5; color: #999;');
                combo.clearInvalid();
            }
        });

        // Enable the appropriate combo based on selection
        switch (radioField.inputValue) {
            case 'EmployeeID':
                if (employeeCombo) {
                    employeeCombo.setDisabled(false);
                    employeeCombo.setFieldStyle('background-color: white; color: black;');
                    employeeCombo.setEmptyText('Chọn nhân viên...');
                    Ext.defer(function() {
                        if (employeeCombo && !employeeCombo.isDestroyed) {
                            employeeCombo.focus();
                        }
                    }, 100);
                }
                break;

            case 'Department':
                if (departmentCombo) {
                    departmentCombo.setDisabled(false);
                    departmentCombo.setFieldStyle('background-color: white; color: black;');
                    departmentCombo.setEmptyText('Chọn phòng ban...');
                    Ext.defer(function() {
                        if (departmentCombo && !departmentCombo.isDestroyed) {
                            departmentCombo.focus();
                        }
                    }, 100);
                }
                break;

            case 'KV':
                if (officeCombo) {
                    officeCombo.setDisabled(false);
                    officeCombo.setFieldStyle('background-color: white; color: black;');
                    officeCombo.setEmptyText('Chọn khu vực...');
                    Ext.defer(function() {
                        if (officeCombo && !officeCombo.isDestroyed) {
                            officeCombo.focus();
                        }
                    }, 100);
                }
                break;

            case 'All':
            default:
                // All combos remain disabled for "Export All"
                console.log('Export All selected - all combos disabled');
                break;
        }
    }

    function validateExportFields() {
        if (!mainForm) return false;

        // Get selected radio
        var selectedRadio = null;
        var radios = ['exportByEmpId', 'exportByDept', 'exportByOff', 'exportByAll'];

        for (var i = 0; i < radios.length; i++) {
            var radio = mainForm.down('[reference=' + radios[i] + ']');
            if (radio && radio.getValue()) {
                selectedRadio = radio.inputValue;
                break;
            }
        }

        // Validate based on selection
        switch (selectedRadio) {
            case 'EmployeeID':
                var empCombo = mainForm.down('[name=byEmployeeID]');
                if (!empCombo || !empCombo.getValue()) {
                    Ext.Msg.alert('Lỗi Validation', 'Vui lòng chọn nhân viên để xuất dữ liệu.');
                    if (empCombo) empCombo.focus();
                    return false;
                }
                break;

            case 'Department':
                var deptCombo = mainForm.down('[name=byDepartment]');
                if (!deptCombo || !deptCombo.getValue()) {
                    Ext.Msg.alert('Lỗi Validation', 'Vui lòng chọn phòng ban để xuất dữ liệu.');
                    if (deptCombo) deptCombo.focus();
                    return false;
                }
                break;

            case 'KV':
                var officeCombo = mainForm.down('[name=byOffice]');
                if (!officeCombo || !officeCombo.getValue()) {
                    Ext.Msg.alert('Lỗi Validation', 'Vui lòng chọn khu vực để xuất dữ liệu.');
                    if (officeCombo) officeCombo.focus();
                    return false;
                }
                break;
        }

        return true;
    }

    function getExportDisplayText() {
        try {
            var radios = [
                { ref: 'exportByEmpId', combo: 'byEmployeeID', text: 'Nhân viên' },
                { ref: 'exportByDept', combo: 'byDepartment', text: 'Phòng ban' },
                { ref: 'exportByOff', combo: 'byOffice', text: 'Khu vực' },
                { ref: 'exportByAll', combo: null, text: 'Tất cả nhân viên' }
            ];

            for (var i = 0; i < radios.length; i++) {
                var radio = mainForm.down('[reference=' + radios[i].ref + ']');
                if (radio && radio.getValue()) {
                    if (radios[i].combo) {
                        var combo = mainForm.down('[name=' + radios[i].combo + ']');
                        if (combo && combo.getValue()) {
                            return radios[i].text + ': ' + combo.getDisplayValue();
                        }
                    }
                    return radios[i].text;
                }
            }
            return 'Tất cả nhân viên';
        } catch (e) {
            console.error('Error getting export display text:', e);
            return 'Tất cả nhân viên';
        }
    }

    function getExportParams() {
        try {
            var params = {
                export_by: 'All'
            };

            var radios = [
                { ref: 'exportByEmpId', type: 'EmployeeID', combo: 'byEmployeeID', param: 'employee_id' },
                { ref: 'exportByDept', type: 'Department', combo: 'byDepartment', param: 'department_id' },
                { ref: 'exportByOff', type: 'KV', combo: 'byOffice', param: 'area_id' },
                { ref: 'exportByAll', type: 'All', combo: null, param: null }
            ];

            for (var i = 0; i < radios.length; i++) {
                var radio = mainForm.down('[reference=' + radios[i].ref + ']');
                if (radio && radio.getValue()) {
                    params.export_by = radios[i].type;

                    if (radios[i].combo && radios[i].param) {
                        var combo = mainForm.down('[name=' + radios[i].combo + ']');
                        if (combo && combo.getValue()) {
                            params[radios[i].param] = combo.getValue();
                        }
                    }
                    break;
                }
            }

            return params;
        } catch (e) {
            console.error('Error getting export params:', e);
            return { export_by: 'All' };
        }
    }

    function getOptionText() {
        try {
            var detailRadio = mainForm.down('[reference=exportPayrollDetail]');
            if (detailRadio && detailRadio.getValue()) {
                return 'Chi tiết phiếu lương';
            }
            return 'Bảng tổng hợp';
        } catch (e) {
            return 'Bảng tổng hợp';
        }
    }

    // =============================================================================
    // FORM PROCESSING FUNCTIONS
    // =============================================================================

    function safeProcessForm() {
        try {
            if (!mainForm || mainForm.isDestroyed) {
                console.error('Form is not available');
                return;
            }

            var form = mainForm.getForm();
            if (!form.isValid()) {
                Ext.Msg.alert('Validation Error', 'Vui lòng điền đầy đủ các trường bắt buộc.');
                return;
            }

            // Validate export selection
            if (!validateExportFields()) {
                return;
            }

            var values = form.getValues();

            // Get display values for confirmation
            var yearCombo = mainForm.down('[name=year]');
            var monthCombo = mainForm.down('[name=month]');

            var yearText = yearCombo ? yearCombo.getDisplayValue() : 'Chưa chọn';
            var monthText = monthCombo ? monthCombo.getDisplayValue() : 'Chưa chọn';
            var exportText = getExportDisplayText();
            var optionText = getOptionText();

            // Show confirmation dialog
            Ext.Msg.show({
                title: 'Xác Nhận Xử Lý',
                message: 'Bạn có chắc chắn muốn xử lý dữ liệu này?<br/><br/>' +
                    '<b>Năm:</b> ' + yearText + '<br/>' +
                    '<b>Tháng:</b> ' + monthText + '<br/>' +
                    '<b>Phạm vi:</b> ' + exportText + '<br/>' +
                    '<b>Loại báo cáo:</b> ' + optionText,
                buttons: Ext.Msg.YESNO,
                icon: Ext.Msg.QUESTION,
                minWidth: 400,
                fn: function(btn) {
                    if (btn === 'yes') {
                        performProcess(values);
                    }
                }
            });
        } catch (e) {
            console.error('Error in safeProcessForm:', e);
            Ext.Msg.alert('Error', 'Đã xảy ra lỗi không mong muốn. Vui lòng thử lại.');
        }
    }

    function performProcess(values) {
        // Show loading mask
        var loadingMask = new Ext.LoadMask({
            target: mainForm,
            msg: 'Đang xử lý dữ liệu, vui lòng đợi...'
        });
        loadingMask.show();

        // Get export parameters
        var exportParams = getExportParams();

        // Prepare request parameters
        var params = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            year: values.year,
            month: values.month,
            option: values.option || 'exportPayrollFull'
        };

        // Merge export parameters
        Ext.apply(params, exportParams);

        console.log('Sending request with params:', params);

        Ext.Ajax.request({
            url: PROCESS_ANNUAL_URL,
            method: 'POST',
            timeout: 60000, // 60 seconds timeout
            params: params,
            success: function(response) {
                try {
                    loadingMask.hide();
                    var jsonResponse = Ext.decode(response.responseText);

                    if (jsonResponse.success) {
                        Ext.Msg.show({
                            title: 'Thành Công',
                            message: jsonResponse.message || 'Xử lý hoàn tất thành công!',
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.INFO,
                            fn: function(btn) {
                                // Optional: Reset form or reload data
                                // mainForm.getForm().reset();
                            }
                        });
                    } else {
                        Ext.Msg.alert('Lỗi Xử Lý', jsonResponse.message || 'Đã xảy ra lỗi trong quá trình xử lý');
                    }
                } catch (e) {
                    loadingMask.hide();
                    console.error('Error parsing response:', e);
                    Ext.Msg.alert('Error', 'Phản hồi không hợp lệ từ server. Vui lòng thử lại.');
                }
            },
            failure: function(response) {
                loadingMask.hide();
                var errorMsg = 'Không thể kết nối đến server. Vui lòng kiểm tra kết nối và thử lại.';

                if (response.status === 401 || response.status === 419) {
                    errorMsg = 'Phiên làm việc đã hết hạn. Vui lòng refresh trang và thử lại.';
                } else if (response.status === 500) {
                    errorMsg = 'Lỗi server. Vui lòng liên hệ quản trị viên.';
                } else if (response.status === 404) {
                    errorMsg = 'Không tìm thấy endpoint xử lý. Vui lòng liên hệ quản trị viên.';
                }

                Ext.Msg.alert('Lỗi Kết Nối', errorMsg);
            }
        });
    }

    // =============================================================================
    // MAIN FORM CREATION
    // =============================================================================

    mainForm = Ext.create('Ext.form.Panel', {
        title: lblPageTitle,
        frame: true,
        bodyStyle: 'padding:15px',
        width: 700,
        buttonAlign: 'center',
        renderTo: 'main-form',
        defaults: {
            labelWidth: 120,
            anchor: '100%',
            margin: '0 0 10 0'
        },
        items: [
            {
                xtype: 'fieldset',
                title: HRMS_LABELS.lblGeneral,
                defaultType: 'combobox',
                layout: 'anchor',
                defaults: {
                    labelWidth: 100,
                    anchor: '100%',
                    margin: '5 0'
                },
                items: [
                    {
                        fieldLabel: 'Year <span style="color:red;">*</span>',
                        name: 'year',
                        store: storeYears,
                        displayField: 'name',
                        valueField: 'id',
                        queryMode: 'local',
                        allowBlank: false,
                        editable: false,
                        emptyText: 'Select a year...',
                        listeners: {
                            change: function(combo, newValue, oldValue) {
                                console.log('Year changed from', oldValue, 'to', newValue);
                            }
                        }
                    },
                    {
                        fieldLabel: 'Month <span style="color:red;">*</span>',
                        name: 'month',
                        store: storeMonths,
                        displayField: 'name',
                        valueField: 'id',
                        queryMode: 'local',
                        allowBlank: false,
                        editable: false,
                        emptyText: 'Select a month...',
                        listeners: {
                            change: function(combo, newValue, oldValue) {
                                console.log('Month changed from', oldValue, 'to', newValue);
                            }
                        }
                    }
                ]
            },
            {
                // Export By Fieldset
                xtype: 'fieldset',
                title: HRMS_LABELS.lblExportBy || 'Export By',
                layout: 'anchor',
                items: [{
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'stretch'
                    },
                    items: [{
                        // Radio buttons column
                        xtype: 'container',
                        flex: 1.4,
                        layout: 'anchor',
                        defaults: {
                            anchor: '100%'
                        },
                        items: [{
                            xtype: 'radiofield',
                            boxLabel: (HRMS_LABELS.lblExport || 'Export') + ' ' + (HRMS_LABELS.lblEmpId || 'Employee ID'),
                            name: 'exportBy',
                            inputValue: 'EmployeeID',
                            reference: 'exportByEmpId',
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleExportByChange(field, newValue);
                                }
                            }
                        }, {
                            xtype: 'radiofield',
                            boxLabel: (HRMS_LABELS.lblExport || 'Export') + ' ' + (HRMS_LABELS.lblDepartment || 'Department'),
                            name: 'exportBy',
                            inputValue: 'Department',
                            reference: 'exportByDept',
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleExportByChange(field, newValue);
                                }
                            }
                        }, {
                            xtype: 'radiofield',
                            boxLabel: HRMS_LABELS.lblExportAll || 'Export All',
                            name: 'exportBy',
                            inputValue: 'All',
                            reference: 'exportByAll',
                            value: true,
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleExportByChange(field, newValue);
                                }
                            }
                        }]
                    }, {
                        // Combo boxes column
                        xtype: 'container',
                        flex: 1,
                        layout: 'anchor',
                        defaults: {
                            anchor: '100%',
                            margin: '0 0 5 10'
                        },
                        items: [{
                            // Employee combo
                            xtype: 'combobox',
                            name: 'byEmployeeID',
                            reference: 'employeeCombo',
                            store: storeUsers,
                            valueField: 'id',
                            displayField: 'custom_name',
                            queryMode: 'local',
                            typeAhead: true,
                            editable: true,
                            disabled: true,
                            minListWidth: 120,
                            emptyText: 'Chọn nhân viên...',
                            listeners: {
                                change: function(combo, newValue) {
                                    if (newValue) {
                                        console.log('Employee selected:', combo.getDisplayValue());
                                    }
                                }
                            }
                        }, {
                            // Department combo
                            xtype: 'combobox',
                            name: 'byDepartment',
                            reference: 'departmentCombo',
                            store: storeDepartments,
                            valueField: 'id',
                            displayField: 'name',
                            queryMode: 'local',
                            typeAhead: true,
                            disabled: true,
                            minListWidth: 120,
                            emptyText: 'Chọn phòng ban...',
                            listeners: {
                                change: function(combo, newValue) {
                                    if (newValue) {
                                        console.log('Department selected:', combo.getDisplayValue());
                                    }
                                }
                            }
                        }]
                    }]
                }]
            },
            {
                xtype: 'fieldset',
                title: 'Option',
                layout: {
                    type: 'hbox',
                    align: 'stretch'
                },
                items: [
                    {
                        xtype: 'radiofield',
                        name: 'option',
                        boxLabel: HRMS_LABELS.lblExportPayrollDetail || 'Xuất phiếu lương',
                        inputValue: 'exportPayrollDetail',
                        reference: 'exportPayrollDetail',
                        flex: 1
                    },
                    {
                        xtype: 'radiofield',
                        name: 'option',
                        boxLabel: HRMS_LABELS.lblExportPayrollFull || 'Xuất bảng lương',
                        reference: 'exportPayrollFull',
                        inputValue: 'exportPayrollFull',
                        flex: 1,
                        value: true
                    }
                ]
            },
            {
                xtype: 'displayfield',
                fieldLabel: '',
                value: '<div style="background: #f0f8ff; padding: 10px; border: 1px solid #b6d7ff; border-radius: 4px; font-size: 12px;">' +
                    '<div style="font-weight: bold; color: #2c5aa0; margin-bottom: 5px;">📋 Hướng dẫn:</div>' +
                    '• <strong>Export All:</strong> Xử lý tất cả nhân viên<br/>' +
                    '• <strong>Export by Employee:</strong> Chọn nhân viên cụ thể<br/>' +
                    '• <strong>Export by Department:</strong> Chọn theo phòng ban<br/>' +
                    '• <strong>Chi tiết:</strong> Xuất phiếu lương chi tiết<br/>' +
                    '• <strong>Bảng tổng:</strong> Xuất bảng tổng hợp' +
                    '</div>',
                margin: '10 0'
            }
        ],

        buttons: [
            {
                text: HRMS_LABELS.lblBack || 'Back',
                iconCls: "icon-back",
                scale: 'medium',
                handler: function() {
                    try {
                        if (typeof BACK_URL !== 'undefined') {
                            window.location.href = BACK_URL;
                        } else {
                            history.back();
                        }
                    } catch (e) {
                        console.error('Error navigating back:', e);
                        history.back();
                    }
                }
            },
            '->',
            {
                text: HRMS_LABELS.lblReset || 'Reset',
                iconCls: "icon-refresh",
                scale: 'medium',
                handler: function() {
                    try {
                        if (mainForm && !mainForm.isDestroyed) {
                            mainForm.getForm().reset();

                            // Reset button text
                            var processBtn = mainForm.down('[iconCls="icon-process"]');
                            if (processBtn) {
                                processBtn.setText((HRMS_LABELS.lblProcess || 'Process') + ' (All Employees)');
                            }

                            // Reset export by to "All"
                            Ext.defer(function() {
                                var allRadio = mainForm.down('[reference=exportByAll]');
                                if (allRadio) {
                                    allRadio.setValue(true);
                                    handleExportByChange(allRadio, true);
                                }
                            }, 100);
                        }
                    } catch (e) {
                        console.error('Error resetting form:', e);
                    }
                }
            },
            {
                text: (HRMS_LABELS.lblExport || 'Export'),
                iconCls: "icon-export",
                scale: 'medium',
                handler: safeProcessForm
            }
        ],

        listeners: {
            afterrender: function() {
                console.log('Form rendered successfully');

                // Initialize export by state after delay to ensure all components are ready
                Ext.defer(function() {
                    var allRadio = mainForm.down('[reference=exportByAll]');
                    if (allRadio) {
                        handleExportByChange(allRadio, true);
                        console.log('Export by initialized to "All"');
                    }
                }, 300);
            },
            destroy: function() {
                console.log('Form destroyed');
            }
        }
    });

    // =============================================================================
    // UTILITY FUNCTIONS FOR EXTERNAL ACCESS
    // =============================================================================

    // Expose functions globally if needed
    window.PayrollFormUtils = {
        validateExportFields: validateExportFields,
        getExportParams: getExportParams,
        getExportDisplayText: getExportDisplayText,
        resetExportSelection: function() {
            var allRadio = mainForm.down('[reference=exportByAll]');
            if (allRadio) {
                allRadio.setValue(true);
                handleExportByChange(allRadio, true);
            }
        },
        getFormValues: function() {
            if (mainForm && !mainForm.isDestroyed) {
                var values = mainForm.getForm().getValues();
                var exportParams = getExportParams();
                return Ext.apply(values, exportParams);
            }
            return {};
        },
        processForm: function() {
            safeProcessForm();
        }
    };

    // =============================================================================
    // CLEANUP AND ERROR HANDLING
    // =============================================================================

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        try {
            if (mainForm && !mainForm.isDestroyed) {
                mainForm.destroy();
            }
        } catch (e) {
            console.error('Error during cleanup:', e);
        }
    });

    // Global error handler for ExtJS
    Ext.on('error', function(error) {
        console.error('ExtJS Error:', error);
        // Don't show alert for every error, just log it
    });

    // Handle store load errors gracefully
    function handleStoreError(store, operation, eOpts) {
        var storeName = store.$className || 'Unknown Store';
        console.error('Store load failed:', storeName, operation);

        // Show user-friendly message for critical stores
        if (storeName.indexOf('Years') > -1 || storeName.indexOf('Months') > -1) {
            Ext.Msg.show({
                title: 'Lỗi Tải Dữ Liệu',
                message: 'Không thể tải dữ liệu cần thiết. Vui lòng refresh trang và thử lại.',
                buttons: Ext.Msg.OK,
                icon: Ext.Msg.WARNING
            });
        }
    }

    // Add error handlers to stores
    storeYears.on('exception', handleStoreError);
    storeMonths.on('exception', handleStoreError);
    storeUsers.on('exception', handleStoreError);
    // storeDepartments.on('exception', handleStoreError);

    // =============================================================================
    // DEVELOPMENT HELPERS (Remove in production)
    // =============================================================================

    if (typeof console !== 'undefined' && console.log) {
        // Development mode logging
        console.log('=== PAYROLL FORM INITIALIZED ===');
        console.log('Form ID:', mainForm.getId());
        console.log('Available utilities:', Object.keys(window.PayrollFormUtils));
        console.log('Stores loaded:', {
            years: storeYears.getCount(),
            months: storeMonths.getCount(),
            users: storeUsers.getCount(),
            // departments: storeDepartments.getCount(),
        });

        // Expose form to global scope for debugging
        window.DEBUG_mainForm = mainForm;
        window.DEBUG_stores = {
            years: storeYears,
            months: storeMonths,
            users: storeUsers,
            departments: storeDepartments,
        };
    }

    // =============================================================================
    // FORM VALIDATION ENHANCEMENTS
    // =============================================================================

    // Add custom validation messages
    Ext.apply(Ext.form.field.VTypes, {
        yearRange: function(val, field) {
            var currentYear = new Date().getFullYear();
            var year = parseInt(val);
            return year >= (currentYear - 10) && year <= (currentYear + 5);
        },
        yearRangeText: 'Năm phải trong khoảng từ ' + (new Date().getFullYear() - 10) + ' đến ' + (new Date().getFullYear() + 5),

        monthRange: function(val, field) {
            var month = parseInt(val);
            return month >= 1 && month <= 12;
        },
        monthRangeText: 'Tháng phải từ 1 đến 12'
    });

    // =============================================================================
    // RESPONSIVE DESIGN ADJUSTMENTS
    // =============================================================================

    // Adjust form width based on screen size
    function adjustFormSize() {
        if (mainForm && !mainForm.isDestroyed) {
            var viewportWidth = Ext.Element.getViewportWidth();
            var newWidth = Math.min(600, viewportWidth - 40);

            if (newWidth !== mainForm.getWidth()) {
                mainForm.setWidth(newWidth);
                console.log('Form width adjusted to:', newWidth);
            }
        }
    }

    // Adjust on window resize
    Ext.EventManager.onWindowResize(adjustFormSize);

    // Initial adjustment
    Ext.defer(adjustFormSize, 500);

    // =============================================================================
    // ACCESSIBILITY ENHANCEMENTS
    // =============================================================================

    // Add ARIA labels and roles
    mainForm.on('afterrender', function() {
        try {
            var formEl = mainForm.getEl();
            if (formEl) {
                formEl.set({
                    'role': 'form',
                    'aria-label': 'Payroll Processing Form'
                });
            }

            // Add aria-describedby for help text
            var helpDisplay = mainForm.down('displayfield[value*="Hướng dẫn"]');
            if (helpDisplay) {
                helpDisplay.getEl().set({
                    'role': 'region',
                    'aria-label': 'Hướng dẫn sử dụng'
                });
            }
        } catch (e) {
            console.warn('Could not set accessibility attributes:', e);
        }
    });

    // =============================================================================
    // PERFORMANCE MONITORING
    // =============================================================================

    var performanceStart = performance.now();

    mainForm.on('afterrender', function() {
        var performanceEnd = performance.now();
        var loadTime = performanceEnd - performanceStart;
        console.log('Form initialization time:', Math.round(loadTime), 'ms');

        if (loadTime > 2000) {
            console.warn('Form took longer than expected to load. Consider optimization.');
        }
    });

    // =============================================================================
    // AUTO-SAVE FUNCTIONALITY (Optional)
    // =============================================================================

    function saveFormState() {
        try {
            if (mainForm && !mainForm.isDestroyed) {
                var values = mainForm.getForm().getValues();
                var exportParams = getExportParams();
                var state = Ext.apply(values, exportParams);

                // Save to localStorage (if available)
                if (typeof localStorage !== 'undefined') {
                    localStorage.setItem('payroll_form_state', JSON.stringify(state));
                }
            }
        } catch (e) {
            console.warn('Could not save form state:', e);
        }
    }

    function loadFormState() {
        try {
            if (typeof localStorage !== 'undefined') {
                var savedState = localStorage.getItem('payroll_form_state');
                if (savedState) {
                    var state = JSON.parse(savedState);
                    console.log('Restored form state:', state);

                    // Restore basic form values
                    if (mainForm && !mainForm.isDestroyed) {
                        mainForm.getForm().setValues(state);

                        // Restore export selection
                        if (state.export_by) {
                            var radioRef = 'exportBy' + (state.export_by === 'All' ? 'All' :
                                state.export_by === 'EmployeeID' ? 'EmpId' :
                                    state.export_by === 'Department' ? 'Dept' : 'Off');
                            var radio = mainForm.down('[reference=' + radioRef + ']');
                            if (radio) {
                                radio.setValue(true);
                            }
                        }
                    }
                }
            }
        } catch (e) {
            console.warn('Could not load form state:', e);
        }
    }

    // Auto-save on form changes (debounced)
    var saveTask = new Ext.util.DelayedTask(saveFormState);

    mainForm.on('fieldchange', function() {
        saveTask.delay(1000); // Save after 1 second of inactivity
    });

    // Load saved state after form is rendered
    mainForm.on('afterrender', function() {
        Ext.defer(loadFormState, 1000);
    });

});