@php
$totalamount = 0;
$amount = 0;
$productTersisa = 0;
@endphp

@foreach ($products as $product)
@php
$productTersisa += $product->quantity_S;
$productTersisa += $product->quantity_M;
$productTersisa += $product->quantity_L;
$productTersisa += $product->quantity_XL;
@endphp
@endforeach

@foreach($orders as $order)
@php  
$totalamount += $order->total_amount;
$amount += $order->quantity;
@endphp
@endforeach


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

        .dashboard-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .stat-box {
            background-color: #343a40;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 30%;
        }

        .chart-container {
            width: 70%;
            margin: 20px auto;
        }
    </style>
</head>

<body>
    @include('admin.sidebar')
    @include('admin.navbar')

    <div class="container-fluid page-body-wrapper">
        <div class="container" align="center">

            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session()->get('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="dashboard-stats">
                <div class="stat-box">
                    <h4>Total Profit</h4>
                    <p>{{ $totalamount }}</p>
                </div>
                <div class="stat-box">
                    <h4>Total Products Sold</h4>
                    <p>{{ $amount }}</p>
                </div>
                <div class="stat-box">
                    <h4>Total Products Remaining</h4>
                    <p>{{ $productTersisa }}</p>
                </div>
            </div>

            <div class="chart-container">
                <h3>Profit Chart</h3>
                <canvas id="profitChart"></canvas>
            </div>
            <div class="chart-container">
                <h3>Product Statistics</h3>
                <canvas id="productChart"></canvas>
            </div>

        </div>
    </div>

    @include('admin.script')

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxProfit = document.getElementById('profitChart').getContext('2d');
        const profitChart = new Chart(ctxProfit, {
            type: 'line',
            data: {
                labels: ['Total Profit'],
                datasets: [{
                    label: 'Total Profit',
                    data: [{{ $totalamount }}],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true,
                    tension: 0.1
                }],
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctxProduct = document.getElementById('productChart').getContext('2d');
        const productChart = new Chart(ctxProduct, {
            type: 'doughnut',
            data: {
                labels: ['Total Products Sold', 'Total Products Remaining'],
                datasets: [{
                    label: 'Product Statistics',
                    data: [{{ $amount }}, {{ $productTersisa }}],
                    backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
                    borderWidth: 0.5
                }]
            }
        });
    </script>
</body>
</html>
