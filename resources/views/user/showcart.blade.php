<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <title>AU REVOIR</title>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/templatemo-sixteen.css">
    <link rel="stylesheet" href="assets/css/owl.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        header { background-color: #343a40; }
        .navbar-brand h2 { color: #ffffff; }
        .navbar-nav .nav-link { color: #ffffff; }
        .nav-item.active .nav-link { color: #ff6347; }
        .nav-link:hover { color: #ff6347; }
        .cart-container { padding: 100px 15px; text-align: center; }
        .cart-table { width: 100%; margin: auto; border-collapse: collapse; }
        .cart-table th, .cart-table td { padding: 15px; border: 1px solid #ddd; }
        .cart-table th { background-color: #343a40; color: #ffffff; }
        .cart-table td { background-color: #ffffff; color: #343a40; }
        .cart-table td a.btn-danger { background-color: #ff6347; border-color: #ff6347; }
        .cart-table td a.btn-danger:hover { background-color: #dc3545; border-color: #dc3545; }
        .pay-now-btn, .confirm-btn { margin-top: 20px; }
        .pay-now-btn { background-color: #007bff; border-color: #007bff; }
        .pay-now-btn:hover { background-color: #0056b3; border-color: #004085; }
        .confirm-btn { background-color: #28a745; border-color: #28a745; display: none; }
        .confirm-btn:hover { background-color: #218838; border-color: #1e7e34; }
        .recipient-form { margin-top: 20px; display: none; text-align: left; padding: 20px; background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .recipient-form h4 { margin-bottom: 15px; color: #343a40; }
        .recipient-form .form-group { margin-bottom: 15px; }
        .recipient-form label { font-weight: bold; color: #343a40; }
        .recipient-form input, .recipient-form textarea, .recipient-form select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .recipient-form select { font-size: 18px; height: 50px; }
    </style>
</head>
<body data-total-amount="{{ $cart->sum('price') }}">
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
    <header class="">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <h2>AU <em>REVOIR</em></h2>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/aboutus') }}">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/contactus') }}">Contact Us</a>
                        </li>
                        <li class="nav-item active">
                            @if (Route::has('login'))
                            @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{url('showcart')}}">
                                <i class="fas fa-shopping-cart"></i>
                                Cart[{{$count}}]
                            </a>
                        </li>
                        <li class="nav-item">
                    <a class="nav-link" href="{{ url('showorder') }}">
                      <i class="fas fa-box"></i> Order[{{ $orderCount }}]
                    </a>
                  </li>
                        <x-app-layout></x-app-layout>
                        @else
                        <li>
                            <a class="nav-link" href="{{ route('login') }}">Log in</a>
                        </li>
                        @if (Route::has('register'))
                        <li>
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                        @endif
                        @endauth
                        @endif
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session()->get('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
    </header>

    @if(count($cart) > 0)
    <div class="cart-container" align="center">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <form action="{{url('order')}}" method="POST" id="orderForm">
                    @csrf
                    @foreach($cart as $carts)
                    <tr>
                        <td>
                            <input type="text" name="productname[]" value="{{$carts->product_title}}" hidden="">
                            {{$carts->product_title}}
                        </td>
                        <td>
                            <input type="text" name="quantity[]" value="{{$carts->quantity}}" hidden="">
                            {{$carts->quantity}}
                        </td>
                        <td>
                            <input type="text" name="price[]" value="{{$carts->price}}" hidden="">
                            {{$carts->price}}
                        </td>
                        <input type="hidden" name="total_amount" id="hiddenTotalAmount" value="{{ $cart->sum('price') }}">
                        <td>
                            <a class="btn btn-danger" href="{{url('delete',$carts->id)}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>

        <button type="button" class="btn btn-primary pay-now-btn" id="payNowBtn">Pay Now</button>

        <div class="recipient-form" id="recipientForm">
            <h4>Recipient Details</h4>
            <div class="form-group">
                <label for="recipientName">Recipient Name</label>
                <input type="text" class="form-control" id="recipientName" name="recipient_name" required>
            </div>
            <div class="form-group">
                <label for="recipientEmail">Recipient Email</label>
                <input type="email" class="form-control" id="recipientEmail" name="recipient_email" required>
            </div>
            <div class="form-group">
                <label for="recipientAddress">Recipient Address</label>
                <textarea class="form-control" id="recipientAddress" name="recipient_address" required></textarea>
            </div>
            <div class="form-group">
                <label for="courier">Delivery Services</label>
                <select class="form-control" id="courier" name="courier" required>
                    <option value="JNE" data-fee="25000">JNE</option>
                    <option value="JNT" data-fee="20000">JNT</option>
                </select>
            </div>
            <h5>Total Amount: <span id="total-amount"></span></h5>
            <button class="btn btn-success confirm-btn" type="submit" id="confirmOrderBtn">Confirm Order</button>
        </div>

        </form>
    </div>
    @else
    <div class="cart-container text-center">
        <h1 class="display-4">Buat Order Pertama Anda</h1>
        <p class="lead">Mulai berbelanja sekarang untuk menemukan produk terbaik kami!</p>
        <a href="{{ url('/') }}" class="btn btn-primary btn-lg mt-3">Lihat Produk</a>
    </div>
    @endif

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Additional Scripts -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/owl.js"></script>
    <script src="assets/js/slick.js"></script>
    <script src="assets/js/isotope.js"></script>
    <script src="assets/js/accordions.js"></script>

    <script>
        document.getElementById('payNowBtn').addEventListener('click', function () {
            document.getElementById('recipientForm').style.display = 'block';
            document.getElementById('confirmOrderBtn').style.display = 'inline-block';
            this.style.display = 'none';
        });

        var courierSelect = document.getElementById('courier');
        var totalAmountElement = document.getElementById('total-amount');
        var ordersTotalAmount = parseFloat(document.body.getAttribute('data-total-amount'));

        function updateTotalAmount() {
            var courierFee = parseInt(courierSelect.options[courierSelect.selectedIndex].getAttribute('data-fee'));
            var newTotalAmount = ordersTotalAmount + courierFee;
            totalAmountElement.textContent = newTotalAmount;
            return newTotalAmount;
        }

        courierSelect.addEventListener('change', updateTotalAmount);

        updateTotalAmount();
    </script>

</body>
</html>
