<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
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
            background:  white;
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
            background: transparent;
            padding: 6px 10px;
            border-radius: 12px;
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
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
            white-space: nowrap;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .menu a:hover,
        .menu-dropdown-toggle:hover {
            background: rgba(91, 152, 238, 0.15);
        }

        .menu a.active,
        .menu-dropdown.open .menu-dropdown-toggle {
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

        .page-wrap.sidebar-collapsed .menu-text,
        .page-wrap.sidebar-collapsed .menu-arrow,
        .page-wrap.sidebar-collapsed .menu-submenu {
            display: none !important;
        }

        .page-wrap.sidebar-collapsed .sidebar {
            padding: 10px 8px 16px;
        }

        .page-wrap.sidebar-collapsed .menu a,
        .page-wrap.sidebar-collapsed .menu-dropdown-toggle {
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
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .content-header h1 {
            font-size: 22px;
            color: #1f3f6d;
            padding: 18px 22px;
            border-bottom: 1px solid #e3ebf5;
            font-weight: normal;
        }

        .breadcrumb {
            padding: 14px 22px;
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
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            padding: 18px;
            display: flex;
            align-items: center;
            gap: 14px;
            min-height: 96px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .summary-card-blue {
            border-left-color: #2f7de1;
        }

        .summary-card-green {
            border-left-color: #41b66a;
        }

        .summary-card-yellow {
            border-left-color: #e7b11d;
        }


        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
        }


        .summary-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            flex-shrink: 0;
        }

        .summary-icon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .summary-blue { background: #2f7de1; }
        .summary-green { background: #41b66a; }
        .summary-yellow { background: #e7b11d; }

        .summary-title {
            font-size: 15px;
            color: #35527c;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 18px;
            color: #1f3f6d;
        }

        .middle-grid {
            display: grid;
            grid-template-columns: 0.88fr 0.88fr;
            justify-content: start;
            gap: 25px;
            margin-bottom: 16px;
        }

       
        .card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
        }

        .card-title {
            font-size: 15px;
            color: #1f3f6d;
            padding: 16px 18px 12px;
        }

        .card-divider {
            height: 1px;
            background: #e4edf8;
            margin: 0 18px;
        }

        .activity-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 0;
            border-bottom: 1px solid #edf3fb;
            transition: background 0.2s ease, transform 0.2s ease, padding-left 0.2s ease;
            border-radius: 10px;
        }

        .activity-row:hover {
            background: rgba(47, 125, 225, 0.06);
            transform: translateX(4px);
            padding-left: 8px;
        }

        .activities-list,
        .demands-list {
            padding: 10px 18px 14px;
        }

        .activity-row,
        .demand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 0;
            border-bottom: 1px solid #edf3fb;
        }

        .demand-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 0;
            border-bottom: 1px solid #edf3fb;
            transition: background 0.2s ease, transform 0.2s ease, padding-left 0.2s ease;
            border-radius: 10px;
        }

        .demand-row:hover {
            background: rgba(65, 182, 106, 0.06);
            transform: translateX(4px);
            padding-left: 8px;
        }


        .activity-row:last-child,
        .demand-row:last-child {
            border-bottom: none;
        }

        .activity-left,
        .demand-left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .activity-content,
        .demand-content {
            min-width: 0;
        }

        .activity-row:last-child,
        .demand-row:last-child {
            border-bottom: none;
        }

        .activity-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .dot-blue { background: #2f7de1; }
        .dot-green { background: #41b66a; }
        .dot-yellow { background: #e7b11d; }

        .activity-name,
        .demand-name {
            font-size: 15px;
            color: #1f3f6d;
        }

        .activity-action,
        .demand-type {
            font-size: 14px;
            color: #6e84a2;
            margin-top: 4px;
        }

        .activity-time {
            font-size: 14px;
            color: #35527c;
            flex-shrink: 0;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 94px;
            padding: 8px 12px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .demand-row:hover .badge {
            transform: scale(1.04);
            box-shadow: 0 4px 10px rgba(29, 67, 112, 0.10);
        }

        .badge-pending { background: #e7b11d; }
        .badge-approved { background: #41b66a; }
        .badge-refused { background: #2f7de1; }

        .calendar-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(29, 67, 112, 0.04);
            overflow: hidden;
            width: fit-content;
            display: inline-block;
            margin-left: 0;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .calendar-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
        }

        .calendar-body {
            padding: 10px 12px 12px;
        }

        .calendar-header-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .calendar-nav-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #edf3fb;
            color: #35527c;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: transition: all 0.2s ease;
        }

        .calendar-nav-btn:hover {
            background: #2f7de1;
            color: white;
            transform: scale(1.05);
        }

        .calendar-nav-btn:hover {
            background: rgba(47, 125, 225, 0.15);
            transform: translateY(-1px);
        }

        .calendar-month {
            font-size: 14px;
            color: #1f3f6d;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 46px);
            gap: 6px;
            justify-content: start;
        }

        .calendar-day-name {
            font-size: 11px;
            color: #6e84a2;
            text-align: center;
            padding-bottom: 2px;
        }

        .calendar-day {
            width: 46px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #35527c;
            background: #f7faff;
            border: 1px solid #edf3fb;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calendar-day:hover {
            background: #2f7de1;
            color: white;
            transform: scale(1.08);
            box-shadow: 0 4px 10px rgba(47, 125, 225, 0.25);
        }

        .calendar-day:active {
            transform: scale(0.95);
        }


        .calendar-day:hover {
            background: rgba(47, 125, 225, 0.10);
            border-color: #2f7de1;
            color: #1f3f6d;
            transform: translateY(-1px);
        }

        .calendar-day.empty {
            background: transparent;
            border: none;
            cursor: default;
        }

        .calendar-day.empty:hover {
            transform: none;
            background: transparent;
            border: none;
        }

        .calendar-day.today {
            background: #2f7de1;
            color: white;
            font-weight: 600;
            box-shadow: 0 0 0 2px rgba(47, 125, 225, 0.2);
        }

        .calendar-day.today:hover {
            background: #266dca;
        }


        @media (max-width: 1450px) {
            .page-wrap {
                width: calc(100vw - 20px);
                margin: 10px;
            }
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
                Bienvenue, {{ $admin->name ?? 'Administrateur' }}
            </div>

            <div class="top-user-dropdown" id="topUserDropdown">
                <button type="button" class="top-user-btn" id="topUserBtn">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name ?? 'Administrateur') }}&background=ffffff&color=2d6fe0&size=120" alt="Profil">
                    <div class="top-user-info">
                        <div class="name">{{ $admin->name ?? 'Administrateur' }}</div>
                        <div class="role">Administrateur</div>
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
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 10.5L12 3L21 10.5"></path>
                            <path d="M5 9.5V21H19V9.5"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Tableau de bord</span>
                </a>

                <a href="{{ route('admin.utilisateurs.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 20C4 16.5 7.5 14 12 14C16.5 14 20 16.5 20 20"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Utilisateur</span>
                </a>

                <a href="{{ route('admin.employes.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21V19A4 4 0 0 0 12 15H8A4 4 0 0 0 4 19V21"></path>
                            <circle cx="10" cy="7" r="4"></circle>
                            <path d="M20 8V14"></path>
                            <path d="M23 11H17"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Employé</span>
                </a>

                <a href="{{ route('admin.temps-travail.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 8V12L15 15"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </span>
                    <span class="menu-text">Temps de travail</span>
                </a>


                <div class="menu-dropdown" id="menuDropdownDemandes">
                    <button type="button" class="menu-dropdown-toggle" id="demandesToggle">
                        <span class="menu-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 5H20V19H4Z"></path>
                                <path d="M8 9H16"></path>
                                <path d="M8 13H14"></path>
                            </svg>
                        </span>
                        <span class="menu-text">Demandes</span>
                        <span class="menu-arrow menu-text">▾</span>
                    </button>

                    <div class="menu-submenu">
                        <a href="{{ route('admin.demandes.conges.index') }}">
                            <span class="menu-text">Congé</span>
                        </a>
                        <a href="{{ route('admin.demandes.permissions.index') }}">
                            <span class="menu-text">Permission</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.statistiques.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 19V11"></path>
                            <path d="M10 19V5"></path>
                            <path d="M16 19V13"></path>
                            <path d="M22 19V9"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Statistiques</span>
                </a>

                <a href="{{ route('admin.rapports.index') }}">
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
                <h1>Tableau de bord</h1>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <a href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 20C4 16.5 7.5 14 12 14C16.5 14 20 16.5 20 20"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Utilisateurs</div>
                        <div class="summary-value">{{ $totalUsers }}</div>
                    </div>
                </div>

                <div class="summary-card summary-card-green">
                    <div class="summary-icon summary-green">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21V19A4 4 0 0 0 12 15H8A4 4 0 0 0 4 19V21"></path>
                            <circle cx="10" cy="7" r="4"></circle>
                            <path d="M20 8V14"></path>
                            <path d="M23 11H17"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Employés</div>
                        <div class="summary-value">{{ $totalEmployes }}</div>
                    </div>
                </div>

                <div class="summary-card summary-card-yellow">
                    <div class="summary-icon summary-yellow">
                        <svg viewBox="0 0 24 24">
                            <circle cx="9" cy="8" r="3"></circle>
                            <circle cx="17" cy="8" r="3"></circle>
                            <path d="M2 19C2 15.5 5 13.5 9 13.5C13 13.5 16 15.5 16 19"></path>
                            <path d="M12 19C12 16.5 14 15 17 15C20 15 22 16.5 22 19"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Demandes en attente</div>
                        <div class="summary-value">{{ $demandesEnAttente }}</div>
                    </div>
                </div>

                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <path d="M4 5H20V19H4Z"></path>
                            <path d="M8 9H16"></path>
                            <path d="M8 13H14"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Total demandes</div>
                        <div class="summary-value">{{ $totalDemandes }}</div>
                    </div>
                </div>
            </div>


            <div class="middle-grid">

        
                <div class="card">
                    <div class="card-title">Activités récentes</div>
                    <div class="card-divider"></div>

                    <div class="activities-list">
                        @forelse($activitesRecentes as $index => $activite)
                            @php
                                $dotClass = match($index % 3) {
                                    0 => 'dot-blue',
                                    1 => 'dot-green',
                                    default => 'dot-yellow',
                                };
                            @endphp

                            <div class="activity-row">
                                <div class="activity-left">
                                    <span class="activity-dot {{ $dotClass }}"></span>
                                    <div class="activity-content">
                                        <div class="activity-name">{{ $activite['nom'] }}</div>
                                        <div class="activity-action">{{ $activite['action'] }}</div>
                                    </div>
                                </div>
                                <div class="activity-time">{{ $activite['heure'] }}</div>
                            </div>
                        @empty
                            <div class="activity-row">
                                <div class="activity-left">
                                    <span class="activity-dot dot-blue"></span>
                                    <div class="activity-content">
                                        <div class="activity-name">Aucune activité</div>
                                        <div class="activity-action">Pas d’activité récente</div>
                                    </div>
                                </div>
                                <div class="activity-time">--:--</div>
                            </div>
                        @endforelse
                    </div>
                </div>

        
                <div class="card">
                    <div class="card-title">Demandes récentes</div>
                    <div class="card-divider"></div>

                    <div class="demands-list">
                        @forelse($demandesRecentes as $demande)
                            @php
                                $badgeClass = match($demande['statut']) {
                                    'approuve', 'approuvée', 'approuver' => 'badge-approved',
                                    'refuse', 'refusée' => 'badge-refused',
                                    default => 'badge-pending',
                                };

                                $libelleStatut = match($demande['statut']) {
                                    'approuve', 'approuvée', 'approuver' => 'Approuvée',
                                    'refuse', 'refusée' => 'Refusée',
                                    default => 'En attente',
                                };
                            @endphp

                            <div class="demand-row">
                                <div class="demand-left">
                                    <div class="demand-content">
                                        <div class="demand-name">{{ $demande['nom'] }}</div>
                                        <div class="demand-type">{{ $demande['type'] }}</div>
                                    </div>
                                </div>
                                <span class="badge {{ $badgeClass }}">{{ $libelleStatut }}</span>
                            </div>
                        @empty
                            <div class="demand-row">
                                <div class="demand-left">
                                    <div class="demand-content">
                                        <div class="demand-name">Aucune demande</div>
                                        <div class="demand-type">Pas de demande récente</div>
                                    </div>
                                </div>
                                <span class="badge badge-pending">Vide</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            
            
                    <div class="calendar-card">
                        <div class="card-title">Calendrier</div>
                        <div class="card-divider"></div>

                        <div class="calendar-body">
                            <div class="calendar-header-inner">
                                <a
                                    href="{{ route('admin.dashboard', ['mois' => $calendarData['mois_precedent'], 'annee' => $calendarData['annee_precedente']]) }}"
                                    class="calendar-nav-btn"
                                >
                                    ‹
                                </a>

                                <div class="calendar-month">
                                    {{ ucfirst($calendarData['mois']) }} {{ $calendarData['annee'] }}
                                </div>

                                <a
                                    href="{{ route('admin.dashboard', ['mois' => $calendarData['mois_suivant'], 'annee' => $calendarData['annee_suivante']]) }}"
                                    class="calendar-nav-btn"
                                >
                                    ›
                                </a>
                            </div>

                            <div class="calendar-grid">
                                <div class="calendar-day-name">Lun</div>
                                <div class="calendar-day-name">Mar</div>
                                <div class="calendar-day-name">Mer</div>
                                <div class="calendar-day-name">Jeu</div>
                                <div class="calendar-day-name">Ven</div>
                                <div class="calendar-day-name">Sam</div>
                                <div class="calendar-day-name">Dim</div>

                                @php
                                    $firstDay = $calendarData['premier_jour_semaine'];
                                    $firstDay = $firstDay === 0 ? 6 : $firstDay - 1;
                                    $nombreJours = $calendarData['nombre_jours'];
                                @endphp

                                @for($i = 0; $i < $firstDay; $i++)
                                    <div class="calendar-day empty"></div>
                                @endfor

                                @for($jour = 1; $jour <= $nombreJours; $jour++)
                                    <div class="calendar-day {{ $jour === $calendarData['jour_actuel'] ? 'today' : '' }}">
                                        {{ $jour }}
                                    </div>
                                @endfor
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
            const demandesToggle = document.getElementById('demandesToggle');
            const demandesDropdown = document.getElementById('menuDropdownDemandes');
            const prevBtn = document.getElementById('prevMonth');
            const nextBtn = document.getElementById('nextMonth');


            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    window.location.href = "?mois={{ $calendarData['mois_precedent'] }}&annee={{ $calendarData['annee_precedente'] }}";
                });
            }

            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    window.location.href = "?mois={{ $calendarData['mois_suivant'] }}&annee={{ $calendarData['annee_suivante'] }}";
                });
            }

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

            if (demandesToggle) {
                demandesToggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (!pageWrap.classList.contains('sidebar-collapsed')) {
                        demandesDropdown.classList.toggle('open');
                    }
                });
            }

            window.addEventListener('click', function (e) {
                if (topUserDropdown && !topUserDropdown.contains(e.target)) {
                    topUserMenu?.classList.remove('show');
                }
            });

        });


    </script>
</body>
</html>