<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DrwinTech - Accueil</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 60px;
            background: white;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 45px;
            margin-right: 10px;
        }

        .logo span {
            font-weight: bold;
            font-size: 20px;
            color: #2c3e50;
        }

        .nav a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }

        .btn {
            background: #4f6df5;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
        }

        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 60px;
            background: linear-gradient(to right, #eef2ff, #ffffff);
        }

        .hero-text {
            max-width: 600px;
        }

        .hero-text h1 {
            font-size: 36px;
            color: #2c3e50;
        }

        .hero-text p {
            color: #555;
            line-height: 1.6;
        }

        .hero img {
            width: 450px;
            border-radius: 10px;
        }

        .section {
            padding: 60px;
        }

        .section h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #2c3e50;
        }

        .grid {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            width: 280px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.05);
            text-align: center;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card svg {
            width: 40px;
            margin-bottom: 15px;
        }

        .image-section {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .image-section img {
            width: 300px;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<header>
    <div class="logo">
        <img src="/Images/drwintech-logo.jpeg" alt="drwintech">
        <span>DrwinTech</span>
    </div>

    <div class="nav">
        <a href="/">Accueil</a>
        <a href="{{ route('choix.connexion') }}" class="btn">Connexion</a>
    </div>
</header>

<!-- HERO -->
<div class="hero">
    <div class="hero-text">
        <h1>Transformation digitale et performance organisationnelle</h1>

        <p>
            La mission de Drwintech Inc Bénin est d’accompagner les organisations 
            dans la conception et la mise en œuvre de solutions numériques performantes, 
            afin d’optimiser leurs modèles opérationnels et renforcer l’efficacité 
            de leurs infrastructures.
        </p>

        <br>

        <p>
            Vision : devenir un acteur de référence en Afrique dans les solutions digitales, 
            en améliorant la performance, l’agilité et les processus des organisations.
        </p>
    </div>

    <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984">
</div>

<!-- OBJECTIFS -->
<div class="section">
    <h2>Objectifs stratégiques</h2>

    <div class="image-section">
        <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216">
        <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998">
        <img src="https://images.unsplash.com/photo-1551434678-e076c223a692">
    </div>

    <div class="grid" style="margin-top:30px;">
        <div class="card">Améliorer la performance des organisations</div>
        <div class="card">Renforcer l’agilité des systèmes</div>
        <div class="card">Optimiser les processus opérationnels</div>
        <div class="card">Anticiper les évolutions technologiques</div>
    </div>
</div>

<!-- APPLICATION -->
<div class="section" style="background:#eef2ff;">
    <h2>Notre solution</h2>

    <div class="grid">
        <div class="card">
            <h3>Gestion des présences</h3>
            <p>Suivi en temps réel des employés avec pointage intelligent.</p>
        </div>

        <div class="card">
            <h3>Géolocalisation</h3>
            <p>Validation des présences basée sur la position réelle.</p>
        </div>

        <div class="card">
            <h3>Rapports avancés</h3>
            <p>Analyse des performances et statistiques détaillées.</p>
        </div>
    </div>
</div>

<!-- AVANTAGES -->
<div class="section">
    <h2>Avantages</h2>

    <div class="grid">
        <div class="card">
            <svg fill="#4f6df5" viewBox="0 0 24 24">
                <path d="M12 2L2 7v6c0 5 3.8 9.7 10 11 6.2-1.3 10-6 10-11V7l-10-5z"/>
            </svg>
            <h3>Sécurité</h3>
            <p>Protection avancée des accès et données.</p>
        </div>

        <div class="card">
            <svg fill="#4f6df5" viewBox="0 0 24 24">
                <path d="M3 13h2v-2H3v2zm4 0h14v-2H7v2z"/>
            </svg>
            <h3>Simplicité</h3>
            <p>Interface intuitive et facile à utiliser.</p>
        </div>

        <div class="card">
            <svg fill="#4f6df5" viewBox="0 0 24 24">
                <path d="M12 4v16m8-8H4"/>
            </svg>
            <h3>Performance</h3>
            <p>Optimisation du travail et gain de temps.</p>
        </div>
    </div>
</div>

</body>
</html>