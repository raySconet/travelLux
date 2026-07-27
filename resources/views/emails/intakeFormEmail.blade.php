<!DOCTYPE html>
<html lang="en">
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

        .mt-20{
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

<div class="mt-20">

    <div>
        Dear {{ $customerName }},
    </div>

    <br>

    <div>
        Please click
        <a href="{{ route('customer.intake-form', $token) }}">
            here
        </a>
        to fill out the form so I can start crafting your dream vacay!
    </div>

    <br>

    <div>Thank you,</div>
    <div>{{ $userName }}</div>
    <div>Travelux</div>

</div>

</body>
</html>