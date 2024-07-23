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
        .order-container { padding: 100px 15px; text-align: center; }
        .order-table { width: 100%; margin: auto; border-collapse: collapse; }
        .order-table th, .order-table td { padding: 15px; border: 1px solid #ddd; }
        .order-table th { background-color: #343a40; color: #ffffff; }
        .order-table td { background-color: #ffffff; color: #343a40; }
        .status-message { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
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
                        <li class="nav-item">
                            @if (Route::has('login'))
                            @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('showcart') }}">
                                <i class="fas fa-shopping-cart"></i>
                                Cart[{{ $count }}]
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('showorder') }}">
                                <i class="fas fa-box"></i>
                                Order[{{ $orderCount }}]
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

    <div class="order-container">
        @if(count($orders) > 0)
        <table class="order-table">
            <thead>
                <tr>
                    <th>Recipient Name</th>
                    <th>Recipient Address</th>
                    <th>Recipient Email</th>
                    <th>Product Name</th>
                    <th>Size</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Shipping Agency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td>{{ $order->recipient_name }}</td>
                    <td>{{ $order->recipient_address }}</td>
                    <td>{{ $order->recipient_email }}</td>
                    <td>{{ $order->product_name }}</td>
                    <td>{{ $order->size }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->price }}</td>
                    <td>{{ $order->courier }}</td>
                    <td>
                        @if($order->payment_status == 2)
                        <span class="status-message">Your order is being delivered</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <h2>Orderan anda masih kosong</h2>
        @endif
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/owl.js"></script>
    <script src="assets/js/slick.js"></script>
    <script src="assets/js/isotope.js"></script>
    <script src="assets/js/accordions.js"></script>
</body>
</html>
