<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <img id="idfot1" src="data:image/jpeg;base64,{{$datos['logo']}}" alt=""
    class="img-fluid shadow border-radius-xl" style="width:30%" />

    <h5>Este mail es para recordarte que tu galería {{$datos['nombregaleria']}} caduca mañana.</h5>

    <p>Por favor, accede aquí y confirma tu selección.</p>
    
    <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> para acceder a la galería.</p>
    
    @if($datos['clave'])
    <p>La clave de acceso para acceder a la galería es: {{($datos['clave'])}}</p>
    @endif

    <p>{{$datos['ruta']}}</p>

    <p></p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>


</body>
</html>
