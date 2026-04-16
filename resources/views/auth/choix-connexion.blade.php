<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choisir un espace - Drwintech</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
        }

        /* 🎬 BACKGROUND IMAGE ANIMÉ */
        body::before {
            content: "";
            position: absolute;
            width: 110%;
            height: 110%;
            background: url('https://images.unsplash.com/photo-1555066931-4365d14bab8c') no-repeat center center/cover;
            animation: zoomBg 20s infinite alternate ease-in-out;
            z-index: -1;
        }

        @keyframes zoomBg {
            from {
                transform: scale(1);
            }
            to {
                transform: scale(1.1);
            }
        }

        /* 🌫 overlay sombre */
        body::after {
            content: "";
            position: absolute;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: -1;
        }

        .container {
            display: flex;
            width: 100%;
            height: 100%;
            padding: 60px;
        }

        /* 🧾 PARTIE GAUCHE */
        .left {
            flex: 1;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeLeft 1s ease;
        }

        .left h1 {
            font-size: 42px;
            margin-bottom: 20px;
        }

        .left p {
            font-size: 18px;
            line-height: 1.6;
            max-width: 500px;
        }

        /* 🔐 PARTIE DROITE */
        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: fadeRight 1s ease;
        }

        .box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 350px;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        .box h2 {
            margin-bottom: 25px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-admin {
            background: #111;
            color: white;
        }

        .btn-admin:hover {
            background: #333;
        }

        .btn-user {
            background: #007bff;
            color: white;
        }

        .btn-user:hover {
            background: #0056b3;
        }

        /* ✨ ANIMATIONS */
        @keyframes fadeLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

    </style>
</head>
<body>

<div class="container">

    <!-- 🧾 TEXTE GAUCHE -->
    <div class="left">
        <h1>Bienvenue chez Drwintech</h1>

        <p>
            Cette plateforme vous permet d'accéder à votre espace sécurisé
            afin de gérer efficacement les présences, les temps de travail
            et les rapports.

            <br><br>

            Selon votre rôle, choisissez l’espace qui vous correspond :
            administrateur pour la gestion globale ou employé / agent pour
            le suivi quotidien.
        </p>
    </div>

    <!-- 🔐 CHOIX DROITE -->
    <div class="right">
        <div class="box">
            <h2>Choisir un espace</h2>

            <a href="{{ route('admin.login') }}" class="btn btn-admin">
                Connexion Administrateur
            </a>

            <a href="{{ route('login') }}" class="btn btn-user">
                Connexion Employé / Agent
            </a>
        </div>
    </div>

</div>

</body>
</html>