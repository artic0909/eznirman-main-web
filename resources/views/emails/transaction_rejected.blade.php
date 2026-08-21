<!DOCTYPE html>
<html>
<head>
    <title>{{ $transactionType }} Rejected</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 5px;">
        <h2 style="color: #d9534f;">{{ $transactionType }} Rejected</h2>
        
        <p>Hello <strong>{{ $userName }}</strong>,</p>
        
        <p>Your recent {{ strtolower($transactionType) }} request has been rejected by the coordinator.</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #d9534f; margin: 20px 0;">
            <h4 style="margin-top: 0;">Transaction Details:</h4>
            <p style="margin-bottom: 0;">{{ $transactionDetails }}</p>
        </div>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #f0ad4e; margin: 20px 0;">
            <h4 style="margin-top: 0;">Reason for Rejection:</h4>
            <p style="margin-bottom: 0;">{{ $rejectionReason }}</p>
        </div>
        
        <p>If you have any questions, please contact your coordinator.</p>
        
        <br>
        <p>Best regards,<br><strong>EZNIRMAN Team</strong></p>
    </div>
</body>
</html>
