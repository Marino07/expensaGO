<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Limit Exceeded</title>
    <style>
        body {
            background: #f0f0f0;
            padding: 16px;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            background: linear-gradient(to right, #e0f7fa, #80deea);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .content {
            padding: 24px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
        }

        .message {
            margin-bottom: 24px;
        }

        .message p {
            color: #555555;
            margin-bottom: 16px;
        }

        .alert {
            background: #ffe5e5;
            border-left: 4px solid #ff5c5c;
            color: #ff5c5c;
            padding: 16px;
            border-radius: 4px;
        }

        .alert p {
            font-weight: bold;
        }

        .footer {
            border-top: 1px solid #eeeeee;
            padding-top: 16px;
            color: #777777;
            font-size: 14px;
        }

        .disclaimer {
            background: #c7c5c5;
            padding: 16px;
            color: black;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="content">
            <div class="header">
                <h1>Hello, {{$userName}}</h1>
            </div>
            <div class="message">
                <p>You have exceeded your daily spending limit.</p>
                <div class="alert">
                    <p>Total spent today: {{$totalSpentToday}}</p>
                </div>
            </div>
            <div class="footer">
                <p>Keep it Up!</p>
                <p>ExpensaGO wishes you all the best!</p>
            </div>
        </div>
        <div class="disclaimer">
            <p>This message was automatically generated. Please do not reply to it.</p>
        </div>
    </div>
</body>

</html>
