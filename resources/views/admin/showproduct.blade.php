<!DOCTYPE html>
<html lang="en">
  <head>

      @include('admin.css')

  </head>

  <body>
      
      <!-- partial -->

      @include('admin.sidebar')

      @include('admin.navbar')

      <!-- partial -->

      <div style="padding-bottom:30px;" class="container-fluid page-body-wrapper">
        <div class="container" align="center">

        @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

        <table>

            <tr style="background-color: grey;">
                <td style="padding:20px;">Title</td>
                <td style="padding:20px;">Description</td>
                <td style="padding:20px;">Quantity</td>
                <td style="padding:20px;">Price</td>
                <td style="padding:20px;">Image</td>
                <td style="padding:20px;">Update</td>
                <td style="padding:20px;">Delete</td>
            </tr>

            @foreach($data as $product)

            <tr align="center" style="background-color: black; ">
                <td>{{$product->title}}</td>
                <td>{{$product->description}}</td>
                <td>{{$product->quantity}}</td>
                <td>{{$product->price}}</td>
                <td>
                    <img height="100 px" width="100 px" src="/productimage/{{$product->image}}">
                </td>

                <td>
                    <a class="btn-btn-primary" href="{{url('updateview',$product->id)}}">Update</a>
                </td>

                <td>
                    <a class="btn-btn-danger" onclick="return confirm('Are you sure?')" href="{{url('deleteproduct',$product->id)}}">Delete</a>
                </td>

            </tr>

            @endforeach

        </table>

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
