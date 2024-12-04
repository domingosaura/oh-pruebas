<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body class="">
    <img id="idfot1" src="data:image/jpeg;base64,{{$datos['logo']}}" alt=""
    class="img-fluid shadow border-radius-xl" style="width:30%" />

    <h5>Aquí tienes el enlace para descargar tu galería en {{$datos['empresa']}}</h5>

    <p>Por favor sé paciente, dependiendo del tamaño de la descarga puede tardar unos minutos.</p>

    <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> para descargar galería.</p>

    <p>{{$datos['ruta']}}</p>
    
    <p>Por seguridad, su archivo de descarga está protegido con la contraseña: <strong>{{$datos['pass']}}</strong></p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>


</body>
</html>
