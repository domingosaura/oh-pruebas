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


    <h5>Solicitud para reserva de sesión en {{$datos['empresa']}}</h5>

    <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> para acceder al calendario de reservas.</p>
    
    <p>{{$datos['ruta']}}</p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>


</body>
</html>
