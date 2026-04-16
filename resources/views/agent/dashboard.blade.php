<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Agent d’accueil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #dfe8f5;
            min-height: 100vh;
            color: #35527c;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .page-wrap {
            width: 1460px;
            min-height: 900px;
            margin: 16px auto;
            background: #edf3fb;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(36, 74, 124, 0.08);
            display: grid;
            grid-template-columns: 250px 1fr;
            grid-template-rows: 86px auto;
            transition: grid-template-columns 0.3s ease;
        }

        .page-wrap.sidebar-collapsed {
            grid-template-columns: 78px 1fr;
        }

        .sidebar-top {
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            border-right: 1px solid #d7e2ef;
            border-bottom: 1px solid #d7e2ef;
            overflow: hidden;
        }

        .company-logo {
            width: 100%;
            height: 86px;
            object-fit: contain;
            display: block;
            padding: 6px 10px;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #dbe5f2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px 0 30px;
            min-height: 86px;
        }

        .welcome-title {
            font-size: 22px;
            color: #35527c;
        }

        .top-user-dropdown {
            position: relative;
        }

        .top-user-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            background: transparent;
            border: none;
            cursor: pointer;
            color: #35527c;
        }

        .top-user-btn img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d9e4f1;
        }

        .top-user-info .name {
            font-size: 14px;
            line-height: 1.1;
            text-align: left;
        }

        .top-user-info .role {
            font-size: 12px;
            margin-top: 4px;
            color: #6d84a3;
            text-align: left;
        }

        .top-user-arrow {
            font-size: 16px;
            color: #35527c;
        }

        .top-user-menu {
            position: absolute;
            top: 62px;
            right: 0;
            min-width: 170px;
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(36, 74, 124, 0.12);
            display: none;
            overflow: hidden;
            z-index: 1000;
        }

        .top-user-menu.show {
            display: block;
        }

        .top-user-menu a,
        .top-user-menu button {
            width: 100%;
            display: block;
            padding: 12px 14px;
            text-align: left;
            background: transparent;
            border: none;
            text-decoration: none;
            color: #35527c;
            font-size: 14px;
            cursor: pointer;
        }

        .top-user-menu a:hover,
        .top-user-menu button:hover {
            background: #eef4fc;
        }

        .sidebar {
            background: #edf3fb;
            border-right: 1px solid #d7e2ef;
            padding: 10px 14px 16px;
            overflow: hidden;
        }

        .sidebar-controls {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 4px 4px 12px;
        }

        .sidebar-toggle {
            width: 38px;
            height: 38px;
            border: none;
            background: transparent;
            color: #35527c;
            cursor: pointer;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-toggle:hover {
            background: #e4edf8;
        }

        .sidebar-toggle svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            text-decoration: none;
            color: #35527c;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 16px;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .menu a:hover {
            background: rgba(91, 152, 238, 0.15);
        }

        .menu a.active {
            background: rgba(91, 152, 238, 0.20);
            border-left: 4px solid #5b98ee;
        }

        .menu-icon {
            width: 18px;
            min-width: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .menu-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .page-wrap.sidebar-collapsed .menu-text {
            display: none !important;
        }

        .page-wrap.sidebar-collapsed .sidebar {
            padding: 10px 8px 16px;
        }

        .page-wrap.sidebar-collapsed .menu a {
            justify-content: center;
            padding: 12px 8px;
            gap: 0;
        }

        .page-wrap.sidebar-collapsed .menu-icon {
            margin: 0;
        }

        .page-wrap.sidebar-collapsed .company-logo {
            width: 68px;
            height: 68px;
            padding: 4px;
        }

        .page-wrap.sidebar-collapsed .sidebar-controls {
            justify-content: center;
        }

        .content {
            background: #edf3fb;
            padding: 18px;
        }

        .content-header {
            background: transparent;
            margin-bottom: 14px;
        }

        .content-title {
            font-size: 24px;
            color: #1f3f6d;
            margin-bottom: 8px;
        }

        .breadcrumb {
            font-size: 14px;
            color: #6e84a2;
        }

        .breadcrumb a {
            color: #6e84a2;
            text-decoration: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }

        .summary-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-left: 5px solid transparent;
            border-radius: 16px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            padding: 18px 18px 16px;
            display: flex;
            align-items: center;
            gap: 16px;
            min-height: 116px;
        }

        .summary-card-green { border-left-color: #41b66a; }
        .summary-card-yellow { border-left-color: #e7a92a; }
        .summary-card-gray { border-left-color: #b8c3d9; }
        .summary-card-blue { border-left-color: #2f7de1; }

        .summary-icon {
            width: 62px;
            height: 62px;
            min-width: 62px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .summary-icon svg {
            width: 28px;
            height: 28px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .summary-green { background: #41b66a; }
        .summary-yellow { background: #e7a92a; }
        .summary-gray { background: #b8c3d9; }
        .summary-blue { background: #2f7de1; }

        .summary-title {
            font-size: 16px;
            color: #35527c;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 20px;
            color: #1f3f6d;
            margin-bottom: 6px;
        }

        .summary-subtext {
            font-size: 13px;
            color: #6e84a2;
        }

        .blocks-grid {
            display: grid;
            grid-template-columns: 1.65fr 0.95fr;
            gap: 18px;
        }

        .card-block {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
        }

        .card-header {
            padding: 18px 22px;
            font-size: 18px;
            color: #1f3f6d;
            border-bottom: 1px solid #e5edf8;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f7faff;
            color: #35527c;
            font-size: 14px;
            font-weight: normal;
            text-align: left;
            padding: 14px 18px;
            border-bottom: 1px solid #e4edf8;
        }

        tbody td {
            padding: 14px 18px;
            border-bottom: 1px solid #edf3fb;
            font-size: 14px;
            color: #35527c;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: rgba(47, 125, 225, 0.03);
        }

        .employee-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .employee-avatar {
            width: 42px;
            height: 42px;
            min-width: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d9e4f1;
        }

        .employee-name {
            line-height: 1.15;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 104px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 13px;
        }

        .badge-present {
            background: #43ad77;
            color: white;
        }

        .badge-retard {
            background: #eab14b;
            color: #4b3604;
        }

        .badge-absent {
            background: #d9def1;
            color: #52627d;
        }

        .badge-justify {
            background: #e9f1ff;
            color: #2f6fce;
        }

        .badge-conge {
            background: #e7f7ed;
            color: #2f9b55;
        }

        .badge-ferie {
            background: #fff4d9;
            color: #b78103;
        }

        .badge-weekend {
            background: #f1ecff;
            color: #6d57b3;
        }

        .badge-neutral {
            background: #eef2f7;
            color: #5f6f86;
        }


        .activity-list {
            display: flex;
            flex-direction: column;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 16px 1fr auto;
            gap: 12px;
            align-items: center;
            padding: 16px 18px;
            border-bottom: 1px solid #edf3fb;
            font-size: 14px;
            color: #35527c;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-green { background: #43ad77; }
        .dot-yellow { background: #eab14b; }
        .dot-gray { background: #c5cde1; }
        .dot-blue { background: #2f7de1; }

        .activity-message {
            line-height: 1.35;
        }

        .activity-time {
            color: #6e84a2;
            font-size: 13px;
            white-space: nowrap;
        }

        @media (max-width: 1450px) {
            .page-wrap {
                width: calc(100vw - 20px);
                margin: 10px;
            }
        }

        @media (max-width: 1180px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .blocks-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 760px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeSlideRight {
            from {
                opacity: 0;
                transform: translateX(-18px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes softPulse {
            0% {
                box-shadow: 0 0 0 0 rgba(47, 125, 225, 0.10);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(47, 125, 225, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(47, 125, 225, 0);
            }
        }

        /* Apparition générale */
        .content-header {
            animation: fadeSlideUp 0.45s ease;
        }

        .stats-grid .summary-card:nth-child(1) {
            animation: fadeSlideUp 0.45s ease 0.05s both;
        }
        .stats-grid .summary-card:nth-child(2) {
            animation: fadeSlideUp 0.45s ease 0.12s both;
        }
        .stats-grid .summary-card:nth-child(3) {
            animation: fadeSlideUp 0.45s ease 0.19s both;
        }
        .stats-grid .summary-card:nth-child(4) {
            animation: fadeSlideUp 0.45s ease 0.26s both;
        }

        .blocks-grid .card-block:first-child {
            animation: fadeSlideUp 0.5s ease 0.32s both;
        }
        .blocks-grid .card-block:last-child {
            animation: fadeSlideUp 0.5s ease 0.40s both;
        }

        .sidebar {
            animation: fadeSlideRight 0.45s ease;
        }

        /* Animation hover cartes */
        .summary-card,
        .card-block {
            transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
        }

        .summary-card:hover,
        .card-block:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(29, 67, 112, 0.10);
        }

        /* Icônes cartes */
        .summary-icon {
            transition: transform 0.25s ease;
        }

        .summary-card:hover .summary-icon {
            transform: scale(1.08);
        }

        /* Lignes du tableau */
        tbody tr {
            transition: background 0.22s ease, transform 0.22s ease;
        }

        tbody tr:hover {
            background: rgba(47, 125, 225, 0.05);
            transform: scale(1.005);
        }

        /* Badges */
        .badge-status {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .badge-status:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        /* Activités */
        .activity-item {
            transition: background 0.22s ease, transform 0.22s ease;
        }

        .activity-item:hover {
            background: rgba(47, 125, 225, 0.04);
            transform: translateX(4px);
        }

        .activity-dot {
            transition: transform 0.22s ease;
        }

        .activity-item:hover .activity-dot {
            transform: scale(1.2);
        }

        /* Menu sidebar */
        .menu a {
            transition: background 0.22s ease, color 0.22s ease, transform 0.22s ease;
        }

        .menu a:hover {
            transform: translateX(4px);
        }

        /* Bouton menu */
        .sidebar-toggle {
            transition: background 0.22s ease, transform 0.22s ease;
        }

        .sidebar-toggle:hover {
            transform: rotate(90deg);
        }

        /* Profil topbar */
        .top-user-btn {
            transition: transform 0.22s ease;
        }

        .top-user-btn:hover {
            transform: translateY(-1px);
        }

        /* Petit pulse sur la carte bleue */
        .summary-card-blue:hover {
            animation: softPulse 0.9s ease;
        }
    </style>
</head>
<body>
    <div class="page-wrap" id="pageWrap">
        <div class="sidebar-top">
            <img src="{{ asset('Images/drwintech-logo.jpeg') }}" alt="DrwinTech" class="company-logo">
        </div>

        <div class="topbar">
            <div class="welcome-title">
                Bienvenue, {{ $agent->name ?? 'Agent d’accueil' }}
            </div>

            <div class="top-user-dropdown" id="topUserDropdown">
                <button type="button" class="top-user-btn" id="topUserBtn">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($agent->name ?? 'Agent') }}&background=ffffff&color=2d6fe0&size=120" alt="Profil">
                    <div class="top-user-info">
                        <div class="name">{{ $agent->name ?? 'Agent d’accueil' }}</div>
                        <div class="role">Agent d’accueil</div>
                    </div>
                    <span class="top-user-arrow">▾</span>
                </button>

                <div class="top-user-menu" id="topUserMenu">
                    <a href="{{ route('profile.edit') }}">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Déconnexion</button>
                    </form>
                </div>
            </div>
        </div>

        <aside class="sidebar">
            <div class="sidebar-controls">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir ou fermer le menu">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 6H20"></path>
                        <path d="M4 12H20"></path>
                        <path d="M4 18H20"></path>
                    </svg>
                </button>
            </div>

            <nav class="menu">
                <a href="{{ route('agent.dashboard') }}" class="active">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 10.5L12 3L21 10.5"></path>
                            <path d="M5 9.5V21H19V9.5"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Tableau de bord</span>
                </a>

                <a href="{{ route('agent.presences.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M7 3H17"></path>
                            <path d="M7 21H17"></path>
                            <path d="M9 7H15"></path>
                            <path d="M9 11H15"></path>
                            <path d="M9 15H13"></path>
                            <rect x="5" y="3" width="14" height="18" rx="2"></rect>
                        </svg>
                    </span>
                    <span class="menu-text">Présences</span>
                </a>

                {{--    
                <a href="{{ route('agent.temps-travail.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 8V12L15 15"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </span>
                    <span class="menu-text">Temps de travail</span>
                </a>

                --}}

                <a href="{{ route('agent.rapports.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                            <path d="M14 3V9H20"></path>
                            <path d="M8 13H16"></path>
                            <path d="M8 17H13"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Rapports</span>
                </a>
            </nav>
        </aside>

        <main class="content">
            <div class="content-header">
                <div class="content-title">Tableau de bord</div>
                <div class="breadcrumb">
                    <a href="{{ route('agent.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <span>Tableau de bord</span>
                </div>
            </div>

            <div class="stats-grid">
                <div class="summary-card summary-card-green">
                    <div class="summary-icon summary-green">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 6L9 17L4 12"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Présents</div>
                        <div class="summary-value">{{ $presents }}</div>
                        <div class="summary-subtext">Cette semaine</div>
                    </div>
                </div>

                <div class="summary-card summary-card-yellow">
                    <div class="summary-icon summary-yellow">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 9V13"></path>
                            <path d="M12 17H12.01"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Retards</div>
                        <div class="summary-value">{{ $retards }}</div>
                        <div class="summary-subtext">&nbsp;</div>
                    </div>
                </div>

                <div class="summary-card summary-card-gray">
                    <div class="summary-icon summary-gray">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6L18 18"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Absents</div>
                        <div class="summary-value">{{ $absents }}</div>
                        <div class="summary-subtext">Cette semaine</div>
                    </div>
                </div>

                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 6H16"></path>
                            <path d="M8 12H16"></path>
                            <path d="M8 18H16"></path>
                            <circle cx="5" cy="6" r="1"></circle>
                            <circle cx="5" cy="12" r="1"></circle>
                            <circle cx="5" cy="18" r="1"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Total pointages</div>
                        <div class="summary-value">{{ $totalPointages }}</div>
                        <div class="summary-subtext">&nbsp;</div>
                    </div>
                </div>
            </div>

            <div class="blocks-grid">
                <div class="card-block">
                    <div class="card-header">Pointages récents</div>

                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Employé</th>
                                    <th>Heure arrivée</th>
                                    <th>Heure départ</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pointagesRecents as $presence)
                                    @php
                                        $nom = trim(($presence->employe?->prenom ?? '') . ' ' . ($presence->employe?->nom ?? ''));
                                        $avatarName = urlencode($nom ?: 'Employe');
                                        $badgeClass = match($presence->statut_pointage) {
                                            'present' => 'badge-present',
                                            'retard' => 'badge-retard',
                                            'absent' => 'badge-absent',
                                            'absent_justifie' => 'badge-justify',
                                            'conge' => 'badge-conge',
                                            'ferie' => 'badge-ferie',
                                            'weekend' => 'badge-weekend',
                                            default => 'badge-neutral',
                                        };

                                        $badgeLabel = match($presence->statut_pointage) {
                                            'present' => 'Présent',
                                            'retard' => 'En retard',
                                            'absent' => 'Absent',
                                            'absent_justifie' => 'Permission',
                                            'conge' => 'En congé',
                                            'ferie' => 'Jour férié',
                                            'weekend' => 'Week-end',
                                            default => 'Inconnu',
                                        };
                                 @endphp
                                    <tr>
                                        <td>
                                            <div class="employee-cell">
                                                <img
                                                    src="https://ui-avatars.com/api/?name={{ $avatarName }}&background=ffffff&color=2d6fe0&size=120"
                                                    alt="{{ $nom }}"
                                                    class="employee-avatar"
                                                >
                                                <div class="employee-name">{{ $nom ?: 'Employé' }}</div>
                                            </div>
                                        </td>
                                       <td>
                                            @if(in_array($presence->statut_pointage, ['absent', 'absent_justifie', 'conge', 'ferie', 'weekend']))
                                                -
                                            @else
                                                {{ $presence->heure_arrivee ?? '-' }}
                                            @endif
                                        </td>
                                        <td>{{ $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') : '' }}</td>
                                        <td>
                                            <span class="badge-status {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">Aucun pointage récent trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-block">
                    <div class="card-header">Activités du jour</div>

                    <div class="activity-list">
                        @forelse($activitesDuJour as $activite)
                            @php
                                $dotClass = match($activite['type']) {
                                    'retard' => 'dot-yellow',
                                    'absent' => 'dot-gray',
                                    'systeme' => 'dot-blue',
                                    default => 'dot-green',
                                };
                            @endphp

                            <div class="activity-item">
                                <span class="activity-dot {{ $dotClass }}"></span>
                                <div class="activity-message">{{ $activite['message'] }}</div>
                                <div class="activity-time">{{ $activite['temps'] }}</div>
                            </div>
                        @empty
                            <div class="activity-item">
                                <span class="activity-dot dot-blue"></span>
                                <div class="activity-message">Aucune activité enregistrée aujourd’hui.</div>
                                <div class="activity-time">--:--</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pageWrap = document.getElementById('pageWrap');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const topUserBtn = document.getElementById('topUserBtn');
            const topUserMenu = document.getElementById('topUserMenu');
            const topUserDropdown = document.getElementById('topUserDropdown');

            document.querySelectorAll('.summary-card, .card-block').forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(18px)';

                setTimeout(() => {
                    el.style.transition = 'opacity 0.45s ease, transform 0.45s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 80 * index);
            });

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    pageWrap.classList.toggle('sidebar-collapsed');
                });
            }

            if (topUserBtn) {
                topUserBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    topUserMenu.classList.toggle('show');
                });
            }

            window.addEventListener('click', function (e) {
                if (topUserDropdown && !topUserDropdown.contains(e.target)) {
                    topUserMenu.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>