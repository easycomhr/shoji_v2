Ext.onReady(function(){

    var s_name;
    var s_code;
    var mainGird;

    Ext.define('LeaveType', {
        extend : 'Ext.data.Model',
        fields : [
            'id', 'leave_category_id', 'code', 'name', 'kind', 'paid_rate', 'note', 'status'
        ],

    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'LeaveType',
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
                orderBy: 'id',        // Sắp xếp theo trường 'id'
                sortDir: 'DESC'       // Hướng sắp xếp giảm dần
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
                $.loadingStart();
                if (mainGird && mainGird.rendered) {
                    s_name = Ext.util.Format.trim(mainGird.down("#s_name").getValue() || '');
                    s_code = Ext.util.Format.trim(mainGird.down("#s_code").getValue() || '');
                } else {
                    s_name = '';
                    s_code = '';
                }

                mainStore.getProxy().extraParams.name = s_name;
                mainStore.getProxy().extraParams.code = s_code;

            },load: function () {
                $.loadingEnd();
            }
        }

    });

    mainStore.sort('id', 'DESC');

    var storeLeaveCategory = Ext.create('Ext.data.Store', {
        fields : [
            'id', 'code','name'
        ],
        proxy : {
            timeout : APP.TimeOut,
            type : 'ajax',
            url : URL_DATA_LEAVE_CATEGORY,
            reader : {
                type : 'json',
                rootProperty : "data",
                totalProperty : "results"
            }
        },
        autoLoad : true,
        listeners: {
            load: function (store, records, success) {
                if (success) {
                    mainStore.load(); // Chỉ load mainStore sau khi storeLeaveCategory load thành công
                } else {
                    console.error('Error loading storeLeaveCategory');
                }
            }
        }
    });

    function renderLeaveCategory(value) {
        try {
            console.log('Value:', value);
            console.log('Store data:', storeLeaveCategory.getData().items);

            if (value == null || value === '') {
                return '';
            }

            const record = storeLeaveCategory.queryBy(function(rec) {
                return rec.get('id') === value; // Sử dụng rec.get để truy xuất giá trị chính xác
            }).first(); // Lấy bản ghi đầu tiên tìm thấy

            return record ? record.get('name') : ''; // Trả về `name` hoặc chuỗi rỗng nếu không tìm thấy
        } catch (e) {
            console.error('Error in renderLeaveCategory:', e);
        }
    }



    var myPagingToolbar = Ext.create('Ext.PagingToolbar', {
        align: 'center',
        displayInfo: true,
        store: mainStore,

    });

    mainGird = Ext.create('Ext.grid.Panel', {
        renderTo: "main-gird",
        store : mainStore,
        title : lblPageTitle,
        listeners : {
            afterrender: function() {
                console.log("mainGird rendered");
                mainStore.load();
            },
            itemkeydown: function(view, record, item, index, key) {
                if (key.getKey() === SYSTEM_CONSTANT.DELETE_KEY) {
                    var selection = mainGird.getView().getSelectionModel().getSelection()[0];
                    if(selection){
                        onDeleteClick()
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
                        text : HRMS_LABELS.lblCode,
                        width : 50
                    }, {
                        xtype : 'textfield',
                        id : 's_code',
                        itemId : 's_code',
                        width : 150,
                        listeners : {
                            specialkey : function(s, e){

                                if(e.getKey() === Ext.EventObject.ENTER){
                                    mainStore.load();
                                }
                            }
                        }
                    }, {
                        xtype : 'tbseparator'
                    },{
                        xtype : 'tbtext',
                        text : HRMS_LABELS.lblName,
                        width : 50
                    }, {
                        xtype : 'textfield',
                        id : 's_name',
                        itemId : 's_name',
                        width : 200,
                        listeners : {
                            specialkey : function(s, e){

                                if(e.getKey() === Ext.EventObject.ENTER){
                                    mainStore.load();
                                }
                            }
                        }
                    }, {
                        xtype : 'tbseparator'
                    }, {
                        iconCls: 'icon-find',
                        text: HRMS_LABELS.lblFind,
                        scope: this,
                        handler: onFindClick
                    },'->',
                    {
                        xtype : 'button',
                        text : HRMS_LABELS.lblInsert,
                        iconCls : 'icon-add',
                        handler : onAddClick
                    }

                ]
            },
        ],

        bbar:['->', myPagingToolbar, { xtype: 'tbfill' } ],
        selModel : {
            selType : 'cellmodel'
        },
        plugins : [

            {
                ptype : 'cellediting',
                clicksToEdit : 1,
                autoCancel : false,
                listeners : {
                    edit : function(editor, e){

                        if(e.originalValue != e.value && e.value != '' ){

                            var conn = new Ext.data.Connection();
                            conn.request({
                                url : URL_STORE,
                                timeout : APP.TimeOut,
                                params : {
                                    _token: _token,
                                    id : e.record.id,
                                    field : e.field,
                                    value : e.value,

                                },
                                success : function(resp, opt){

                                    var result = Ext.util.JSON.decode(resp.responseText);
                                    if(result.success){
                                        $.showMessage('success', result.message);
                                        e.record.commit();

                                    }else{

                                        $.showMessage('error', result.message);
                                        e.record.reject();
                                    }
                                },
                                failure : function(){
                                    e.record.reject();
                                    $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                                }
                            });

                        }else{
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
                header : 'ID',
                dataIndex : 'id',
                width : 100,
                hidden : true
            }, {
                header : HRMS_LABELS.lblCode,
                dataIndex : 'code',
                width: 120,
                field : {
                    type : 'textfield'
                },

            },{
                header : HRMS_LABELS.lblName,
                dataIndex : 'name',
                width: 200,
                field : {
                    type : 'textfield'
                },
            },{
                text : HRMS_LABELS.lblLeaveCategory,
                dataIndex : 'leave_category_id',
                width : 250,
                renderer: renderLeaveCategory,
                editor:{
                    xtype : 'combo',
                    store : storeLeaveCategory,
                    displayField : 'name',
                    valueField : 'id',
                    queryMode : 'local',
                    editable: false // Tùy chọn nếu cần
                },
            },{
                header : HRMS_LABELS.lblKind,
                dataIndex : 'kind',
                width: 80,
                field : {
                    type : 'numberfield'
                },
            },{
                header : HRMS_LABELS.lblPaidRate,
                dataIndex : 'paid_rate',
                width: 100,
                field : {
                    type : 'numberfield'
                },
            },{
                header : HRMS_LABELS.lblNote,
                dataIndex : 'note',
                flex: 1,
                field : {
                    type : 'textfield'
                },

            },{
                align: "center",
                header: HRMS_LABELS.lblAction,
                xtype : 'actioncolumn',
                width : 100,
                sortable : false,
                menuDisabled : true,
                items : [
                    {
                        iconCls : 'cell-editing-delete-row',
                        tooltip : HRMS_LABELS.lblDelete,
                        handler: onDeleteClick
                    }
                ],
            }
        ],

    });

    function initModalEdit() {
        return Ext.create('Ext.window.Window', {
            title: HRMS_LABELS.lblAddNew,
            modal: true,
            width: 500,
            y: 100,
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 10,
                    defaults: {
                        labelWidth: 100, // Set label width for all fields
                        width: '100%'    // Set input width for all fields
                    },
                    items: [
                        {
                            xtype: 'hiddenfield',
                            name: '_token',
                            value: _token,
                        },
                        {
                            xtype: 'hiddenfield',
                            name: 'id'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: HRMS_LABELS.lblCode,
                            name: 'code'
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: HRMS_LABELS.lblName + ' (*)',
                            name: 'name',
                            allowBlank: false,
                        },
                        {
                            xtype: 'combobox',
                            fieldLabel: HRMS_LABELS.lblLeaveCategory,
                            name: 'leave_category_id',
                            store: storeLeaveCategory, // Dùng storeLeaveCategory
                            displayField: 'name',      // Hiển thị trường name
                            valueField: 'id',          // Giá trị lưu là trường id
                            queryMode: 'local',        // Lấy dữ liệu từ store đã tải
                            editable: false,           // Không cho phép nhập tay
                            allowBlank: true,          // Cho phép giá trị rỗng nếu cần
                            emptyText: HRMS_LABELS.lblSelect, // Placeholder nếu không chọn
                        },{
                            xtype: 'numberfield',
                            fieldLabel: HRMS_LABELS.lblKind + ' (*)',
                            name: 'kind',
                            allowBlank: false,
                        },{
                            xtype: 'numberfield',
                            fieldLabel: HRMS_LABELS.lblPaidRate,
                            name: 'paid_rate',
                            allowBlank: false,
                            decimalPrecision: 2, // Allows up to 2 decimal places
                            step: 0.01,          // Increments by 0.01
                            minValue: 0,          // Ensures no negative numbers if applicable
                            maxvalue: 0
                        },
                        {
                            xtype: 'textareafield',
                            fieldLabel: HRMS_LABELS.lblNote,
                            name: 'note'
                        },
                        {
                            xtype: 'checkboxfield',  // Checkbox field
                            fieldLabel: HRMS_LABELS.lblContinueAdd,
                            name: 'is_continue',
                            checked: false  // Set to true nếu muốn checkbox mặc định được chọn
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
                        text: HRMS_LABELS.lblSave,
                        handler: function () {
                            var modal = this.up('window');
                            var form = this.up('window').down('form');
                            var formData = form.getValues();
                            saveData(formData, form, modal);
                        }
                    },
                    {
                        text: HRMS_LABELS.lblClose,
                        handler: function () {
                            this.up('window').hide();
                        }
                    }
                ]
            },

        });
    }


    function onAddClick() {
        var modalEdit = initModalEdit();
        modalEdit.show();
    }

    function saveData(formData, form, modal) {
        var conn = new Ext.data.Connection();
        conn.request({
            url : URL_STORE,
            timeout : APP.TimeOut,
            params: formData,
            success: function(resp, opt) {
                var result = Ext.util.JSON.decode(resp.responseText);
                if (result.success) {
                    $.showMessage('success', result.message);

                    mainStore.load();
                    mainGird.getView().scrollTo(0,0);

                    if(parseInt(result.is_continue) === 0){
                        modal.close();  // Đóng và destroy
                    } else {
                        form.reset();   // ← Chỉ reset khi tiếp tục thêm
                    }
                } else {
                    $.showMessage('error', result.message);
                }
            },
            failure: function() {
                $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
            }
        });
    }


    function onDeleteClick(view, recIndex, cellIndex, item, e, selection){
        Ext.MessageBox.show({
            title: TRANSLATED_LABELS.lblHeaderDelete,
            msg: TRANSLATED_LABELS.lblConfirmDelete,
            icon: Ext.MessageBox.WARNING,
            buttons: Ext.MessageBox.OKCANCEL,
            fn: function(btn) {
                if (btn === 'ok') {
                    if (selection && selection.data) { // Kiểm tra selection tồn tại
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
                                var result = Ext.util.JSON.decode(resp.responseText);
                                Ext.Msg.hide();
                                if (result.success) {
                                    $.showMessage('success', result.message);
                                    mainGird.store.remove(selection);
                                } else {
                                    $.showMessage('error', result.message);
                                }
                            },
                            failure: function(resp, opt) {
                                $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                            }
                        });
                    }
                }
            }
        });
    }

    function onFindClick(){
        mainStore.load();
    }

    var element = Ext.get('main-gird');

    element.on('resize', function(e) {
        if (mainGird) {
            mainGird.update(); // Chỉ gọi nếu `mainGird` tồn tại
        }
    });

});
