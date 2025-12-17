
var REDIS_PREFIX = '';

APP.ApiUrl = function(path, param) {
    param = typeof param !== 'undefined' ? param : '';
    if (param != '') {
        path += '?' + $.param(param);
    }
    return APP.API_URL + path;
};

APP.formatNumber = function(number){
    return Ext.util.Format.number(number, '0,000');
};

APP.formatNumberDecimal = function(number){
    return Ext.util.Format.number(number, '0,000.00');
};

APP.warningPopup = function(title, message, icon){

    Ext.Msg.show({
        title: title,
        msg: message,
        minWidth: 250,
        modal: true,
        icon: (icon != null) ? icon : Ext.Msg.INFO,
        buttons: Ext.Msg.OK
    });
};

APP.formatDate = function (value){
    try{

        if(value == "" || value == null || value == "0000-00-00"){
            return '';
        }

        var dateObj = new Date(value);
        return Ext.Date.format(dateObj, dateFormat);

    }
    catch(e){}
};

APP.formatTime = function (value){
    if (value && Ext.isDate(value)){
        return Ext.Date.format(value, 'H:i:s');
    }
    return value;
};

$.loadingStart = function() {
    var cover = $('<div class="loading-cover"></div>')
        .css('position', 'fixed')
        .css('width', "100%")
        .css('height', "100%")
        .css('z-index', 11000)
        .css('opacity', '0.4')
        .css('filter', 'alpha(opacity=40);')
        .css('background-color', 'rgba(0, 0, 0, 0.35)')
        .css('top', 0)
        .css('background-image', 'url("'+ loading_image_path +'")')
        .css('background-repeat', 'no-repeat')
        .css('background-size', '50px 50px')
        .css('background-position', 'center center')
        .appendTo('body');
};

function loadingStart(){
    var cover = $('<div class="loading-cover"></div>')
        .css('position', 'fixed')
        .css('width', "100%")
        .css('height', "100%")
        .css('z-index', 11000)
        .css('opacity', '0.4')
        .css('filter', 'alpha(opacity=40);')
        .css('background-color', 'rgba(0, 0, 0, 0.35)')
        .css('top', 0)
        .css('background-image', 'url("'+ loading_image_path +'")')
        .css('background-repeat', 'no-repeat')
        .css('background-size', '50px 50px')
        .css('background-position', 'center center')
        .appendTo('body');
}

$.loadingEnd = function() {
    $('.loading-cover').remove();
};

function loadingEnd(){
    $('.loading-cover').remove();
};

$.showMessage = function(type, message){

    switch (type) {
        case 'success':
            toastr.success(message, TRANSLATED_LABELS.lblSuccess);
            break;
        case 'error':
            toastr.error(message, TRANSLATED_LABELS.lblError);
            break;
        case 'warning':
            toastr.warning(message, TRANSLATED_LABELS.lblWarning);
            break;
    }

};


APP.ApiUrl = function(path, param) {
    param = typeof param !== 'undefined' ? param : '';
    if (param != '') {
        path += '?' + $.param(param);
    }
    return APP.API_URL + path;
};

$.loadAjax = function(url, dataPost) {
	$.loadingStart();
    var dataJson = $.ajax({
        'url': url,
        'type': 'POST',
        'dataType': 'json',
        'data': dataPost,
        'async': false,
        success: function(data) {
        	$.loadingEnd();
        },
        error: function(xhr, textStatus, thrownError) {
        	$.loadingEnd();
            console.log(thrownError);
        }
    });
    return dataJson.responseText;
};

$.loadContentModal = function(url, target, elm, modal) {
	$(elm).html("");
	$.get(url, function (data) {
        var $content = $(data).find(target);
        $(elm).html($content);
        $(modal).modal("show");
    });
};

$.getContentModal = function(url, elm, modal) {
	dataJson = $.loadAjax(url, {action: "get"}),
    dataObj  = JSON.parse(dataJson);
	if (dataObj.success == true) {
        //Show modal popup
        var contentHtml = dataObj.contentHtml;
        $(elm).html(contentHtml);
        $(modal).modal("show");
    }
};

$.postFormAjax = function(form) {
    var fields = form.serializeArray();
    var url = form.attr("action");
    var dataPost = {};
    $.each( fields, function( i, field ) {
       dataPost[field.name] = field.value;
    });
    var dataJson = $.loadAjax(url, dataPost);
    return dataJson;
};

