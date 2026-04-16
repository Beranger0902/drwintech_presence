<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choix Connexion</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #4f6df5;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .btn {
            display: block;
            margin: 15px 0;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            color: white;
        }

        .admin {
            background: #e74c3c;
        }

        .user {
            background: #2ecc71;
        }
    </style>
</head>

<body>

<div class="container">
    <h2>Choisissez votre espace</h2>

    <a href="/admin/login" class="btn admin">Administrateur</a>

    <a href="/login" class="btn user">Employé / Agent d'accueil</a>
</div>

</body>
</html>