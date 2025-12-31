Ext.onReady(function(){

    var s_name;
    var s_code;
    var mainGird;


    Ext.define('Nation', {
        extend : 'Ext.data.Model',
        fields : [
            'id', 'code', 'name', 'short_name', 'area_code'
        ],

    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'Nation',
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
                flex: 1,
                field : {
                    type : 'textfield'
                },

            },{
                header : HRMS_LABELS.lblShortName,
                dataIndex : 'short_name',
                width: 120,
                align: 'center',
                field : {
                    type : 'textfield'
                },

            },{
                align: 'center',
                header : HRMS_LABELS.lblAreaCode,
                dataIndex : 'area_code',
                width: 120,
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


    // Hàm cập nhật field chung có thể tái sử dụng
    function initModalEdit() {
        return Ext.create('Ext.window.Window', {
            title: HRMS_LABELS.lblCreate,
            modal: true,
            width: 500,
            y: 100,
            closeAction: 'destroy',
            items: [
                {
                    xtype: 'form',
                    bodyPadding: 10,
                    defaults: {
                        labelWidth: 100 , // Set label width for all fields
                        width: '100%'   // Set input width for all fields
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
                            fieldLabel: HRMS_LABELS.lblName+'(*)',
                            name: 'name',
                            allowBlank: false,
                        },

                        {
                            xtype: 'textfield',
                            fieldLabel: HRMS_LABELS.lblShortName,
                            name: 'short_name'
                        },{
                            xtype: 'textfield',
                            fieldLabel: HRMS_LABELS.lblAreaCode,
                            name: 'area_code'
                        },

                        {
                            xtype: 'checkboxfield',  // Checkbox field
                            fieldLabel: HRMS_LABELS.lblContinueAdd,
                            name: 'is_continue',
                            checked: false  // Set to true if you want the checkbox to be initially checked
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
            params:formData,
            success : function(resp, opt){
                $.loadingEnd();
                var result = Ext.util.JSON.decode(resp.responseText);
                if(result.success){
                    $.showMessage('success', result.message);

                    mainStore.load();
                    mainGird.getView().scrollTo(0,0);

                    if(parseInt(result.is_continue) === 0){
                        modal.close();  // Đóng và destroy
                    } else {
                        form.reset();   // ← Chỉ reset khi tiếp tục thêm
                    }
                }else{
                    $.showMessage('error', result.message);
                }
            },
            failure : function(){
                $.loadingEnd();
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
                if (btn === 'ok'){
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
                    selection = selection ? selection : mainGird.getView().getSelectionModel().getSelection()[0];
                    var conn = new Ext.data.Connection();
                    conn.request({
                        url: URL_DELETE,
                        timeout: APP.TimeOut,
                        params: {
                            _token: _token,
                            action: SYSTEM_CONSTANT.ACTION_DELETE,
                            id: selection.data.id,
                        },
                        success: function(resp,opt){
                            var result = Ext.util.JSON.decode(resp.responseText);
                            Ext.Msg.hide();
                            if(result.success){
                                // hide the loading mask
                                $.showMessage('success', result.message);
                                mainGird.store.remove(selection);
                            }else{
                                $.showMessage('error', result.message);
                            }
                        },
                        failure: function(resp,opt){
                            $.showMessage('error', TRANSLATED_LABELS.lblConnectServerFailed);
                        }
                    });
                }
            }
        });
    }

    function onFindClick(){
        mainStore.load();
    }

    var element = Ext.get('main-gird');

    element.on('resize', function(e) {
        mainGird.update();
    });

});
