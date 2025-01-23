
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email Address</title>
</head>
<body style="background: linear-gradient(135deg, #f4f6f9 0%, #aec8e7 100%); font-family: Arial, sans-serif; margin: 0; padding: 10px;">
    <table style="max-width: 600px; width: 100%; margin: 10px auto; background-color: white; border-radius: 24px; overflow: hidden; box-shadow: 0 15px 30px -12px rgba(0, 0, 0, 0.1);" cellpadding="0" cellspacing="0">
        <tr>
            <td style="padding: 0;">
                {!! App\View\Components\EmailLogo::render() !!}
            </td>
        </tr>
        <tr>
            <td style="padding: 32px;">
                <h1 style="color: #1e40af; font-size: 24px; margin-bottom: 20px;">Verify Your Email Address</h1>
                <p style="color: #334155; font-size: 16px; line-height: 1.5; margin-bottom: 24px;">
                    Thank you for registering with ExpensaGO! Please click the button below to verify your email address.
                </p>
                <div style="text-align: center; margin-bottom: 32px;">
                    <a href="{{ $actionUrl }}"
                       style="display: inline-block;
                              background: linear-gradient(135deg, #3095c3 0%, #9cc3e5 100%);
                              color: white;
                              text-decoration: none;
                              padding: 14px 28px;
                              border-radius: 50px;
                              font-size: 16px;
                              font-weight: 500;">
                        Verify Email Address
                    </a>
                </div>
                <p style="color: #64748b; font-size: 14px;">
                    If you did not create an account, no further action is required.
                </p>
            </td>
        </tr>
        <tr>
            <td style="background: linear-gradient(135deg, #789ae4 0%, #576cb1 100%); padding: 24px; text-align: center;">
                <p style="color: white; margin: 0; font-size: 14px;">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </td>
        </tr>
    </table>
</body>
</html>
