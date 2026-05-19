Ext.onReady(function () {

    // ── Local store built from server-side table registry ──────────────────
    var store = Ext.create('Ext.data.Store', {
        fields : ['key', 'label', 'old_table', 'new_table', 'status', 'synced_at', 'result'],
        data   : MIGRATION_TABLES
    });

    function doSync(record, callback) {
        record.set('status', 'Đang sync...');
        record.set('result', '');

        Ext.Ajax.request({
            url     : URL_SYNC,
            method  : 'POST',
            timeout : APP.TimeOut,
            params  : {
                table  : record.get('key'),
                _token : CSRF_TOKEN
            },
            success: function (resp) {
                var r  = Ext.util.JSON.decode(resp.responseText);
                var msg = r.success
                    ? ('Hoàn tất: ' + (r.upserted || 0) + ' bản ghi' + (r.skipped ? ', bỏ qua: ' + r.skipped : ''))
                    : ('Lỗi: ' + (r.message || 'Unknown'));
                record.set('status', r.success ? 'OK' : 'Lỗi');
                record.set('result', msg);
                if (callback) callback();
            },
            failure: function () {
                record.set('status', 'Lỗi');
                record.set('result', 'Kết nối thất bại');
                if (callback) callback();
            }
        });
    }

    // ── Grid ──────────────────────────────────────────────────────────────
    Ext.create('Ext.grid.Panel', {
        renderTo   : 'main-gird',
        store      : store,
        stripeRows : true,
        tbar       : [{
            xtype   : 'button',
            text    : 'Migrate All',
            iconCls : 'x-fa fa-database',
            handler : function () {
                Ext.Msg.confirm('Xác nhận', 'Migrate tất cả table theo thứ tự?', function (choice) {
                    if (choice !== 'yes') return;
                    var records = store.getRange();
                    var idx = 0;
                    function next() {
                        if (idx >= records.length) {
                            Ext.Msg.alert('Hoàn tất', 'Đã migrate tất cả table.');
                            return;
                        }
                        doSync(records[idx++], next);
                    }
                    next();
                });
            }
        }],
        columns    : [
            {
                text      : 'Table nguồn',
                dataIndex : 'old_table',
                flex      : 1,
                renderer  : function (v) { return '<b>' + v + '</b>'; }
            },
            {
                text      : 'Table đích',
                dataIndex : 'new_table',
                flex      : 1,
                renderer  : function (v) { return '<b>' + v + '</b>'; }
            },
            {
                text      : 'Kết quả',
                dataIndex : 'result',
                flex      : 2,
                renderer  : function (v, meta, rec) {
                    var status = rec.get('status');
                    if (status === 'OK' || status === 'migrated') {
                        meta.style = 'color:#28a745;';
                    } else if (status === 'Lỗi') {
                        meta.style = 'color:#dc3545;';
                    } else if (status === 'Đang sync...') {
                        meta.style = 'color:#6c757d;';
                    }
                    return v || '';
                }
            },
            {
                text      : 'Chức năng',
                width     : 120,
                align     : 'center',
                xtype     : 'widgetcolumn',
                widget    : {
                    xtype   : 'button',
                    text    : 'Sync',
                    iconCls : 'x-fa fa-sync',
                    handler : function (btn) {
                        var record = btn.getWidgetRecord();
                        Ext.Msg.confirm(
                            'Xác nhận Sync',
                            'Sync dữ liệu từ <b>' + record.get('old_table') + '</b> → <b>' + record.get('new_table') + '</b>?',
                            function (choice) {
                                if (choice === 'yes') doSync(record);
                            }
                        );
                    }
                }
            }
        ]
    });

});
