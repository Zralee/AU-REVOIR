<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
    <style type="text/css">
        .title {
            color: white; 
            padding-top: 25px; 
            font-size: 25px;
        }

        label {
            display: inline-block;
            width: 200px;
        }
    </style>
</head>

<body>
    <!-- partial -->
    @include('admin.sidebar')
    @include('admin.navbar')

    <!-- partial -->
    
    <div class="container-fluid page-body-wrapper">
        <div class="container" align="center">

            <h1 class="title">Add Product</h1>

            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ url('uploadproduct') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div style="padding: 15px;">
                    <label>Product Title</label>
                    <input style="color: black;" type="text" name="title" placeholder="Give a product title" required>
                </div>

                <div style="padding: 15px;">
                    <label>Price</label>
                    <input style="color: black;" type="number" name="price" placeholder="Give a price" required>
                </div>

                <div style="padding: 15px;">
                    <label>Description</label>
                    <input style="color: black;" type="text" name="description" placeholder="Give a description" required>
                </div>

                <div style="padding: 15px;">
                    <label>Quantity</label>
                    <input style="color: black;" type="text" name="quantity" placeholder="Product Quantity" required>
                </div>

                <div style="padding: 15px;">
                    <input type="file" name="file">
                </div>

                <div style="padding: 15px;">
                    <input class="btn btn-success" type="submit">
                </div>
            </form>
        </div>
    </div>
    <!-- partial -->

    @include('admin.script')

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
