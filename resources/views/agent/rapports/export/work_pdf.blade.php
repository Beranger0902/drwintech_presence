<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport des heures de travail</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            text-align: center;
        }

        th {
            background: #f2f2f2;
        }

        .stats {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
 <div style="text-align: left; margin-bottom: 10px;">
        <img src="{{ public_path('Images/drwintech-logo.jpeg') }}" class="logo" style="width: 120px;">
</div>
<h2>Rapport des heures de travail</h2>

<div class="info">
    <strong>Période :</strong>
    {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
    <br>

    <strong>Employé :</strong>
    {{ $employe ? $employe->prenom . ' ' . $employe->nom : 'Tous les employés' }}
</div>

<div class="stats">
    <p><strong>Total heures :</strong> {{ $rapport['stats']['heures_travaillees'] }}</p>
    <p><strong>Heures normales :</strong> {{ $rapport['stats']['heures_normales'] }}</p>
    <p><strong>Heures supplémentaires :</strong> {{ $rapport['stats']['heures_supp'] }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Période</th>
            <th>Heures normales</th>
            <th>Heures supp</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rapport['details'] as $detail)
            <tr>
                <td>{{ $detail['nom'] }}</td>
                <td>{{ $detail['periode_debut'] }} → {{ $detail['periode_fin'] }}</td>
                <td>{{ $detail['heures_normales'] }}</td>
                <td>{{ $detail['heures_supp'] }}</td>
                <td>{{ $detail['total_heures'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>