<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contrato</title>
    <style>
        body {
            background: white;
        }

        page[size="A4"] {
            background: white;
            width: 21cm;
            /*height: 29.7cm;*/
            display: block;
            margin: 0 auto;
            /*padding: 10px;*/
            margin-bottom: 0.5cm;
            box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
        }

        .page-break {
            page-break-after: always;
        }

        @media print {

            body,
            page[size="A4"] {
                margin: 0;
                box-shadow: 0;
            }
        }

        <?php echo file_get_contents(asset('assets').'/css/bootstrap.min.css');
        ?>

        <?php echo file_get_contents(asset('assets').'/css/quill.snow.css');
        ?>
    </style>
</head>

<body>
    <page size="A4nosan" class="ql-editor">
        <p>{!!$contrato!!}</p>
    </page>




    <table>
        <tr>
          <td class="text-center">
            @if($firmaemp && 1==1)
            <img src="data:image/png;base64,{{$firmaemp}}" alt="" class="img-fluid" style="max-width:90%" />
            @endif
            <p>{{$nombreemp}}</p>

          </td>
          <td class="text-center">
            @if($firmacli && 1==1)
            <img src="data:image/png;base64,{{$firmacli}}" alt="" class="img-fluid" style="max-width:90%" />
            @endif
            <p>{{$nombrecli}}</p>

          </td>
        </tr>
      </table>
      


</body>

</html>