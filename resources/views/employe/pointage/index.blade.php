<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pointage Employé</title>
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
            width: 120%;
            max-width: 1260px;
            min-height: 810px;
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
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
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
            border-left: 4px solid #5b98ee
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
            padding-bottom: 10px;
        }

        .content {
            background: #edf3fb;
            padding: 18px;
            width: 100%;
            max-width: 100%;
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
            font-weight: bold;
            color: #1f3f6d;
            padding: 18px 22px;
            border-bottom: 1px solid #e3ebf5;
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

        .pointage-grid-top {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        .mini-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            padding: 18px 22px;
        }

        .mini-card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #6d84a3;
            font-size: 14px;
            font-weight: normal;
            margin-bottom: 10px;
        }

        .mini-card-title svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .mini-card-value {
            border-top: 1px solid #e6edf7;
            padding-top: 14px;
            font-size: 16px;
            font-weight: normal;
            color: #35527c;
        }

        .map-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            padding: 14px;
            margin-bottom: 18px;
        }

        .map-frame {
            width: 100%;
            max-width: 100%;
            height: 280px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #dfe8f3;
        }

        .map-frame iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .map-position {
            text-align: center;
            font-size: 15px;
            color: #35527c;
            margin-top: 10px;
        }

        .map-position strong {
            color: #1f3f6d;
        }

        .action-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 18px;
        }

        .pointage-action-btn {
            border: none;
            border-radius: 10px;
            color: white;
            cursor: pointer;
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            width: 100%;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .pointage-action-btn svg {
            width: 46px;
            height: 46px;
            flex-shrink: 0;
            fill: white;
            stroke: white;
            stroke-width: 1.5;
        }

        .btn-arrivee {
            background: linear-gradient(180deg, #37c34a 0%, #28b23c 100%);
        }

        .btn-depart {
            background: linear-gradient(180deg, #ffa216 0%, #f28b00 100%);
        }

        .action-text-main {
            font-size: 18px;
            font-weight: bold;
            text-align: left;
        }

        .action-text-sub {
            font-size: 13px;
            margin-top: 4px;
            opacity: 0.95;
            text-align: left;
        }

        .infos-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            overflow: hidden;
        }

        .infos-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f3f6d;
            padding: 14px 18px;
            border-bottom: 1px solid #e6edf7;
        }

        .info-row {
            display: grid;
            grid-template-columns: 180px 1fr;
            padding: 12px 18px;
            border-bottom: 1px solid #e6edf7;
            font-size: 15px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #5e7798;
            font-weight: normal;
        }

        .info-value {
            color: #1f3f6d;
            font-weight: normal;
        }

        .status-success {
            color: #22a63d;
        }

        .status-error {
            color: #d93025;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 65, 120, 0.65);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            padding: 20px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 520px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
        }

        .modal-content {
            padding: 24px 24px 0;
            text-align: center;
        }

        .modal-title {
            font-size: 22px;
            font-weight: bold;
            color: #1f3f6d;
            margin-bottom: 18px;
        }

        .modal-divider {
            height: 1px;
            background: #e6edf7;
            margin-bottom: 20px;
        }

        .loader-circle {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            border: 10px solid #cfe0f5;
            border-top-color: #2f7de1;
            margin: 0 auto 18px;
            animation: spin 1s linear infinite;
            position: relative;
        }

        .loader-center {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .loader-center svg {
            width: 34px;
            height: 34px;
            stroke: #2f7de1;
            stroke-width: 2;
            fill: none;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .modal-text {
            font-size: 18px;
            color: #35527c;
            margin-bottom: 20px;
        }

        .modal-map {
            width: 100%;
            height: 180px;
            overflow: hidden;
            border-top: 1px solid #e6edf7;
            margin-top: 20px;
        }

        .modal-map iframe {
            width: 100%;
            height: 100%;
            border: none;
            display: block;
        }

        .success-icon {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #31b73f;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .success-icon svg {
            width: 54px;
            height: 54px;
            stroke: white;
            stroke-width: 3;
            fill: none;
        }

        .success-time {
            font-size: 56px;
            color: #1f3f6d;
            font-weight: bold;
            margin: 14px 0;
        }

        .success-position {
            font-size: 17px;
            color: #35527c;
            margin-bottom: 16px;
        }

        .success-position strong {
            color: #1f3f6d;
        }

        .modal-footer {
            padding: 18px 24px 24px;
            text-align: center;
        }

        .ok-btn {
            border: none;
            background: #2fa13b;
            color: white;
            padding: 14px 26px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            min-width: 220px;
        }

        .error-icon {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #d93025;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
        }

        .error-icon svg {
            width: 52px;
            height: 52px;
            stroke: white;
            stroke-width: 3;
            fill: none;
        }

        .error-message {
            font-size: 18px;
            color: #d93025;
            font-weight: bold;
            margin-bottom: 14px;
        }

        .close-btn {
            border: none;
            background: #35527c;
            color: white;
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="page-wrap" id="pageWrap">
        <div class="sidebar-top">
            <img src="{{ asset('Images/drwintech-logo.jpeg') }}" alt="DrwinTech" class="company-logo">
        </div>

        <div class="topbar">
            <div class="welcome-title">Pointage</div>

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
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir ou fermer le menu">
                    <svg viewBox="0 0 24 24">
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

                <div class="menu-dropdown {{ request()->routeIs('employe.demandes.conges.*') || request()->routeIs('employe.demandes.permissions.*') ? 'open' : '' }}" id="menuDropdownDemandes">
                    <button type="button" class="menu-dropdown-toggle" id="demandesToggle">
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

                    <div class="menu-submenu">
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
            <div class="content-header">
                <div class="breadcrumb">
                    <a href="{{ route('employe.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <a href="{{ route('employe.pointage.index') }}">Pointage</a>
                </div>
            </div>

            <div class="pointage-grid-top">
                <div class="mini-card">
                    <div class="mini-card-title">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="5" width="18" height="16"></rect>
                            <path d="M8 3V7"></path>
                            <path d="M16 3V7"></path>
                            <path d="M3 10H21"></path>
                        </svg>
                        <span>Date</span>
                    </div>
                    <div class="mini-card-value">{{ now()->translatedFormat('d F Y') }}</div>
                </div>

                <div class="mini-card">
                    <div class="mini-card-title">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21S18 15.5 18 10.5A6 6 0 0 0 6 10.5C6 15.5 12 21 12 21Z"></path>
                            <path d="M12 13A2.5 2.5 0 1 0 12 8A2.5 2.5 0 0 0 12 13Z"></path>
                        </svg>
                        <span>Votre Position</span>
                    </div>
                    <div class="mini-card-value" id="position-text">Latitude: --, Longitude: --</div>
                </div>
            </div>
            <div class="map-card">
                <div class="map-frame">
                    <iframe
                        id="mainMap"
                        src="https://maps.google.com/maps?q=Drwintech,Aibatin2,Cotonou,Benin&z=15&output=embed"
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="map-position">
                    Position actuelle :
                    <strong id="position-short">--</strong>
                </div>
            </div>

            <div class="action-grid">
                <form id="form-arrivee" method="POST" action="{{ route('employe.pointage.arrivee') }}">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude-arrivee">
                    <input type="hidden" name="longitude" id="longitude-arrivee">

                    <button type="button" class="pointage-action-btn btn-arrivee" id="btnArrivee">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 6V12L16 14"></path>
                        </svg>
                        <div>
                            <div class="action-text-main">Pointer l'Arrivée</div>
                            <div class="action-text-sub">Enregistrer votre heure d'arrivée</div>
                        </div>
                    </button>
                </form>

                <form id="form-depart" method="POST" action="{{ route('employe.pointage.depart') }}">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude-depart">
                    <input type="hidden" name="longitude" id="longitude-depart">

                    <button type="button" class="pointage-action-btn btn-depart" id="btnDepart">
                        <svg viewBox="0 0 24 24">
                            <path d="M5 12H19"></path>
                            <path d="M12 5L19 12L12 19"></path>
                        </svg>
                        <div>
                            <div class="action-text-main">Pointer le Départ</div>
                            <div class="action-text-sub">Enregistrer votre heure de départ</div>
                        </div>
                    </button>
                </form>
            </div>

            <div class="infos-card">
                <div class="infos-title">Infos de Pointage</div>

                <div class="info-row">
                    <div class="info-label">Heure d'arrivée :</div>
                    <div class="info-value" id="heure-arrivee-valeur">{{ $presenceDuJour?->heure_arrivee ?? 'Non enregistré' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Heure de départ :</div>
                    <div class="info-value" id="heure-depart-valeur">{{ $presenceDuJour?->heure_depart ?? 'Non enregistré' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Statut actuel :</div>
                    <div class="info-value status-success" id="statut-actuel">{{ $presenceDuJour?->statut_pointage ?? 'En cours' }}</div>
                </div>

                <div class="info-row">
                    <div class="info-label">Adresse :</div>
                    <div class="info-value">Aibatin,Cotonou, Bénin</div>
                </div>
            </div>
        </main>
    </div>
    <div class="modal-overlay" id="verificationModal">
        <div class="modal-box">
            <div class="modal-content">
                <div class="modal-title">Pointage en cours...</div>
                <div class="modal-divider"></div>

                <div class="loader-circle">
                    <div class="loader-center">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 21S18 15.5 18 10.5A6 6 0 0 0 6 10.5C6 15.5 12 21 12 21Z"></path>
                            <path d="M12 13A2.5 2.5 0 1 0 12 8A2.5 2.5 0 0 0 12 13Z"></path>
                        </svg>
                    </div>
                </div>

                <div class="modal-text">Vérification de votre position...</div>
            </div>

            <div class="modal-map">
                <iframe id="verificationMap" src="https://maps.google.com/maps?q=Drwintech,Aibatin2,Cotonou,Benin&z=15&output=embed"></iframe>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="successModal">
        <div class="modal-box">
            <div class="modal-content">
                <div class="success-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 13L10 18L19 7"></path>
                    </svg>
                </div>

                <div class="modal-title">Pointage enregistré avec succès !</div>
                <div class="modal-divider"></div>

                <div class="success-time" id="successTime">--:--</div>
                <div class="success-position">Position validée : <strong>Cotonou, Bénin</strong></div>
            </div>

            <div class="modal-map">
                <iframe id="successMap" src="https://maps.google.com/maps?q=Drwintech,Aibatin2,Cotonou,Benin&z=15&output=embed"></iframe>
            </div>

            <div class="modal-footer">
                <button class="ok-btn" id="okBtn">OK / Retour au tableau</button>
            </div>
        </div>
    </div>
    <div class="modal-overlay" id="errorModal">
        <div class="modal-box">
            <div class="modal-content">
                <div class="error-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M6 6L18 18"></path>
                        <path d="M18 6L6 18"></path>
                    </svg>
                </div>

                <div class="modal-title">Pointage refusé</div>
                <div class="modal-divider"></div>
                <div class="error-message" id="errorMessage">Vous êtes hors de la zone autorisée.</div>
            </div>

            <div class="modal-footer">
                <button class="close-btn" id="closeErrorBtn">Fermer</button>
            </div>
        </div>
    </div>

    @if (session('error'))
        <script>
            window._SERVER_ERROR_ = @json(session('error'));
        </script>
    @endif

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const pageWrap = document.getElementById('pageWrap');
        const sidebarToggle = document.getElementById('sidebarToggle');

        const topUserBtn = document.getElementById('topUserBtn');
        const topUserMenu = document.getElementById('topUserMenu');
        const topUserDropdown = document.getElementById('topUserDropdown');

        const demandesToggle = document.getElementById('demandesToggle');
        const demandesDropdown = document.getElementById('menuDropdownDemandes');

        const btnArrivee = document.getElementById('btnArrivee');
        const btnDepart = document.getElementById('btnDepart');

        const formArrivee = document.getElementById('form-arrivee');
        const formDepart = document.getElementById('form-depart');

        const verificationModal = document.getElementById('verificationModal');
        const successModal = document.getElementById('successModal');
        const errorModal = document.getElementById('errorModal');

        const errorMessage = document.getElementById('errorMessage');
        const okBtn = document.getElementById('okBtn');
        const closeErrorBtn = document.getElementById('closeErrorBtn');

        const latitudeArrivee = document.getElementById('latitude-arrivee');
        const longitudeArrivee = document.getElementById('longitude-arrivee');
        const latitudeDepart = document.getElementById('latitude-depart');
        const longitudeDepart = document.getElementById('longitude-depart');

        const mainMap = document.getElementById('mainMap');
        const verificationMap = document.getElementById('verificationMap');
        const successMap = document.getElementById('successMap');

        const positionText = document.getElementById('position-text');
        const positionShort = document.getElementById('position-short');
        const successTime = document.getElementById('successTime');

        let currentAction = null;

        function toggleSidebar() {
            if (pageWrap) {
                pageWrap.classList.toggle('sidebar-collapsed');
            }
        }

        function toggleUserMenu() {
            if (topUserMenu) {
                topUserMenu.classList.toggle('show');
            }
        }

        function toggleDemandesMenu() {
            if (!pageWrap || !demandesDropdown) return;
            if (pageWrap.classList.contains('sidebar-collapsed')) return;
            demandesDropdown.classList.toggle('open');
        }

        function updateMaps(lat, lng) {
            const url = `https://maps.google.com/maps?q=Drwintech,Aibatin2,Cotonou,Benin,${lat},${lng}&z=16&output=embed`;

            if (mainMap) mainMap.src = url;
            if (verificationMap) verificationMap.src = url;
            if (successMap) successMap.src = url;

            if (positionText) {
                positionText.textContent = `Latitude: ${lat.toFixed(6)}, Longitude: ${lng.toFixed(6)}`;
            }

            if (positionShort) {
                positionShort.textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
            }
        }

        function showVerificationModal() {
            if (verificationModal) verificationModal.classList.add('show');
        }

        function hideVerificationModal() {
            if (verificationModal) verificationModal.classList.remove('show');
        }

        function showSuccessModal() {
            if (successTime) {
                successTime.textContent = new Date().toLocaleTimeString('fr-FR', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            if (successModal) {
                successModal.classList.add('show');
            }
        }

        function hideSuccessModal() {
            if (successModal) successModal.classList.remove('show');
        }

        function showErrorModal(message) {
            if (errorMessage) errorMessage.textContent = message;
            if (errorModal) errorModal.classList.add('show');
        }

        function hideErrorModal() {
            if (errorModal) errorModal.classList.remove('show');
        }

        async function startPointage(action) {
            currentAction = action;

            if (!navigator.geolocation) {
                showErrorModal("La géolocalisation n'est pas supportée par votre navigateur.");
                return;
            }

            showVerificationModal();

            navigator.geolocation.getCurrentPosition(
                async function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    updateMaps(lat, lng);

                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const url = action === 'arrivee'
                        ? "{{ route('employe.pointage.arrivee') }}"
                        : "{{ route('employe.pointage.depart') }}";

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                latitude: lat,
                                longitude: lng
                            })
                        });

                        const result = await response.json();

                        hideVerificationModal();

                        if (!response.ok || !result.success) {
                            showErrorModal(result.message || "Une erreur est survenue pendant le pointage.");
                            return;
                        }

                        if (result.data && result.data.heure) {
                            successTime.textContent = result.data.heure;
                        }

                        if (action === 'arrivee') {
                            const heureArriveeValeur = document.getElementById('heure-arrivee-valeur');
                            if (heureArriveeValeur) {
                                heureArriveeValeur.textContent = result.data.heure;
                            }
                        } else {
                            const heureDepartValeur = document.getElementById('heure-depart-valeur');
                            if (heureDepartValeur) {
                                heureDepartValeur.textContent = result.data.heure;
                            }
                        }

                        const statutActuel = document.getElementById('statut-actuel');
                        if (statutActuel) {
                            statutActuel.textContent = action === 'arrivee' ? 'present' : 'termine';
                        }

                        showSuccessModal();
                    } catch (error) {
                        hideVerificationModal();
                        showErrorModal("Erreur réseau ou serveur inaccessible.");
                    }
                },
                function (error) {
                    hideVerificationModal();

                    let message = "Impossible de récupérer votre position.";
                    if (error.code === 1) {
                        message = "Vous avez refusé l'accès à la géolocalisation.";
                    } else if (error.code === 2) {
                        message = "Position indisponible.";
                    } else if (error.code === 3) {
                        message = "Temps dépassé pour récupérer votre position.";
                    }

                    showErrorModal(message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        if (topUserBtn) {
            topUserBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleUserMenu();
            });
        }

        if (demandesToggle) {
            demandesToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDemandesMenu();
            });
        }

        if (btnArrivee) {
            btnArrivee.addEventListener('click', function () {
                startPointage('arrivee');
            });
        }

        if (btnDepart) {
            btnDepart.addEventListener('click', function () {
                startPointage('depart');
            });
        }

        if (okBtn) {
            okBtn.addEventListener('click', function () {
                hideSuccessModal();
                window.location.reload();
            });
        }

        if (closeErrorBtn) {
            closeErrorBtn.addEventListener('click', hideErrorModal);
        }

        window.addEventListener('click', function (e) {
            if (topUserDropdown && !topUserDropdown.contains(e.target)) {
                topUserMenu?.classList.remove('show');
            }
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                updateMaps(position.coords.latitude, position.coords.longitude);
            });
        }

        if (window._SERVER_ERROR_) {
            showErrorModal(window._SERVER_ERROR_);
        }
    });
    </script>
</body>
</html> 