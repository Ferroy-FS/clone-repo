<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: Arial, sans-serif; background: #F4E8E3; margin: 0; padding: 20px; }
  .container { max-width: 500px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
  .header { background: #000000; padding: 30px; text-align: center; }
  .header h1 { color: #DB854F; margin: 0; font-size: 28px; }
  .header p { color: rgba(255,255,255,0.7); margin: 5px 0 0; font-size: 14px; }
  .body { padding: 32px; }
  .greeting { font-size: 16px; color: #334155; margin-bottom: 16px; }
  .message { font-size: 14px; color: #64748b; margin-bottom: 24px; line-height: 1.6; }
  .otp-box { background: #F4E8E3; border: 2px dashed #DB854F; border-radius: 12px; padding: 24px; text-align: center; margin: 24px 0; }
  .otp-code { font-size: 42px; font-weight: bold; color: #DB854F; letter-spacing: 12px; }
  .otp-label { font-size: 13px; color: #64748b; margin-top: 8px; }
  .warning { background: #fff8f0; border-left: 4px solid #DB854F; padding: 12px 16px; border-radius: 0 8px 8px 0; font-size: 13px; color: #64748b; margin-top: 20px; }
  .footer { background: #000; padding: 20px; text-align: center; }
  .footer p { color: rgba(255,255,255,0.4); font-size: 12px; margin: 0; }
  .footer span { color: #DB854F; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h1>💪 Fitnezz</h1>
    <p>Gym Management System — Surabaya</p>
  </div>
  <div class="body">
    <p class="greeting">Hello, <strong>{{ $userName }}</strong>!</p>
    <p class="message">
      @if($type === 'login')
        You are trying to log in to your Fitnezz account. Use the OTP code below to continue:
      @else
        You requested a password reset for your Fitnezz account. Use the OTP code below:
      @endif
    </p>
    <div class="otp-box">
      <div class="otp-code">{{ $otp }}</div>
      <div class="otp-label">Code valid for <strong>5 minutes</strong></div>
    </div>
    <div class="warning">
      Do not share this code with anyone. Fitnezz team will never ask for your OTP code.
    </div>
  </div>
  <div class="footer">
    <p>2026 <span>Fitnezz Gym</span> — Surabaya, Indonesia</p>
  </div>
</div>
</body>
</html>
