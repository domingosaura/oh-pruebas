<!DOCTYPE html>
<html lang='es'>
<head>

    <meta property="og:title" content="OhMyPhoto"/>
    <meta property="og:description" content="galería"/>
    <meta property="og:url" content="{{URL('/')}}"/>
    <meta property="og:image"content="/oh/dot.png"/>
  

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0QPYR7HL02"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0QPYR7HL02');
</script>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/oh/oh_icon_min.png">
    <link rel="icon" type="image/png" href="/oh/oh_icon_min.png">
    <title>
        OhMyPhoto {{date('Y')}}
    </title>


    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="{{ asset('assets') }}/css/nucleo-icons.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/nucleo-svg.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/virtualselect/virtual-select.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/filepond/filepond.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/filepond/filepond-plugin-image-preview.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/oh.css?v=2" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="{{ asset('assets') }}/css/bootstrap.min.css" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets') }}/css/material-dashboard.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/quill.snow.css" rel="stylesheet">
    <!--<link href="{ { asset('assets') } }/js/toastr/toastr.min.css" rel="stylesheet" />-->
    <link href="{{ asset('assets') }}/css/choices.min.css" rel="stylesheet" />
    <link href="{{ asset('assets') }}/css/lightbox.min.css" rel="stylesheet" />
    @livewireStyles
</head>

<body class="g-sidenav-show bg-gray-200">
    {{ $slot }}

    <script src="{{ asset('assets') }}/js/core/popper.min.js"></script>
    <script src="{{ asset('assets') }}/js/core/bootstrap.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/smooth-scrollbar.min.js"></script>
    <!-- Kanban scripts -->
    <script src="{{ asset('assets') }}/js/plugins/dragula/dragula.min.js"></script>
    <script src="{{ asset('assets') }}/js/plugins/jkanban/jkanban.js"></script>
    <script src="{{ asset('assets') }}/js/jquery-3.7.1.min.js"></script>
    <!--<script src="{ { asset('assets') } }/js/toastr/toastr.min.js"></script>-->
    <script src="{{ asset('assets') }}/js/plugins/sweetalert.min.js"></script>
    <script src="{{ asset('assets') }}/virtualselect/virtual-select.min.js"></script>
    <script src="{{ asset('assets') }}/filepond/filepond-plugin-file-validate-size.min.js"></script>
    <script src="{{ asset('assets') }}/filepond/filepond-plugin-file-metadata.min.js"></script>
    <script src="{{ asset('assets') }}/filepond/filepond-plugin-image-preview.min.js"></script>
    <script src="{{ asset('assets') }}/filepond/filepond.min.js"></script>
    <script src="{{ asset('assets') }}/filepond/es-es.js" type="module"></script>
    <script src="{{ asset('assets') }}/js/clipboard.min.js"></script>
    <script src="{{ asset('assets') }}/js/cookies.js" type="text/javascript"></script>


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-header text-center">
                    <h5 class="modal-title text-center" id="staticBackdropLabel">Procesando...</h5>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class="col-12 loader text-center mb-4"></div>
                </div>
                    Por favor espere a que se procesen los datos recibidos.<br/>Esta pantalla se cerrará cuando termine el proceso.
                </div>
            </div>
        </div>
    </div>

    @stack('js')
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
<!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets') }}/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets') }}/js/material-dashboard.min.js?v=3.0.1"></script>
    @livewireScripts
<script>


window.addEventListener('mensaje', event => { 
    Swal.fire({
        position: "center",
        icon: event.detail[0].type,
        iconColor:'#ff6f0e',
        title: event.detail[0].message,
        showConfirmButton: false,
        showCloseButton:true,
        timer: 4000
        });
});
window.addEventListener('mensajecorto', event => { 
    Swal.fire({
        position: "center",
        icon: event.detail[0].type,
        iconColor:'#ff6f0e',
        title: event.detail[0].message,
        showConfirmButton: false,
        showCloseButton:true,
        timer: 2000
        });
});
window.addEventListener('mensajelargo', event => { 
    Swal.fire({
        position: "center",
        icon: event.detail[0].type,
        iconColor:'#ff6f0e',
        title: event.detail[0].message,
        showConfirmButton: false,
        showCloseButton:true,
        timer: 15000
        });
});

