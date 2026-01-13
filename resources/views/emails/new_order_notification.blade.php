<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Order Notification</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: 'Segoe UI', Roboto, Arial, sans-serif;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#f4f4f4; padding:40px 0;">
        <tr>
            <td align="center">
                <!-- Container -->
                <table width="600" border="0" cellspacing="0" cellpadding="0" style="background:#ffffff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); overflow:hidden;">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background:#2563eb; padding:20px;">
                            <h1 style="margin:0; font-size:22px; color:#ffffff;">📦 New Order Alert</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <p style="font-size:16px; margin:0 0 15px;">Hello Admin,</p>
                            <p style="font-size:15px; margin:0 0 20px;">
                                A new order has just been placed. Here are the details:
                            </p>

                            <!-- Order Info Card -->
                            <table width="100%" cellpadding="12" cellspacing="0" border="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:8px; margin-bottom:20px;">
                                <tr>
                                    <td width="30%" style="font-weight:600; color:#111827;">Order Code</td>
                                    <td style="color:#374151;">{{ $order?->code }}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="font-weight:600; color:#111827;">Customer Name</td>
                                    <td style="color:#374151;">{{ $order?->user?->name }}</td>
                                </tr>
                                <tr>
                                    <td width="30%" style="font-weight:600; color:#111827;">Customer Email</td>
                                    <td style="color:#374151;">{{ $order?->user?->email }}</td>
                                </tr>
                            </table>

                            <p style="font-size:15px; margin:0 0 25px;">
                                Please log in to the admin panel to review and process this order.
                            </p>

                            <!-- Button -->
                            <table cellspacing="0" cellpadding="0" border="0" align="center" style="margin:auto;">
                                <tr>
                                    <td bgcolor="#2563eb" style="border-radius:6px;">
                                        <a href="{{ route('all_orders.index') }}" target="_blank"
                                            style="display:inline-block; padding:12px 24px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:6px;">
                                            View Order
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background:#f3f4f6; padding:15px; font-size:13px; color:#6b7280;">
                            © {{ date('Y') }} Sky Business Trade. All rights reserved.
                        </td>
                    </tr>
                </table>
                <!-- End Container -->
            </td>
        </tr>
    </table>

</body>
</html>