$.postFormAjaxWithFile = function(form) {
	var dataPost = new FormData(form[0]);
    var url = form.attr("action");
    var dataJson = $.ajax({
        url: url,
        type: 'POST',
        data: dataPost,
        async: false,
        success: function (data) {
        	$.loadingEnd();
        },
        error: function(xhr, textStatus, thrownError) {
        	$.loadingEnd();
            console.log(thrownError);
        },
        cache: false,
        contentType: false,
        processData: false
    });
    return dataJson.responseText;
};

$.setNavigation = function() {
    var path = window.location.pathname;
    path = path.replace(/\/$/, "");
    path = decodeURIComponent(path);

    $(".nav-custom a").each(function () {
        var href = $(this).attr('href');
        if (path.substring(0, href.length) === href) {
            $(this).closest('li').addClass('active').parent().closest('li').addClass('active');
        }
    });
}

$.initSelectPicker = function(elm) {

	$(elm).selectpicker({
		liveSearch: true,
		//width: "auto",
        size: 5
	});

}

$.initInputFile = function(elm, preview) {

	$(elm).fileinput({
        initialPreview: preview,
        showUpload: false,
        showRemove: false,
        maxFileSize: 5012,
        maxFileCount: 1,
        msgProgress: 'Loading file {index} of {files} - {name} - {percent}% completed.',
        allowedFileExtensions: ["jpg", "png"],
        overwriteInitial: true,
        allowedFileTypes: ["image"]
    });

    $('.file-actions').hide();

}

$.slideTopMessage = function(message, type){

    $.notify({
        message: message,
    },{
        allow_dismiss: true,
        type: type,
        placement: {
                from: "top",
                align: "center"
        },
        animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
        },
        delay: 3000,
        z_index: 10060,
        timer: 1000
    });

}

$(function() {

	$( ".dropdown" ).hover(function() {

	    $( this ).addClass( "open" );

	  }, function() {

	    $( this ).removeClass( "open" );

	  }

	);

	$( ".dropdown-submenu" ).hover(function() {

	    $( this ).addClass( "open" );

	  }, function() {

	    $( this ).removeClass( "open" );

	  }

	);

    $('input.number-format').keyup(function(event) {

        // skip for arrow keys
        if(event.which >= 37 && event.which <= 40) return;

        // format number
        $(this).val(function(index, value) {
            return value
                .replace(/\D/g, "")
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                ;
        });
    });

});

function focusInput(idPopup, input){
    $(idPopup).on('shown.bs.modal', function () {
        $(input).focus();
    });
}

function removePopup(){
    $('.modal-backdrop').remove();
}

function clickAsEnter (btn, cls){
    $(cls).keypress(function (e) {
        if (e.which == 13){
            $(btn).focus().click();
        }
    });
}


function formatDate2Type(value, type){
    var date = new Date(value),
        day = date.getDate(),
        month = date.getMonth() + 1,
        year = date.getFullYear();
    type = type != undefined && type != '' ? type : 'YMD';

    day = day > 9 ? day : '0'+day;
    month = month > 9 ? month : '0'+month;

    if(type == 'YM') {
        return year + '/' + month;
    } else if(type == 'DMY') {
        return day + '/' + month + '/' + year;
    } else if (type == "MDY"){
        return month + '/' + day + '/'+ year;
    } else {
        return year + '/' + month + '/' + day;
    }
}

function formatDateTime(value, type, is_date) {
    var array_time = value.split(' '),
        date_format = '';
    if(array_time[0] != undefined) {
        date_format = formatDate2Type(array_time[0], type);
        if(is_date) {
            return date_format;
        } else {
            return date_format + ' ' + array_time[1];
        }
    }

    return value;
}

function ajax(options, loading, endLoadingSuccess) {
    loading = loading !== undefined? loading : true;
    endLoadingSuccess = endLoadingSuccess !== undefined? endLoadingSuccess : true;
    options.async = options.async !== undefined? options.async : true;
    options.dataType = options.dataType || "json";

    // options.success = options.success || function(response) {
    //     console.log(response);
    //     if(loading) {
    //         $.loadingEnd();
    //     }
    // };

    // options.error = options.error || function(err) {
    //     if(loading) {
    //         $.loadingEnd();
    //     }
    //     console.log(err);
    // };

    options.complete = options.complete || function(xhr) {
        if(xhr.status == 200) {
            if(endLoadingSuccess) {
                $.loadingEnd();
            }
        }
        else {
            $.loadingEnd();
        }
    }

    options.error = options.error || function(err) {
        $.loadingEnd();
        slideMessage(TRANSLATED_LABELS.lblError, TRANSLATED_LABELS.lblRequestError, "danger");
    }

    if(loading) {
        $.loadingStart();
    }
    return $.ajax(options);
}

