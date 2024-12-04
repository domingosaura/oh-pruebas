<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        <?php echo file_get_contents(asset('assets').'/css/quill.snow.css');
        ?>
    </style>
</head>

<body>
    <div class="ql-editor">

        {!!$datos['personalizado']!!}

        @if($datos['ruta'])
        <p>Pulsa <a href="{{$datos['ruta']}}">aquí</a> para acceder a la galería.</p>
        <p>Puedes acceder a la galería hasta {{Utils::fechaEsp($datos['caduco'])}}</p>
        @endif
        @if($datos['clave'])
        <p>La clave de acceso para acceder a la galería es: {{($datos['clave'])}}</p>
        @endif

    </div>
    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
    </div>
</body>

</html>