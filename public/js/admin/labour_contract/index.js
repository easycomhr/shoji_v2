Ext.onReady(function(){

    var s_user_id;
    var s_status;
    var mainGird;
    var exportBtn;

    Ext.define('LabourContract', {
        extend : 'Ext.data.Model',
        fields : [
            'id',
            'user_id',
            'code',
            'name',
            'contract_type_id',
            'contract_type_name',
            { name: 'start_date', type: 'date', dateFormat: 'Y-m-d' },
            { name: 'end_date', type: 'date', dateFormat: 'Y-m-d' },
            'status',
            'notes'
        ],
    });

    var userStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'code', 'name', 'custom_name'],
        proxy: {
            type: 'ajax',
            url: MASTER_ROUTE.USERS_URL,
            reader: { type: 'json', rootProperty: 'data' }
        },
        autoLoad: true
    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'LabourContract',
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
                    s_user_id = mainGird.down('#s_user_id').getValue() || '';
                    s_status  = mainGird.down('#s_status').getValue() || '';
                } else {
                    s_user_id = '';
                    s_status  = '';
                }

                mainStore.getProxy().extraParams.user_id = s_user_id;
                mainStore.getProxy().extraParams.status = s_status;

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

    function statusRenderer(value) {
        if (value === 'active') {
            return '<span style="color:green;">' + value + '</span>';
        } else if (value === 'expired') {
            return '<span style="color:orange;">' + value + '</span>';
        } else if (value === 'terminated') {
            return '<span style="color:red;">' + value + '</span>';
        }
        return value || '';
    }

    var statusStore = Ext.create('Ext.data.Store', {
        fields: ['value', 'text'],
        data: [
            { value: 'active',     text: 'active' },
            { value: 'expired',    text: 'expired' },
            { value: 'terminated', text: 'terminated' }
        ]
    });

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
                        xtype        : 'combobox',
                        id           : 's_user_id',
                        itemId       : 's_user_id',
                        store        : userStore,
                        displayField : 'custom_name',
                        valueField   : 'id',
                        queryMode    : 'local',
                        anyMatch     : true,
                        typeAhead    : false,
                        triggerAction: 'all',
                        emptyText    : HRMS_LABELS.lblEmpId,
                        width        : 440,
                        listeners    : {
                            specialkey: function(s, e) {
                                if (e.getKey() === Ext.EventObject.ENTER) {
                                    mainStore.load();
                                }
                            },
                            select: function() {
                                mainStore.load();
                            }
                        }
                    }, {
                        xtype : 'tbseparator'
                    }, {
                        xtype      : 'tbtext',
                        text       : 'Trạng thái',
                        width      : 70
                    }, {
                        xtype      : 'combobox',
                        id         : 's_status',
                        itemId     : 's_status',
                        width      : 120,
                        store      : Ext.create('Ext.data.Store', {
                            fields : ['value', 'text'],
                            data   : [
                                { value: '',           text: 'Tất cả' },
                                { value: 'active',     text: 'active' },
                                { value: 'expired',    text: 'expired' },
                                { value: 'terminated', text: 'terminated' }
                            ]
                        }),
                        displayField  : 'text',
                        valueField    : 'value',
                        value         : '',
                        editable      : false,
                        queryMode     : 'local'
                    }, {
                        xtype : 'tbseparator'
                    }, {
                        iconCls : 'icon-find',
                        text    : HRMS_LABELS.lblFind,
                        scope   : this,
                        handler : onFindClick
                    }, '->', {
                        xtype    : 'button',
                        text     : 'Xuất Excel',
                        iconCls  : 'icon-excel',
                        disabled : true,
                        itemId   : 'btnExport',
                        handler  : onExportClick
                    }, {
                        xtype   : 'button',
                        text    : HRMS_LABELS.lblInsert,
                        iconCls : 'icon-add',
                        handler : onAddClick
                    }
                ]
            },
        ],

        bbar : ['->', myPagingToolbar, { xtype: 'tbfill' }],
        enableTextSelection : true,
        selModel : {
            selType : 'cellmodel',
            listeners : {
                selectionchange : function(sm, selected) {
                    exportBtn = mainGird.down('#btnExport');
                    if (exportBtn) {
                        exportBtn.setDisabled(selected.length !== 1);
                    }
                }
            }
        },
        plugins : [
            {
                ptype        : 'cellediting',
                clicksToEdit : 1,
                autoCancel   : false,
                listeners    : {
                    beforeedit : function(editor, e) {
                        var readOnlyFields = ['user_id', 'code', 'name', 'contract_type_id', 'contract_type_name'];
                        if (Ext.Array.indexOf(readOnlyFields, e.field) !== -1) {
                            return false;
                        }
                    },
                    edit : function(editor, e) {
                        var val = e.value;

                        if (e.field === 'start_date' || e.field === 'end_date') {
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
                header    : 'Loại HĐ',
                dataIndex : 'contract_type_name',
                width     : 150
            }, {
                header    : 'Ngày bắt đầu',
                dataIndex : 'start_date',
                width     : 110,
                align     : 'center',
                renderer  : dateRenderer,
                editor    : {
                    xtype  : 'datefield',
                    format : 'Y-m-d'
                }
            }, {
                header    : 'Ngày kết thúc',
                dataIndex : 'end_date',
                width     : 110,
                align     : 'center',
                renderer  : dateRenderer,
                editor    : {
                    xtype  : 'datefield',
                    format : 'Y-m-d'
                }
            }, {
                header    : 'Trạng thái',
                dataIndex : 'status',
                width     : 110,
                align     : 'center',
                renderer  : statusRenderer,
                editor    : {
                    xtype        : 'combobox',
                    store        : statusStore,
                    displayField : 'text',
                    valueField   : 'value',
                    editable     : false,
                    queryMode    : 'local'
                }
            }, {
                header    : 'Ghi chú',
                dataIndex : 'notes',
                flex      : 1,
                minWidth  : 150,
                editor    : {
                    xtype : 'textfield'
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
                            fieldLabel : 'Loại HĐ ID (*)',
                            name       : 'contract_type_id',
                            allowBlank : false,
                        },
                        {
                            xtype        : 'datefield',
                            fieldLabel   : 'Ngày bắt đầu',
                            name         : 'start_date',
                            format       : 'Y-m-d',
                            submitFormat : 'Y-m-d',
                        },
                        {
                            xtype        : 'datefield',
                            fieldLabel   : 'Ngày kết thúc',
                            name         : 'end_date',
                            format       : 'Y-m-d',
                            submitFormat : 'Y-m-d',
                        },
                        {
                            xtype        : 'combobox',
                            fieldLabel   : 'Trạng thái',
                            name         : 'status',
                            store        : statusStore,
                            displayField : 'text',
                            valueField   : 'value',
                            value        : 'active',
                            editable     : false,
                            queryMode    : 'local'
                        },
                        {
                            xtype      : 'textarea',
                            fieldLabel : 'Ghi chú',
                            name       : 'notes',
                            rows       : 3,
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

    function onExportClick() {
        var selection = mainGird.getView().getSelectionModel().getSelection()[0];
        if (!selection) {
            Ext.Msg.alert('Thông báo', 'Vui lòng chọn 1 hợp đồng để xuất.');
            return;
        }
        window.location.href = URL_EXPORT + '?id=' + selection.data.id;
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
