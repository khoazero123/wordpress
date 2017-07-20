

var FGC_Chat = {};

FGC_Chat.init = function() {
    FGC_Chat.createCookie();
    FGC_Chat.getCookie();
    FGC_Chat.askNotification();
}


FGC_Chat.main = function(pusher) {
    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        if('Notification' in window && Notification.permission == 'granted') {
            FGC_Chat.createNotification(data);
        } //else 
        alert(data.message);
    });
}

FGC_Chat.createNotification = function (data) {
    n = new Notification('Bạn nhận được thông báo mới', {
        body: data.name + ' đã gửi tin nhắn cho bạn: ' + data.message,
        icon: data.icon | 'https://freetuts.net/public/logo/icon.png',
        tag: data.url | 'http://google.com/'
    });
    setTimeout(n.close.bind(n), 10000);
    n.onclick = function () {
        window.location.href = this.tag;
    }
}

FGC_Chat.askNotification = function () {
    document.addEventListener('DOMContentLoaded', function () {
        if (!Notification) {
            alert('Trình duyệt của bạn không hỗ trợ chức năng này.');return;
        }
        if (Notification.permission !== 'granted')
            Notification.requestPermission();
    });
}

FGC_Chat.createCookie = function(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}
FGC_Chat.getCookie = function(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) {
                c_end = document.cookie.length;
            }
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return "";
}
