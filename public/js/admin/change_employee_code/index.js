Ext.onReady(function () {

    // ─── Employee store ────────────────────────────────────────────────────────
    var userStore = Ext.create('Ext.data.Store', {
        fields : ['id', 'custom_name'],
        proxy  : {
            type : 'ajax',
            url  : MASTER_ROUTE.USERS_URL,
            reader : { type: 'json', rootProperty: 'data' }
        },
        autoLoad : true
    });

    // ─── Form ─────────────────────────────────────────────────────────────────
    var form = Ext.create('Ext.form.Panel', {
        title       : lblPageTitle,
        width       : 520,
        bodyPadding : 20,
        renderTo    : 'change-employee-code-form',
        defaults    : { anchor: '100%', labelWidth: 120 },
        buttonAlign : 'center',

        items: [
            {
                xtype        : 'combobox',
                fieldLabel   : 'Nhân viên',
                name         : 'user_id',
                itemId       : 'cboUser',
                store        : userStore,
                displayField : 'custom_name',
                valueField   : 'id',
                queryMode    : 'local',
                typeAhead    : true,
                allowBlank   : false,
                minChars     : 0,
                listConfig   : { minWidth: 360 }
            },
            {
                xtype      : 'datefield',
                fieldLabel : 'Ngày vào',
                name       : 'on_date',
                itemId     : 'dtOnDate',
                format     : 'd/m/Y',
                allowBlank : false,
                value      : new Date()
            }
        ],

        buttons: [
            {
                text    : 'Xử lý',
                iconCls : 'x-fa fa-check',
                scale   : 'medium',
                handler : onProcess
            }
        ]
    });

    // ─── Handler ───────────────────────────────────────────────────────────────
    function onProcess() {
        if (!form.isValid()) return;

        var userId = form.down('#cboUser').getValue();
        var onDate = form.down('#dtOnDate').getValue();

        if (!userId) {
            Ext.Msg.alert('Thông báo', 'Vui lòng chọn nhân viên.');
            return;
        }
        if (!onDate) {
            Ext.Msg.alert('Thông báo', 'Vui lòng chọn ngày vào.');
            return;
        }

        var dateStr = Ext.Date.format(onDate, 'Y-m-d');

        Ext.Msg.confirm(
            'Xác nhận',
            'Thao tác này sẽ thay đổi mã nhân viên. Bạn có chắc chắn không?',
            function (btn) {
                if (btn !== 'yes') return;

                $.loadingStart();

                Ext.Ajax.request({
                    url     : URL_PROCESS,
                    method  : 'POST',
                    timeout : APP.TimeOut,
                    params  : {
                        _token  : CSRF_TOKEN,
                        user_id : userId,
                        on_date : dateStr
                    },
                    success : function (resp) {
                        $.loadingEnd();
                        var result = Ext.decode(resp.responseText);
                        if (result.success) {
                            Ext.Msg.alert('Thành công', 'Mã nhân viên đã được thay đổi thành: <b>' + result.new_code + '</b>', function () {
                                form.reset();
                            });
                        } else {
                            Ext.Msg.alert('Lỗi', result.message || 'Đã có lỗi xảy ra.');
                        }
                    },
                    failure : function () {
                        $.loadingEnd();
                        Ext.Msg.alert('Lỗi', 'Không thể kết nối đến server.');
                    }
                });
            }
        );
    }
});
