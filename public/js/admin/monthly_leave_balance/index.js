Ext.onReady(function(){

    var s_year;
    var s_month;
    var s_code;
    var mainGird;

    Ext.define('MonthlyLeaveBalance', {
        extend : 'Ext.data.Model',
        fields: [
            { name: 'id', type: 'int' },
            { name: 'user_id', type: 'int' },
            { name: 'user_code', type: 'string' },
            { name: 'year', type: 'int' },
            { name: 'month', type: 'int' },
            { name: 'opening_balance', type: 'float' },
            { name: 'monthly_accrued', type: 'float' },
            { name: 'monthly_used', type: 'float' },
            { name: 'monthly_remaining', type: 'float' },
        ]
    });

    var userStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'code', 'name'],
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
        autoLoad: false,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('userStore loaded:', store.getCount(), 'items');
                    if (mainStore && !mainStore.isDestroyed) {
                        mainStore.load();
                    }
                } else {
                    console.error('Error loading userStore');
                }
            }
        }
    });

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
        autoLoad: false,
        listeners: {
            load: function(store, records, success) {
                if (success) {
                    console.log('storeYears loaded:', store.getCount(), 'items');
                    if (userStore && !userStore.isDestroyed) {
                        userStore.load();
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
                    if (storeYears && !storeYears.isDestroyed) {
                        storeYears.load();
                    }
                } else {
                    console.error('Error loading storeMonths');
                }
            }
        }
    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'MonthlyLeaveBalance',
        pageSize: SYSTEM_CONSTANT.DEFAULT_PAGE_SIZE,
        proxy : {
            timeout: 60000,
            type : 'ajax',
            url : URL_DATA,
            params: {
                _token: _token,
                action: SYSTEM_CONSTANT.ACTION_VIEW,
            },
            extraParams: {
                orderBy: 'id',
                sortDir: 'DESC'
            },
            reader : {
                type : 'json',
                rootProperty : 'rows',
                successProperty : 'success'
            }
        },
        autoLoad : false,
        loadMask: false,
        listeners:{
            beforeload : function(){
                if (!mainGird || mainGird.isDestroyed || !mainGird.rendered) {
                    console.error('mainGird is not available or has been destroyed');
                    return false;
                }

                try {
                    var codeField = mainGird.down("#s_code");
                    var yearField = mainGird.down("#s_year");
                    var monthField = mainGird.down("#s_month");

                    var s_code = codeField ? String(codeField.getValue() || '') : '';
                    var s_year = yearField ? String(yearField.getValue() || '') : '';
                    var s_month = monthField ? String(monthField.getValue() || '') : '';

                    console.log('beforeload params:', { s_user_code: s_code, s_year: s_year, s_month: s_month });

                    mainStore.proxy.extraParams.s_user_code = s_code;
                    mainStore.proxy.extraParams.s_year = s_year;
                    mainStore.proxy.extraParams.s_month = s_month;
                } catch (e) {
                    console.error('Error getting search parameters:', e);
                    return false;
                }
            },
            load: function () {
                // Handle successful load
            }
        }
    });

    mainStore.sort('id', 'DESC');

    var myPagingToolbar = Ext.create('Ext.PagingToolbar', {
        align: 'center',
        displayInfo: true,
        store: mainStore,
    });

    // Safe loading function
    function safeLoadMainStore() {
        try {
            if (mainStore && !mainStore.isDestroyed && mainGird && !mainGird.isDestroyed && mainGird.rendered) {
                mainStore.load({
                    callback: function(records, operation, success) {
                        if (!success) {
                            var error = operation.getError();
                            if (error && (error.status === 401 || error.status === 419)) {
                                Ext.Msg.alert('Session Expired', 'Your session has expired. Please refresh the page.', function() {
                                    window.location.reload();
                                });
                            } else {
                                console.error('Load failed:', error);
                            }
                        }
                    }
                });
            } else {
                console.warn('Cannot load store - components not available');
                if (confirm('The page needs to be refreshed. Continue?')) {
                    window.location.reload();
                }
            }
        } catch (e) {
            console.error('Error in safeLoadMainStore:', e);
            if (confirm('An error occurred. Refresh the page?')) {
                window.location.reload();
            }
        }
    }

    mainGird = Ext.create('Ext.grid.Panel', {
        renderTo: "main-gird",
        store: mainStore,
        title: lblPageTitle,
        listeners: {
            itemkeydown: function(view, record, item, index, key) {
                if (key.getKey() === SYSTEM_CONSTANT.DELETE_KEY) {
                    var selection = mainGird.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        onDeleteClick(null, null, null, null, null, selection);
                    }
                }
            },
            destroy: function() {
                console.log('mainGird destroyed');
            }
        },
        dockedItems: [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'tbtext',
                text: HRMS_LABELS.lblUserCode,
                width: 100
            }, {
                xtype: 'textfield',
                id: 's_code',
                itemId: 's_code',
                width: 150,
                listeners: {
                    specialkey: function(s, e) {
                        if (e.getKey() === Ext.EventObject.ENTER) {
                            safeLoadMainStore();
                        }
                    }
                }
            },{
                xtype: 'tbtext',
                text: HRMS_LABELS.lblYear,
                width: 50
            }, {
                xtype: 'combobox',
                id: 's_year',
                itemId: 's_year',
                store: storeYears,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                width: 100,
                value: currentYear,
                listeners: {}
            }, {
                xtype: 'tbseparator'
            }, {
                xtype: 'tbtext',
                text: HRMS_LABELS.lblMonth,
                width: 50
            }, {
                xtype: 'combobox',
                id: 's_month',
                itemId: 's_month',
                store: storeMonths,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                width: 100,
                value: currentMonth,
                listeners: {}
            }, {
                xtype: 'tbseparator'
            },{
                iconCls: 'icon-find',
                text: HRMS_LABELS.lblFind,
                handler: onFindClick
            }]
        }],
        bbar: ['->', myPagingToolbar, { xtype: 'tbfill' }],
        selModel: {
            selType: 'cellmodel'
        },
        plugins: [{
            ptype: 'cellediting',
            clicksToEdit: 1,
            autoCancel: false,
            listeners: {
                edit: function(editor, e) {
                    if (e.originalValue != e.value && e.value !== '') {
                        // Handle cell editing - commented out as in original
                    } else {
                        e.record.reject();
                    }
                }
            }
        }, {
            ptype: 'gridfilters'
        }],
        columns: [{
            header: 'ID',
            dataIndex: 'id',
            width: 100,
            hidden: true
        }, {
            header: HRMS_LABELS.lblUserCode,
            dataIndex: 'user_code',
            width: 160,
            editor: {
                xtype: 'textfield'
            }
        }, {
            header: HRMS_LABELS.lblFullName,
            dataIndex: 'user_id',
            width: 180,
            renderer: function(value) {
                if (!userStore || userStore.isDestroyed) {
                    return value;
                }
                var record = userStore.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        }, {
            header: HRMS_LABELS.lblMonth,
            dataIndex: 'month',
            width: 100,
            editor: {
                xtype: 'numberfield',
                allowDecimals: false,
                minValue: 1,
                maxValue: 12
            }
        },{
            header: HRMS_LABELS.lblYear,
            dataIndex: 'year',
            width: 100,
            editor: {
                xtype: 'numberfield',
                allowDecimals: false,
                minValue: 2000,
                maxValue: 9999
            }
        }, {
            header: HRMS_LABELS.lblOpeningBalance,
            dataIndex: 'opening_balance',
            align: 'right',
            width: 140,
            editor: {
                xtype: 'numberfield',
                allowDecimals: true,
                decimalPrecision: 2,
                minValue: 0
            },
            renderer: function(value) {
                return Ext.util.Format.number(value, '0.00');
            }
        }, {
            header: HRMS_LABELS.lblMonthlyAccrued,
            dataIndex: 'monthly_accrued',
            width: 140,
            align: 'right',
            editor: {
                xtype: 'numberfield',
                allowDecimals: true,
                decimalPrecision: 2,
                minValue: 0
            },
            renderer: function(value) {
                return Ext.util.Format.number(value, '0.00');
            }
        }, {
            header: HRMS_LABELS.lblMonthlyUsed,
            dataIndex: 'monthly_used',
            width: 160,
            align: 'right',
            editor: {
                xtype: 'numberfield',
                allowDecimals: true,
                decimalPrecision: 2,
                minValue: 0
            },
            renderer: function(value) {
                return Ext.util.Format.number(value, '0.00');
            }
        }, {
            header: HRMS_LABELS.lblMonthlyRemaining,
            dataIndex: 'monthly_remaining',
            width: 130,
            align: 'right',
            editor: {
                xtype: 'numberfield',
                allowDecimals: true,
                decimalPrecision: 2,
                minValue: 0
            },
            renderer: function(value) {
                return Ext.util.Format.number(value, '0.00');
            }
        }, {
            align: "center",
            header: HRMS_LABELS.lblAction,
            xtype: 'actioncolumn',
            width: 100,
            sortable: false,
            menuDisabled: true,
            items: [{
                iconCls: 'cell-editing-delete-row',
                tooltip: HRMS_LABELS.lblHeaderDelete,
                handler: onDeleteClick
            }]
        }]
    });

    function onDeleteClick(view, recIndex, cellIndex, item, e, selection){
        if (!mainGird || mainGird.isDestroyed) {
            console.error('Grid is not available');
            return;
        }

        Ext.MessageBox.show({
            title: TRANSLATED_LABELS.lblHeaderDelete,
            msg: TRANSLATED_LABELS.lblConfirmDelete,
            icon: Ext.MessageBox.WARNING,
            buttons: Ext.MessageBox.OKCANCEL,
            fn: function(btn) {
                if (btn === 'ok') {
                    if (selection && selection.data) {
                        Ext.Msg.wait(
                            TRANSLATED_LABELS.lblDeletingData,
                            TRANSLATED_LABELS.lblDeletingDataContent,
                            {
                                interval: 1000,
                                duration: 50000,
                                increment: 50,
                                scope: this,
                            }
                        );
                        var conn = new Ext.data.Connection();
                        conn.request({
                            url: URL_DELETE,
                            timeout: APP.TimeOut,
                            params: {
                                _token: _token,
                                action: SYSTEM_CONSTANT.ACTION_DELETE,
                                id: selection.data.id,
                            },
                            success: function(resp, opt) {
                                try {
                                    var result = Ext.util.JSON.decode(resp.responseText);
                                    Ext.Msg.hide();
                                    if (result.success) {
                                        $.showMessage('success', result.message);
                                        if (mainGird && !mainGird.isDestroyed && mainGird.store) {
                                            mainGird.store.remove(selection);
                                        }
                                    } else {
                                        $.showMessage('error', result.message);
                                    }
                                } catch (e) {
                                    console.error('Error parsing response:', e);
                                    Ext.Msg.hide();
                                    $.showMessage('error', 'Error processing server response');
                                }
                            },
                            failure: function(resp, opt) {
                                Ext.Msg.hide();
                                $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                            }
                        });
                    }
                }
            }
        });
    }

    function onFindClick(){
        safeLoadMainStore();
    }

    // Resize handler optimized for ExtJS 6
    var element = Ext.get('main-gird');
    if (element) {
        element.on('resize', function(e) {
            try {
                if (mainGird && !mainGird.isDestroyed && mainGird.rendered) {
                    mainGird.updateLayout();
                }
            } catch (e) {
                console.error('Error in resize handler:', e);
            }
        });
    }

    // Cleanup handler
    window.addEventListener('beforeunload', function() {
        try {
            if (mainGird && !mainGird.isDestroyed) {
                mainGird.destroy();
            }
        } catch (e) {
            console.error('Error during cleanup:', e);
        }
    });

});