Ext.onReady(function(){

    var s_name;
    var s_user_id;
    var mainGird;

    Ext.define('UserInsurance', {
        extend : 'Ext.data.Model',
        fields : [
            'id',
            'user_id',
            'code',
            'name',
            'id_card',
            'social_insurance_number',
            { name: 'social_insurance_start_date', type: 'date', dateFormat: 'Y-m-d' },
            'social_insurance_place',
            'health_insurance_number',
            'health_insurance_place',
            { name: 'social_insurance_end_date', type: 'date', dateFormat: 'Y-m-d' },
            'total_months',
            { name: 'is_locked', type: 'boolean' }
        ],
    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'UserInsurance',
        pageSize: SYSTEM_CONSTANT.DEFAULT_PAGE_SIZE,
        proxy : {
            timeout : APP.TimeOut,
            type : 'ajax',
            url : URL_DATA,
            params: {
                _token: _token,
                action: SYSTEM_CONSTANT.ACTION_VIEW,
            },
            reader : {
                type : 'json',
                rootProperty : 'rows',
                successProperty : 'success',
                totalProperty : 'total'
            }
        },
        autoLoad : false,
        listeners:{
            beforeload : function(){
                $.loadingStart();

                if (mainGird && mainGird.rendered) {
                    s_name   = Ext.util.Format.trim(mainGird.down('#s_name').getValue() || '');
                    s_user_id = Ext.util.Format.trim(mainGird.down('#s_user_id').getValue() || '');
                } else {
                    s_name   = '';
                    s_user_id = '';
                }

                mainStore.getProxy().extraParams.name    = s_name;
                mainStore.getProxy().extraParams.user_id = s_user_id;

            }, load: function () {
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

    function dateRenderer(value) {
        if (!value) return '';
        try {
            var d = (value instanceof Date) ? value : new Date(value);
            if (isNaN(d.getTime())) return '';
            return Ext.Date.format(d, 'Y-m-d');
        } catch (e) {
            return '';
        }
    }

    mainGird = Ext.create('Ext.grid.Panel', {
        renderTo: 'main-gird',
        store : mainStore,
        title : lblPageTitle,
        listeners : {
            afterrender: function() {
                mainStore.load();
            },
            itemkeydown: function(view, record, item, index, key) {
                if (key.getKey() === SYSTEM_CONSTANT.DELETE_KEY) {
                    var selection = mainGird.getView().getSelectionModel().getSelection()[0];
                    if (selection) {
                        onDeleteClick(view, null, null, null, null, selection);
                    }
                }
            },
        },
        dockedItems : [
            {
                xtype : 'toolbar',
                dock : 'top',
                items : [
                    {
                        xtype : 'tbtext',
                        text  : HRMS_LABELS.lblEmpId,
                        width : 60
                    }, {
                        xtype  : 'textfield',
                        id     : 's_user_id',
                        itemId : 's_user_id',
                        width  : 120,
                        listeners : {
                            specialkey : function(s, e){
                                if (e.getKey() === Ext.EventObject.ENTER) {
                                    mainStore.load();
                                }
                            }
                        }
                    }, {
                        xtype : 'tbseparator'
                    }, {
                        xtype : 'tbtext',
                        text  : HRMS_LABELS.lblName,
                        width : 50
                    }, {
                        xtype  : 'textfield',
                        id     : 's_name',
                        itemId : 's_name',
                        width  : 200,
                        listeners : {
                            specialkey : function(s, e){
                                if (e.getKey() === Ext.EventObject.ENTER) {
                                    mainStore.load();
                                }
                            }
                        }
                    }, {
                        xtype : 'tbseparator'
                    }, {
                        iconCls : 'icon-find',
                        text    : HRMS_LABELS.lblFind,
                        scope   : this,
                        handler : onFindClick
                    }, '->', {
                        xtype   : 'button',
                        text    : HRMS_LABELS.lblInsert,
                        iconCls : 'icon-add',
                        handler : onAddClick
                    }
                ]
            },
        ],

        bbar : ['->', myPagingToolbar, { xtype: 'tbfill' }],
        selModel : {
            selType : 'cellmodel'
        },
        plugins : [
            {
                ptype        : 'cellediting',
                clicksToEdit : 1,
                autoCancel   : false,
                listeners    : {
                    beforeedit : function(editor, e) {
                        if (e.record.get('is_locked')) {
                            $.showMessage('error', 'This record is locked. Please unlock before editing.');
                            return false;
                        }
                        // user_id, code, name, id_card, total_months are read-only
                        var readOnlyFields = ['user_id', 'code', 'name', 'id_card', 'total_months'];
                        if (Ext.Array.indexOf(readOnlyFields, e.field) !== -1) {
                            return false;
                        }
                    },
                    edit : function(editor, e) {
                        var val = e.value;

                        // Format date fields before sending to server
                        if (e.field === 'social_insurance_start_date' || e.field === 'social_insurance_end_date') {
                            if (val instanceof Date) {
                                val = Ext.Date.format(val, 'Y-m-d');
                            }
                        }

                        if (e.originalValue != e.value && e.value !== '') {
                            var conn = new Ext.data.Connection();
                            conn.request({
                                url     : URL_STORE,
                                timeout : APP.TimeOut,
                                params  : {
                                    _token : _token,
                                    id     : e.record.data.id,
                                    field  : e.field,
                                    value  : val,
                                },
                                success : function(resp, opt){
                                    var result = Ext.util.JSON.decode(resp.responseText);
                                    if (result.success) {
                                        $.showMessage('success', result.message || 'Saved');
                                        e.record.commit();
                                    } else {
                                        $.showMessage('error', result.message || 'Error');
                                        e.record.reject();
                                    }
                                },
                                failure : function(){
                                    e.record.reject();
                                    $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                                }
                            });
                        } else {
                            e.record.reject();
                        }
                    }
                }
            }, {
                ptype : 'gridfilters'
            }
        ],
        columns : [
            {
                header    : 'ID',
                dataIndex : 'id',
                width     : 60,
                hidden    : true
            }, {
                header    : HRMS_LABELS.lblEmpId,
                dataIndex : 'code',
                width     : 140,
                align     : 'center'
            }, {
                header    : HRMS_LABELS.lblName,
                dataIndex : 'name',
                flex      : 1,
                minWidth  : 200
            }, {
                header    : 'ID Card',
                dataIndex : 'id_card',
                width     : 120
            }, {
                header    : 'SI Number',
                dataIndex : 'social_insurance_number',
                width     : 130,
                editor    : {
                    xtype : 'textfield'
                }
            }, {
                header    : 'SI Start Date',
                dataIndex : 'social_insurance_start_date',
                width     : 110,
                align     : 'center',
                renderer  : dateRenderer,
                editor    : {
                    xtype  : 'datefield',
                    format : 'Y-m-d'
                }
            }, {
                header    : 'SI Place',
                dataIndex : 'social_insurance_place',
                width     : 130,
                editor    : {
                    xtype : 'textfield'
                }
            }, {
                header    : 'Health Ins. Number',
                dataIndex : 'health_insurance_number',
                width     : 130,
                editor    : {
                    xtype : 'textfield'
                }
            }, {
                header    : 'Health Place',
                dataIndex : 'health_insurance_place',
                width     : 130,
                editor    : {
                    xtype : 'textfield'
                }
            }, {
                header    : 'End Date',
                dataIndex : 'social_insurance_end_date',
                width     : 110,
                align     : 'center',
                renderer  : dateRenderer,
                editor    : {
                    xtype  : 'datefield',
                    format : 'Y-m-d'
                }
            }, {
                header    : 'Total Months',
                dataIndex : 'total_months',
                width     : 100,
                align     : 'center'
            }, {
                header    : HRMS_LABELS.lblLocked,
                dataIndex : 'is_locked',
                width     : 80,
                align     : 'center',
                renderer  : function(value) {
                    return value ? '<span style="color:red;">Yes</span>' : 'No';
                }
            }, {
                align        : 'center',
                header       : HRMS_LABELS.lblAction,
                xtype        : 'actioncolumn',
                width        : 80,
                sortable     : false,
                menuDisabled : true,
                items        : [
                    {
                        iconCls : 'cell-editing-delete-row',
                        tooltip : HRMS_LABELS.lblDelete,
                        handler : onDeleteClick
                    }
                ],
            }
        ],
    });

    function initModalAdd() {
        return Ext.create('Ext.window.Window', {
            title       : HRMS_LABELS.lblCreate,
            modal       : true,
            width       : 520,
            y           : 80,
            closeAction : 'destroy',
            items: [
                {
                    xtype       : 'form',
                    bodyPadding : 10,
                    defaults    : {
                        labelWidth : 150,
                        width      : '100%'
                    },
                    items: [
                        {
                            xtype : 'hiddenfield',
                            name  : '_token',
                            value : _token,
                        },
                        {
                            xtype      : 'textfield',
                            fieldLabel : HRMS_LABELS.lblEmpId + ' (*)',
                            name       : 'user_id',
                            allowBlank : false,
                        },
                        {
                            xtype      : 'textfield',
                            fieldLabel : 'SI Number',
                            name       : 'social_insurance_number',
                        },
                        {
                            xtype      : 'datefield',
                            fieldLabel : 'SI Start Date',
                            name       : 'social_insurance_start_date',
                            format     : 'Y-m-d',
                            submitFormat: 'Y-m-d',
                        },
                        {
                            xtype      : 'textfield',
                            fieldLabel : 'SI Place',
                            name       : 'social_insurance_place',
                        },
                        {
                            xtype      : 'textfield',
                            fieldLabel : 'Health Ins. Number',
                            name       : 'health_insurance_number',
                        },
                        {
                            xtype      : 'textfield',
                            fieldLabel : 'Health Place',
                            name       : 'health_insurance_place',
                        },
                        {
                            xtype      : 'datefield',
                            fieldLabel : 'End Date',
                            name       : 'social_insurance_end_date',
                            format     : 'Y-m-d',
                            submitFormat: 'Y-m-d',
                        },
                        {
                            xtype      : 'checkboxfield',
                            fieldLabel : HRMS_LABELS.lblContinueAdd,
                            name       : 'is_continue',
                            checked    : false
                        }
                    ]
                }
            ],
            buttons: {
                layout: {
                    pack: 'center'
                },
                items: [
                    {
                        text    : HRMS_LABELS.lblSave,
                        handler : function () {
                            var modal = this.up('window');
                            var form  = this.up('window').down('form');
                            if (form.isValid()) {
                                var formData = form.getValues();
                                saveData(formData, form, modal);
                            } else {
                                Ext.Msg.alert('Warning', 'Please check input data.');
                            }
                        }
                    },
                    {
                        text    : HRMS_LABELS.lblClose,
                        handler : function () {
                            this.up('window').close();
                        }
                    }
                ]
            },
        });
    }

    function onAddClick() {
        var modalAdd = initModalAdd();
        modalAdd.show();
    }

    function saveData(formData, form, modal) {
        var conn = new Ext.data.Connection();
        conn.request({
            url     : URL_STORE,
            timeout : APP.TimeOut,
            params  : formData,
            success : function(resp, opt){
                $.loadingEnd();
                var result = Ext.util.JSON.decode(resp.responseText);
                if (result.success) {
                    $.showMessage('success', result.message || 'Saved');

                    mainStore.load();
                    mainGird.getView().scrollTo(0, 0);

                    if (parseInt(formData.is_continue) !== 1) {
                        modal.close();
                    } else {
                        form.reset();
                    }
                } else {
                    $.showMessage('error', result.message || 'Error');
                }
            },
            failure : function(){
                $.loadingEnd();
                $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
            }
        });
    }

    function onDeleteClick(view, recIndex, cellIndex, item, e, selection) {
        Ext.MessageBox.show({
            title   : TRANSLATED_LABELS.lblHeaderDelete,
            msg     : TRANSLATED_LABELS.lblConfirmDelete,
            icon    : Ext.MessageBox.WARNING,
            buttons : Ext.MessageBox.OKCANCEL,
            fn      : function(btn) {
                if (btn === 'ok') {
                    Ext.Msg.wait(
                        TRANSLATED_LABELS.lblDeletingData,
                        TRANSLATED_LABELS.lblDeletingDataContent,
                        {
                            interval  : 1000,
                            duration  : 50000,
                            increment : 50,
                            scope     : this,
                        }
                    );
                    selection = selection ? selection : mainGird.getView().getSelectionModel().getSelection()[0];
                    var conn = new Ext.data.Connection();
                    conn.request({
                        url     : URL_DELETE,
                        timeout : APP.TimeOut,
                        params  : {
                            _token : _token,
                            action : SYSTEM_CONSTANT.ACTION_DELETE,
                            id     : selection.data.id,
                        },
                        success : function(resp, opt){
                            var result = Ext.util.JSON.decode(resp.responseText);
                            Ext.Msg.hide();
                            if (result.success) {
                                $.showMessage('success', result.message || 'Deleted');
                                mainGird.store.remove(selection);
                            } else {
                                $.showMessage('error', result.message || 'Error');
                            }
                        },
                        failure : function(){
                            $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                        }
                    });
                }
            }
        });
    }

    function onFindClick() {
        mainStore.load();
    }

    var element = Ext.get('main-gird');
    if (element) {
        element.on('resize', function() {
            mainGird.update();
        });
    }

});