window.addEventListener('openwhatsapp', event => {
    //console.log(event.detail[0].id);
    window.open(event.detail[0].id, '_blank');
});

window.addEventListener('openpdf', event => {
    window.open(event.detail[0].id, '_blank');
});

window.addEventListener('alerta', event => { 
    alert(event.detail[0].message);
});

window.addEventListener('scrolltop', event => { 
    //window.scrollTo(0,0); // no funciona
    document.getElementById('master').scrollIntoView();
});

window.addEventListener('postprocesado_end', event => { 
    //$('#staticBackdrop').modal('hide');
    Swal.close();
  });

window.addEventListener('postprocesado_end5', event => { 
    setTimeout(function(){
      //scrolltopp();
      Swal.close();
      //$('#staticBackdrop').modal('hide');
    }, 5000);
    Swal.close();
    //$('#staticBackdrop').modal('hide');
  });

  window.addEventListener('openmodalid', event => { 
    $('#'+event.detail[0].id).modal('show');
  });

  window.addEventListener('closemodalid', event => { 
    $('#'+event.detail[0].id).modal('hide');
  });

  window.addEventListener('closemodalclass', event => { 
    $('.'+event.detail[0].id).modal('hide');
  });

function postprocesado_init(){
    Swal.fire({
        position: "center",
        icon: 'info',
        iconColor:'#ff6f0e',
        //title: event.detail[0].message,
        titleText: 'Por favor, espere...',
        html: 'Estamos procesando las imágenes recibidas, espere...<br/><div class="col-12 loader text-center mb-4"></div>',
        showConfirmButton: false,
        showCloseButton:false,
        allowOutsideClick:false,
        //timer: 4000
        });
    try {
      scrolltopp();      
    } catch (error) {}
    //$('#staticBackdrop').modal('show');
}
function postprocesado_init_descarga(){
    Swal.fire({
        position: "center",
        icon: 'info',
        iconColor:'#ff6f0e',
        //title: event.detail[0].message,
        titleText: 'Por favor, espere...',
        html: 'Estamos procesando su solicitud, esto puede tardar varios minutos no cierre el navegador<br/><div class="col-12 loader text-center mb-4"></div>',
        showConfirmButton: false,
        showCloseButton:false,
        allowOutsideClick:false,
        //timer: 4000
        });
    try {
      scrolltopp();      
    } catch (error) {}
    //$('#staticBackdrop').modal('show');
}

  function postprocesado_endd(){
    //$('#staticBackdrop').modal('hide');
    Swal.close();
  }

  function postprocesado_endd5(){
    setTimeout(function(){
      //scrolltopp();
      Swal.close();
      //$('#staticBackdrop').modal('hide');
    }, 5000);
    //scrolltopp();
    //$('#staticBackdrop').modal('hide');
    Swal.close();
    if($('#staticBackdrop').is(":hidden")==false){
    }
  }

function scrolltopp(){
    document.getElementById('master').scrollIntoView();
}

function clipboard(idid) {
    let text = document.getElementById(idid).innerHTML;
}
</script>

<div id="overbox3" style="display:none">
    <div id="infobox3">
        <h6>&nbsp;</h6>
        <h6>Esta web utiliza cookies para obtener datos estadísticos de la navegación de sus usuarios. Si continúas navegando consideramos que aceptas su uso.</h6>
        <h6>
            <a onclick="aceptar_cookies();" rel="tooltip" class="btn botonoh_negro puntero"
            data-original-title=""
            title="Aceptar cookies y cerrar">
            <i class="material-icons">check</i>&nbsp;Aceptar y continuar
        </a>
        <a rel="tooltip" class="btn botonoh_negro"
        href="{{ route('cookies')}}" data-original-title=""
        title="Ver política de cookies">
        <i class="material-icons">visibility</i>&nbsp;Política de cookies
        </a>
        </h6>
        <h6>&nbsp;</h6>
    </div>
</div>

</body>

</html>
