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
        .quillnomargen{
        padding: 0 !important;
        white-space: normal !important;
        }

    </style>
</head>

<body>
    <div class="ql-editor quillnomargen">

        {!!$datos['personalizado']!!}

    </div>
    <div class="copyright text-center text-sm text-lg-start">
        © {{date('Y')}}&nbsp;OhMyPhoto
    </div>
</body>

</html>