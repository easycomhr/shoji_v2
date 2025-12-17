Ext.Date.dayNames = [
	'CN',
	'Hai',
	'Ba',
	'Tư',
	'Năm',
	'Sáu',
	'Bảy'
];
                 
Ext.Date.monthNames = [
	'Tháng 1',
	'Tháng 2',
	'Tháng 3',
	'Tháng 4',
	'Tháng 5',
	'Tháng 6',
	'Tháng 7',
	'Tháng 8',
	'Tháng 9',
	'Tháng 10',
	'Tháng 11',
	'Tháng 12'
];

Ext.Date.shortMonthNames = [
	"Th 1",
	"Th 2",
	"Th 3",
	"Th 4",
	"Th 5",
	"Th 6",
	"Th 7",
	"Th 8",
	"Th 9",
	"Th 10",
	"Th 11",
	"Th 12"
];

Ext.Date.getShortMonthName = function(month) {
    return Ext.Date.shortMonthNames[month];
};
                    
Ext.define("Ext.locale.vi.picker.Date", {
    override: "Ext.picker.Date",
    todayText: "Hôm nay",
    minText: "Ngày không hợp lệ",
    maxText: "Ngày không hợp lệ",
    disabledDaysText: "",
    disabledDatesText: "",
    nextText: 'Tháng sau',
    prevText: 'Tháng trước',
    monthYearText: '',
    format: "d/m/Y",
    startDay: 1
});

Ext.define("Ext.locale.vi.toolbar.Paging", {
    override: "Ext.toolbar.Paging",
    displayMsg: 'Hiển thị {0} - {1} / {2}',
    emptyMsg: 'Không có dữ liệu để hiển thị',
    beforePageText: 'Trang',
    afterPageText: '/ {0}',
    firstText: 'Trang đầu',
    prevText: 'Trang trước',
    nextText: 'Trang sau',
    lastText: 'Trang cuối',
    refreshText: 'Làm mới',
});
