<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Password Reset Code</title>
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                text-align: center;
            }
            .container {
                max-width: 600px;
                margin: 20px auto;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }
            .header {
                background-color: #1a73e8;
                color: #ffffff;
                padding: 20px;
                text-align: center;
            }
            .header h1 {
                margin: 0;
                font-size: 24px;
            }
            .content {
                padding: 30px;
                color: #333333;
            }
            .otp-code {
                font-size: 32px;
                font-weight: bold;
                color: #1a73e8;
                text-align: center;
                margin: 20px 0;
                letter-spacing: 5px;
            }
            .content p {
                font-size: 16px;
                line-height: 1.6;
                margin: 10px 0;
            }
            .footer {
                background-color: #f4f4f4;
                padding: 20px;
                text-align: center;
                font-size: 14px;
                color: #666666;
            }
            @media only screen and (max-width: 600px) {
                .container {
                    margin: 10px;
                }
                .otp-code {
                    font-size: 28px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>Shoporia Password Reset</h1>
            </div>
            <div class="content">
                <p>Hello {{ $name }},</p>
                <p>We received a request to reset your Shoporia account password. Please use the following OTP to verify your identity:</p>
                <div class="otp-code">{{ $otp }}</div>
                <p>This OTP is valid for 4 minutes only. If you did not request a password reset, please ignore this email.</p>
                <p>To reset your password, enter this OTP in the Shoporia app.</p>
                <p>Thank you for choosing Shoporia!</p>
            </div>
            <div class="footer">
                <p>© {{ date('Y') }} Shoporia. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
