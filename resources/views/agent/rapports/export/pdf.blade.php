<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f3f6d;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #2f7de1;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            height: 50px;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .info {
            margin-bottom: 15px;
        }

       .stats {
            width: 100%;
            margin: 15px 0;
        }

        .stats table {
            width: 100%;
        }

        .stat-box {
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            font-size: 13px;
        }

        .present { background: #43ad77; }
        .retard { background: #eab14b; }
        .absent { background: #e56a6a; }
        .conge { background: #7a9cf5; }

        .chart {
            text-align: center;
            margin: 20px 0;
        }

        .chart img {
            width: 100%;
            max-height: 300px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 12px;
        }

        th {
            background: #f0f5ff;
        }

        .status {
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 12px;
        }

        .chip-present { background: #e8f7ee; color: #278a4d; }
        .chip-retard { background: #fff4df; color: #a56a00; }
        .chip-absent { background: #fdecec; color: #c94c4c; }
        .chip-conge { background: #edf2ff; color: #4869c9; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="title">Rapport de présence</div>
            <div>
                {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
            </div>
        </div>

        <img src="{{ public_path('Images/drwintech-logo.jpeg') }}" class="logo">
    </div>

    <div class="info">
        Employé : 
        {{ $employe ? $employe->prenom . ' ' . $employe->nom : 'Tous les employés' }}
    </div>

    <div class="stats">
        <table>
            <tr>
                <td><div class="stat-box present">Présents<br>{{ $rapport['stats']['presents'] }}</div></td>
                <td><div class="stat-box retard">Retards<br>{{ $rapport['stats']['retards'] }}</div></td>
                <td><div class="stat-box absent">Absents<br>{{ $rapport['stats']['absents'] }}</div></td>
                <td><div class="stat-box conge">Congés<br>{{ $rapport['stats']['conges'] }}</div></td>
            </tr>
        </table>
    </div>

    @if($chart)
        <div class="chart">
            <img src="{{ $chart }}">
        </div>
    @endif


    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Date</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rapport['details'] as $detail)
                <tr>
                    <td>{{ $detail['nom'] }}</td>
                    <td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td>
                    <td>{{ $detail['heure_arrivee'] ?? '-' }}</td>
                    <td>{{ $detail['heure_depart'] ?? '-' }}</td>
                    <td>
                        <span class="status 
                            {{ $detail['statut_code'] == 'present' ? 'chip-present' : '' }}
                            {{ $detail['statut_code'] == 'retard' ? 'chip-retard' : '' }}
                            {{ $detail['statut_code'] == 'absent' ? 'chip-absent' : '' }}
                            {{ $detail['statut_code'] == 'conge' ? 'chip-conge' : '' }}">
                            {{ $detail['statut'] }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>