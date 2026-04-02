<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Administration</title>
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
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 16px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-label {
            font-size: 14px;
            color: #6d84a3;
        }

        .filter-input,
        .filter-select {
            height: 42px;
            border: 1px solid #dbe5f2;
            border-radius: 10px;
            background: #fff;
            color: #35527c;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            min-width: 170px;
        }

        .btn-primary {
            background: #2f7de1;
            color: white;
            border: none;
            border-radius: 10px;
            padding: 12px 20px;
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
        .summary-card-red { border-left-color: #e56a6a; }

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
        .summary-red { background: #e56a6a; }

        .summary-title {
            font-size: 15px;
            color: #35527c;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 18px;
            color: #1f3f6d;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 16px;
        }

        .chart-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            box-shadow: 0 3px 10px rgba(29, 67, 112, 0.05);
            padding: 18px;
            min-height: 320px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .chart-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.10);
        }

        .chart-title {
            font-size: 16px;
            color: #1f3f6d;
            margin-bottom: 14px;
        }

        .chart-wrap {
            position: relative;
            width: 100%;
            height: 240px;
        }

        .legend-list {
            margin-top: 18px;
            display: grid;
            gap: 10px;
        }

        .legend-item {
            display: grid;
            grid-template-columns: 18px 1fr auto;
            gap: 10px;
            align-items: center;
            font-size: 14px;
            color: #35527c;
        }

        .legend-color {
            width: 18px;
            height: 18px;
            border-radius: 4px;
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

        @media (max-width: 1450px) {
            .page-wrap {
                width: calc(100vw - 20px);
                margin: 10px;
            }
        }

        @media (max-width: 1100px) {
            .stats-grid,
            .charts-grid {
                grid-template-columns: 1fr;
            }

            .toolbar-card {
                justify-content: flex-start;
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

                <div class="menu-dropdown {{ request()->routeIs('admin.demandes.*') ? 'open' : '' }}" id="menuDropdownDemandes">
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
                        <a href="{{ route('admin.demandes.conges.index') }}">
                            <span class="menu-text">Congé</span>
                        </a>
                        <a href="{{ route('admin.demandes.permissions.index') }}">
                            <span class="menu-text">Permission</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.statistiques.index') }}" class="active">
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
                <h1>Statistiques</h1>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <a href="{{ route('admin.statistiques.index') }}">Statistiques</a>
                </div>
            </div>

            

            @if(session('success'))
                <div class="flash-success">
                    {{ session('success') }}
                </div>
            @endif



            <form method="GET" action="{{ route('admin.statistiques.index') }}" class="toolbar-card">
                <div class="filter-group">
                    <span class="filter-label">Période</span>
                    <input type="date" name="date_debut" class="filter-input" value="{{ $dateDebut->format('Y-m-d') }}">
                    <input type="date" name="date_fin" class="filter-input" value="{{ $dateFin->format('Y-m-d') }}">
                </div>
                <div class="filter-group">
                    <span class="filter-label">Département</span>
                    <select name="departement" class="filter-select">
                        <option value="">Tous les départements</option>
                        @foreach($departements as $item)
                            <option value="{{ $item }}" {{ $departement === $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary">Filtrer</button>
            </form>


 
 
            <div class="stats-grid">
                <div class="summary-card summary-card-blue">
                    <div class="summary-icon summary-blue">
                        <svg viewBox="0 0 24 24">
                            <circle cx="8" cy="8" r="3"></circle>
                            <circle cx="16" cy="8" r="3"></circle>
                            <path d="M2 19C2 15.5 5 13.5 8 13.5C11 13.5 14 15.5 14 19"></path>
                            <path d="M10 19C10 16.5 13 15 16 15C19 15 22 16.5 22 19"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Total Utilisateurs</div>
                        <div class="summary-value">{{ $totalUtilisateurs }}</div>
                    </div>
                </div>



                <div class="summary-card summary-card-green">
                    <div class="summary-icon summary-green">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 6H16"></path>
                            <path d="M8 10H16"></path>
                            <path d="M8 14H12"></path>
                            <path d="M6 3H18A2 2 0 0 1 20 5V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19V5A2 2 0 0 1 6 3Z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Activités récentes</div>
                        <div class="summary-value">{{ $totalActivitesRecentes }}</div>
                    </div>
                </div>



                <div class="summary-card summary-card-yellow">
                    <div class="summary-icon summary-yellow">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 2V6"></path>
                            <path d="M16 2V6"></path>
                            <path d="M3 10H21"></path>
                            <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                            <path d="M8 14H8.01"></path>
                            <path d="M12 14H12.01"></path>
                            <path d="M16 14H16.01"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Congés</div>
                        <div class="summary-value">{{ $totalConges }}</div>
                    </div>
                </div>



                <div class="summary-card summary-card-red">
                    <div class="summary-icon summary-red">
                        <svg viewBox="0 0 24 24">
                            <path d="M8 2V6"></path>
                            <path d="M16 2V6"></path>
                            <path d="M3 10H21"></path>
                            <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                            <path d="M9 15H15"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="summary-title">Permissions</div>
                        <div class="summary-value">{{ $totalPermissions }}</div>
                    </div>
                </div>
            </div>



            <div class="charts-grid">
                <div class="chart-card">
                    <div class="chart-title">Activités récentes par jour</div>
                    <div class="chart-wrap">
                        <canvas id="activitiesChart"></canvas>
                    </div>
                </div>



                <div class="chart-card">
                    <div class="chart-title">Répartition des demandes</div>
                    <div class="chart-wrap">
                        <canvas id="requestsChart"></canvas>
                    </div>



                    <div class="legend-list">
                        @php
                            $legendColors = [
                                '#41b66a', // Congés approuvés
                                '#e7b11d', // Congés en attente
                                '#e56a6a', // Congés refusés
                                '#2f7de1', // Permissions approuvées
                                '#f29e4c', // Permissions en attente
                                '#8ba3c7', // Permissions refusées
                            ];
                        @endphp

                        @foreach($repartitionDemandes['labels'] as $index => $label)
                            <div class="legend-item">
                                <span class="legend-color" style="background: {{ $legendColors[$index] }}"></span>
                                <span>{{ $label }}</span>
                                <strong>{{ $repartitionDemandes['values'][$index] }}</strong>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
    // Script pour gérer les interactions du menu et l'affichage de graphiques avec Chart.js, incluant le toggle de la sidebar, l'affichage du menu utilisateur et la génération des graphiques en fonction des données fournies pour une expérience utilisateur fluide et une visualisation efficace des statistiques au sein de l'interface d'administration
        document.addEventListener('DOMContentLoaded', function () {
            const pageWrap = document.getElementById('pageWrap');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const topUserBtn = document.getElementById('topUserBtn');
            const topUserMenu = document.getElementById('topUserMenu');
            const topUserDropdown = document.getElementById('topUserDropdown');
            const demandesToggle = document.getElementById('demandesToggle');
            const demandesDropdown = document.getElementById('menuDropdownDemandes');

// Toggle de la sidebar pour reduire ou aggradir le munu latéral, ajustant automatiquement l'affichage des élements du menu 
// pour une expérience utilisateur optimale, et permettant également d'afficher ou cacher le sous-menu des demandes en fonction de l'état de la sidebar pour éviter les interactions complexes dans un espace réduit
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


// Initialisation des graphique Chart.js pour afficher les activités récentd par jour et la répartition des éléments de données fournies pour une visualisation claire et efficace des statistiques au sein de l'interface d'administration, offrant ainsi aux administrateurs une analyse approfondie des données de l'application à travers des graphiques interactifs et esthétiques
// Le graphique des activités récentes par jour est un graphique à barres qui affiche les différentes catégories d'activités (congés, permissions, ajouts utilisateurs) avec des couleurs distinctes pour une meilleure différenciation visuelle, 
// Tandis que le graphique de répartition des demandes est un graphique à secteurs qui montre la distribution des différents types de demandes (congés approuvés, en attente, refusés, etc.) avec une légende détaillée pour une compréhension approfondie des données
            const activitiesChart = document.getElementById('activitiesChart');
            if (activitiesChart) {
                new Chart(activitiesChart, {
                    type: 'bar',
                    data: {
                        labels: @json($labels),
                        datasets: [
                            {
                                label: 'Congés',
                                data: @json($serieConges),
                                backgroundColor: '#2f7de1',
                                borderRadius: 6
                            },
                            {
                                label: 'Permissions',
                                data: @json($seriePermissions),
                                backgroundColor: '#e7b11d',
                                borderRadius: 6
                            },
                            {
                                label: 'Ajouts utilisateurs',
                                data: @json($serieAjouts),
                                backgroundColor: '#41b66a',
                                borderRadius: 6
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // Graphique de répartition des demandes avec un graphique à secteurs pour visualiser la distribution des différents types de demandes, utilisant des couleurs distinctes pour chaque catégorie et une légende détaillée pour une compréhension approfondie des données, offrant ainsi une analyse visuelle claire des tendances et des répartitions au sein de l'entreprise

            const requestsChart = document.getElementById('requestsChart');
            if (requestsChart) {
                new Chart(requestsChart, {
                    type: 'doughnut',
                    data: {
                        labels: @json($repartitionDemandes['labels']),
                        datasets: [{
                            data: @json($repartitionDemandes['values']),
                            backgroundColor: [
                                '#41b66a',
                                '#e7b11d',
                                '#e56a6a',
                                '#2f7de1',
                                '#f29e4c',
                                '#8ba3c7'
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '55%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>