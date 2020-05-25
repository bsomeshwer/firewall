<!DOCTYPE html>
<html>
<head>
    <style type="text/css">
        .card-header {
            margin-left: 10%;
            margin-right: 10%;
            background-color: #f5f8fa;
            width: 80%;
            padding: 1%;
        }

        .name {
            text-align: center;
            font-weight: bold;
            font-size: 18pt;
        }

        .container {
            margin-top: 10px;
            margin-left: 10%;
            margin-right: 10%;
            width: 80%;
            padding: 1%;
            /*background-color: #fcffc9;*/
            background-color: #ffffff;
        }

        .trace {
            width: 80%;
            text-align: left;
            font-size: 14pt;
            /*white-space: normal;*/
            /*word-break: break-word;*/
            overflow-wrap: break-word;
        }
    </style>

</head>
<body>
<div class="card-header">
    <p class="name">
        {{ 'Application Name: '.' '. config('app.name') }}
    </p>
    <small style="float: right">
        An exception occurred in the application... {{\Carbon\Carbon::now()->format('Y-m-d H:i:s')}}
    </small>
</div>

<div class="container">
    <p class="trace">
        <span style="font-weight: bold; font-style: italic; text-decoration: underline">
            Exception trace information:</span>
        <br><br>
        <span style="margin-top: 5px">{{$exception}}</span>
    </p>
</div>
</body>
</html>