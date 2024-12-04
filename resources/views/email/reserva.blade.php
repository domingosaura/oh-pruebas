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

    <h5>Este mail es una confirmación de reserva en {{$datos['empresa']}}</h5>

    <p>Confirmamos que se ha efectuado su reserva para la fecha {{$datos['fechareserva']}}@if($datos['importe']>0)por un importe de {{$datos['importe']}} &euro; por medio de {{$datos['pago']}}@endif.</p>
    
    <p></p>

    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
      </div>

</body>
</html>
