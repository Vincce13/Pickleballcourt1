<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Booking + Receipt – Admin Alert</title>
</head>
<body style="margin:0;padding:0;background:#f7f7fb;font-family:Arial,sans-serif;color:#0f0f14;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f7f7fb;padding:32px 0;">
<tr><td align="center">
<table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

    <!-- LOGO -->
    <tr><td align="center" style="padding-bottom:20px;">
        <div style="font-weight:900;font-size:22px;letter-spacing:4px;color:#0f0f14;">
            TDA<span style="color:#4f46e5;"> COURT</span>
        </div>
        <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;margin-top:3px;">Admin Notification</div>
    </td></tr>

    <!-- CARD -->
    <tr><td style="background:#fff;border-radius:16px;border:1px solid #e4e4ef;overflow:hidden;">

        <!-- HEADER -->
        <div style="background:linear-gradient(135deg,#059669,#10b981);padding:28px 36px;text-align:center;">
            <div style="font-size:44px;margin-bottom:10px;">📎🔔</div>
            <div style="font-size:22px;font-weight:900;color:#fff;margin-bottom:6px;">New Booking — Receipt Uploaded!</div>
            <div style="font-size:13px;color:rgba(255,255,255,.88);margin-bottom:14px;">
                A customer has uploaded their GCash receipt. Please verify and confirm the booking.
            </div>
            <div style="background:rgba(255,255,255,.2);border-radius:10px;padding:10px 24px;display:inline-block;">
                <div style="font-size:11px;color:rgba(255,255,255,.7);letter-spacing:1px;text-transform:uppercase;margin-bottom:4px;">Booking Reference</div>
                <div style="font-size:24px;font-weight:900;color:#fff;letter-spacing:4px;">{{ $reservation->reference_number }}</div>
            </div>
        </div>

        <!-- RECEIPT NOTICE -->
        <div style="background:#ecfdf5;border-bottom:2px solid #6ee7b7;padding:14px 36px;text-align:center;">
            <div style="font-size:13px;color:#065f46;font-weight:600;">
                📸 Receipt uploaded on <strong>{{ $reservation->receipt_uploaded_at?->format('M d, Y') }}</strong>
                at <strong>{{ $reservation->receipt_uploaded_at?->format('h:i A') }}</strong>
                ({{ $reservation->receipt_uploaded_at?->diffForHumans() }})
            </div>
        </div>

        <!-- DETAILS -->
        <div style="padding:28px 36px;">

            <!-- CLIENT -->
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#6b6b80;margin-bottom:10px;">👤 Client Information</div>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;width:40%;">Full Name</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">Mobile</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->mobile_number }}</td>
                </tr>
                <tr>
                    <td style="padding:9px 0;font-size:13px;color:#6b6b80;">Email</td>
                    <td style="padding:9px 0;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->email }}</td>
                </tr>
            </table>

            <!-- BOOKING -->
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#6b6b80;margin-bottom:10px;">📅 Booking Details</div>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;width:40%;">Court</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->court_name }}</td>
                </tr>
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">Date</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->booking_date->format('l, F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;vertical-align:top;">Time Slots</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;text-align:right;">
                        @if(is_array($reservation->time_slots))
                            @foreach($reservation->time_slots as $slot)
                                <span style="display:inline-block;background:#eef2ff;color:#4f46e5;font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;margin:2px;">{{ $slot }}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">Payment Method</td>
                    <td style="padding:9px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->payment_method }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 0;font-size:14px;font-weight:700;">Amount to Verify</td>
                    <td style="padding:12px 0;font-size:22px;font-weight:900;color:#059669;text-align:right;">₱{{ number_format($reservation->amount) }}</td>
                </tr>
            </table>

            <!-- STEPS TO VERIFY -->
            <div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:12px;padding:16px 18px;margin-bottom:22px;">
                <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:10px;">⚡ Action Required — Steps to verify:</div>
                <div style="font-size:13px;color:#78350f;line-height:2;">
                    1️⃣ Click the button below to view the receipt in the admin panel.<br>
                    2️⃣ Open your <strong>GCash app</strong> → check transaction history.<br>
                    3️⃣ Verify the amount <strong>₱{{ number_format($reservation->amount) }}</strong> was received.<br>
                    4️⃣ Confirm the reference note: <strong>{{ $reservation->reference_number }}</strong><br>
                    5️⃣ Click <strong>"💰 Mark as Paid"</strong> → customer will automatically receive a confirmation email.
                </div>
            </div>

            <!-- CTA -->
            <div style="text-align:center;">
                <a href="{{ url('/admin/reservations/' . $reservation->id) }}"
                   style="display:inline-block;background:#4f46e5;color:#fff;font-size:15px;font-weight:700;padding:14px 32px;border-radius:10px;text-decoration:none;">
                    📎 View Receipt & Verify Payment →
                </a>
                <div style="margin-top:10px;font-size:12px;color:#9ca3af;">
                    Submitted {{ $reservation->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

    </td></tr>

    <!-- FOOTER -->
    <tr><td style="padding:20px 0;text-align:center;">
        <div style="font-size:11px;color:#9ca3af;line-height:1.8;">
            © {{ date('Y') }} TDA Court Admin Notification<br>
            Sent because a customer uploaded a GCash receipt for booking <strong>{{ $reservation->reference_number }}</strong>.
        </div>
    </td></tr>

</table>
</td></tr>
</table>
</body>
</html>