<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Received – TDA Court</title>
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
        <div style="font-size:11px;letter-spacing:2px;text-transform:uppercase;color:#9ca3af;margin-top:3px;">Court Reservation</div>
    </td></tr>

    <!-- CARD -->
    <tr><td style="background:#fff;border-radius:16px;border:1px solid #e4e4ef;overflow:hidden;">

        <!-- HEADER -->
        <div style="background:linear-gradient(135deg,#4f46e5,#7c3aed);padding:36px;text-align:center;">
            <div style="font-size:52px;margin-bottom:12px;">📋</div>
            <div style="font-size:26px;font-weight:900;color:#fff;margin-bottom:8px;">We've Received Your Booking!</div>
            <div style="font-size:14px;color:rgba(255,255,255,.88);margin-bottom:18px;">
                Hi <strong>{{ $reservation->full_name }}</strong>! Your reservation has been submitted.<br>
                Please complete the next step below to secure your slot.
            </div>
            <!-- REFERENCE -->
            <div style="background:rgba(255,255,255,.18);border-radius:12px;padding:14px 28px;display:inline-block;">
                <div style="font-size:11px;color:rgba(255,255,255,.7);letter-spacing:1px;text-transform:uppercase;margin-bottom:6px;">Booking Reference</div>
                <div style="font-size:26px;font-weight:900;color:#fff;letter-spacing:5px;">{{ $reservation->reference_number }}</div>
            </div>
        </div>

        <!-- STATUS BADGE -->
        <div style="background:#fffbeb;border-bottom:2px solid #fcd34d;padding:14px 36px;text-align:center;">
            <div style="font-size:14px;font-weight:700;color:#92400e;">
                ⏳ Status: Pending Payment &nbsp;·&nbsp; Action required from you below
            </div>
        </div>

        <!-- BODY -->
        <div style="padding:28px 36px;">

            <!-- BOOKING DETAILS -->
            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1.5px;color:#6b6b80;margin-bottom:12px;">📋 Your Booking Details</div>
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;width:40%;">👤 Name</td>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">🏸 Court</td>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->court_name }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">📅 Date</td>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->booking_date->format('l, F j, Y') }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;vertical-align:top;">⏰ Time Slots</td>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;text-align:right;">
                        @if(is_array($reservation->time_slots))
                            @foreach($reservation->time_slots as $slot)
                                <span style="display:inline-block;background:#eef2ff;color:#4f46e5;font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;margin:2px;">{{ $slot }}</span>
                            @endforeach
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;color:#6b6b80;">💳 Payment Method</td>
                    <td style="padding:10px 0;border-bottom:1px solid #f3f4f6;font-size:13px;font-weight:700;text-align:right;">{{ $reservation->payment_method }}</td>
                </tr>
                <tr>
                    <td style="padding:14px 0;font-size:15px;font-weight:700;">💰 Total Amount Due</td>
                    <td style="padding:14px 0;font-size:22px;font-weight:900;color:#4f46e5;text-align:right;">₱{{ number_format($reservation->amount) }}</td>
                </tr>
            </table>

            <!-- NEXT STEP: UPLOAD RECEIPT -->
            <div style="background:#ecfdf5;border:1.5px solid #6ee7b7;border-radius:12px;padding:20px;margin-bottom:24px;text-align:center;">
                <div style="font-size:16px;font-weight:900;color:#065f46;margin-bottom:8px;">📸 Next Step: Upload Your Receipt</div>
                <div style="font-size:13px;color:#047857;line-height:1.8;margin-bottom:16px;">
                    Please send your payment of <strong>₱{{ number_format($reservation->amount) }}</strong> via <strong>{{ $reservation->payment_method }}</strong>,<br>
                    then upload your receipt screenshot so our admin can verify and confirm your booking.
                </div>
                <a href="{{ url('/booking/' . $reservation->reference_number) }}"
                   style="display:inline-block;background:#059669;color:#fff;font-size:15px;font-weight:700;padding:14px 32px;border-radius:10px;text-decoration:none;">
                    📎 Upload Receipt Now →
                </a>
            </div>

            <!-- HOW IT WORKS -->
            <div style="background:#f7f7fb;border:1px solid #e4e4ef;border-radius:12px;padding:18px;margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:#0f0f14;margin-bottom:12px;">🔄 What happens next?</div>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="width:32px;vertical-align:top;padding-top:2px;">
                            <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:900;color:#fff;">1</div>
                        </td>
                        <td style="padding:0 0 12px 10px;font-size:13px;color:#374151;">
                            <strong>Pay via {{ $reservation->payment_method }}</strong> — send ₱{{ number_format($reservation->amount) }} and take a screenshot of your receipt.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:32px;vertical-align:top;padding-top:2px;">
                            <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:900;color:#fff;">2</div>
                        </td>
                        <td style="padding:0 0 12px 10px;font-size:13px;color:#374151;">
                            <strong>Upload the screenshot</strong> using the button above — our admin will be notified immediately.
                        </td>
                    </tr>
                    <tr>
                        <td style="width:32px;vertical-align:top;padding-top:2px;">
                            <div style="width:24px;height:24px;background:#4f46e5;border-radius:50%;text-align:center;line-height:24px;font-size:11px;font-weight:900;color:#fff;">3</div>
                        </td>
                        <td style="padding:0 0 0 10px;font-size:13px;color:#374151;">
                            <strong>Admin reviews your receipt</strong> and marks your booking as confirmed — you'll receive a final confirmation email. ✅
                        </td>
                    </tr>
                </table>
            </div>

            <!-- REMINDER -->
            <div style="background:#fffbeb;border:1.5px solid #fcd34d;border-radius:12px;padding:16px 18px;margin-bottom:24px;">
                <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:8px;">⚠️ Important</div>
                <div style="font-size:13px;color:#78350f;line-height:1.9;">
                    Your slot is <strong>not yet confirmed</strong> until the admin verifies your payment.<br>
                    Please upload your receipt as soon as possible to avoid losing your slot.
                </div>
            </div>

            <!-- VIEW BOOKING CTA -->
            <div style="text-align:center;">
                <a href="{{ url('/booking/' . $reservation->reference_number) }}"
                   style="display:inline-block;background:#4f46e5;color:#fff;font-size:15px;font-weight:700;padding:14px 32px;border-radius:10px;text-decoration:none;">
                    View Booking Status →
                </a>
                <div style="margin-top:14px;font-size:12px;color:#9ca3af;">
                    Questions? Contact us at
                    <a href="mailto:hello@TDA.com" style="color:#4f46e5;text-decoration:none;">hello@TDA.com</a>
                    or call <strong>0966 615 4780</strong>
                </div>
            </div>

        </div>
    </td></tr>

    <!-- FOOTER -->
    <tr><td style="padding:24px 0;text-align:center;">
        <div style="font-size:12px;color:#9ca3af;line-height:1.8;">
            © {{ date('Y') }} TDA Court Reservation · San Fernando<br>
            This email was sent to <strong>{{ $reservation->email }}</strong> because you submitted a booking on our website.
        </div>
    </td></tr>

</table>
</td></tr>
</table>
</body>
</html>