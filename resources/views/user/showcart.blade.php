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
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        header {
            background-color: #343a40;
        }

        .navbar-brand h2 {
            color: #ffffff;
        }

        .navbar-nav .nav-link {
            color: #ffffff;
        }

        .nav-item.active .nav-link {
            color: #ff6347;
        }

        .nav-link:hover {
            color: #ff6347;
        }

        .cart-container {
            padding: 100px 15px;
            text-align: center;
        }

        .cart-table {
            width: 100%;
            margin: auto;
            border-collapse: collapse;
        }

        .cart-table th, .cart-table td {
            padding: 15px;
            border: 1px solid #ddd;
        }

        .cart-table th {
            background-color: #343a40;
            color: #ffffff;
        }

        .cart-table td {
            background-color: #ffffff;
            color: #343a40;
        }

        .cart-table td a.btn-danger {
            background-color: #ff6347;
            border-color: #ff6347;
        }

        .cart-table td a.btn-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .confirm-btn {
            margin-top: 20px;
            background-color: #28a745;
            border-color: #28a745;
        }

        .confirm-btn:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .alert-dismissible .close {
            position: relative;
            top: -2px;
            right: -21px;
            color: inherit;
        }
    </style>
</head>

<body>

    <!-- Preloader -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>

    <!-- Header -->
    <header class="">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="{{ url('/') }}"><h2>AU <em>REVOIR</em></h2></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item ">
                <a class="nav-link" href="{{ url('/') }}">Home
                  <span class="sr-only">(current)</span>
                </a>
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

                    <li class="nav-item active">
                      <a class="nav-link" href="{{url('showcart')}}">
                      <i class="fas fa-shopping-cart"></i>  
                      Cart[{{$count}}]</a>
                    </li>
                        
                        <x-app-layout>

                        </x-app-layout>
                   
                    @else
                        <li> <a class="nav-link" href="{{ route('login') }}" >Log in</a></li>

                        @if (Route::has('register'))
                        <li> <a class="nav-link" href="{{ route('register') }}" >Register</a></li>
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
                <form action="{{url('order')}}" method="POST">
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
                        <td>
                            <a class="btn btn-danger" href="{{url('delete',$carts->id)}}">Delete</a>
                        </td>
                    </tr>
                    @endforeach
            </tbody>
        </table>

      
        <button class="btn btn-success confirm-btn">Confirm Order</button>
   
        </form>
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
        cleared[0] = cleared[1] = cleared[2] = 0; // set a cleared flag for each field
        function clearField(t) { // declaring the array outside of the function makes it static and global
            if (!cleared[t.id]) { // function makes it static and global
                cleared[t.id] = 1; // you could use true and false, but that's more typing
                t.value = ''; // with more chance of typos
                t.style.color = '#fff';
            }
        }
    </script>

</body>

</html>
