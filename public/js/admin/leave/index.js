Ext.onReady(function(){

    var s_from_date;
    var s_to_date;
    var s_code;
    var mainGird;

    Ext.define('AnnualLeave', {
        extend : 'Ext.data.Model',
        fields: [
            { name: 'id', type: 'int' },
            { name: 'user_id', type: 'int' },
            { name: 'user_code', type: 'string' },
            { name: 'leave_date', type: 'date' },
            { name: 'leave_type_id', type: 'int' },
            { name: 'leave_session_id', type: 'int' },
            { name: 'leave_amount', type: 'float' },
            { name: 'comment', type: 'string' },
            { name: 'approved', type: 'int' }
        ]
    });

    var storeLeaveTypes = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.LEAVE_TYPE_URL,
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
                    console.log('storeLeaveTypes loaded:', store.getCount(), 'items');
                    if (userStore && !userStore.isDestroyed) {
                        userStore.load();
                    }
                } else {
                    console.error('Error loading storeLeaveTypes');
                }
            }
        }
    });

    var storeLeaveSessions = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.LEAVE_SESSION_URL,
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
                    console.log('storeLeaveSessions loaded:', store.getCount(), 'items');
                    if (userStore && !userStore.isDestroyed) {
                        userStore.load();
                    }
                } else {
                    console.error('Error loading storeLeaveSessions');
                }
            }
        }
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

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'AnnualLeave',
        pageSize: SYSTEM_CONSTANT.DEFAULT_PAGE_SIZE,
        proxy : {
            timeout : APP.TimeOut,
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
        listeners:{
            beforeload : function(){
                if (!mainGird || mainGird.isDestroyed || !mainGird.rendered) {
                    console.error('mainGird is not available or has been destroyed');
                    return false;
                }

                try {
                    $.loadingStart();

                    var codeField = mainGird.down("#s_code");
                    var fromDateField = mainGird.down("#s_from_date");
                    var toDateField = mainGird.down("#s_to_date");

                    s_code = codeField ? Ext.util.Format.trim(codeField.getValue() || '') : '';
                    s_from_date = fromDateField ? fromDateField.getValue() : '';
                    s_to_date = toDateField ? toDateField.getValue() : '';

                    // Format dates for server
                    if (s_from_date) {
                        s_from_date = Ext.Date.format(s_from_date, 'Y-m-d');
                    }
                    if (s_to_date) {
                        s_to_date = Ext.Date.format(s_to_date, 'Y-m-d');
                    }

                    console.log('beforeload params:', { s_user_code: s_code, s_from_date: s_from_date, s_to_date: s_to_date });

                    mainStore.proxy.extraParams.s_user_code = s_code;
                    mainStore.proxy.extraParams.s_from_date = s_from_date;
                    mainStore.proxy.extraParams.s_to_date = s_to_date;
                } catch (e) {
                    console.error('Error in beforeload:', e);
                    $.loadingEnd();
                    return false;
                }
            },
            load: function () {
                $.loadingEnd();
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
            }, {
                xtype: 'tbseparator'
            }, {
                xtype: 'tbtext',
                text: 'From Date',
                width: 80
            }, {
                xtype: 'datefield',
                id: 's_from_date',
                itemId: 's_from_date',
                format: 'd/m/Y',
                width: 140,
                value: new Date(new Date().getFullYear(), 0, 1), // First day of current year
                listeners: {
                    select: function() {
                        safeLoadMainStore();
                    },
                    specialkey: function(s, e) {
                        if (e.getKey() === Ext.EventObject.ENTER) {
                            safeLoadMainStore();
                        }
                    }
                }
            }, {
                xtype: 'tbtext',
                text: 'To Date',
                width: 60
            }, {
                xtype: 'datefield',
                id: 's_to_date',
                itemId: 's_to_date',
                format: 'd/m/Y',
                width: 140,
                value: new Date(new Date().getFullYear(), 11, 31), // Last day of current year
                listeners: {
                    select: function() {
                        safeLoadMainStore();
                    },
                    specialkey: function(s, e) {
                        if (e.getKey() === Ext.EventObject.ENTER) {
                            safeLoadMainStore();
                        }
                    }
                }
            }, {
                xtype: 'tbseparator'
            }, {
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
            width: 150,
            editor: {
                xtype: 'textfield'
            }
        }, {
            header: HRMS_LABELS.lblName,
            dataIndex: 'user_id',
            width: 200,
            renderer: function(value) {
                if (!userStore || userStore.isDestroyed) {
                    return value;
                }
                var record = userStore.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        }, {
            header: 'Leave date',
            dataIndex: 'leave_date',
            width: 120,
            renderer: function(value) {
                if (value) {
                    return Ext.Date.format(value, 'd/m/Y');
                }
                return value;
            }
        }, {
            header: 'Leave Type',
            dataIndex: 'leave_type_id',
            width: 150,
            renderer: function(value) {
                if (!storeLeaveTypes || storeLeaveTypes.isDestroyed) {
                    return value;
                }
                var record = storeLeaveTypes.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        }, {
            header: 'Session',
            dataIndex: 'leave_session_id',
            width: 100,
            renderer: function(value) {
                if (!storeLeaveSessions || storeLeaveSessions.isDestroyed) {
                    return value;
                }
                var record = storeLeaveSessions.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        }, {
            header: 'Amount',
            dataIndex: 'leave_amount',
            width: 90,
            align: 'right',
            renderer: function(value) {
                return value ? parseFloat(value).toFixed(1) : '0.0';
            }
        },{
            text: 'Approved',
            dataIndex: 'approved',
            width: 90,
            align: 'center',
            renderer: function(value, metaData, record) {
                var checked = (value == 1 || value === true || value === 'true') ? 'checked="checked"' : '';
                var recordId = record.get('id');
                return '<input type="checkbox" ' + checked + ' onchange="toggleApproved(this, ' + recordId + ')" />';
            }
        }, {
            header: 'Note',
            dataIndex: 'note',
            width: 120,
            editor: {
                xtype: 'textfield',
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

    window.toggleApproved = function(checkbox, recordId) {
        try {
            var record = mainStore.findRecord('id', recordId);
            if (record) {
                var newValue = checkbox.checked ? 1 : 0;
                record.set('approved', newValue);

                // Optional: Auto save to server
                // updateRecord(record);
            }
        } catch (e) {
            console.error('Error toggling approved:', e);
        }
    };

});