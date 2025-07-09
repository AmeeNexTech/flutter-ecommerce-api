<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - Shoporia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #ffffff;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
            background: #ffffff;
        }

        .greeting {
            font-size: 24px;
            color: #2d3748;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .otp-container {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .otp-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            z-index: -1;
        }

        .otp-label {
            font-size: 14px;
            color: #718096;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .otp-code {
            font-size: 48px;
            font-weight: bold;
            color: #2d3748;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .otp-expiry {
            font-size: 14px;
            color: #e53e3e;
            margin-top: 15px;
            font-weight: 600;
        }

        .warning {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }

        .warning-title {
            font-size: 16px;
            color: #c53030;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .warning-text {
            font-size: 14px;
            color: #742a2a;
            line-height: 1.6;
        }

        .info-box {
            background: #ebf8ff;
            border-left: 4px solid #3182ce;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }

        .info-title {
            font-size: 16px;
            color: #2c5282;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-text {
            font-size: 14px;
            color: #2a4365;
            line-height: 1.6;
        }

        .footer {
            background: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            font-size: 14px;
            color: #718096;
            margin-bottom: 15px;
        }

        .social-links {
            margin-top: 20px;
        }

        .social-link {
            display: inline-block;
            margin: 0 10px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .social-link:hover {
            color: #764ba2;
        }

        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }

            .email-container {
                border-radius: 15px;
            }

            .header {
                padding: 30px 20px;
            }

            .content {
                padding: 30px 20px;
            }

            .otp-code {
                font-size: 36px;
                letter-spacing: 6px;
            }

            .greeting {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🔐 Shoporia</div>
            <div class="header-subtitle">Password Reset Request</div>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $name }}! 👋</div>

            <div class="message">
                We received a request to reset your Shoporia account password. To ensure your account security, please use the verification code below to complete the password reset process:
            </div>

            <div class="otp-container">
                <div class="otp-label">Your Reset Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-expiry">⏰ Valid for 3 minutes only</div>
            </div>

            <div class="info-box">
                <div class="info-title">ℹ️ How to Reset Your Password</div>
                <div class="info-text">
                    1. Enter the code above in the password reset form<br>
                    2. Create a new strong password<br>
                    3. Confirm your new password<br>
                    4. Click "Reset Password" to complete
                </div>
            </div>

            <div class="warning">
                <div class="warning-title">⚠️ Security Notice</div>
                <div class="warning-text">
                    • Never share this code with anyone<br>
                    • Shoporia staff will never ask for this code<br>
                    • If you didn't request a password reset, please ignore this email<br>
                    • Consider changing your password regularly for better security
                </div>
            </div>

            <div class="message">
                If you have any questions or need assistance, please contact our support team. We're here to help!
            </div>
        </div>

        <div class="footer">
            <div class="footer-text">
                © {{ date('Y') }} Shoporia. All rights reserved.<br>
                This email was sent to {{ $email ?? 'your email' }}
            </div>

            <div class="social-links">
                <a href="#" class="social-link">Help Center</a>
                <a href="#" class="social-link">Privacy Policy</a>
                <a href="#" class="social-link">Terms of Service</a>
            </div>
        </div>
    </div>
</body>
</html>
