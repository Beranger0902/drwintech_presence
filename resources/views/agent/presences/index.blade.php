<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présences - Agent d’accueil</title>
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
            margin-bottom: 16px;
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

        .toolbar-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 16px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            padding: 18px;
            margin-bottom: 18px;
        }

        .toolbar-title {
            font-size: 16px;
            color: #35527c;
            margin-bottom: 16px;
        }

        .toolbar-form {
            display: grid;
            grid-template-columns: 1.35fr 1fr 0.85fr auto;
            gap: 16px;
            align-items: center;
        }

        .filter-field {
            position: relative;
        }

        .filter-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #5f7da5;
            pointer-events: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .filter-icon svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .filter-range-wrap {
            display: grid;
            grid-template-columns: 1fr auto 1fr auto;
            gap: 10px;
            align-items: center;
        }

        .filter-range-wrap .separator-arrow {
            color: #6f87a5;
            font-size: 22px;
            text-align: center;
        }

        .filter-range-wrap .dropdown-arrow {
            color: #6f87a5;
            font-size: 16px;
            margin-left: -30px;
            pointer-events: none;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            height: 48px;
            border: 1px solid #dbe5f2;
            border-radius: 12px;
            background: white;
            color: #35527c;
            padding: 0 14px 0 42px;
            font-size: 14px;
            outline: none;
            appearance: none;
        }

        .filter-select.simple {
            padding-left: 14px;
        }

        .btn-filter {
            height: 48px;
            min-width: 112px;
            border: none;
            border-radius: 12px;
            background: #2f7de1;
            color: white;
            font-size: 14px;
            cursor: pointer;
            padding: 0 22px;
        }

        .btn-filter:hover {
            background: #266dca;
        }

        .table-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 16px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            overflow: hidden;
        }

        .table-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px 10px;
        }

        .table-card-title {
            font-size: 18px;
            color: #1f3f6d;
        }

        .search-box {
            position: relative;
            width: 340px;
            max-width: 100%;
        }

        .search-box input {
            width: 100%;
            height: 46px;
            border: 1px solid #dbe5f2;
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            color: #35527c;
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
            min-width: 108px;
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

        .action-btn {
            min-width: 98px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid #dbe5f2;
            background: white;
            color: #2f6fce;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background: #eef4fc;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .pagination-zone {
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            color: #5f7da5;
        }

        .pagination-zone .pagination-info {
            font-size: 14px;
        }

        .pagination-zone .pagination-links {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pagination-zone .pagination-links svg {
            width: 18px;
            height: 18px;
        }

        .pagination-zone .pagination-links nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pagination-zone .pagination-links .page-item,
        .pagination-zone .pagination-links .page-link,
        .pagination-zone .pagination-links span,
        .pagination-zone .pagination-links a {
            min-width: 38px;
            height: 38px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            border: 1px solid #dbe5f2;
            color: #5f7da5;
            background: white;
            font-size: 14px;
            padding: 0 10px;
        }

        .pagination-zone .pagination-links .active span,
        .pagination-zone .pagination-links span[aria-current="page"] {
            background: #2f7de1;
            color: white;
            border-color: #2f7de1;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 65, 120, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            padding: 16px;
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 100%;
            max-width: 620px;
            background: white;
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
            overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px;
            border-bottom: 1px solid #e6edf7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-header h2 {
            font-size: 22px;
            color: #1f3f6d;
            font-weight: normal;
        }

        .close-modal-btn {
            background: transparent;
            border: none;
            font-size: 28px;
            color: #6d84a3;
            cursor: pointer;
            text-decoration: none;
            line-height: 1;
        }

        .modal-body {
            padding: 20px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 14px 18px;
            margin-bottom: 18px;
        }

        .detail-label {
            color: #6d84a3;
            font-size: 14px;
        }

        .detail-value {
            color: #1f3f6d;
            font-size: 14px;
        }

        .modal-footer {
            padding: 0 20px 20px;
            display: flex;
            justify-content: flex-end;
        }

        .btn-secondary {
            height: 46px;
            padding: 0 20px;
            border-radius: 12px;
            border: none;
            background: #2f7de1;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background: #266dca;
        }

        @media (max-width: 1450px) {
            .page-wrap {
                width: calc(100vw - 20px);
                margin: 10px;
            }
        }

        @media (max-width: 1180px) {
            .toolbar-form {
                grid-template-columns: 1fr;
            }

            .table-card-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
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
                <a href="{{ route('agent.dashboard') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M3 10.5L12 3L21 10.5"></path>
                            <path d="M5 9.5V21H19V9.5"></path>
                        </svg>
                    </span>
                    <span class="menu-text">Tableau de bord</span>
                </a>

                <a href="{{ route('agent.presences.index') }}" class="active">
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

                <a href="{{ route('agent.temps-travail.index') }}">
                    <span class="menu-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 8V12L15 15"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </span>
                    <span class="menu-text">Temps de travail</span>
                </a>

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
                <div class="content-title">Présences</div>
                <div class="breadcrumb">
                    <a href="{{ route('agent.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <span>Présences</span>
                </div>
            </div>

            <form method="GET" action="{{ route('agent.presences.index') }}" class="toolbar-card">
                <div class="toolbar-title">Enregistrements: {{ $totalPresences }} résultats</div>

                <div class="toolbar-form">
                    <div class="filter-field">
                        <span class="filter-icon">
                            <svg viewBox="0 0 24 24">
                                <path d="M8 2V6"></path>
                                <path d="M16 2V6"></path>
                                <path d="M3 10H21"></path>
                                <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                            </svg>
                        </span>

                        <div class="filter-range-wrap">
                            <input type="date" name="date_debut" class="filter-input" value="{{ $dateDebut->format('Y-m-d') }}">
                            <span class="separator-arrow">→</span>
                            <input type="date" name="date_fin" class="filter-input" value="{{ $dateFin->format('Y-m-d') }}">
                            <span class="dropdown-arrow">▾</span>
                        </div>
                    </div>

                    <div class="filter-field">
                        <span class="filter-icon">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="8" r="4"></circle>
                                <path d="M4 20C4 16.5 7.5 14 12 14C16.5 14 20 16.5 20 20"></path>
                            </svg>
                        </span>
                        <select name="departement" class="filter-select">
                            <option value="">Tous les départements</option>
                            @foreach($departements as $item)
                                <option value="{{ $item }}" {{ $departement === $item ? 'selected' : '' }}>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <select name="statut" class="filter-select simple">
                            <option value="">Tous</option>
                            <option value="present" {{ $statut === 'present' ? 'selected' : '' }}>Présent</option>
                            <option value="termine" {{ $statut === 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="retard" {{ $statut === 'retard' ? 'selected' : '' }}>En retard</option>
                            <option value="absent" {{ $statut === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="absent_justifie" {{ $statut === 'absent_justifie' ? 'selected' : '' }}>Absence justifiée</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">Filtrer</button>
                </div>
            </form>

            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-card-title">Liste des présences</div>

                    <form method="GET" action="{{ route('agent.presences.index') }}" class="search-box">
                        <input type="hidden" name="date_debut" value="{{ $dateDebut->format('Y-m-d') }}">
                        <input type="hidden" name="date_fin" value="{{ $dateFin->format('Y-m-d') }}">
                        <input type="hidden" name="departement" value="{{ $departement }}">
                        <input type="hidden" name="statut" value="{{ $statut }}">
                        <input type="text" name="search" placeholder="Rechercher" value="{{ $search }}">
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Date</th>
                                <th>Heure arrivée</th>
                                <th>Heure départ</th>
                                <th>Statut</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($presences as $presence)
                                @php
                                    $nom = trim(($presence->employe?->prenom ?? '') . ' ' . ($presence->employe?->nom ?? ''));
                                    $avatarName = urlencode($nom ?: 'Employe');

                                     $badgeClass = match($presence->statut_pointage) {
                                        'retard' => 'badge-retard',
                                        'absent' => 'badge-absent',
                                        'absent_justifie' => 'badge-justify',
                                        'conge' => 'badge-conge',
                                        'ferie' => 'badge-ferie',
                                        'weekend' => 'badge-weekend',
                                        'termine', 'present' => 'badge-present',
                                        default => 'badge-neutral',
                                    };

                                    $badgeLabel = match($presence->statut_pointage) {
                                        'retard' => 'En retard',
                                        'absent' => 'Absent',
                                        'absent_justifie' => 'Permission',
                                        'conge' => 'En congé',
                                        'ferie' => 'Jour férié',
                                        'weekend' => 'Week-end',
                                        'termine', 'present' => 'Présent',
                                        default => ucfirst(str_replace('_', ' ', $presence->statut_pointage ?? 'Inconnu')),
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
                                    <td>{{ \Carbon\Carbon::parse($presence->date_presence)->format('d/m/Y') }}</td>
                                    <td>{{ $presence->heure_arrivee ? \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') : '-' }}</td>
                                    <td>{{ $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') : '' }}</td>
                                    <td>
                                        <span class="badge-status {{ $badgeClass }}">{{ $badgeLabel }}</span>
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('agent.presences.index', array_merge(request()->query(), ['view' => $presence->id])) }}"
                                            class="action-btn"
                                        >
                                            <svg viewBox="0 0 24 24">
                                                <path d="M1 12C3.5 7 7.5 4 12 4C16.5 4 20.5 7 23 12C20.5 17 16.5 20 12 20C7.5 20 3.5 17 1 12Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            Voir
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Aucune présence trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-zone">
                    <div class="pagination-info">
                        Affichage de {{ $presences->firstItem() ?? 0 }} à {{ $presences->lastItem() ?? 0 }} sur {{ $presences->total() }} entrées
                    </div>

                    <div class="pagination-links">
                        {{ $presences->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if($selectedPresence && $openModal === 'view')
        @php
             $selectedBadgeClass = match($selectedPresence->statut_pointage) {
                'retard' => 'badge-retard',
                'absent' => 'badge-absent',
                'absent_justifie' => 'badge-justify',
                'conge' => 'badge-conge',
                'ferie' => 'badge-ferie',
                'weekend' => 'badge-weekend',
                'termine', 'present' => 'badge-present',
                default => 'badge-neutral',
            };

            $selectedBadgeLabel = match($selectedPresence->statut_pointage) {
                'retard' => 'En retard',
                'absent' => 'Absent',
                'absent_justifie' => 'Permission',
                'conge' => 'En congé',
                'ferie' => 'Jour férié',
                'weekend' => 'Week-end',
                'termine', 'present' => 'Présent',
                default => ucfirst(str_replace('_', ' ', $selectedPresence->statut_pointage ?? 'Inconnu')),
            };
        @endphp

        <div class="modal-overlay show" id="viewPresenceModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h2>Détail de la présence</h2>
                    <a href="{{ route('agent.presences.index', request()->except(['view'])) }}" class="close-modal-btn">&times;</a>
                </div>

                <div class="modal-body">
                    <div class="detail-grid">
                        <div class="detail-label">Employé</div>
                        <div class="detail-value">
                            {{ $selectedPresence->employe?->prenom ?? '' }} {{ $selectedPresence->employe?->nom ?? '' }}
                        </div>

                        <div class="detail-label">Matricule</div>
                        <div class="detail-value">{{ $selectedPresence->employe?->matricule ?? '-' }}</div>

                        <div class="detail-label">Département</div>
                        <div class="detail-value">{{ $selectedPresence->employe?->departement ?? '-' }}</div>

                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ \Carbon\Carbon::parse($selectedPresence->date_presence)->format('d/m/Y') }}</div>

                        <div class="detail-label">Heure arrivée</div>
                        <div class="detail-value">
                            {{ $selectedPresence->heure_arrivee ? \Carbon\Carbon::parse($selectedPresence->heure_arrivee)->format('H:i:s') : '-' }}
                        </div>

                        <div class="detail-label">Heure départ</div>
                        <div class="detail-value">
                            {{ $selectedPresence->heure_depart ? \Carbon\Carbon::parse($selectedPresence->heure_depart)->format('H:i:s') : '-' }}
                        </div>

                        <div class="detail-label">Statut</div>
                        <div class="detail-value">
                            <span class="badge-status {{ $selectedBadgeClass }}">{{ $selectedBadgeLabel }}</span>
                        </div>

                        <div class="detail-label">Latitude arrivée</div>
                        <div class="detail-value">{{ $selectedPresence->latitude_arrivee ?? '-' }}</div>

                        <div class="detail-label">Longitude arrivée</div>
                        <div class="detail-value">{{ $selectedPresence->longitude_arrivee ?? '-' }}</div>

                        <div class="detail-label">Latitude départ</div>
                        <div class="detail-value">{{ $selectedPresence->latitude_depart ?? '-' }}</div>

                        <div class="detail-label">Longitude départ</div>
                        <div class="detail-value">{{ $selectedPresence->longitude_depart ?? '-' }}</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{ route('agent.presences.index', request()->except(['view'])) }}" class="btn-secondary">Fermer</a>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pageWrap = document.getElementById('pageWrap');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const topUserBtn = document.getElementById('topUserBtn');
            const topUserMenu = document.getElementById('topUserMenu');
            const topUserDropdown = document.getElementById('topUserDropdown');

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