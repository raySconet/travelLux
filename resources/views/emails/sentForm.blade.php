<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>

        body{
            padding:0;
            margin:0;
            font-family:Arial;
        }

        .titleContainer{
            background:#B6844A;
            text-align:center;
            padding:12px;
        }

        .titles{
            color:white;
            font-weight:bold;
            letter-spacing:1px;
        }

        .mt20{
            margin-top:20px;
        }

    </style>

</head>

<body>

<div class="titleContainer">
    <div class="titles">
        Travelux
    </div>
</div>

<div class="mt20">

    <div>
        Dear {{ $customerName }},
    </div>

    <br>

    <div>
        We need you to complete this form so we can continue planning your vacation.
    </div>

    <br>

    <div>
        Please click
        <a href="{{ route('form.show',$token) }}">
            here
        </a>
        to complete the form.
        Don't forget to click Submit when you're done.
    </div>

    <br>

    <div>
        Thanks for choosing Travelux!
    </div>

    <br>

    <div>Thank you,</div>
    <div>{{ $userName }}</div>
    <div>Travelux</div>

</div>

</body>
</html>