$.fn.createDataTable = function(options, loading) {
    loading = loading !== undefined? loading : true;

    options.pageLength = options.pageLength || 15 || 15;
    //options.lengthMenu = options.lengthMenu || TRANSLATED_LABELS.arrayPage || [ 10, 25, 50, 75, 100 ];

    options.searchDelay = options.searchDelay || 500;
    options.paging = options.paging || true;
    options.lengthChange = options.lengthChange || false;
    options.info = options.info || true;
    options.searching = options.searching || true;
    options.ordering = options.ordering || true;
    options.orderMulti = options.orderMulti || false;
    options.serverSide = options.serverSide || true;
    options.language = APP.dataTableLanguage;

    return $(this).on('preXhr.dt', function ( e, settings, data ) {
        if(loading) {
            $.loadingStart();
        }
    })
    .on('processing.dt', function ( e, settings, processing ) {
        if(loading) {
            $.loadingEnd();
            // $(this).find(".tooltips").tooltip();
        }
    })
    .DataTable(options);
}


function removeModelName(arr, model) {
    for (var loop = 0; loop < arr.length; loop++) {
        arr[loop] = arr[loop][model];
    }
}

/**
 *
 * @param str: message you want to confirm
 */
$.loadDataConfirmDelete = function(str) {

    // e.preventDefault();
    $("#header-confirm").text(TRANSLATED_LABELS.lblHeaderDelete);
    $("#content-confirm").text(TRANSLATED_LABELS.lblConfirmDelete+'?');
    $("#modal-confirm-delete").modal("show");
    var data = getRowData(this);
    if(data) {
        $("#btn-delete-object").attr("data-id", data.id);
    }

}

function showModal(modalId) {
    $(modalId).modal("show");
}

function renderColor(color, text) {
    var text_color = color;
    if(text != undefined) {
        text_color = text;
    }
    var html = "<span class='cell-color' style='background-color: "+color+"!important;'>"+text_color+"</span>";
    return html;
}

$.createMiniColor = function(domId) {


    $(domId).minicolors({
        control: $(domId).attr('data-control') || 'hue',
        defaultValue: $(domId).val() || '#fff',
        format: $(domId).attr('data-format') || 'hex',
        keywords: $(domId).attr('data-keywords') || '',
        inline: $(domId).attr('data-inline') === 'true',
        letterCase: $(domId).attr('data-letterCase') || 'lowercase',
        opacity: $(domId).attr('data-opacity'),
        position: $(domId).attr('data-position') || 'bottom',
        swatches: $(domId).attr('data-swatches') ? $(domId).attr('data-swatches').split('|') : [],
        change: function(value, opacity) {
            if( !value ) return;
            if( opacity ) value += ', ' + opacity;
            if( typeof console === 'object' ) {
                console.log(value);
            }
        },
        theme: 'bootstrap'
    });
}

function hideModal(modalId) {
    $(modalId).modal("hide");
}

function getRowDataMaster(btn, table) {
    var row = $(btn).parents("tr");
    var data = table.row(row).data();
    return data;
}

function loadModalConfirm(options, dataCallback, yesCallback, noCallback) {
    if(options.header === undefined) {
        options.header = TRANSLATED_LABELS.lblHeaderDelete;
    }
    if(options.content === undefined) {
        options.content = TRANSLATED_LABELS.lblConfirmDelete+'?';
    }
    if(options.btnYesText === undefined) {
        options.btnYesText = TRANSLATED_LABELS.lblYes;
    }
    if(options.btnNoText === undefined) {
        options.btnNoText = TRANSLATED_LABELS.lblNo;
    }
    if(options.btnYesIcon === undefined) {
        options.btnYesIcon = "fas fa-check";
    }
    if(options.btnNoIcon === undefined) {
        options.btnNoIcon = "fas fa-times";
    }


    $("#header-confirm").text(options.header);
    $("#content-confirm").text(options.content);
    $("#lbl-confirm-yes-text").text(options.btnYesText);
    $("#lbl-confirm-no-text").text(options.btnNoText);
    $("#btn-confirm-yes-icon").removeClass().addClass(options.btnYesIcon);
    $("#btn-confirm-no-icon").removeClass().addClass(options.btnNoIcon);

    if(dataCallback) {
        dataCallback();
    }

    $("#btn-delete-object").off("click");
    if(yesCallback) {
        $("#btn-delete-object").on("click", yesCallback);
    }

    $("#btn-delete-no").off("click");
    if(noCallback) {
        $("#btn-delete-no").on("click", noCallback);
    }

    $("#modal-confirm-delete").modal("show");
}

