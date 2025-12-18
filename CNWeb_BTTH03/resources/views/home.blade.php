<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicines</title>
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
        .medicines-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .medicine-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .medicine-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .medicine-info {
            margin: 10px 0;
        }
        .medicine-info strong {
            color: #555;
        }
        .price {
            font-size: 1.2em;
            color: #27ae60;
            font-weight: bold;
        }
        .stock {
            color: #e74c3c;
        }
        .stock.in-stock {
            color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Available Medicines</h1>
        
        <div class="medicines-grid">
            @foreach($medicines as $medicine)
                <div class="medicine-card">
                    <h3>{{ $medicine->name }}</h3>
                    <div class="medicine-info">
                        <strong>Brand:</strong> {{ $medicine->brand }}
                    </div>
                    <div class="medicine-info">
                        <strong>Dosage:</strong> {{ $medicine->dosage }}
                    </div>
                    <div class="medicine-info">
                        <strong>Form:</strong> {{ $medicine->form }}
                    </div>
                    <div class="medicine-info">
                        <strong>Price:</strong> <span class="price">${{ number_format($medicine->price, 2) }}</span>
                    </div>
                    <div class="medicine-info">
                        <strong>Stock:</strong> 
                        <span class="stock {{ $medicine->stock > 0 ? 'in-stock' : '' }}">
                            {{ $medicine->stock }} units
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>