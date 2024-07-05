<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Gateway</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <script type="text/javascript"
        src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-l7JdIPn6WNbwOhBc"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #001f3f;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .order-details {
            padding: 15px;
        }
        .order {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }
        .order h2 {
            margin: 0;
            font-size: 20px;
        }
        .order p {
            margin: 5px 0;
        }
        .order p span {
            font-weight: 500;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: 700;
            padding: 15px;
            border-top: 1px solid #ddd;
        }
        .pay-button-container {
            text-align: center;
            padding: 15px;
        }
        .pay-button {
            background-color: #001f3f;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .pay-button:hover {
            background-color: #004080;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Details</h1>
        </div>
        <div class="order-details">
            @if(isset($orders) && count($orders) > 0)
                @foreach($orders as $order)
                    <div class="order">
                        <h2>Payment for Order #{{ $order->id }}</h2>
                        <p><span>Product Name:</span> {{ $order->product_name }}</p>
                        <p><span>Quantity:</span> {{ $order->quantity }}</p>
                        <p><span>Price:</span> Rp.{{ $order->price }}</p>
                        <p><span>Total:</span> Rp.{{ $order->price * $order->quantity }}</p>
                    </div>
                @endforeach
                <div class="total">
                    Total Amount: Rp.{{ $orders->sum(function($order) { return $order->price * $order->quantity; }) }}
                </div>
            @else
                <p>No order data found.</p>
            @endif
        </div>
        <div class="pay-button-container">
            <button id="pay-button" class="pay-button">Pay!</button>
        </div>
    </div>
    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            snap.pay('{{ $snapToken }}');
        });
    </script>
</body>
</html>
