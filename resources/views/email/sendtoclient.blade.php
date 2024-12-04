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


    <h5>Aquí tienes el enlace para acceder a tu galería en {{$datos['empresa']}}</h5>

    @if($datos['numfotos']>0)
    <p>Tienes que elegir {{($datos['numfotos'])}} fotografías.</p>
    @endif
    
    <p>Puedes acceder a la galería hasta el día {{Utils::fechaEsp($datos['caduco'])}}</p>
    
    @if($datos['clave'])
    <p>La clave de acceso para acceder a la galería es: {{($datos['clave'])}}</p>
    @endif

    <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> para acceder a la galería.</p>

    <p>{{$datos['ruta']}}</p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>


</body>
</html>
