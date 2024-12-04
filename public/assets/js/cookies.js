function aceptar_cookies() {
    var expire = new Date();
    expire = new Date(expire.getTime() + 7776000000);
    document.cookie = "ohmyphoto-cookie=aceptada; expires=" + expire;
    $('#overbox3').hide();
}

$(function () {
    if(document.cookie.match(/^(.*;)?\s*ohmyphoto-cookie\s*=\s*[^;]+(.*)?$/)==null){
        $('#overbox3').show();
    }else{
        $('#overbox3').hide();
    }
});
