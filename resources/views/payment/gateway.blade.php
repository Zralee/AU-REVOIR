<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AU REVOIR</title>
    
    <!-- logo icon au-revoir -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/images/logo-au-revoir.png') }}">

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
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background-color: #001f3f;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .order-details {
            padding: 20px;
        }
        .order {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .order p {
            margin: 5px 0;
            font-size: 16px;
        }
        .order p span {
            font-weight: 500;
        }
        .total {
            text-align: right;
            font-size: 18px;
            font-weight: 700;
            padding: 20px;
            border-top: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .total.small-font {
            font-size: 14px;
            font-weight: 500;
        }
        .pay-button-container {
            text-align: center;
            padding: 20px;
        }
        .pay-button {
            background-color: #001f3f;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
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
            Order Details
        </div>
        <div class="order-details">
            @if(isset($orders) && count($orders) > 0)
                @foreach($orders as $order)
                    <div class="order">
                        <p><span>Product Name:</span> {{ $order->product_name }}</p>
                        <p><span>Size:</span> {{ $order->size }}</p>
                        <p><span>Quantity:</span> {{ $order->quantity }}</p>
                        <p><span>Price:</span> Rp.{{ number_format($order->price, 0, ',', '.') }}</p>
                    </div>
                @endforeach
                <div class="total small-font">
                    Shipping Costs : {{ $orders[0]->courier }} - Fee: Rp.{{ number_format($courierFee, 0, ',', '.') }}
                </div>
                <div class="total">
                    Total Amount: Rp.{{ number_format($orders->sum(function($order) { return $order->price * $order->quantity; }) + $courierFee, 0, ',', '.') }}
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
var orderId = '{{ $orderId }}'; // Order ID
payButton.addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result) {
            fetch('/update-payment-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    order_id: orderId,
                    payment_status: 2
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Redirect or show success message
                    window.location.href = '/payment-success';
                } else {
                    console.log('Failed to update payment status');
                }
            })
            .catch(error => console.error('Error:', error));
        },
        onPending: function(result) {
            console.log(result);
        },
        onError: function(result) {
            console.error(result);
        }
    });
});
    </script> 






</body>
</html>
