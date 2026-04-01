
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demandes de congé</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

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

        .page-wrap.sidebar-collapsed { grid-template-columns: 78px 1fr; }

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
            padding: 8px 12px;
            background: white;
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

        .welcome-title { font-size: 22px; font-weight: normal; }

        .top-user-dropdown { position: relative; }

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

        .top-user-arrow { font-size: 18px; color: #35527c; }

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

        .top-user-menu.show { display: block; }

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
        .top-user-menu button:hover { background: #eef4fc; }

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

        .sidebar-toggle:hover { background: #e4edf8; }

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
            transition: all 0.25s ease;
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

        .menu a.active { border-left: 4px solid #5b98ee; }

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

        .menu-text { transition: opacity 0.2s ease; }

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

        .menu-dropdown.open .menu-submenu { display: flex; }

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

        .content-page-header {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 16px;
        }

        .page-title-row {
            padding: 18px 22px;
            border-bottom: 1px solid #e3ebf5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .page-title-row h1 {
            font-size: 22px;
            font-weight: bold;
            color: #1f3f6d;
            margin: 0;
        }

        .add-btn {
            border: none;
            background: #2f7de1;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-btn:hover { background: #256dcb; }

        .page-breadcrumb-row {
            padding: 14px 22px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: #7b8da8;
        }

        .page-breadcrumb-row a {
            text-decoration: none;
            color: #7b8da8;
        }

        .page-breadcrumb-row .active-breadcrumb { color: #4f6f97; }

        .table-card {
            background: white;
            border: 1px solid #dbe5f2;
            border-radius: 14px;
            padding: 18px;
        }

        .table-wrapper { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        thead th {
            background: #eef3fb;
            color: #35527c;
            font-size: 15px;
            font-weight: bold;
            padding: 14px 12px;
            text-align: left;
            border-bottom: 1px solid #d9e4f2;
        }

        tbody td {
            padding: 14px 12px;
            border-bottom: 1px solid #e8eef7;
            font-size: 14px;
            color: #35527c;
        }

        tbody tr:nth-child(even) { background: #fafcff; }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 7px;
            font-size: 13px;
            font-weight: bold;
            color: white;
        }

        .badge-attente { background: #f0b429; color: #3a2d00; }
        .badge-approuve { background: #24b36b; }
        .badge-refuse { background: #e05a47; }

        .action-link {
            color: #2f7de1;
            text-decoration: none;
            font-weight: bold;
        }

        .pagination-wrapper {
            margin-top: 18px;
            display: flex;
            justify-content: center;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(28, 65, 120, 0.45);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 3000;
            padding: 12px;
        }

        .modal-overlay.show { display: flex; }

        .modal-box {
            width: 100%;
            max-width: 560px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 18px 50px rgba(0,0,0,0.18);
            overflow: hidden;
        }

        .modal-header {
            padding: 14px 18px;
            border-bottom: 1px solid #e6edf7;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body {
            padding: 18px;
        }
        .modal-header h2 {
            font-size: 22px;
            color: #1f3f6d;
            margin: 0;
        }

        .close-modal-btn {
            background: transparent;
            border: none;
            font-size: 24px;
            color: #6d84a3;
            cursor: pointer;
        }

        .modal-body { padding: 22px; }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 12px;
        }

        .form-group.full { grid-column: 1 / -1; }

        .form-group label {
            font-size: 14px;
            color: #35527c;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            border: 1px solid #cfdcec;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            color: #35527c;
            outline: none;
            background: white;
        }

        .form-group input,
        .form-group select {
            height: 42px;
        }
        .form-group textarea {
            min-height: 90px;
            resize: vertical;
        }

        .status-pending {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #e7b11d;
            color: white;
        }

        .status-approuver {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #41b66a;
            color: white;
        }

        .status-refuser {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #2f7de1;
            color: white;
        }

        .submit-btn {
            border: none;
            background: #2fa13b;
            color: white;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            width: 220px;
        }

        .submit-btn:hover { background: #278832; }

        .success-message {
            background: #e9f9ef;
            border: 1px solid #b9ebc8;
            color: #208a4a;
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .error-list {
            background: #fff1f0;
            border: 1px solid #f1b9b4;
            color: #c0392b;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        @media (max-width: 900px) {
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page-wrap" id="pageWrap">
        <div class="sidebar-top">
            <img src="{{ asset('Images/drwintech-logo.jpeg') }}" alt="DrwinTech" class="company-logo">
        </div>

        <div class="topbar">
            <div class="welcome-title"></div>

            <div class="top-user-dropdown" id="topUserDropdown">
                <button type="button" class="top-user-btn" id="topUserBtn">
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
                    <span class="menu-text">Accueil</span>
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

                <div class="menu-dropdown open" id="menuDropdownDemandes">
                    <button type="button" class="menu-dropdown-toggle active" id="demandesToggle">
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
                        <a href="{{ route('employe.demandes.conges.index') }}" class="active">
                            <span class="menu-text">Congé</span>
                        </a>

                        <a href="{{ route('employe.demandes.permissions.index') }}">
                            <span class="menu-text">Permission</span>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        <main class="content">
            <div class="content-page-header">
                <div class="page-title-row">
                    <h1>Mes demandes de congé</h1>
                    <button type="button" class="add-btn" id="openCongeModal">Faire une demande</button>
                </div>

                <div class="page-breadcrumb-row">
                    <a href="{{ route('employe.dashboard') }}">Accueil</a>
                    <span>/</span>
                    <a href="{{ route('employe.demandes.conges.index') }}" class="active-breadcrumb">Congé</a>
                </div>
            </div>

            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="error-list">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Type de congé</th>
                                <th>Période</th>
                                <th>Jours</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($demandesConge as $demande)
                                <tr>
                                    <td>{{ $demande->conge?->type_conge ?? '-' }}</td>
                                    <td>
                                        {{ $demande->conge?->date_debut ? \Carbon\Carbon::parse($demande->conge->date_debut)->format('d/m/Y') : '-' }}
    -
                                        {{ $demande->conge?->date_fin ? \Carbon\Carbon::parse($demande->conge->date_fin)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td>
                                        @if($demande->conge?->date_debut && $demande->conge?->date_fin)
                                            {{ \Carbon\Carbon::parse($demande->conge->date_debut)->diffInDays(\Carbon\Carbon::parse($demande->conge->date_fin)) + 1 }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                                                        
                                    
                                   <td>
                                        @php
                                            $classeStatut = match($demande->statut) {
                                                'approuver' => 'status-approuver',
                                                'refuser' => 'status-refuser',
                                                default => 'status-pending',
                                            };

                                            $libelleStatut = match($demande->statut) {
                                                'approuver' => 'Approuvé',
                                                'refuser' => 'Refusé',
                                                default => 'En attente',
                                            };
                                        @endphp

                                        <span class="{{ $classeStatut }}">{{ $libelleStatut }}</span>
                                    </td>
                                                                        
                                    
                                    <td>
                                        @if ($demande->conge && $demande->conge->piece_jointe)
                                            <a class="action-link" href="{{ asset('storage/' . $demande->conge->piece_jointe) }}" target="_blank">
                                                Voir la pièce
                                            </a>
                                        @else
                                            <span style="color:#7b8da8;">Aucune pièce</span>
                                        @endif
                                    </td>
                                
                                
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align:center;">Aucune demande de congé.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            <div class="pagination-wrapper">
                    {{ $demandesConge->links() }}
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay {{ $errors->any() ? 'show' : '' }}" id="congeModal">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Nouvelle demande de congé</h2>
                <button type="button" class="close-modal-btn" id="closeCongeModal">&times;</button>
            </div>

            <div class="modal-body">
                <form method="POST" action="{{ route('employe.demandes.conges.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <label for="type_conge">Type de congé</label>
                        <select name="type_conge" id="type_conge" required>
                            <option value="">Sélectionner le type </option>
                            <option value="Congé annuel" {{ old('type_conge') === 'Congé annuel' ? 'selected' : '' }}>Congé annuel</option>
                            <option value="Congé maladie" {{ old('type_conge') === 'Congé maladie' ? 'selected' : '' }}>Congé maladie</option>
                            <option value="Congé sans solde" {{ old('type_conge') === 'Congé sans solde' ? 'selected' : '' }}>Congé sans solde</option>
                        </select>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="date_debut">Date de début</label>
                            <input type="date" name="date_debut" id="date_debut" value="{{ old('date_debut') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="date_fin">Date de fin</label>
                            <input type="date" name="date_fin" id="date_fin" value="{{ old('date_fin') }}" required>
                        </div>
                    </div>

                    <div class="form-group full">
                        <label for="observation">Commentaire</label>
                        <textarea name="observation" id="observation" placeholder="Ajouter un commentaire...">{{ old('observation') }}</textarea>
                    </div>

                    <div class="form-group full">
                        <label for="piece_jointe">Pièce jointe</label>
                        <input type="file" name="piece_jointe" id="piece_jointe">
                    </div>

                    <button type="submit" class="submit-btn">Soumettre la demande</button>
                </form>
            </div>
        </div>
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

            const congeModal = document.getElementById('congeModal');
            const openCongeModal = document.getElementById('openCongeModal');
            const closeCongeModal = document.getElementById('closeCongeModal');

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

            if (openCongeModal) {
                openCongeModal.addEventListener('click', function () {
                    congeModal.classList.add('show');
                });
            }

            if (closeCongeModal) {
                closeCongeModal.addEventListener('click', function () {
                    congeModal.classList.remove('show');
                });
            }

            if (congeModal) {
                congeModal.addEventListener('click', function (e) {
                    if (e.target === congeModal) {
                        congeModal.classList.remove('show');
                    }
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