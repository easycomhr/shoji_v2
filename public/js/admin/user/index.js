Ext.onReady(function(){

    var s_name;
    var s_code;
    var storeDepartment;
    var mainStore;

    Ext.define('User', {
        extend : 'Ext.data.Model',
        fields : [
            'id', 'code', 'name', 'department_id', 'position_id'
        ],

    });

    storeDepartment = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.DEPARTMENT_URL,
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

                    if (storePosition && !storePosition.isDestroyed) {
                        storePosition.load();
                    }
                } else {
                    console.error('Error loading storeDepartment');
                }
            }
        }
    });

    storePosition = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
            timeout: APP.TimeOut,
            type: 'ajax',
            url: MASTER_ROUTE.POSITION_URL,
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
                    if (mainStore && !mainStore.isDestroyed) {
                        mainStore.load();
                    }
                } else {
                    console.error('Error loading storePosition');
                }
            }
        }
    });

    mainStore = Ext.create('Ext.data.Store', {
        model : 'User',
        pageSize: SYSTEM_CONSTANT.DEFAULT_PAGE_SIZE,
        proxy : {
            timeout : APP.TimeOut,
            type : 'ajax',
            url : URL_DATA,
            params: {
                _token: $('meta[name="csrf-token"]').attr('content'),
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
                // Check grid availability
                if (!mainGird || mainGird.isDestroyed || !mainGird.rendered) {
                    console.error('Grid not ready');
                    return false;
                }

                try {
                    $.loadingStart();

                    // Safe field access
                    var nameField = mainGird.down("#s_name");
                    var codeField = mainGird.down("#s_code");

                    s_name = nameField ? Ext.util.Format.trim(nameField.getValue() || '') : '';
                    s_code = codeField ? Ext.util.Format.trim(codeField.getValue() || '') : '';

                    mainStore.proxy.extraParams.name = s_name;
                    mainStore.proxy.extraParams.code = s_code;

                } catch (e) {
                    console.error('Error in beforeload:', e);
                    $.loadingEnd();
                    return false;
                }
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
    var columns = [
        {
            header : 'ID',
            dataIndex : 'id',
            width : 100,
            hidden : true
        }, {
            header : HRMS_LABELS.lblEmpId,
            dataIndex : 'code',
            width: 160,


        },{
            header : HRMS_LABELS.lblName,
            dataIndex : 'name',
            width: 200,


        },{
            header : HRMS_LABELS.lblDepartment,
            dataIndex : 'department_id',
            width: 250,
            align: 'left',
            renderer: function(value) {
                if (!storeDepartment || storeDepartment.isDestroyed) {
                    return value;
                }
                var record = storeDepartment.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        },{
            header : HRMS_LABELS.lblPosition,
            dataIndex : 'position_id',
            flex: 1,
            renderer: function(value) {
                if (!storePosition || storePosition.isDestroyed) {
                    return value;
                }
                var record = storePosition.findRecord('id', value);
                return record ? record.get('name') : value;
            }
        }
    ];

    var mainGird = Ext.create('Ext.grid.Panel', {
        renderTo: "main-gird",
        store : mainStore,
        title : lblPageTitle,
        listeners : {
// Thêm listener cho double click
            itemdblclick: function(view, record, item, index, e, eOpts) {
                // Lấy column được click
                var position = view.getPositionByEvent(e);
                var column = position ? position.column : null;

                console.log('column:', column);
                console.log('column dataIndex:', column ? column.dataIndex : 'null');
                console.log('column: '+column);
                console.log('position:', position);

                // Kiểm tra nếu click vào cột Employee ID (code)
                if (column && column.dataIndex === 'code') {
                    var employeeCode = record.get('code');
                    if (employeeCode) {
                        // Replace employee_code trong URL template
                        var profileUrl = URL_PROFILE.replace('employee_code', employeeCode);
                        // Điều hướng đến URL
                        window.location.href = profileUrl;
                    }
                }
            }
        },
        dockedItems : [
            {
                xtype : 'toolbar',
                dock : 'top',
                items : [
                    {
                        xtype : 'tbtext',
                        text : HRMS_LABELS.lblEmpId,
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
                    },{
                        iconCls: 'icon-find',
                        text: HRMS_LABELS.lblFind,
                        scope: this,
                        handler: onFindClick
                    },

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

                }
            }, {
                ptype : 'gridfilters'
            }

        ],
        columns : columns,

    });





    function onFindClick(){
        mainStore.load();
    }

    var element = Ext.get('main-gird');

    element.on('resize', function(e) {
        mainGird.update();
    });

});
