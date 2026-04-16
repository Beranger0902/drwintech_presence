<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Drwintech</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
        }

        /* 🎬 PARTIE GAUCHE (IMAGE ANIMÉE) */
        .left {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .left::before {
            content: "";
            position: absolute;
            width: 110%;
            height: 110%;
            background: url('https://images.unsplash.com/photo-1551434678-e076c223a692') no-repeat center/cover;
            animation: zoomBg 20s infinite alternate ease-in-out;
        }

        @keyframes zoomBg {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(40, 60, 120, 0.6);
        }

        .left-content {
            position: absolute;
            color: white;
            z-index: 2;
            padding: 60px;
            max-width: 500px;
        }

        .left-content h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .left-content p {
            line-height: 1.6;
            font-size: 18px;
        }

        /* 🔐 PARTIE DROITE */
        .right {
            flex: 1;
            background: #f7f9fc;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 360px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 50px;
        }

        .logo h2 {
            margin-top: 10px;
            color: #2c3e50;
        }

        .login-box h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        .login-box p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            outline: none;
            transition: 0.3s;
        }

        .input-group input:focus {
            border-color: #4f6df5;
            box-shadow: 0 0 5px rgba(79,109,245,0.3);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(to right, #4f6df5, #6a82fb);
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .forgot {
            text-align: right;
            font-size: 13px;
            margin-bottom: 15px;
        }

        .forgot a {
            text-decoration: none;
            color: #4f6df5;
        }

    </style>
</head>

<body>

<!-- 🧾 GAUCHE -->
<div class="left">
    <div class="overlay"></div>

    <div class="left-content">
        <h1>Système professionnel de gestion du personnel</h1>

        <p>
            Accédez à votre espace pour gérer vos pointages,
            suivre votre temps de travail et consulter vos
            informations en toute sécurité.

            <br><br>

            Cette plateforme vous offre une expérience simple,
            rapide et efficace pour votre quotidien professionnel.
        </p>
    </div>
</div>

<!-- 🔐 DROITE -->
<div class="right">
    <div class="login-box">

        <!-- LOGO -->
        <div class="logo">
            <img src="/Images/drwintech-logo.jpeg" alt="drwintech">
            <h2>DRWINTECH</h2>
        </div>

        <h3>Connexion</h3>
        <p>Heureux de vous revoir !</p>
        <p>Veuillez  acceder à votre espace</p>

        <!-- FORMULAIRE -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="input-group">
                <input type="email" name="email" placeholder="Adresse e-mail" required>
            </div>

            <div class="input-group">
                <input type="password" name="password" placeholder="Mot de passe" required>
            </div>

            <div class="forgot">
                <a href="#">Mot de passe oublié ?</a>
            </div>

            <button type="submit" class="btn">
                Se connecter
            </button>

        </form>

    </div>
</div>

</body>
</html>