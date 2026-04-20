
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temps de travail - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
   
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --bg-page: #dfe8f5;
            --bg-panel: #edf3fb;
            --white: #ffffff;
            --border: #dbe5f2;
            --border-soft: #e8eef7;
            --text-main: #35527c;
            --text-dark: #1f3f6d;
            --text-soft: #6d84a3;
            --blue: #2f7de1;
            --blue-soft: rgba(91, 152, 238, 0.20);
            --yellow: #eab14b;
            --green: #43ad77;
            --shadow: 0 8px 24px rgba(36, 74, 124, 0.08);
            --shadow-card: 0 3px 10px rgba(29, 67, 112, 0.05);
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
            background: var(--bg-panel);
            padding: 18px;
        }

        .content-header {
            margin-bottom: 16px;
            animation: fadeUp 0.45s ease;
        }

        .content-title {
            font-size: 24px;
            color: var(--text-dark);
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
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            padding: 18px;
            margin-bottom: 18px;
            animation: fadeUp 0.5s ease 0.06s both;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .toolbar-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.08);
        }

        .toolbar-title {
            font-size: 16px;
            color: var(--text-main);
            margin-bottom: 16px;
        }

        .toolbar-form {
            display: grid;
            grid-template-columns: 1.25fr 1fr auto;
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

        .separator-arrow {
            color: #6f87a5;
            font-size: 22px;
            text-align: center;
        }

        .dropdown-arrow {
            color: #6f87a5;
            font-size: 16px;
            margin-left: -30px;
            pointer-events: none;
        }

        .filter-input,
        .filter-select {
            width: 100%;
            height: 48px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--white);
            color: var(--text-main);
            padding: 0 14px 0 42px;
            font-size: 14px;
            outline: none;
            appearance: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .filter-select.simple {
            padding-left: 42px;
        }

        .filter-input:focus,
        .filter-select:focus,
        .search-box input:focus {
            border-color: #9bbcf0;
            box-shadow: 0 0 0 3px rgba(47, 125, 225, 0.08);
        }

        .btn-filter {
            height: 48px;
            min-width: 112px;
            border: none;
            border-radius: 12px;
            background: var(--blue);
            color: var(--white);
            font-size: 14px;
            cursor: pointer;
            padding: 0 22px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-filter:hover {
            background: #266dca;
            transform: translateY(-1px);
        }

        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
            animation: fadeUp 0.55s ease 0.12s both;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .table-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.08);
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
            color: var(--text-dark);
        }

        .search-box {
            position: relative;
            width: 340px;
            max-width: 100%;
        }

        .search-box input {
            width: 100%;
            height: 46px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            color: var(--text-main);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
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
            color: var(--text-main);
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
            color: var(--text-main);
            vertical-align: middle;
        }

        tbody tr {
            transition: background 0.22s ease, transform 0.22s ease;
        }

        tbody tr:hover {
            background: rgba(47, 125, 225, 0.03);
            transform: scale(1.002);
        }

        .employee-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }    

        .employee-avatar {
            width: 48px;
            height: 48px;
            font-size: 16px;
        }
        .employee-name {
            line-height: 1.15;
        }

        .supp-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 74px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
            background: #f4c56c;
            color: #594107;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .supp-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .summary-badge {
            padding: 8px 14px;
            font-size: 13px;
            border-radius: 999px;
        }

        .action-btn {
            min-width: 98px;
            height: 42px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--white);
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
            transform: translateY(-1px);
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
            border: 1px solid var(--border);
            color: #5f7da5;
            background: var(--white);
            font-size: 14px;
            padding: 0 10px;
        }

        .pagination-zone .pagination-links .active span,
        .pagination-zone .pagination-links span[aria-current="page"] {
            background: var(--blue);
            color: var(--white);
            border-color: var(--blue);
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
            backdrop-filter: blur(2px);
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn 0.25s ease;
        }


        .modal-box {
            width: 100%;
            max-width: 700px; /* 🔥 réduit fortement */
            max-height: 85vh;
            background: white;
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            animation: modalPop 0.25s ease;
        }

        .modal-body-scroll {
            overflow-y: auto;
            max-height: calc(85vh - 70px);
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
            color: var(--text-dark);
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
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .close-modal-btn:hover {
            color: var(--text-main);
            transform: rotate(90deg);
        }

        .modal-employee {
            padding: 18px 20px 12px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .modal-employee-avatar {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 1px solid #d9e4f1;
            object-fit: cover;
        }

        .modal-employee-name {
            font-size: 18px;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .modal-employee-period {
            font-size: 14px;
            color: var(--text-soft);
        }

        .modal-body {
            padding: 16px 18px;
        }

        .modal-table-wrap {
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .modal-body table th,
        .modal-body table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .modal-summary {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            padding: 14px 0 4px;
        }

        .summary-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            padding: 10px 18px;
            font-size: 14px;
        }

        .pill-total {
            background: #5e9a8a;
            color: white;
        }

        .pill-supp {
            background: #eab14b;
            color: #4b3604;
        }

        .modal-footer {
            padding: 0 20px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
        }

        .modal-footer-info {
            color: #6e84a2;
            font-size: 14px;
        }

        .btn-secondary {
            height: 46px;
            padding: 0 22px;
            border-radius: 12px;
            border: none;
            background: var(--blue);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-secondary:hover {
            background: #266dca;
            transform: translateY(-1px);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-18px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalPop {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(8px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
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
                Bienvenue, {{  $admin->name ?? 'Administrateur' }}
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

                <a href="{{ route('admin.temps-travail.index') }}" class="active">
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
                <div class="content-title">Temps de travail</div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <span>Temps de travail</span>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.temps-travail.index') }}" class="toolbar-card">
                <div class="toolbar-title">Enregistrements: {{ count($data) }} résultats</div>

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
                            <input type="date" name="date_debut" class="filter-input" value="{{ $dateDebut }}">
                            <span class="separator-arrow">→</span>
                            <input type="date" name="date_fin" class="filter-input" value="{{ $dateFin }}">
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
                        <select name="employe_id" class="filter-select simple">
                            <option value="">Tous les employés</option>
                            @foreach($employes as $emp)
                                <option value="{{ $emp->id }}" {{ request('employe_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->prenom }} {{ $emp->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">Filtrer</button>
                </div>
            </form>

            <div class="table-card">
                <div class="table-card-header">
                    <div class="table-card-title">Temps de travail des employés</div>

                    <form method="GET" action="{{ route('admin.temps-travail.index') }}" class="search-box">
                        <input type="hidden" name="date_debut" value="{{ $dateDebut }}">
                        <input type="hidden" name="date_fin" value="{{ $dateFin }}">
                        <input type="hidden" name="employe_id" value="{{ request('employe_id') }}">
                        <input type="text" name="search" placeholder="Rechercher" value="{{ request('search') }}">
                    </form>
                </div>

                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Total heures</th>
                                <th>Heures en service</th>
                                <th>Heures supplémentaires</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($data as $item)
                                @php
                                    $nom = trim(($item['employe']->prenom ?? '') . ' ' . ($item['employe']->nom ?? ''));
                                    $avatarName = urlencode($nom ?: 'Employe');
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

                                    <td>{{ $item['total_heures'] }} h</td>
                                    <td>{{ $item['heures_service'] }} h</td>
                                    <td>
                                        @if($item['heures_supp'] > 0)
                                            <span class="supp-badge">{{ $item['heures_supp'] }} h</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <a
                                            href="{{ route('admin.temps-travail.index', array_merge(request()->query(), ['view' => $item['employe']->id])) }}"
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
                                    <td colspan="5">Aucune donnée trouvée.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination-zone">
                    <div class="pagination-info">
                        Affichage de {{ $employes->firstItem() ?? 0 }} à {{ $employes->lastItem() ?? 0 }} sur {{ $employes->total() }} entrées
                    </div>

                    <div class="pagination-links">
                        {{ $employes->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if($selectedEmploye)
        @php
            $selectedNom = trim(($selectedEmploye->prenom ?? '') . ' ' . ($selectedEmploye->nom ?? ''));
            $selectedAvatar = urlencode($selectedNom ?: 'Employe');
            $totalHoursLabel = floor($totalGlobal / 60) . ' h ' . ($totalGlobal % 60) . ' min';
            $suppHoursLabel = floor($suppGlobal / 60) . ' h ' . ($suppGlobal % 60) . ' min';
        @endphp

        <div class="modal-overlay show" id="viewWorkModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h2>Détail du temps de travail</h2>
                    <a href="{{ route('admin.temps-travail.index', request()->except(['view'])) }}" class="close-modal-btn">&times;</a>
                </div>

                <div class="modal-employee">
                    <img
                        src="https://ui-avatars.com/api/?name={{ $selectedAvatar }}&background=ffffff&color=2d6fe0&size=120"
                        alt="{{ $selectedNom }}"
                        class="modal-employee-avatar"
                    >
                    <div>
                        <div class="modal-employee-name">{{ $selectedNom }}</div>
                        <div class="modal-employee-period">
                            {{ \Carbon\Carbon::parse($dateDebut)->translatedFormat('F Y') }}
                        </div>
                    </div>
                </div>

                <div class="modal-body-scroll">
                    <div class="modal-body">
                        <div class="modal-table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Heure arrivée</th>
                                        <th>Heure départ</th>
                                        <th>Temps travaillé</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($details as $detail)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td>
                                            <td>{{ $detail['arrivee'] ? \Carbon\Carbon::parse($detail['arrivee'])->format('H:i') : '-' }}</td>
                                            <td>{{ $detail['depart'] ? \Carbon\Carbon::parse($detail['depart'])->format('H:i') : '-' }}</td>
                                            <td>{{ $detail['temps'] ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Aucun détail trouvé.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="modal-summary">
                            <span class="summary-pill pill-total">Total : {{ $totalHoursLabel }}</span>
                            <span class="summary-pill pill-supp">Dont heures supp : {{ $suppHoursLabel }}</span>
                        </div>
                    </div>
             </div>
            

                <div class="modal-footer">
                    <div class="modal-footer-info">
                        Affichage de 1 à {{ $details->count() }} sur {{ $details->count() }} pointages
                    </div>

                    <a href="{{ route('admin.temps-travail.index', request()->except(['view'])) }}" class="btn-secondary">Fermer</a>
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
                    topUserMenu.classList.remove('show');
                }


            });
        });
    </script>
</body>
</html>
