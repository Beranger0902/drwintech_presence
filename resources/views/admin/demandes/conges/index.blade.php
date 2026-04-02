<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Congés - Administration</title>
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

        .toolbar-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select,
        .search-input {
            height: 42px;
            border: 1px solid #dbe5f2;
            border-radius: 10px;
            background: #fff;
            color: #35527c;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
        }

        .search-input {
            width: 240px;
        }

        .btn-primary {
            background: #2f7de1;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: #266dca;
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

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
        }

        .summary-card-blue { border-left-color: #2f7de1; }
        .summary-card-green { border-left-color: #41b66a; }
        .summary-card-yellow { border-left-color: #e7b11d; }

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

        .table-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .table-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
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
            padding: 15px 18px;
            border-bottom: 1px solid #e4edf8;
        }

        tbody td {
            padding: 15px 18px;
            border-bottom: 1px solid #edf3fb;
            font-size: 14px;
            color: #35527c;
        }

        tbody tr:hover {
            background: rgba(47, 125, 225, 0.04);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 110px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: white;
        }

        .status-pending { background: #e7b11d; }
        .status-approuver { background: #41b66a; }
        .status-refuser { background: #2f7de1; }

        .actions-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            border: none;
            background: #edf3fb;
            color: #35527c;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .action-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .action-btn:hover {
            transform: translateY(-1px);
        }

        .action-view:hover {
            background: rgba(47, 125, 225, 0.15);
            color: #2f7de1;
        }

        .action-approuver:hover {
            background: rgba(65, 182, 106, 0.15);
            color: #2f9b55;
        }

        .action-refuser:hover {
            background: rgba(231, 177, 29, 0.15);
            color: #c89400;
        }

        .action-btn[disabled] {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .pagination-wrap {
            padding: 16px 18px;
        }

        .flash-success {
            background: #eefbf3;
            color: #2f9b55;
            border: 1px solid #cfeedd;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 16px;
            font-size: 14px;
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
            max-width: 540px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
            overflow: hidden;
        }

        .modal-header {
            padding: 16px 18px;
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
            font-size: 24px;
            color: #6d84a3;
            cursor: pointer;
        }

        .modal-body {
            padding: 18px;
        }

        .detail-list {
            display: grid;
            gap: 12px;
        }

        .detail-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 12px;
            align-items: start;
            font-size: 14px;
        }

        .detail-label {
            color: #6d84a3;
        }

        .detail-value {
            color: #1f3f6d;
            line-height: 1.5;
        }

        .modal-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 18px;
        }

        .btn-secondary {
            background: #edf3fb;
            color: #35527c;
            border: none;
            border-radius: 10px;
            padding: 12px 18px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: #dfe9f8;
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
                <a href="{{ route('admin.dashboard') }}">
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

                <div class="menu-dropdown open" id="menuDropdownDemandes">
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
                        <a href="{{ route('admin.demandes.conges.index') }}" class="active">
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
            </nav>
        </aside>

        <main class="content">
            <div class="content-header">
                <h1>Gestion des Congés</h1>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <a href="{{ route('admin.demandes.conges.index') }}">Congé</a>
                </div>
            </div>

            @if(session('success'))
                <div class="flash-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="toolbar-card">
                <div></div>

                <div class="toolbar-right">
                    <form method="GET" action="{{ route('admin.demandes.conges.index') }}" style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <select name="statut" class="filter-select">
                            <option value="">Tous les statuts</option>
                            <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                            <option value="approuve" {{ request('statut') === 'approuve' ? 'selected' : '' }}>Approuvé</option>
                            <option value="refuse" {{ request('statut') === 'refuse' ? 'selected' : '' }}>Refusé</option>
                        </select>

                        <input type="text" name="search" class="search-input" placeholder="Rechercher..." value="{{ request('search') }}">

                        <button type="submit" class="btn-primary">Filtrer</button>
                    </form>
                </div>
            </div>

            <div class="stats-grid">
                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <path d="M16 21V19A4 4 0 0 0 12 15H8A4 4 0 0 0 4 19V21"></path>
                            <circle cx="10" cy="7" r="4"></circle>
                            <path d="M20 8V14"></path>
                            <path d="M23 11H17"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Total demandes</div>
                        <div class="summary-value">{{ $totalConges }}</div>
                    </div>
                </div>
                <div class="summary-card summary-card-yellow">
                    <div class="summary-icon summary-yellow">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 7V12L15 15"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">En attente</div>
                        <div class="summary-value">{{ $congesEnAttente }}</div>
                    </div>
                </div>

                <div class="summary-card summary-card-green">
                    <div class="summary-icon summary-green">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 6L9 17L4 12"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Approuvées</div>
                        <div class="summary-value">{{ $congesApprouves }}</div>
                    </div>
                </div>

                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 6L6 18"></path>
                            <path d="M6 6L18 18"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Refusées</div>
                        <div class="summary-value">{{ $congesRefuses }}</div>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Date début</th>
                                <th>Date fin</th>
                                <th>Durée</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($conges as $congeDemande)
                                @php
                                    $statutClass = match($congeDemande->statut) {
                                        'approuver' => 'status-approuver',
                                        'refuser' => 'status-refuser',
                                        default => 'status-pending',
                                    };

                                    $statutLabel = match($congeDemande->statut) {
                                        'approuver' => 'Approuvé',
                                        'refuser' => 'Refusé',
                                        default => 'En attente',
                                    };

                                    $debut = $congeDemande->conge?->date_debut ? \Carbon\Carbon::parse($congeDemande->conge->date_debut) : null;
                                    $fin = $congeDemande->conge?->date_fin ? \Carbon\Carbon::parse($congeDemande->conge->date_fin) : null;
                                    $duree = ($debut && $fin) ? $debut->diffInDays($fin) + 1 : 0;
                                @endphp

                                <tr>
                                    <td>{{ $congeDemande->employe?->nom }} {{ $congeDemande->employe?->prenom }}</td>
                                    <td>{{ $debut ? $debut->format('d/m/Y') : '--/--/----' }}</td>
                                    <td>{{ $fin ? $fin->format('d/m/Y') : '--/--/----' }}</td>
                                    <td>{{ $duree }} jour{{ $duree > 1 ? 's' : '' }}</td>
                                    <td>{{ $congeDemande->observation ?? 'Aucun motif' }}</td>
                                    <td>
                                        <span class="status-badge {{ $statutClass }}">{{ $statutLabel }}</span>
                                    </td>
                                    <td>
                                        <div class="actions-cell">
                                            <a href="{{ route('admin.demandes.conges.index', array_merge(request()->query(), ['view' => $congeDemande->id])) }}" class="action-btn action-view">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M1 12C3.5 7 7.5 4 12 4C16.5 4 20.5 7 23 12C20.5 17 16.5 20 12 20C7.5 20 3.5 17 1 12Z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            <form method="POST" action="{{ route('admin.demandes.conges.approuver', $congeDemande->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn action-approuver" {{ $congeDemande->statut !== 'en_attente' ? 'disabled' : '' }}>
                                                    <svg viewBox="0 0 24 24">
                                                        <path d="M20 6L9 17L4 12"></path>
                                                    </svg>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('admin.demandes.conges.refuser', $congeDemande->id) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="action-btn action-refuser" {{ $congeDemande->statut !== 'en_attente' ? 'disabled' : '' }}>
                                                    <svg viewBox="0 0 24 24">
                                                        <path d="M18 6L6 18"></path>
                                                        <path d="M6 6L18 18"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Aucune demande de congé trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-wrap">
                    {{ $conges->links() }}
                </div>
            </div>
        </main>
    </div>

    @if($selectedConge && $openModal === 'view')
        @php
            $debutModal = $selectedConge->conge?->date_debut ? \Carbon\Carbon::parse($selectedConge->conge->date_debut) : null;
            $finModal = $selectedConge->conge?->date_fin ? \Carbon\Carbon::parse($selectedConge->conge->date_fin) : null;
            $dureeModal = ($debutModal && $finModal) ? $debutModal->diffInDays($finModal) + 1 : 0;
        @endphp

        <div class="modal-overlay show" id="viewModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h2>Détail Congé</h2>
                    <a href="{{ route('admin.demandes.conges.index', request()->except(['view'])) }}" class="close-modal-btn" style="text-decoration:none;">&times;</a>
                </div>

                <div class="modal-body">
                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-label">Employé</div>
                            <div class="detail-value">{{ $selectedConge->employe?->nom }} {{ $selectedConge->employe?->prenom }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $selectedConge->employe?->user?->email }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date début</div>
                            <div class="detail-value">{{ $debutModal ? $debutModal->format('d/m/Y') : '--/--/----' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date fin</div>
                            <div class="detail-value">{{ $finModal ? $finModal->format('d/m/Y') : '--/--/----' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Durée</div>
                            <div class="detail-value">{{ $dureeModal }} jour{{ $dureeModal > 1 ? 's' : '' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Motif</div>
                            <div class="detail-value">{{ $selectedConge->observation ?? 'Aucun motif' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Statut</div>
                            <div class="detail-value">
                                {{ $selectedConge->statut === 'approuver' ? 'Approuvé' : ($selectedConge->statut === 'refuser' ? 'Refusé' : 'En attente') }}
                            </div>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <a href="{{ route('admin.demandes.conges.index', request()->except(['view'])) }}" class="btn-secondary">Fermer</a>
                    </div>
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
            const demandesToggle = document.getElementById('demandesToggle');
            const demandesDropdown = document.getElementById('menuDropdownDemandes');

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