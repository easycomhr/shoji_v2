function initDaterangepicker() {
    $(".input-date")
        .daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            autoApply: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: false,
            minYear: 1950,
            locale: {
                format: "YYYY/MM/DD",
                separator: " - ",
                applyLabel: "適用",
                cancelLabel: "キャンセル",
                daysOfWeek: ["日", "月", "火", "水", "木", "金", "土"],
                monthNames: [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                firstDay: 0,
            },
        })
        .on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
          //  picker.element.valid();
        })
        .on("show.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        })
        .on("hide.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        });
}

function initDaterangepickerTime() {
    $(".input-datetime")
        .daterangepicker({
            autoUpdateInput: true,
            //            autoApply: true,
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
            autoApply: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: false,
            minYear: 1950,
            locale: {
                format: "YYYY/MM/DD HH:mm:ss",
                separator: " - ",
                applyLabel: "適用",
                cancelLabel: "キャンセル",
                daysOfWeek: ["日", "月", "火", "水", "木", "金", "土"],
                monthNames: [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                firstDay: 0,
            },
        })
        .on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
            picker.element.valid();
        })
        .on("show.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        })
        .on("hide.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        });
}

function initDaterangepickerAllowEmpty() {
    $(".input-date-empty:not(.initialized)").each(function () {
        $(this).addClass('initialized');
        let input = $(this);
        let value = input.val();
        input.val('');
        input.daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: false,
            autoApply: true,
            timePicker24Hour: true,
            timePickerIncrement: 1,
            timePickerSeconds: false,
            minYear: 1950,
            locale: {
                format: "YYYY/MM/DD",
                separator: " - ",
                applyLabel: "適用",
                cancelLabel: "キャンセル",
                daysOfWeek: ["日", "月", "火", "水", "木", "金", "土"],
                monthNames: [
                    "1月",
                    "2月",
                    "3月",
                    "4月",
                    "5月",
                    "6月",
                    "7月",
                    "8月",
                    "9月",
                    "10月",
                    "11月",
                    "12月",
                ],
                firstDay: 0,
            },
        })
        .on("apply.daterangepicker", function (e, picker) {
            picker.element.val(picker.startDate.format(picker.locale.format));
        })
        .on("show.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        })
        .on("hide.daterangepicker", function (ev, picker) {
            picker.container.find(".calendar-time").addClass("readonly");
        });
        input.val(value);

        input.parent().find('.js-calendar-icon').each(function () {
            $(this).unbind('click').click(function () {
                input.focus();
            })
        })
    });
}

function changeDate(id, days) {
    const dateInput = document.getElementById(id);
    let currentDate = new Date(dateInput.value.replaceAll('/', '-'));

    // Check if dateInput.value is a valid date
    if (isNaN(currentDate.getTime())) {
        currentDate = new Date(); // Use current date if the input date is invalid
    }

    currentDate.setDate(currentDate.getDate() + days);

    dateInput.value = currentDate.toISOString().split('T')[0].replaceAll('-', '/');
}

function setYesterdayDateRange(from_id, to_id) {
    let fromDateInput = document.getElementById(from_id);
    let toDateInput = document.getElementById(to_id);

    let yesterdayDate = new Date();
    yesterdayDate.setDate(yesterdayDate.getDate() - 1);
    yesterdayDate = yesterdayDate.toISOString().split('T')[0].replaceAll('-', '/');

    fromDateInput.value = toDateInput.value = yesterdayDate;
}

function setTodayDateRange(from_id, to_id) {
    let fromDateInput = document.getElementById(from_id);
    let toDateInput = document.getElementById(to_id);

    let currentDate = new Date().toISOString().split('T')[0].replaceAll('-', '/');

    fromDateInput.value = toDateInput.value = currentDate;
}

function setThisMonthDateRange(from_id, to_id) {
    let today = new Date();
    let firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    let lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

    let fromDateInput = document.getElementById(from_id);
    let toDateInput = document.getElementById(to_id);

    fromDateInput.value = formatDate(firstDayOfMonth);
    toDateInput.value = formatDate(lastDayOfMonth);
}

const formatDate = (date) => {
    let month = '' + (date.getMonth() + 1), // Months are zero indexed
        day = '' + date.getDate(),
        year = date.getFullYear();

    if (month.length < 2) month = '0' + month;
    if (day.length < 2) day = '0' + day;

    return [year, month, day].join('/');
};

function initJsTooltip() {
    $('.js-tooltip').each(function () {
        $(this).parent().css('position', 'relative');
        let tooltip = $(this).next();

        $(this).mouseenter(function (e) {
            let div_height = tooltip.height();
            let parent_height = e.pageY;
            let section_height = $('#container').height();
            let height = div_height + parent_height - section_height;
            if (height > 5) {
                tooltip.css('top', 'unset');
                tooltip.css('bottom', '110%');
            } else {
                tooltip.css('top', '105%');
                tooltip.css('bottom', 'unset');
            }

            tooltip.css('left', e.offsetX + 10)
        });

        $(this).hover(() => tooltip.addClass('show'), () => tooltip.removeClass('show'));
    })
}

function initJsSortable() {
    $('.js-sortable').each(function () {
        $(this).sortable({
            placeholder: "ui-state-highlight",
            helper: function (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            },
            update: function (event, ui) {
                var sortedIds = $(this).sortable("toArray", {
                    attribute: "data-id",
                });
                const csrf = $('meta[name="csrf-token"]').attr("content");

                $.ajax({
                    url: SORT_URL,
                    method: "POST",
                    data: {
                        _token: csrf,
                        sortedIds: sortedIds,
                    },
                    success: function (response) {
                        if (response.success) {
                            // Notification.showSuccess(response.message);
                        } else {
                            // Notification.showSuccess(response.message);
                        }
                    },
                });
            },
        }).disableSelection();

        $(this).find('tr').hover(
            function () {
                $(this).addClass("hvColor");
            },
            function () {
                $(this).removeClass("hvColor");
            }
        );
    });
}

$(function () {
    var topBtn = $(".totop");
    topBtn.hide();
    //スクロールが100に達したらボタン表示
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            topBtn.fadeIn();
        } else {
            topBtn.fadeOut();
        }
    });
    //スクロールしてトップ
    topBtn.click(function () {
        $("body,html").animate(
            {
                scrollTop: 0,
            },
            500
        );
        return false;
    });
});

$(document).ready(function () {
    $("#local_navi .section.navfx .sub_nav_heading").hover(
        function () {
            $(this).addClass("ov");
        },
        function () {
            $(this).removeClass("ov");
        }
    );
});

$("#local_navi dt")
    .click(function () {
        $(this).toggleClass("open");

        var index = $("dt").index(this);

        $("dd").eq(index).slideToggle("fast");
    })
    .css("cursor", "pointer");
