<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Your Jaguza password reset code</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f4; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden;">
                    <tr>
                        <td style="background:#1E7B4E; padding:24px; text-align:center;">
                            <h1 style="color:#ffffff; margin:0; font-size:20px;">Jaguza</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px; color:#333333;">
                            <p style="margin-top:0;">Hi {{ $name }},</p>
                            <p>We received a request to reset the password for your Jaguza account. Use the code below to continue. This code expires in 10 minutes.</p>
                            <p style="text-align:center; margin:32px 0;">
                                <span style="display:inline-block; font-size:32px; font-weight:bold; letter-spacing:8px; color:#1E7B4E;">{{ $code }}</span>
                            </p>
                            <p>If you didn't request a password reset, you can safely ignore this email &mdash; your password will not be changed.</p>
                            <p style="margin-bottom:0;">&mdash; The Jaguza Team</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