function closeModalConfirm() {
    $("#modal-confirm-delete").modal("hide");
}

$.getRowData = function(btn, table) {
    var row = $(btn).parents("tr");
    return table.row(row).data();
}

function showErrors(frm, errors, frmValidator) {
    var ele = $(frm);
    ele.find("label.error").remove();

    if(errors) {
        for(var k in errors) {
            var error = {};
            error[k] = errors[k];
            frmValidator.showErrors(error);
        }
    }
}

function dateTimePicker(time_format){
    $(".datetimepicker").datetimepicker({
        format: time_format
    });
}

function timePicker(time_format){
    $(".timepicker").datetimepicker({
        format: time_format
    });
}

function getPrefix(id) {
    if(PREFIX_DATA) {
        for(var k in PREFIX_DATA) {
            if(PREFIX_DATA[k]["Prefix"]["id"] == id) {
                return PREFIX_DATA[k]["Prefix"];
            }
        }
    }

    return null;
}

function getPrefixName(id) {
    var prefix = getPrefix(id);
    if(prefix) {
        return prefix["name"];
    }

    return "";
}

function getCurrentLangKey() {
    if(CURRENT_LANG_KEY) {
        return CURRENT_LANG_KEY;
    }

    return "en-us";
}

function formatShortDate(date, lang, seperate) {
    if(date) {
        if(lang === undefined) {
            lang = getCurrentLangKey();
        }

        if(seperate === undefined) {
            seperate = "/";
        }

        if(lang == "vi-vn") {
            return date.getDate() + seperate + (date.getMonth() + 1) + seperate + x.getYear();
        }

        return  (date.getMonth() + 1) + seperate + date.getDate() + seperate + x.getYear();
    }

    return "";
}


function formatLongDate(date, lang, seperate) {
    if(date) {
        if(lang === undefined) {
            lang = getCurrentLangKey();
        }

        if(seperate === undefined) {
            seperate = "/";
        }

        if(lang == "vi-vn") {
            return date.getDate() + seperate + (date.getMonth() + 1) + seperate + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
        }

        return  (date.getMonth() + 1) + seperate + date.getDate() + seperate + date.getFullYear() + " " + date.getHours() + ":" + date.getMinutes();
    }

    return "";
}

function parseShortDate(str) {
    if(str !== undefined) {
        var temp = str.split("-");
        if(temp.length > 2) {
            try {
                var d = parseInt(temp[2]);
                var m = parseInt(temp[1]);
                var y = parseInt(temp[0]);
                return new Date(y,m - 1,d);
            } catch (error) {

            }
        }
    }

    return null;
}


function parseLongDate(str) {
    if(str !== undefined) {
        var temp = str.split(" ");
        if(temp.length > 1) {
            try {
                var temp1 = temp[0].split("-");
                var temp2 = temp[1].split(":");

                if(temp1.length > 2 && temp2.length > 2) {
                    var d = parseInt(temp1[2]);
                    var m = parseInt(temp1[1]);
                    var y = parseInt(temp1[0]);
                    var h = parseInt(temp2[0]);
                    var i = parseInt(temp2[1]);
                    var s = parseInt(temp2[2]);
                    return new Date(y,m - 1,d, h, i, s);
                }
            } catch (error) {

            }
        }
    }

    return null;
}

function activeCodeMirror(domId, type, theme){

    theme = typeof theme == 'undefined' ? 'dracula' : theme;

    var editor = CodeMirror.fromTextArea(document.getElementById(domId), {
        lineNumbers: true,
        styleActiveLine: true,
        matchBrackets: true,
        mode: type,
        autoCloseTags: true
    });

    return editor;
}

