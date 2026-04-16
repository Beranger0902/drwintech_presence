<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DrwinTech - Accueil</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 20px 60px;
            background: white;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .logo {
            font-weight: bold;
            font-size: 22px;
            color: #4f6df5;
        }

        .nav a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .hero {
            height: 90vh;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 60px;
        }

        .hero-text {
            max-width: 500px;
        }

        .hero-text h1 {
            font-size: 42px;
            color: #333;
        }

        .hero-text p {
            color: #666;
            margin: 20px 0;
        }

        .btn {
            padding: 12px 25px;
            background: #4f6df5;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }

        .hero img {
            width: 500px;
        }

        .section {
            padding: 60px;
            text-align: center;
        }

        .cards {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 250px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
        }
    </style>
</head>

<body>

<header>
    <div class="logo">DrwinTech</div>
    <div class="nav">
        <a href="/">Accueil</a>
        <a href="{{ route('choix.connexion') }}">Connexion</a>
    </div>
</header>

<div class="hero">
    <div class="hero-text">
        <h1>Gestion intelligente des présences</h1>
        <p>
            DrwinTech est une plateforme moderne permettant de gérer efficacement 
            les présences, les pointages et les activités des employés en temps réel.
        </p>
        <a href="{{ route('choix.connexion') }}" class="btn">Se connecter</a>
    </div>

    <img src="https://cdn-icons-png.flaticon.com/512/1055/1055687.png">
</div>

<div class="section">
    <h2>Pourquoi choisir DrwinTech ?</h2>

    <div class="cards">
        <div class="card">
            <h3>📍 Géolocalisation</h3>
            <p>Pointage sécurisé basé sur la position réelle.</p>
        </div>

        <div class="card">
            <h3>📊 Suivi en temps réel</h3>
            <p>Consultez les présences instantanément.</p>
        </div>

        <div class="card">
            <h3>🔐 Sécurité avancée</h3>
            <p>Protection des accès avec authentification sécurisée.</p>
        </div>
    </div>
</div>

</body>
</html>