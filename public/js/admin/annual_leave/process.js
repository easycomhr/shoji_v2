Ext.onReady(function () {

    var mainForm;

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

    // Safe form processing function
    function safeProcessForm() {
        try {
            if (!mainForm || mainForm.isDestroyed) {
                console.error('Form is not available');
                return;
            }

            var form = mainForm.getForm();
            if (!form.isValid()) {
                Ext.Msg.alert('Validation Error', 'Please fill in all required fields correctly.');
                return;
            }

            var values = form.getValues();

            // Get display values for confirmation
            var yearCombo = mainForm.down('[name=year]');
            var monthCombo = mainForm.down('[name=month]');
            var employeeCombo = mainForm.down('[name=employee_id]');

            var yearText = yearCombo ? yearCombo.getDisplayValue() : 'Not selected';
            var monthText = monthCombo ? monthCombo.getDisplayValue() : 'Not selected';
            var employeeText = 'All employees';

            if (values.employee_id && employeeCombo) {
                var selectedRecord = employeeCombo.getSelection();
                if (selectedRecord) {
                    employeeText = selectedRecord.get('custom_name') || selectedRecord.get('name') || 'Selected employee';
                }
            }

            // Show confirmation dialog
            Ext.Msg.show({
                title: 'Confirm Process',
                message: 'Are you sure you want to process this data?<br/>' +
                    '<b>Year:</b> ' + yearText + '<br/>' +
                    '<b>Month:</b> ' + monthText + '<br/>' +
                    '<b>Employee:</b> ' + employeeText,
                buttons: Ext.Msg.YESNO,
                icon: Ext.Msg.QUESTION,
                fn: function(btn) {
                    if (btn === 'yes') {
                        performProcess(values);
                    }
                }
            });
        } catch (e) {
            console.error('Error in safeProcessForm:', e);
            Ext.Msg.alert('Error', 'An unexpected error occurred. Please try again.');
        }
    }

    function performProcess(values) {
        // Show loading mask
        var loadingMask = new Ext.LoadMask({
            target: mainForm,
            msg: 'Processing data, please wait...'
        });
        loadingMask.show();

        Ext.Ajax.request({
            url: PROCESS_ANNUAL_URL,
            method: 'POST',
            timeout: 60000, // 60 seconds timeout
            params: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                year: values.year,
                month: values.month,
                user_id: values.employee_id || null // Send null if no employee selected
            },
            success: function(response) {
                try {
                    loadingMask.hide();
                    var jsonResponse = Ext.decode(response.responseText);

                    if (jsonResponse.success) {
                        Ext.Msg.show({
                            title: 'Success',
                            message: jsonResponse.message || 'Process completed successfully!',
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.INFO,
                            fn: function(btn) {
                                // Optional: Reset form or reload data
                                // mainForm.getForm().reset();
                            }
                        });
                    } else {
                        Ext.Msg.alert('Processing Error', jsonResponse.message || 'An error occurred during processing');
                    }
                } catch (e) {
                    loadingMask.hide();
                    console.error('Error parsing response:', e);
                    Ext.Msg.alert('Error', 'Invalid response from server. Please try again.');
                }
            },
            failure: function(response) {
                loadingMask.hide();
                var errorMsg = 'Failed to connect to server. Please check your connection and try again.';

                if (response.status === 401 || response.status === 419) {
                    errorMsg = 'Your session has expired. Please refresh the page and try again.';
                } else if (response.status === 500) {
                    errorMsg = 'Server error occurred. Please contact administrator.';
                }

                Ext.Msg.alert('Connection Error', errorMsg);
            }
        });
    }

    mainForm = Ext.create('Ext.form.Panel', {
        title: lblPageTitle,
        frame: true,
        bodyStyle: 'padding:15px',
        width: 550,
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
                title: 'Processing Parameters',
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
                    },
                    {
                        fieldLabel: 'Employee',
                        name: 'employee_id',
                        store: storeUsers,
                        displayField: 'custom_name',
                        valueField: 'id',
                        queryMode: 'local',
                        allowBlank: true,
                        editable: false,
                        emptyText: 'Select an employee (optional)...',
                        // Enable clear button
                        clearable: true,
                        triggers: {
                            clear: {
                                cls: 'x-form-clear-trigger',
                                tooltip: 'Clear selection',
                                handler: function() {
                                    this.setValue(null);
                                    this.fireEvent('change', this, null, this.lastValue);
                                }
                            }
                        },
                        listeners: {
                            change: function(combo, newValue, oldValue) {
                                var processBtn = mainForm.down('button[iconCls="icon-process"]');
                                if (processBtn) {
                                    var btnText = newValue ?
                                        HRMS_LABELS.lblProcess + ' (Selected Employee)' :
                                        HRMS_LABELS.lblProcess + ' (All Employees)';
                                    processBtn.setText(btnText);
                                }
                            }
                        }
                    }
                ]
            },
            {
                xtype: 'displayfield',
                fieldLabel: '',
                value: '<div style="background: #f0f0f0; padding: 8px; border-radius: 3px; font-size: 11px;">' +
                    '<b>Note:</b> If no employee is selected, the process will run for all employees.</div>',
                margin: '10 0'
            }
        ],

        buttons: [
            {
                text: HRMS_LABELS.lblBack,
                iconCls: "icon-back",
                scale: 'medium',
                handler: function() {
                    try {
                        window.location.href = BACK_URL;
                    } catch (e) {
                        console.error('Error navigating back:', e);
                        history.back();
                    }
                }
            },
            '->',
            {
                text: HRMS_LABELS.lblReset,
                iconCls: "icon-refresh",
                scale: 'medium',
                handler: function() {
                    try {
                        if (mainForm && !mainForm.isDestroyed) {
                            mainForm.getForm().reset();

                            // Reset button text
                            var processBtn = mainForm.down('[text*=' + HRMS_LABELS.lblProcess + ']');
                            if (processBtn) {
                                processBtn.setText(HRMS_LABELS.lblProcess + ' (All Employees)');
                            }
                        }
                    } catch (e) {
                        console.error('Error resetting form:', e);
                    }
                }
            },
            {
                text: HRMS_LABELS.lblProcess + ' (All Employees)',
                iconCls: "icon-process",
                scale: 'medium',
                handler: safeProcessForm
            }
        ],

        listeners: {
            afterrender: function() {
                console.log('Form rendered successfully');
            },
            destroy: function() {
                console.log('Form destroyed');
            }
        }
    });

    // Add keyboard shortcuts
    new Ext.util.KeyNav(Ext.getBody(), {
        enter: function(e) {
            if (mainForm && !mainForm.isDestroyed) {
                var focusedField = Ext.ComponentQuery.query('field:focus')[0];
                if (focusedField && focusedField.up('form') === mainForm) {
                    e.preventDefault();
                    safeProcessForm();
                }
            }
        },
        esc: function(e) {
            if (mainForm && !mainForm.isDestroyed) {
                mainForm.getForm().reset();
            }
        }
    });

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

});