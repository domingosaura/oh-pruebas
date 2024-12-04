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


    <h5>Se ha reservado su sesión en {{$datos['empresa']}}</h5>
    
    <h4>{{$datos['titulo']}}</h4>
    <h5>{{$datos['descripcion']}}</h5>
    <h5>Fecha de la cita: {{$datos['start']}}</h5>



    <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> si tiene que cancelar la reserva.</p>
    
    <p>{{$datos['ruta']}}</p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>


</body>
</html>
