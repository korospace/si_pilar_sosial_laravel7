<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f8f9fa;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-header img {
            max-width: 100px;
        }
        .card-header h1 {
            margin: 0;
            font-weight: bold;
            font-size: 40px;
        }
        .card1 {
            margin-top: 30px;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .card2 {
            margin-top: 30px;
            padding-left: 20px;
            padding-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        .text-muted {
            color: #6c757d;
            font-size: 20px;
            font-weight: 500;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
            font-size: 40px;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: max-content
        }
    </style>
</head>
<body>
    <div class="container p-5">
        <div class="row">
            <div class="col-12">
                <div class="card1 card-secondary card-outline">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h1 class="text-muted text-bold">RESET PASSWORD</h1>
                    </div>
                </div>
                <div class="card2 mt-4 px-4 pt-4 pb-5">
                    <h4 class="text-muted mb-5">Hai <b>{{ $user->name }}</b>, berikut ini adalah password baru anda. Segera lakukan perubahan password setelah anda berhasil login.</h4>
                    <div class="mt-5 d-flex justify-content-center">
                        <div class="btn-secondary" style="font-size: 40px;">
                            {{ $newPassword }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
