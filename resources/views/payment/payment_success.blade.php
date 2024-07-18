<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            max-width: 600px;
            background: #fff;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            padding: 0;
            border: 1px solid #ddd;
            overflow: hidden;
        }
        .header {
            background-color: #001f3f;
            color: white;
            padding: 30px 20px; /* Mengurangi padding untuk mengurangi ruang kosong */
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
            border-bottom: 1px solid #ddd;
        }
        .content h2 {
            color: #2ecc71;
            margin-top: 0; /* Menghilangkan margin-top untuk merapikan teks */
            font-size: 22px;
        }
        .content p {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .footer {
            padding: 20px;
        }
        .button {
            background-color: #001f3f;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .button:hover {
            background-color: #004080;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Payment Successful</h1>
        </div>
        <div class="content">
            <h2>Thank you for your purchase!</h2>
            <p>Your payment has been processed successfully.</p>
        </div>
        <div class="footer">
            <a href="/" class="button">Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
