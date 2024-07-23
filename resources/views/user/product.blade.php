<style>
  .product-title {
    font-size: 1.25rem;
    font-weight: bold;
    margin: 10px 0;
}

.product-price {
    font-size: 1.15rem;
    color: #28a745;
    margin-bottom: 10px;
}

.product-image {
    max-height: 200px;
    object-fit: cover;
    width: 100%;
}

.product-item {
    border: 1px solid #ddd;
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.product-item:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.down-content {
    padding: 10px 0;
}

.product-image {
    width: 400px;
    height: 400px;
    object-fit: cover;
}

.down-content p {
    font-size: 0.9rem;
    color: #555;
}

.quantity-input {
    width: 100px;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}

.btn-primary:hover {
    background-color: #0056b3;
    border-color: #004085;
}

</style>

<div class="latest-products">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h2>Search Product</h2>
                    <a href="{{ url('/') }}">View All Products <i class="fa fa-angle-right"></i></a>

                    <form action="{{ url('/search') }}" class="form-inline mt-3 justify-content-center">
                        @csrf
                        <input class="form-control mr-2" type="text" name="search" placeholder="Search" value="{{ old('search', $search ?? '') }}">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
            </div>
        </div>



        <div class="row mt-4">
    @if(isset($data) && $data->count() > 0)
        @foreach($data as $product)
            <div class="col-md-3 mb-4 d-flex align-items-stretch flex-row">
                <div class="product-item d-flex flex-column">
                    <a href="#"><img class="img-fluid product-image" width="400" height="400" src="/productimage/{{ $product->image }}" alt=""></a>
                    <div class="down-content text-center flex-grow-1">
                        <a href="#"><h4 class="product-title">{{ $product->title }}</h4></a>
                        <p></p>
                        
                        <h6></h6>
                        <p class="product-price">Rp.{{ number_format($product->price, 0, ',', '.') }}</p>
                        
                        <form action="{{ url('addcart', $product->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="size">Select Size:</label>
                                <select name="size" id="size" class="form-control">
                                    <option value="S">S ({{ $product->quantity_S }} available)</option>
                                    <option value="M">M ({{ $product->quantity_M }} available)</option>
                                    <option value="L">L ({{ $product->quantity_L }} available)</option>
                                    <option value="XL">XL ({{ $product->quantity_XL }} available)</option>
                                </select>
                            </div>
                            <input type="number" value="1" min="1" class="form-control mx-auto quantity-input" name="quantity">
                            <br>
                            <input class="btn btn-primary" type="submit" value="Add To Cart">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="col-md-12 text-center">
            <h1>Product yang anda cari tidak ditemukan!</h1>
        </div>
    @endif
</div>






        @if(method_exists($data, 'links'))
            <div class="d-flex justify-content-center">
                {!! $data->links() !!}
            </div>
        @endif
    </div>
</div>
