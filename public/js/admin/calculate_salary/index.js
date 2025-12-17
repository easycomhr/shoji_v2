Ext.onReady(function () {

    var mainForm;

    // =============================================================================
    // STORES DEFINITION
    // =============================================================================
    var storeSalaryPeriod = Ext.create('Ext.data.Store', {
        fields: [
            'id',
            'name',
            {name: 'from_date', type:'date', dateFormat:'Y-m-d'},
            {name: 'to_date', type:'date', dateFormat: 'Y-m-d'},
            'standard_working_days',
            'is_locked'
        ],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.SALARY_PERIOD_URL,
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
                    console.log('storeSalaryPeriod loaded:', store.getCount(), 'items');
                } else {
                    console.error('Error loading storeSalaryPeriod');
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

    //Additional stores for departments and areas
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
    // UTILITY FUNCTIONS
    // =============================================================================

    // Date formatting function from old code
    var date_format_string = 'd/m/Y';
    function formatDate(value) {
        try {
            if (!value) return '';

            var dateObj = value;
            if (typeof value === 'string') {
                dateObj = new Date(value);
            }

            if (!(dateObj instanceof Date) || isNaN(dateObj.getTime())) {
                return '';
            }

            // Check for epoch date
            var epochDate = new Date(1970, 0, 1);
            if (dateObj.getTime() === epochDate.getTime()) {
                return '';
            }

            // Use Ext.Date.format if available
            if (typeof Ext !== 'undefined' && Ext.Date && Ext.Date.format) {
                return Ext.Date.format(dateObj, 'd/m/Y');
            } else {
                // Fallback manual formatting
                var day = dateObj.getDate().toString().padStart(2, '0');
                var month = (dateObj.getMonth() + 1).toString().padStart(2, '0');
                var year = dateObj.getFullYear();
                return day + '/' + month + '/' + year;
            }
        } catch (e) {
            console.error('Error formatting date:', e);
            return '';
        }
    }

    // Function to update salary period info displays
    function updateSalaryPeriodInfo(record) {
        try {
            // Get the display fields
            var fromDateDisplay = mainForm.down('[itemId=fromDateDisplay]');
            var toDateDisplay = mainForm.down('[itemId=toDateDisplay]');
            var standardWorkingDayDisplay = mainForm.down('[itemId=standardWorkingDayDisplay]');

            if (record && record.data) {

                // Update display values
                if (fromDateDisplay) {
                    fromDateDisplay.setValue('From Date: ' + formatDate(record.data.from_date));
                }
                if (toDateDisplay) {
                    toDateDisplay.setValue('To Date: ' + formatDate(record.data.to_date));
                }
                if (standardWorkingDayDisplay) {
                    standardWorkingDayDisplay.setValue('Standard Working Day: ' + (record.data.standard_working_days || ''));
                }

                // Store selected salary data globally like in old code
                window.selectedSalaryData = record.data;

                // Enable/disable process button based on lock status
                var processBtn = mainForm.down('[iconCls="icon-process"]');
                console.log(typeof record.data.is_locked)
                console.log(processBtn)
                if (processBtn) {
                    processBtn.setDisabled(false);
                }
            } else {
                // Clear displays
                if (fromDateDisplay) fromDateDisplay.setValue('From Date: ');
                if (toDateDisplay) toDateDisplay.setValue('To Date: ');
                if (standardWorkingDayDisplay) standardWorkingDayDisplay.setValue('Standard Working Day: ');

                window.selectedSalaryData = null;
            }
        } catch (e) {
            console.error('Error updating salary period info:', e);
        }
    }

    // =============================================================================
    // EXPORT BY HANDLER FUNCTIONS
    // =============================================================================

    function handleCalculateByChange(radioField, newValue) {
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
        var radios = ['calculateByEmpId', 'calculateByDept', 'calculateByOff', 'calculateByAll'];

        for (var i = 0; i < radios.length; i++) {
            var radio = mainForm.down('[reference=' + radios[i] + ']');
            if (radio && radio.getValue()) {
                selectedRadio = radio.inputValue;
                break;
            }
        }

        console.log(selectedRadio)

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
                { ref: 'calculateByEmpId', combo: 'byEmployeeID', text: 'Nhân viên' },
                { ref: 'calculateByDept', combo: 'byDepartment', text: 'Phòng ban' },
                { ref: 'calculateByOff', combo: 'byOffice', text: 'Khu vực' },
                { ref: 'calculateByAll', combo: null, text: 'Tất cả nhân viên' }
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
            };

            var radios = [
                { ref: 'calculateByEmpId', type: 'EmployeeID', combo: 'byEmployeeID', param: 'employee_id' },
                { ref: 'calculateByDept', type: 'Department', combo: 'byDepartment', param: 'department_id' },
                { ref: 'calculateByAll', type: 'All', combo: null, param: null }
            ];

            for (var i = 0; i < radios.length; i++) {
                var radio = mainForm.down('[reference=' + radios[i].ref + ']');
                if (radio && radio.getValue()) {

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

            // Check if salary period is selected
            var salaryPeriodCombo = mainForm.down('[name=salaryPeriod]');
            if (!salaryPeriodCombo || !salaryPeriodCombo.getValue()) {
                Ext.Msg.alert('Lỗi Validation', 'Vui lòng chọn kỳ lương trước khi xử lý.');
                if (salaryPeriodCombo) salaryPeriodCombo.focus();
                return;
            }

            // Check if salary period is locked
            if (window.selectedSalaryData && window.selectedSalaryData.is_locked) {
                Ext.Msg.alert('Lỗi', 'Kỳ lương này đã bị khóa, không thể xử lý.');
                return;
            }

            // Validate export selection
            if (!validateExportFields()) {
                return;
            }

            var values = form.getValues();

            // Get display values for confirmation
            var salaryPeriodText = salaryPeriodCombo ? salaryPeriodCombo.getDisplayValue() : 'Chưa chọn';
            var exportText = getExportDisplayText();

            // Show confirmation dialog
            Ext.Msg.show({
                title: 'Xác Nhận Xử Lý',
                message: 'Bạn có chắc chắn muốn tính lương cho dữ liệu này?<br/><br/>' +
                    '<b>Kỳ lương:</b> ' + salaryPeriodText + '<br/>' +
                    '<b>Phạm vi:</b> ' + exportText + '<br/>' +
                    (window.selectedSalaryData && window.selectedSalaryData.fromdate ?
                        '<b>Từ ngày:</b> ' + formatDate(window.selectedSalaryData.fromdate) + '<br/>' : '') +
                    (window.selectedSalaryData && window.selectedSalaryData.todate ?
                        '<b>Đến ngày:</b> ' + formatDate(window.selectedSalaryData.todate) + '<br/>' : '') +
                    (window.selectedSalaryData && window.selectedSalaryData.standardworkingday ?
                        '<b>Ngày công chuẩn:</b> ' + window.selectedSalaryData.standardworkingday : ''),
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
            salaryPeriod: values.salaryPeriod,
            calculateBy: values.calculateBy || 'All'
        };

        // Merge export parameters
        Ext.apply(params, exportParams);

        console.log('Sending request with params:', params);

        Ext.Ajax.request({
            url: PROCESS_URL,
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
                    labelWidth: 180,
                    anchor: '100%',
                    margin: '5 0'
                },
                items: [
                    {
                        fieldLabel: 'Select Salary Period <span style="color:red;">*</span>',
                        name: 'salaryPeriod',
                        store: storeSalaryPeriod,
                        displayField: 'name',
                        valueField: 'id',
                        queryMode: 'local',
                        allowBlank: false,
                        editable: false,
                        emptyText: 'Select a Salary Period...',
                        listeners: {
                            change: function(combo, newValue, oldValue) {
                                console.log('Salary period changed from', oldValue, 'to', newValue);

                                if (newValue) {
                                    // Get the selected record
                                    var record = combo.getStore().findRecord('id', newValue);
                                    if (record) {
                                        updateSalaryPeriodInfo(record);
                                    }
                                } else {
                                    updateSalaryPeriodInfo(null);
                                }
                            }
                        }
                    },
                    // Add the display fields for FromDate, ToDate, StandardWorkingDay
                    {
                        xtype: 'displayfield',
                        itemId: 'fromDateDisplay',
                        fieldLabel: '',
                        value: 'From Date: ',
                        fieldCls: 'x-form-item-label',
                        margin: '5 0 0 0'
                    },
                    {
                        xtype: 'displayfield',
                        itemId: 'toDateDisplay',
                        fieldLabel: '',
                        value: 'To Date: ',
                        fieldCls: 'x-form-item-label',
                        margin: '5 0 0 0'
                    },
                    {
                        xtype: 'displayfield',
                        itemId: 'standardWorkingDayDisplay',
                        fieldLabel: '',
                        value: 'Standard Working Day: ',
                        fieldCls: 'x-form-item-label',
                        margin: '5 0 0 0'
                    }
                ]
            },
            {
                // Export By Fieldset
                xtype: 'fieldset',
                title: HRMS_LABELS.lblOption || 'Option',
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
                        flex: 1,
                        layout: 'anchor',
                        defaults: {
                            anchor: '100%'
                        },
                        items: [{
                            xtype: 'radiofield',
                            boxLabel: (HRMS_LABELS.lblExport || 'Export') + ' ' + (HRMS_LABELS.lblEmpId || 'Employee ID'),
                            name: 'calculateBy',
                            inputValue: 'EmployeeID',
                            reference: 'calculateByEmpId',
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleCalculateByChange(field, newValue);
                                }
                            }
                        }, {
                            xtype: 'radiofield',
                            boxLabel: (HRMS_LABELS.lblExport || 'Export') + ' ' + (HRMS_LABELS.lblDepartment || 'Department'),
                            name: 'calculateBy',
                            inputValue: 'Department',
                            reference: 'calculateByDept',
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleCalculateByChange(field, newValue);
                                }
                            }
                        }, {
                            xtype: 'radiofield',
                            boxLabel: HRMS_LABELS.lblAll || 'All',
                            name: 'calculateBy',
                            inputValue: 'All',
                            reference: 'calculateByAll',
                            value: true,
                            listeners: {
                                change: function(field, newValue) {
                                    if (newValue) handleCalculateByChange(field, newValue);
                                }
                            }
                        }]
                    }, {
                        // Combo boxes column
                        xtype: 'container',
                        flex: 1.4,
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
                xtype: 'displayfield',
                fieldLabel: '',
                value: '<div style="background: #f0f8ff; padding: 10px; border: 1px solid #b6d7ff; border-radius: 4px; font-size: 12px;">' +
                    '<div style="font-weight: bold; color: #2c5aa0; margin-bottom: 5px;">📋 Hướng dẫn:</div>' +
                    '• <strong>All:</strong> Xử lý tất cả nhân viên<br/>' +
                    '• <strong>Employee:</strong> Chọn nhân viên cụ thể<br/>' +
                    '• <strong>Department:</strong> Chọn theo phòng ban<br/>' +
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

                            // Reset display fields
                            updateSalaryPeriodInfo(null);

                            // Reset button text
                            var processBtn = mainForm.down('[iconCls="icon-process"]');
                            if (processBtn) {
                                processBtn.setText((HRMS_LABELS.lblProcess || 'Process') + ' (All Employees)');
                                processBtn.setDisabled(true); // Disable until salary period is selected
                            }

                            // Reset export by to "All"
                            Ext.defer(function() {
                                var allRadio = mainForm.down('[reference=calculateByAll]');
                                if (allRadio) {
                                    allRadio.setValue(true);
                                    handleCalculateByChange(allRadio, true);
                                }
                            }, 100);
                        }
                    } catch (e) {
                        console.error('Error resetting form:', e);
                    }
                }
            },
            {
                text: (HRMS_LABELS.lblProcess || 'Process'),
                iconCls: "icon-process",
                scale: 'medium',
                disabled: true, // Initially disabled until salary period is selected
                handler: safeProcessForm
            }
        ],

        listeners: {
            afterrender: function() {
                console.log('Form rendered successfully');

                // Initialize export by state after delay to ensure all components are ready
                Ext.defer(function() {
                    var allRadio = mainForm.down('[reference=calculateByAll]');
                    if (allRadio) {
                        handleCalculateByChange(allRadio, true);
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
        updateSalaryPeriodInfo: updateSalaryPeriodInfo,
        formatDate: formatDate,
        resetExportSelection: function() {
            var allRadio = mainForm.down('[reference=calculateByAll]');
            if (allRadio) {
                allRadio.setValue(true);
                handleCalculateByChange(allRadio, true);
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
    storeUsers.on('exception', handleStoreError);
    storeDepartments.on('exception', handleStoreError);
    storeSalaryPeriod.on('exception', handleStoreError);

    // =============================================================================
    // DEVELOPMENT HELPERS (Remove in production)
    // =============================================================================

    if (typeof console !== 'undefined' && console.log) {
        // Development mode logging
        console.log('=== PAYROLL FORM INITIALIZED ===');
        console.log('Form ID:', mainForm.getId());
        console.log('Available utilities:', Object.keys(window.PayrollFormUtils));
        console.log('Stores loaded:', {
            users: storeUsers.getCount(),
            departments: storeDepartments.getCount(),
            salaryPeriod: storeSalaryPeriod.getCount()
        });

        // Expose form to global scope for debugging
        window.DEBUG_mainForm = mainForm;
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
                            var radioRef = 'calculateBy' + (state.export_by === 'All' ? 'All' :
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