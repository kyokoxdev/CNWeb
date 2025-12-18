<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .sales-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: left;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            color: #27ae60;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Purchase History</h1>
        
        <div class="sales-table">
            @if($sales->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>Medicine Name</th>
                            <th>Brand</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total</th>
                            <th>Sale Date</th>
                            <th>Customer Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sales as $sale)
                            <tr>
                                <td>#{{ $sale->sale_id }}</td>
                                <td>{{ $sale->medicine->name }}</td>
                                <td>{{ $sale->medicine->brand }}</td>
                                <td>{{ $sale->quantity }}</td>
                                <td>${{ number_format($sale->medicine->price, 2) }}</td>
                                <td class="total">${{ number_format($sale->quantity * $sale->medicine->price, 2) }}</td>
                                <td>{{ date('M d, Y H:i', strtotime($sale->sale_date)) }}</td>
                                <td>{{ $sale->customer_phone }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="no-data">
                    <p>No purchase history available.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>