Ext.onReady(function(){

    var s_name;
    var s_code;
    var mainGird;

    Ext.define('ContractType', {
        extend : 'Ext.data.Model',
        fields : [
            'id', 'code', 'name', 'from_time', 'to_time', 'paid_rate', 'note'
        ],
    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'ContractType',
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
                        // Xử lý logic format lại date nếu cần trước khi gửi server
                        var val = e.value;
                        if (e.field === 'from_time' || e.field === 'to_time') {
                            if (val instanceof Date) {
                                val = Ext.Date.format(val, 'H:i:s');
                            }
                        }

                        if(e.originalValue != e.value && e.value != '' ){
                            var conn = new Ext.data.Connection();
                            conn.request({
                                url : URL_STORE,
                                timeout : APP.TimeOut,
                                params : {
                                    _token: _token,
                                    id : e.record.id,
                                    field : e.field,
                                    value : val, // Sử dụng giá trị đã xử lý
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
                editor : { // Đổi từ field sang editor cho chuẩn ExtJS 6
                    xtype : 'textfield'
                }
            },{
                header : HRMS_LABELS.lblName,
                dataIndex : 'name',
                width : 200,
                editor : {
                    xtype : 'textfield'
                }
            },
            // --- CÁC CỘT MỚI THÊM ---
            {
                header : HRMS_LABELS.lblFromTime, // Thay bằng HRMS_LABELS.lblFromTime nếu có
                dataIndex : 'from_time',
                width : 100,
                align: 'center',
                editor : {
                    xtype : 'timefield',
                    format: 'H:i',
                    submitFormat: 'H:i:s',
                    increment: 15
                }
            },
            {
                header : HRMS_LABELS.lblToTime, // Thay bằng HRMS_LABELS.lblToTime nếu có
                dataIndex : 'to_time',
                width : 100,
                align: 'center',
                editor : {
                    xtype : 'timefield',
                    format: 'H:i',
                    submitFormat: 'H:i:s',
                    increment: 15
                }
            },
            {
                header : HRMS_LABELS.lblPaidRate+"(%)", // Thay bằng HRMS_LABELS.lblPaidRate nếu có
                dataIndex : 'paid_rate',
                cls: 'wrap-header',
                width : 150,
                align: 'center',
                editor : {
                    xtype : 'numberfield',
                    minValue: 0,
                    decimalPrecision: 2
                }
            },
            // -------------------------
            {
                header : HRMS_LABELS.lblNote,
                dataIndex : 'note',
                flex: 1,
                align: 'left',
                editor : {
                    xtype : 'textfield'
                }
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
                        labelWidth: 100 ,
                        width: '100%'
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
                        // --- CÁC FIELD MỚI THÊM VÀO FORM ---
                        {
                            xtype: 'container',
                            layout: 'hbox',
                            defaults: {
                                labelWidth: 100,
                            },
                            items: [
                                {
                                    xtype: 'timefield',
                                    fieldLabel: HRMS_LABELS.lblFromTime, // Thay bằng biến label
                                    name: 'from_time',
                                    format: 'H:i',
                                    submitFormat: 'H:i:s',
                                    increment: 15,
                                    flex: 1,
                                    margin: '0 5 0 0'
                                },
                                {
                                    xtype: 'timefield',
                                    fieldLabel: HRMS_LABELS.lblToTime, // Thay bằng biến label
                                    name: 'to_time',
                                    format: 'H:i',
                                    submitFormat: 'H:i:s',
                                    increment: 15,
                                    flex: 1,
                                    labelWidth: 60, // Label ngắn hơn cho cột thứ 2
                                    margin: '0 0 0 5'
                                }
                            ]
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: HRMS_LABELS.lblPaidRate+"(%)", // Thay bằng biến label
                            name: 'paid_rate',
                            minValue: 0,
                            decimalPrecision: 2,
                            value: 0
                        },
                        // -----------------------------------
                        {
                            xtype: 'textareafield',
                            fieldLabel: HRMS_LABELS.lblNote,
                            name: 'note'
                        },
                        {
                            xtype: 'checkboxfield',
                            fieldLabel: HRMS_LABELS.lblContinueAdd,
                            name: 'is_continue',
                            checked: false
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
                            // Cần kiểm tra validate form trước khi lưu
                            if(form.isValid()){
                                var formData = form.getValues();
                                saveData(formData, form, modal);
                            } else {
                                Ext.Msg.alert('Warning', 'Please check input data.');
                            }
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
                        modal.close();
                    } else {
                        form.reset();
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
    if(element){
        element.on('resize', function(e) {
            mainGird.update();
        });
    }

});