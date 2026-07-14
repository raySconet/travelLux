<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <style>
            body {
                padding: 0;
                margin: 0;
                font-family: Arial;
            }

            .titleContainer {
                background-color: #B6844A;
                text-align: center;
                padding: 12px !important;
            }

            .titleContainer .titles {
                color: #FFFFFF;
                letter-spacing: 1px;
                text-align: center;
            }

            .fontBold {
                font-weight: bold;
            }

            .mt-20 {
                margin-top: 20px;
            }
        </style>
    </head>

    <body>
        <div class="titleContainer">
            <div class="titles fontBold">Tavelux</div>
        </div>

        <div class="mt-20">
            <div>Dear {{ $customerName }}</div>

            <br/>

            <div>{{ $userName }} has invited you to add your own information to our travel management system so that we can better serve you in your travel needs.</div>
            
            <br/>

            <div>
                Please click
                <a href="{{ route('customer.invitation', ['token' => $invitation]) }}">
                    here
                </a>
                to accept the invitation and provide your information.
            </div>

            <br/>
            
            <div>Thank you,</div>
            <div>{{ $userName }}</div>
            <div>Travelux</div>
        </div>
    </body>
</html>