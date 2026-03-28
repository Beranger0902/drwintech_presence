<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Employé</title>
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
            overflow-y: auto;
            overflow-x: hidden;
        }

        .page-wrap {
            width: 100%;
            max-width: 1260px;
            min-height: 860px;
            margin: 20px auto;
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
            background:  white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border-right: 1px solid #d7e2ef;
            border-bottom: 1px solid #d7e2ef;
            overflow: hidden;
        }

        .company-logo {
            width: 100%;
            height: 74px;
            object-fit: contain;
            display: block;
            padding: 6px 10px;
            background: transparent;
            border-radius: 12px;
        }

        .topbar {
            background: white;
            color: #3b5b86;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px 0 30px;
            border-bottom: 1px solid #dbe5f2;
        }

        .welcome-title {
            font-size: 22px;
            font-weight: normal;
        }

        .top-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .top-user img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d9e4f1;
        }

        .top-user-info .name {
            font-size: 14px;
            line-height: 1.1;
        }

        .top-user-info .role {
            font-size: 12px;
            margin-top: 4px;
            color: #6d84a3;
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
            color: #3b5b86;
        }

        .top-user-btn img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d9e4f1;
        }

        .top-user-arrow {
            font-size: 18px;
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
            transition: all 0.3s ease;
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
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a,
        .menu button.logout-btn {
            text-decoration: none;
            color: #35527c;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: normal;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .menu a:hover,
        .menu button.logout-btn:hover {
            background: rgba(91, 152, 238, 0.15);
            color: #35527c;
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

        .menu-text {
            transition: opacity 0.2s ease;
        }

        .menu-separator {
            height: 1px;
            background: #d9e3f1;
            margin: 10px 0;
        }

        .menu-dropdown {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .menu a,
        .menu-dropdown-toggle {
            text-decoration: none;
            color: #35527c;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: normal;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .menu-dropdown-toggle:hover {
           background: rgba(91, 152, 238, 0.15);
            color: #35527c;
        }

        .menu a.active,
        .menu-dropdown.open .menu-dropdown-toggle {
           background: rgba(91, 152, 238, 0.2);
            color: #35527c;
        }

        .menu a,
        .menu-dropdown-toggle {
            transition: all 0.25s ease;
        }

        .menu a.active {
            border-left: 4px solid #5b98ee;
        }


        .menu-arrow {
            margin-left: auto;
            font-size: 14px;
        }

        .menu-submenu {
            display: none;
            flex-direction: column;
            gap: 6px;
            margin-left: 34px;
        }

        .menu-dropdown.open .menu-submenu {
            display: flex;
        }

        .menu-submenu a {
            padding: 10px 14px;
            font-size: 14px;
            border-radius: 8px;
        }

        .page-wrap.sidebar-collapsed .menu-arrow,
        .page-wrap.sidebar-collapsed .menu-submenu {
            display: none !important;
        }

        .page-wrap.sidebar-collapsed .sidebar-top {
            padding: 4px;
            justify-content: center;
        }

        .page-wrap.sidebar-collapsed .menu-text {
            display: none;
        }

        .page-wrap.sidebar-collapsed .menu a,
        .page-wrap.sidebar-collapsed .menu button.logout-btn {
            justify-content: center;
            padding: 12px 8px;
            gap: 0;
        }

        .page-wrap.sidebar-collapsed .menu-icon {
            margin: 0;
        }

        .page-wrap.sidebar-collapsed .company-logo {
            width: 54px;
            height: 54px;
            padding: 2px;
            object-fit: cover;
            border-radius: 50px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        }

        .page-wrap.sidebar-collapsed .sidebar-controls {
            justify-content: center;
            padding-bottom: 10px;
        }

        .content {
            background: #edf3fb;
            padding: 16px;
            overflow: visible;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1.65fr 1fr;
            gap: 12px;
            min-height: 100%;
        }

        .left-column,
        .right-column {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-height: auto;
        }

        .card {
            background: #f9fbff;
            border: 1px solid #dae5f2;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
        }

        .card-title {
            font-size: 15px;
            font-weight: normal;
            color: #35527c;
            padding: 14px 16px 10px;
        }

        .card-divider {
            height: 1px;
            background: #e4edf8;
            margin: 0 16px;
        }

        .day-state {
            padding: 18px 18px 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            align-items: center;
            gap: 12px;
            min-height: 255px;
        }

        .day-hours p {
            font-size: 14px;
            color: #49658b;
            margin-bottom: 16px;
        }

        .day-hours strong {
            font-size: 16px;
            color: #35527c;
            font-weight: normal;
        }

        .action-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 56px;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: normal;
            margin-top: 12px;
        }

        .btn-arrivee {
            background: linear-gradient(180deg, #41c66e 0%, #2db15a 100%);
        }

        .btn-depart {
            background: linear-gradient(180deg, #ff8a1d 0%, #f06f0d 100%);
        }

        .map-box {
            width: 100%;
            height: 210px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e1ebf7;
        }

        .mini-card-body {
            padding: 10px 16px 12px;
        }

        .resume-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: #49658b;
            padding: 8px 0;
        }

        .resume-row strong {
            color: #35527c;
            font-size: 16px;
            font-weight: normal;
        }

        .list-body {
            padding: 8px 14px 10px;
        }

        .waiting-card {
            background: #f9fbff;
            border: 1px solid #dae5f2;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            min-height: 230px;
        }

        .waiting-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 4px;
            border-bottom: 1px solid #e4edf8;
            color: #49658b;
            font-size: 14px;
        }

        .waiting-row:last-child {
            border-bottom: none;
        }

        .waiting-row strong {
            color: #35527c;
            font-size: 16px;
            font-weight: normal;
        }

        .history-card {
            min-height: 210px;
        }

        .history-list {
            padding: 10px 12px 16px;
        }

        .history-item {
            display: grid;
            grid-template-columns: 1.1fr 1fr auto;
            align-items: center;
            background: #f2f6fd;
            border-radius: 8px;
            padding: 10px 12px;
            margin-bottom: 8px;
            font-size: 14px;
            color: #49658b;
        }

        .history-item:last-child {
            margin-bottom: 0;
        }

        .history-item strong {
            color: #35527c;
            font-weight: normal;
        }

        .status-present {
            color: #30a84f;
        }

        .status-absent {
            color: #f07b17;
        }

        .status-justified {
            color: #7b61ff;
        }

        .status-holiday {
            color: #3b82f6;
        }

        .status-weekend {
            color: #6b7280;
        }

        .stats-card {
            min-height: 220px;
            margin-top: 0;
        }

        .stats-card-body {
            padding: 10px 12px 12px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .mini-stat-box {
            border: 1px solid #e2eaf5;
            border-radius: 8px;
            background: white;
            padding: 8px;
            min-height: 170px;
            display: flex;
            flex-direction: column;
        }

        .mini-stat-title {
            text-align: center;
            font-size: 11px;
            color: #56708f;
            margin-bottom: 8px;
            font-weight: normal;
        }

        .real-chart {
            flex: 1;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 6px;
            padding: 8px 4px 0;
        }

            .chart-item {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-end;
                gap: 4px;
            }

            .chart-value {
                font-size: 9px;
                color: #56708f;
            }

            .chart-bar {
                width: 18px;
                border-radius: 4px 4px 0 0;
                background: linear-gradient(180deg, #68a4ff 0%, #2f73e6 100%);
            }

            .chart-label {
                font-size: 9px;
                color: #6f86a4;
            }

            .real-calendar {
                display: grid;
                grid-template-columns: repeat(7, 1fr);
                gap: 4px;
                margin-top: 4px;
            }

            .calendar-header {
                text-align: center;
                font-size: 9px;
                color: #6f86a4;
                padding: 2px 0;
            }

            .calendar-day {
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 9px;
                border-radius: 4px;
                color: #35527c;
                background: #f4f7fc;
            }

            .calendar-day.empty {
                background: transparent;
            }

            .calendar-day.today {
                background: #5b98ee;
                color: white;
            }

            .calendar-day.worked-day {
                background: #d8ecff;
                color: #2f73e6;
                font-weight: bold;
            }

            .calendar-day.today.worked-day {
                background: #5b98ee;
                color: white;
            }

            .calendar-footer {
                margin-top: 8px;
                text-align: center;
                font-size: 9px;
                color: #56708f;
            }

        @media (max-width: 1450px) {
            .page-wrap {
                width: calc(100vw - 20px);
                margin: 10px;
            }
        }
    </style>
</head>

<script>
    function toggleSidebar() {
        document.getElementById('pageWrap').classList.toggle('sidebar-collapsed');
    }

    function toggleDemandesMenu() {
        if (document.getElementById('pageWrap').classList.contains('sidebar-collapsed')) {
            return;
        }

        document.querySelector('.menu-dropdown').classList.toggle('open');
    }
</script>
    <body>
        <div class="page-wrap" id="pageWrap">
            <div class="sidebar-top">
                <img src="{{ asset('Images/drwintech-logo.jpeg') }}" alt="DrwinTech" class="company-logo">
            </div>

            <div class="topbar">
                <div class="welcome-title">Bienvenue, {{ $employe?->prenom ?? 'Jean' }} {{ $employe?->nom ?? 'Dupont' }}!</div>

                <div class="top-user-dropdown" id="topUserDropdown">
                    <button type="button" class="top-user-btn" onclick="toggleUserMenu()">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(($employe?->prenom ?? 'Jean').' '.($employe?->nom ?? 'Dupont')) }}&background=ffffff&color=2d6fe0&size=120" alt="Profil">

                        <div class="top-user-info">
                            <div class="name">{{ $employe?->prenom ?? 'Jean' }} {{ $employe?->nom ?? 'Dupont' }}</div>
                            <div class="role">{{ $employe?->poste ?? 'Développeur' }}</div>
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
                    <button type="button" class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Ouvrir ou fermer le menu">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path d="M4 6H20"></path>
                            <path d="M4 12H20"></path>
                            <path d="M4 18H20"></path>
                        </svg>
                    </button>
                </div>

                <nav class="menu">
                    <a href="{{ route('employe.dashboard') }}" class="{{ request()->routeIs('employe.dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M3 10.5L12 3L21 10.5"></path>
                                <path d="M5 9.5V21H19V9.5"></path>
                            </svg>
                        </span>
                        <span class="menu-text">Tableau de bord</span>
                    </a>

                    <a href="{{ route('employe.pointage.index') }}" class="{{ request()->routeIs('employe.pointage.*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 21S18 15.5 18 10.5A6 6 0 0 0 6 10.5C6 15.5 12 21 12 21Z"></path>
                                <path d="M12 13A2.5 2.5 0 1 0 12 8A2.5 2.5 0 0 0 12 13Z"></path>
                            </svg>
                        </span>
                        <span class="menu-text">Pointage</span>
                    </a>

                    <a href="{{ route('employe.historique.index') }}" class="{{ request()->routeIs('employe.historique.*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 8V12L15 15"></path>
                                <path d="M3.5 12A8.5 8.5 0 1 0 12 3.5"></path>
                            </svg>
                        </span>
                        <span class="menu-text">Historique</span>
                    </a>

                    <div class="menu-separator"></div>

                    <a href="{{ route('employe.temps-travail.index') }}" class="{{ request()->routeIs('employe.temps-travail.*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 8V12L15 15"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                            </svg>
                        </span>
                        <span class="menu-text">Temps de travail</span>
                    </a>

                    <div class="menu-dropdown {{ request()->routeIs('employe.demandes.conges.*') || request()->routeIs('employe.demandes.permissions.*') ? 'open' : '' }}">
                            <button type="button" class="menu-dropdown-toggle" onclick="toggleDemandesMenu()">
                                <span class="menu-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M4 5H20V19H4Z"></path>
                                        <path d="M8 9H16"></path>
                                        <path d="M8 13H14"></path>
                                    </svg>
                                </span>
                                <span class="menu-text">Demande</span>
                                <span class="menu-arrow menu-text">▾</span>
                            </button>

                            <div class="menu-submenu" id="demandesSubmenu">
                                <a href="{{ route('employe.demandes.conges.index') }}" class="{{ request()->routeIs('employe.demandes.conges.*') ? 'active' : '' }}">
                                    <span class="menu-text">Congé</span>
                                </a>

                                <a href="{{ route('employe.demandes.permissions.index') }}" class="{{ request()->routeIs('employe.demandes.permissions.*') ? 'active' : '' }}">
                                    <span class="menu-text">Permission</span>
                                </a>
                            </div>
                    </div>
                
                </nav>
            </aside>

            <main class="content">
                <div class="dashboard-grid">
                    <div class="left-column">
                        <div class="card">
                            <div class="card-title">État de la journée</div>
                            <div class="card-divider"></div>

                            <div class="day-state">
                                <div class="day-hours">
                                    <p>Heure d'arrivée : <strong>{{ $presenceDuJour?->heure_arrivee ?? '--:--' }}</strong></p>
                                    <p>Heure de départ : <strong>{{ $presenceDuJour?->heure_depart ?? '--:--' }}</strong></p>

                                    <a href="{{ route('employe.pointage.index') }}" class="action-link btn-arrivee">
                                        Pointer l’arrivée
                                    </a>

                                    <a href="{{ route('employe.pointage.index') }}" class="action-link btn-depart">
                                        Pointer le départ
                                    </a>
                                </div>

                                <div class="map-box">
                                    <iframe
                                        width="100%"
                                        height="100%"
                                        frameborder="0"
                                        style="border:0"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        src="https://maps.google.com/maps?q=Drwintech,Aibatin2,Cotonou,Benin&z=15&output=embed"
                                        allowfullscreen>
                                    </iframe>
                                </div>
                            </div>
                        </div>

                        <div class="card history-card">
                            <div class="card-title">Historique des pointages</div>
                            <div class="card-divider"></div>

                            <div class="history-list">
                                @forelse ($historiqueRecent as $presence)
                                    <div class="history-item">
                                        <div><strong>{{ \Carbon\Carbon::parse($presence->date_presence)->format('d/m/Y') }}</strong></div>
                                        <div><strong>{{ $presence->heure_arrivee ?? '--:--' }} - {{ $presence->heure_depart ?? '--:--' }}</strong></div>
                                      
                                      
                                            @php
                                                $statut = strtolower($presence->statut_pointage ?? 'absent');

                                                $libelleStatut = match ($statut) {
                                                    'present' => 'Présent',
                                                    'termine' => 'Présent',
                                                    'retard' => 'Retard',
                                                    'absent' => 'Absent',
                                                    'absent_justifie' => 'Abs. justifiée',
                                                    'conge' => 'Congé',
                                                    'ferie' => 'Férié',
                                                    'weekend' => 'Week-end',
                                                    default => ucfirst(str_replace('_', ' ', $statut)),
                                                };

                                                $classeStatut = match ($statut) {
                                                    'present', 'termine' => 'status-present',
                                                    'retard', 'absent' => 'status-absent',
                                                    'absent_justifie', 'conge' => 'status-justified',
                                                    'ferie' => 'status-holiday',
                                                    'weekend' => 'status-weekend',
                                                    default => '',
                                                };
                                            @endphp

                                            <div class="{{ $classeStatut }}">
                                                {{ $libelleStatut }}
                                            </div>
                                    
                                    </div>
                                @empty
                                    <div class="history-item">
                                        <div><strong>Aucune donnée</strong></div>
                                        <div><strong>--:-- - --:--</strong></div>
                                        <div class="status-absent">Absent</div>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="right-column">
                        <div class="card">
                            <div class="card-title">Résumé du temps de travail</div>
                            <div class="card-divider"></div>

                            <div class="mini-card-body">
                                <div class="resume-row">
                                    <span>Cette semaine :</span>
                                    <strong>{{ $stats['semaine'] }}</strong>
                                </div>


                                <div class="resume-row">
                                    <span>Ce mois :</span>
                                    <strong>{{ $stats['mois'] }}</strong>
                                </div>

                                <div class="resume-row">
                                    <span>Heures supp :</span>
                                    <strong>
                                        {{ floor(($presenceDuJour->heures_supplementaires ?? 0) / 60) }}h 
                                        {{ ($presenceDuJour->heures_supplementaires ?? 0) % 60 }}min
                                    </strong>
                                </div>

                            </div>
                        </div>

                        <div class="card waiting-card">
                            <div class="card-title">Demandes en attente</div>
                            <div class="card-divider"></div>

                            <div class="list-body">
                                <div class="waiting-row">
                                    <div class="left">
                                        <span>Congés en attente :</span>
                                    </div>
                                    <strong>{{ $stats['conges_en_attente'] }}</strong>
                                </div>

                                <div class="waiting-row">
                                    <div class="left">
                                        <span>Permissions en attente :</span>
                                    </div>
                                    <strong>{{ $stats['permissions_en_attente'] }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="card stats-card">
                            <div class="card-title">Mes statistiques</div>
                            <div class="card-divider"></div>

                            @php
                                use Carbon\Carbon;

                                $maintenant = Carbon::now();
                                $debutMois = $maintenant->copy()->startOfMonth();
                                $finMois = $maintenant->copy()->endOfMonth();

                                $premierJourSemaine = $debutMois->dayOfWeekIso; // 1=lundi ... 7=dimanche
                                $casesVidesAvant = $premierJourSemaine - 1;
                                $nombreJours = $finMois->day;

                                $labelsSemaine = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
                                $moisFrancais = [
                                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                ];

                                $maxHeures = max($heuresSemaine ?: [1]);
                                $maxHeures = $maxHeures > 0 ? $maxHeures : 1;
                            @endphp

                            <div class="stats-card-body">
                                <div class="mini-stat-box">
                                    <div class="mini-stat-title">Heures de la semaine</div>

                                    <div class="real-chart">
                                        @foreach ($heuresSemaine as $index => $heure)
                                            @php
                                                $hauteur = max(12, ($heure / $maxHeures) * 90);
                                            @endphp
                                            <div class="chart-item">
                                                <div class="chart-value">{{ number_format($heure, 1) }}h</div>
                                                <div class="chart-bar" style="height: {{ $hauteur }}px;"></div>
                                                <div class="chart-label">{{ $labelsSemaine[$index] }}</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mini-stat-box">
                                    <div class="mini-stat-title">
                                        {{ $moisFrancais[$maintenant->month] }} {{ $maintenant->year }}
                                    </div>

                                    <div class="real-calendar">
                                        <div class="calendar-header">L</div>
                                        <div class="calendar-header">M</div>
                                        <div class="calendar-header">M</div>
                                        <div class="calendar-header">J</div>
                                        <div class="calendar-header">V</div>
                                        <div class="calendar-header">S</div>
                                        <div class="calendar-header">D</div>

                                        @for ($i = 1; $i <= $casesVidesAvant; $i++)
                                            <div class="calendar-day empty"></div>
                                        @endfor

                                        @for ($jour = 1; $jour <= $nombreJours; $jour++)
                                            @php
                                                $dateCourante = Carbon::create($maintenant->year, $maintenant->month, $jour);
                                                $estAujourdhui = $dateCourante->isToday();
                                                $estJourTravaille = in_array($jour, $joursTravaillesMois ?? []);
                                            @endphp

                                            <div class="calendar-day {{ $estAujourdhui ? 'today' : '' }} {{ $estJourTravaille ? 'worked-day' : '' }}">
                                                {{ $jour }}
                                            </div>
                                        @endfor
                                    </div>

                                    <div class="calendar-footer">
                                        Aujourd’hui : {{ $maintenant->format('d/m/Y') }}
                                    </div>
                                </div>
                            
                                <div class="mini-stat-box">
                                    <div class="mini-stat-title">Statuts du mois</div>
                                    <div class="mini-card-body">
                                        <div class="resume-row">
                                            <span>Présents :</span>
                                            <strong>{{ $stats['presents'] }}</strong>
                                        </div>
                                        <div class="resume-row">
                                            <span>Retards :</span>
                                            <strong>{{ $stats['retards'] }}</strong>
                                        </div>
                                        <div class="resume-row">
                                            <span>Absents :</span>
                                            <strong>{{ $stats['absents'] }}</strong>
                                        </div>
                                        <div class="resume-row">
                                            <span>Abs. justifiées :</span>
                                            <strong>{{ $stats['absences_justifiees'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    
                    </div>
                </div>
            </main>
        </div>

            <script>
                function toggleSidebar() {
                    document.getElementById('pageWrap').classList.toggle('sidebar-collapsed');
                }

                function toggleUserMenu() {
                    document.getElementById('topUserMenu').classList.toggle('show');
                }

                window.addEventListener('click', function (e) {
                    const dropdown = document.getElementById('topUserDropdown');

                    if (!dropdown.contains(e.target)) {
                        document.getElementById('topUserMenu').classList.remove('show');
                    }
                });
            </script>
    </body>
</html>