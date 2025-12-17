class Notification {
    constructor() {
    }

    static showNotice(messageType, message, messageHeader = '') {
        toastr.clear();

        toastr.options = {
            closeButton: false,
            positionClass: 'toast-top-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 3000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };

        toastr[messageType](message, messageHeader);
    }

    static showError(message, messageHeader = '') {
        this.showNotice('error', message, messageHeader);
    }

    static showSuccess(message, messageHeader = '') {
        this.showNotice('success', message, messageHeader);
    }
}

$(document).ready(() => {
    new Notification();
    window.Notification = Notification;
});
