<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multiplication Table</title>
    <link rel="stylesheet" href="{{ asset('bootstrap-5.3.3-dist/css/bootstrap.min.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
            color: #2c3e50;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
            margin-bottom: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: white;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            text-align: center;
        }

        .table {
            margin-bottom: 0;
            font-size: 1rem;
        }

        .table-bordered td {
            border: 1px solid #e9ecef;
            padding: 0.75rem;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #fdf6ec;
        }

        .table td {
            text-align: center;
            font-weight: 500;
            color: #4a5568;
        }

        h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 2rem;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Multiplication Table (1 to 20)</h1>
        <div class="row">
            @for ($tableNumber = 1; $tableNumber <= 20; $tableNumber++)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            Table of {{ $tableNumber }}
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    @for ($i = 1; $i <= 20; $i++)
                                        <tr>
                                            <td>{{ $i }} * {{ $tableNumber }} = {{ $i * $tableNumber }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <script src="{{ asset('bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
