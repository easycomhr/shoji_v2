Ext.onReady(function(){

    var s_name;
    var s_code;
    var mainGird;

    Ext.define('WorkShift', {
        extend : 'Ext.data.Model',
        fields : [
            'id', 'code', 'work_start', 'work_end', 'is_day_off', 'is_night_shift', 'ot_early', 'ot_night', 'note'
        ],

    });

    var mainStore = Ext.create('Ext.data.Store', {
        model : 'WorkShift',
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
                    s_code = Ext.util.Format.trim(mainGird.down("#s_code").getValue() || '');
                } else {
                    s_code = '';
                }

                mainStore.getProxy().extraParams.code = s_code;

            },load: function () {
                $.loadingEnd();
            }
        }

    });

    mainStore.sort('id', 'DESC');

    var storeOTType = Ext.create('Ext.data.Store', {
        fields : [
            'id', 'name'
        ],
        proxy : {
            timeout : APP.TimeOut,
            type : 'ajax',
            url : URL_DATA_OT_TYPE,
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
                    console.log('storeOTType loaded:', storeOTType.getCount(), 'items');
                    mainStore.load(); // Chỉ load mainStore sau khi storeOTType load thành công
                } else {
                    console.error('Error loading storeOTType');
                }
            }
        }
    });

    function renderOTType(value) {
        try {
            console.log('Value:', value);
            console.log('Store data OT Type:', storeOTType.getData().items);

            if (value == null || value === '') {
                return '';
            }

            const record = storeOTType.queryBy(function(rec) {
                return rec.get('id') === value; // Sử dụng rec.get để truy xuất giá trị chính xác
            }).first(); // Lấy bản ghi đầu tiên tìm thấy

            return record ? record.get('name') : ''; // Trả về `name` hoặc chuỗi rỗng nếu không tìm thấy
        } catch (e) {
            console.error('Error in renderOTType:', e);
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
                header: HRMS_LABELS.lblWorkStart,
                dataIndex: 'work_start',
                width: 100,
                editor: {
                    xtype: 'timefield',
                    format: 'H:i:s', // Định dạng giờ: phút (24 giờ)
                    increment: 15, // Tăng thời gian theo từng 15 phút
                    allowBlank: false // Không được để trống (có thể tùy chỉnh theo nhu cầu)
                },
                renderer: timeRenderer

            },{
                header: HRMS_LABELS.lblWorkEnd,
                dataIndex: 'work_end',
                width: 100,
                editor: {
                    xtype: 'timefield',
                    format: 'H:i:s', // Định dạng giờ: phút (24 giờ)
                    increment: 15, // Tăng thời gian theo từng 15 phút
                    allowBlank: false // Không được để trống (có thể tùy chỉnh theo nhu cầu)
                },
                renderer: timeRenderer

            },{
                xtype : 'checkcolumn',
                text : HRMS_LABELS.lblDayOff,
                id : 'is_day_off',
                headerCheckbox : false,
                width : 100,
                name : 'is_day_off',
                dataIndex : 'is_day_off',
                stopSelection : false,
                listeners : {
                    checkchange : onCheckColumnChange
                }
            },{
                xtype : 'checkcolumn',
                text : HRMS_LABELS.lblNightShift,
                id : 'is_night_shift',
                width : 100,
                name : 'is_night_shift',
                dataIndex : 'is_night_shift',
                stopSelection : false,
                headerCheckbox: false,
                listeners : {
                    checkchange : onCheckColumnChange
                }
            },{
                text : HRMS_LABELS.lblDefaultOTEarlyType,
                dataIndex : 'ot_early',
                width : 180,
                renderer: renderOTType,
                editor:{
                    xtype : 'combo',
                    store : storeOTType,
                    displayField : 'name',
                    valueField : 'id',
                    queryMode : 'local',
                    editable: false // Tùy chọn nếu cần
                },
            },{
                text : HRMS_LABELS.lblDefaultOTLateType,
                dataIndex : 'ot_late',
                width : 180,
                renderer: renderOTType,
                editor:{
                    xtype : 'combo',
                    store : storeOTType,
                    displayField : 'name',
                    valueField : 'id',
                    queryMode : 'local',
                    editable: false // Tùy chọn nếu cần
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

    function onCheckColumnChange(column, rowIndex, checked, record){
        var conn = new Ext.data.Connection();
        var field = column.dataIndex;

        conn.request({
            url: URL_STORE,
            timeout: APP.TimeOut,
            params: {
                _token: _token,         // Thêm token xác thực nếu cần
                id: record.id,          // ID bản ghi
                field: field,           // Tên trường đang thay đổi
                value: checked          // Giá trị thay đổi (true/false hoặc 1/0)
            },
            success: function(resp,opt){
                var result = Ext.util.JSON.decode(resp.responseText);
                if(result.success){
                    record.commit();
                }else{
                    $.showMessage('error', result.message);
                    record.reject();
                }
            },
            failure: function(){
                record.reject();
                $.showMessage('error', 'Cannot connect to server!');
            }
        });
    }

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
                            xtype: 'timefield',
                            fieldLabel: HRMS_LABELS.lblWorkStart, // Label cho work_start
                            name: 'work_start',
                            format: 'H:i:s', // Định dạng 24 giờ
                            increment: 15,   // Tăng thời gian theo từng 15 phút
                            allowBlank: false, // Bắt buộc phải nhập
                            value: '08:00:00' // Giá trị mặc định là 08:00:00
                        },
                        {
                            xtype: 'timefield',
                            fieldLabel: HRMS_LABELS.lblWorkEnd, // Label cho work_end
                            name: 'work_end',
                            format: 'H:i:s', // Định dạng 24 giờ
                            increment: 15,   // Tăng thời gian theo từng 15 phút
                            allowBlank: false, // Bắt buộc phải nhập
                            value: '17:00:00' // Giá trị mặc định là 08:00:00
                        },
                        {
                            xtype: 'checkboxfield', // Checkbox cho Day off
                            fieldLabel: HRMS_LABELS.lblDayOff, // Label cho Day off
                            name: 'is_day_off',
                            inputValue: true, // Giá trị trả về khi được chọn
                            uncheckedValue: false, // Giá trị trả về khi không được chọn
                            checked: false, // Giá trị mặc định chưa được chọn
                        },
                        {
                            xtype: 'checkboxfield', // Checkbox cho Night shift
                            fieldLabel: HRMS_LABELS.lblNightShift, // Label cho Night shift
                            name: 'is_night_shift',
                            inputValue: true, // Giá trị trả về khi được chọn
                            uncheckedValue: false, // Giá trị trả về khi không được chọn
                            checked: false // Giá trị mặc định chưa được chọn
                        },
                        {
                            xtype: 'combobox',
                            fieldLabel: HRMS_LABELS.lblDefaultOTEarlyType,
                            name: 'ot_early',
                            store: storeOTType, // Dùng storeOTType
                            displayField: 'name',      // Hiển thị trường name
                            valueField: 'id',          // Giá trị lưu là trường id
                            queryMode: 'local',        // Lấy dữ liệu từ store đã tải
                            editable: false,           // Không cho phép nhập tay
                            allowBlank: true,          // Cho phép giá trị rỗng nếu cần
                            emptyText: HRMS_LABELS.lblSelect, // Placeholder nếu không chọn
                        },{
                            xtype: 'combobox',
                            fieldLabel: HRMS_LABELS.lblDefaultOTLateType,
                            name: 'ot_late',
                            store: storeOTType, // Dùng storeOTType
                            displayField: 'name',      // Hiển thị trường name
                            valueField: 'id',          // Giá trị lưu là trường id
                            queryMode: 'local',        // Lấy dữ liệu từ store đã tải
                            editable: false,           // Không cho phép nhập tay
                            allowBlank: true,          // Cho phép giá trị rỗng nếu cần
                            emptyText: HRMS_LABELS.lblSelect, // Placeholder nếu không chọn
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

    function timeRenderer(value) {
        // Kiểm tra giá trị có hợp lệ không
        if (value) {

            if(value.length === 8){
                return value;
            }

            var dateValue = new Date(value); // Chuyển giá trị thành đối tượng Date
            if (!isNaN(dateValue.getTime())) { // Nếu giá trị hợp lệ
                return Ext.Date.format(dateValue, 'H:i:s'); // Định dạng hiển thị giờ:phút:giây
            }
        }
        return ''; // Nếu không có giá trị hoặc sai định dạng, trả về chuỗi trống
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



