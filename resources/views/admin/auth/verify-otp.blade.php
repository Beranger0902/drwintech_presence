<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vérification OTP</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome (icônes réels) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f6df5, #6c63ff);
            overflow: hidden;
        }

        /* Animation fond */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 10%, transparent 10%);
            background-size: 50px 50px;
            animation: moveBg 10s linear infinite;
        }

        @keyframes moveBg {
            0% { transform: translate(0,0); }
            100% { transform: translate(-50px,-50px); }
        }

        .container {
            width: 420px;
            background: white;
            border-radius: 16px;
            padding: 40px 30px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            text-align: center;
            animation: fadeIn 0.6s ease;
            position: relative;
            z-index: 2;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 50px;
            color: #4f6df5;
            margin-bottom: 15px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        p {
            font-size: 14px;
            color: #666;
            margin-bottom: 25px;
        }

        .otp-input {
            width: 100%;
            padding: 14px;
            font-size: 18px;
            text-align: center;
            border-radius: 10px;
            border: 1px solid #ddd;
            outline: none;
            margin-bottom: 20px;
            transition: 0.3s;
        }

        .otp-input:focus {
            border-color: #4f6df5;
            box-shadow: 0 0 8px rgba(79,109,245,0.4);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #4f6df5, #6c63ff);
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .resend {
            margin-top: 15px;
            font-size: 14px;
        }

        .resend button {
            border: none;
            background: none;
            color: #4f6df5;
            cursor: pointer;
            font-weight: 600;
        }

        .error {
            background: #ffe0e0;
            color: #c0392b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .success {
            background: #e0ffe5;
            color: #27ae60;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="icon">
            <i class="fas fa-shield-alt"></i>
        </div>

        <h2>Vérification de sécurité</h2>
        <p>Entrez le code OTP envoyé à votre email</p>

        <!-- Messages -->
        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <!-- FORM OTP -->
        <form method="POST" action="{{ route('admin.verify') }}">
            @csrf
            <input type="text" name="otp" class="otp-input" placeholder="000000" required>
            <button type="submit" class="btn">
                <i class="fas fa-check-circle"></i> Vérifier
            </button>
        </form>

        <!-- RESEND -->
        <div class="resend">
            <form method="POST" action="{{ route('admin.resend.otp') }}">
                @csrf
                <button type="submit">
                    <i class="fas fa-redo"></i> Renvoyer le code
                </button>
            </form>
        </div>

    </div>

</body>
</html>