function initSelect2(arrData, elm, url){

    $(elm).select2({
        data: arrData,
        allowClear: true,
        placeholder: "Select a State",
        debug: true,
        ajax: {
            url: url,
            dataType: 'json',
            delay: 500,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                // parse the results into the format expected by Select2
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data, except to indicate that infinite
                // scrolling can be used
                params.page = params.page || 1;
                return {
                    results: data.items,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 2,
        templateResult: function (data) {
            if (data.id === '') { // adjust for custom placeholder values
                return 'Custom styled placeholder text';
            }
            return data.name;
        },
        templateSelection: function (data) {
            return data.name;
        },
        language: {
            inputTooShort: function() {
                return 'Vui lòng nhập 2 ký tự trở lên';
            },
            noResults: function() {
                return 'Không tìm thấy kết quả';
            }
        }
    });

}

function destroySelect2(elm) {
    $(elm).select2("val", "");
    $(elm).select2("refresh");
}

function loadTree(el, data_tree, data_detail) {
    $(el).jstree({
        plugins: ["checkbox"],
        core: {
            data: data_tree,
            themes: {
                "responsive": true
            }
        },
        checkbox: {
            "keep_selected_style": false
        },
        types : {
            "default" : {
                "icon" : "fa fa-folder icon-state-warning icon-lg"
            },
            "folder" : {
                "valid_children" : ["file"],
                "icon" : "fa fa-folder icon-state-warning icon-lg"
            },
            "file" : {
                "valid_children" : [],
                "icon" : "fa fa-file icon-state-warning icon-lg"
            }
        }
    }).on('ready.jstree', function() {
        $(el).jstree("open_all");
    });
}

function formatNumber(num, decimal) {
    var format = decimal || ",";
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1"+format)
};

function numberCurrency() {
    $('.check-number').each(function(i, e) {
        $(this).val(formatNumber($(this).val()));
    });
    $('.check-number').on( "keyup", function( event ) {
        // When user select text in the document, also abort.
        var selection = window.getSelection().toString();
        if ( selection !== '' ) {
            return;
        }

        // When the arrow keys are pressed, abort.
        if ( $.inArray( event.keyCode, [38,40,37,39] ) !== -1 ) {
            return;
        }

        var $this = $( this );

        // Get the value.
        var input = $this.val();

        var input = input.replace(/[\D\s\._\-]+/g, "");
        input = input ? parseInt( input, 10 ) : 0;

        $this.val( function() {
            return ( input === 0 ) ? "" : input.toLocaleString( "en-US" );
        } );
    });
};

$.fn.openLoading = function (text, icon, fixed) {
    if (this.length == 0) {
        return;
    }

    if (text === undefined) {
        text = "Loading...";
    }
    if (icon === undefined) {
        icon = '<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>';
    }
    var position = "absolute";
    if (fixed) {
        position = "fixed";
    }

    var _loading = '<div style="z-index: 9999999; position: ' + position + '; width: 100%; height: 100%; '
                    + 'text-align: center; background: rgba(0, 0, 0, 0.5); '
                    + 'color: #fff; font-size: 16px; top: 0; left: 0; opacity: 1; transition: opacity 0.5s;">'
                    + '<span style="transform: translate(-50%, -50%); top: 50%; position: absolute; left: 50%;">'
                    + icon + ' ' + text + '</span></div>';


    if (this[0].loadingElement !== undefined) {
        this[0].loadingElement.remove();
    }

    this.css("position", "relative");
    this[0].loadingElement = $(_loading).appendTo(this);
};


$.fn.closeLoading = function () {
    if (this.length == 0) {
        return;
    }

    if (this[0].loadingElement !== undefined) {
        var loadingElement = this[0].loadingElement;
        loadingElement.css("opacity", 0);
        setTimeout(function () {
            loadingElement.remove();
            loadingElement = undefined;
        }, 500);
    }
};

$.fn.openLoadingSmall = function () {
    if (this.length == 0) {
        return;
    }

    var icon = '<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>';
    var position = "absolute";

    var _loading = '<div style="z-index: 9999999; position: ' + position + '; width: 100%; height: 100%; '
                    + 'text-align: center; background: rgba(0, 0, 0, 0.5); '
                    + 'color: #fff; font-size: 16px; top: 0; left: 0; opacity: 1; transition: opacity 0.5s;">'
                    + '<span style="transform: translate(-50%, -50%); top: 50%; position: absolute; left: 50%;">'
                    + icon + ' </span></div>';


    if (this[0].loadingElement !== undefined) {
        this[0].loadingElement.remove();
    }

    this.css("position", "relative");
    this[0].loadingElement = $(_loading).appendTo(this);
};

function isSunday(value) {
    var date = new Date(value);
    return date.getDay() == 0;
}

function replaceDecimal(value, search, replace) {
    if (search == '.') {
        return value.replace(/\./g, replace);
    } else if (search == ',') {
        return value.replace(/\,/g, replace);
    } else {
        return value.replace(search, replace);
    }
}

function renderCheckboxChecked(el, is_check) {
    $(el).prop('checked',is_check).uniform('refresh');
}

function renderCheckbox(cls, id, value, field) {

    var checked = parseInt(value) == 1 ? 'checked' : '';

    return '<label class="mt-checkbox mt-checkbox-outline" >'+
        '<input type="checkbox" class="'+cls+'" data-id="'+id+'" data-field="'+field+'" '+checked+'> ' +
        '<span></span>'+
        ' </label>'
        ;
}

function notifyOrder(message) {
    $.notify({
        title: '<i class="fa fa-bell"></i>',
        message: message
    },{
        allow_dismiss: true,
        type: 'success',
        placement: {
            from: "top",
            align: "right"
        },
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        delay: 3000,
        z_index: 10060,
        timer: 2000
    });
}

/**
 * Khoi tao file input
 * @param el
 * @param initPreview: arr_path file
 * @param initialPreviewConfig: arr config preview(Ex: [{"caption": file_name, "downloadUrl": $pathFile, "key": 0, ...}]
 */
function reInitFileInput(el, initPreview, initialPreviewConfig) {

    var config = el.data("fileinput"),
        opts;
    el.fileinput("clear");

    opts = {
        overwriteInitial: config.overwriteInitial,
        initialPreview: initPreview,
        initialPreviewAsData: true,
        language: config.language,
        maxFileSize: config.maxFileSize,
        showRemove: config.showRemove,
        showUpload: config.showUpload,
        showDownload: config.showDownload,
        showUploadedThumbs: config.showUploadedThumbs,
        previewFileType: config.previewFileType,
        browseClass: config.browseClass,
        browseLabel: config.browseLabel,
        browseIcon: config.browseIcon,
        removeClass: config.removeClass,
        removeLabel: config.removeLabel,
        uploadClass: config.uploadClass,
        uploadLabel: config.uploadLabel,
        dropZoneTitle: config.dropZoneTitle,
        msgSizeTooLarge: config.msgSizeTooLarge,
        allowedFileExtensions: config.allowedFileExtensions,
        previewFileIconSettings: config.previewFileIconSettings,
        initPreview: config.initPreview,
        allowedPreviewTypes: config.allowedPreviewTypes,
        previewFileExtSettings: config.previewFileExtSettings

    };
    if(initialPreviewConfig != undefined) {
        opts.initialPreviewConfig = initialPreviewConfig;
    }
    el.fileinput("destroy");
    el.fileinput(opts);
}

function get_url_extension( url ) {
    return url.split(/\#|\?/)[0].split('.').pop().trim();
}

function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function loadNotification() {
    $("#header_inbox_bar").openLoadingSmall();

    $.ajax({
        url: APP.ApiUrl("document/documentReview/loadNotify"),
        dataType: 'json',
        type: 'GET',
        success: function (resp) {
            var content_html_notify = resp.content_html;
            $(".list-document-review").html(content_html_notify);
            $(".total-notify-review").text(resp.total_notify);
        }
    });

    setTimeout(function () {
        $("#header_inbox_bar").closeLoading();
    }, 1500);
}

function convertDate(date, lang_key){
    try {
        var format = lang_key == "en-us" ? "MM/DD/YYYY" : "DD/MM/YYYY";
        var d = new moment(date);
        return d.format(format);
    }
    catch (e) {

    }

    return null;
}

function refreshSelect2(el,fieldId,fieldName){
    $('#'+ el).html('<option value="' + fieldId + '">' + fieldName + '</option>');
    if ($('#'+ el).find("option[value='" + fieldId + "']").length) {
        $('#'+ el).val(fieldId).trigger('change');
    } else {
        var newData = {
            id: fieldId,
            label: fieldName
        };
        var newOption = new Option(newData.label, newData.id, false, false);
        $('#'+ el).append(newOption).trigger('change');
    }
}

function renderStatus(status) {
    if (status == 0) {
        return '<span class="badge badge-danger">' + TRANSLATED_LABELS.lblLock + '</span>';
    }

    return '<span class="badge badge-success">' + TRANSLATED_LABELS.lblActive + '</span>';
}

function focusInputModal(modal_id, input_focus) {
    $(modal_id).on('shown.bs.modal', function () {
        setTimeout(function() { input_focus.focus() }, 100);
    });
}

