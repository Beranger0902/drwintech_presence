<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            --text-main: #35527c;
            --text-dark: #1f3f6d;
            --text-soft: #6d84a3;
            --blue: #2f7de1;
            --blue-soft: rgba(91, 152, 238, 0.20);
            --yellow: #eab14b;
            --green: #43ad77;
            --red: #e56a6a;
            --purple: #8d79d8;
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
        .filter-select:focus {
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

        .section-title {
            font-size: 18px;
            color: var(--text-dark);
            margin: 0 0 14px 4px;
            animation: fadeUp 0.52s ease 0.10s both;
        }

        .report-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .report-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            padding: 18px 20px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            min-height: 145px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            animation: fadeUp 0.55s ease both;
        }

        .report-card:nth-child(1) { animation-delay: 0.14s; }
        .report-card:nth-child(2) { animation-delay: 0.20s; }
        .report-card:nth-child(3) { animation-delay: 0.26s; }

        .report-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(29, 67, 112, 0.10);
        }

        .report-left {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .report-icon {
            width: 54px;
            height: 54px;
            min-width: 54px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: transform 0.25s ease;
        }

        .report-card:hover .report-icon {
            transform: scale(1.06);
        }

        .report-icon svg {
            width: 24px;
            height: 24px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .icon-blue { background: #7f9bff; }
        .icon-yellow { background: #eab14b; }
        .icon-green { background: #68c3a4; }

        .report-text h3 {
            font-size: 16px;
            color: var(--text-dark);
            margin-bottom: 8px;
            font-weight: normal;
        }

        .report-text p {
            font-size: 14px;
            color: var(--text-main);
            line-height: 1.45;
            max-width: 350px;
        }

        .report-actions {
            display: flex;
            align-items: flex-end;
            height: 100%;
        }

        .btn-generate {
            border: none;
            background: var(--blue);
            color: white;
            border-radius: 12px;
            min-width: 106px;
            height: 42px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-generate:hover {
            background: #266dca;
            transform: translateY(-1px);
        }

        .table-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            box-shadow: var(--shadow-card);
            overflow: hidden;
            animation: fadeUp 0.58s ease 0.32s both;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .table-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(29, 67, 112, 0.08);
        }

        .table-card-title {
            font-size: 18px;
            color: var(--text-dark);
            padding: 18px 22px 10px;
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

        .time-worked {
            color: var(--text-main);
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

        .empty-row {
            text-align: center;
            color: var(--text-soft);
            padding: 28px 18px;
        }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(18, 32, 56, 0.48);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 4000;
            padding: 16px;
            backdrop-filter: blur(2px);
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn 0.25s ease;
        }

        .modal-box {
            width: 100%;
            max-width: 860px;
            max-height: 88vh;
            background: white;
            border-radius: 20px;
            box-shadow: 0 22px 60px rgba(0,0,0,0.22);
            overflow: hidden;
            animation: modalPop 0.28s ease;
            display: flex;
            flex-direction: column;
        }

        .loading-modal-box {
            max-width: 420px;
        }

        .modal-content-scroll {
            overflow-y: auto;
            max-height: calc(88vh - 76px);
            padding-bottom: 16px;
        }

        .modal-header {
            padding: 18px 22px;
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

        .loading-body {
            padding: 28px 22px 24px;
            text-align: center;
        }

        .loading-icon-wrap {
            width: 84px;
            height: 84px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #eef4ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--blue);
            position: relative;
        }

        .loading-icon-wrap svg {
            width: 34px;
            height: 34px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            z-index: 2;
        }

        .loading-ring {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 4px solid #d8e6ff;
            border-top-color: var(--blue);
            animation: spin 0.9s linear infinite;
        }

        .loading-title {
            font-size: 20px;
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .loading-text {
            font-size: 15px;
            color: var(--text-soft);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .loading-actions {
            display: flex;
            justify-content: center;
        }

        .btn-cancel {
            height: 44px;
            padding: 0 22px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            color: var(--text-main);
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .btn-cancel:hover {
            background: #f6f9fe;
            transform: translateY(-1px);
        }

        .success-banner {
            margin: 18px 22px 0;
            background: #eaf8ef;
            border: 1px solid #c8e9d3;
            color: #278a4d;
            border-radius: 12px;
            padding: 14px 16px;
            font-size: 14px;
        }

        .result-filters {
            margin: 16px 18px 0;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .result-filter-box {
            background: #f8fbff;
            border: 1px solid #e4edf8;
            border-radius: 12px;
            padding: 12px 14px;
        }

        .result-filter-label {
            font-size: 13px;
            color: var(--text-soft);
            margin-bottom: 6px;
        }

        .result-filter-value {
            font-size: 15px;
            color: var(--text-dark);
        }

        .result-stats {
            margin: 16px 18px 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .result-stat-card {
            border-radius: 12px;
            padding: 12px 14px;
            color: white;
            min-height: 78px;
        }

        .stat-present { background: #43ad77; }
        .stat-retard { background: #eab14b; color: #4b3604; }
        .stat-absent { background: #e56a6a; }
        .stat-conge { background: #7a9cf5; }

        .result-stat-title {
            font-size: 13px;
            margin-bottom: 6px;
        }

        .result-stat-value {
            font-size: 20px;
        }

        .result-charts {
            margin: 16px 18px 0;
            display: grid;
            grid-template-columns: 1.15fr 0.85fr;
            gap: 12px;
        }

       .chart-card {
            background: #ffffff;
            border: 1px solid #e4edf8;
            border-radius: 14px;
            padding: 14px;
        }

        .chart-title {
            font-size: 16px;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .chart-wrap {
            position: relative;
            height: 210px;
        }

        .pie-legend {
            margin-top: 14px;
            display: grid;
            gap: 8px;
        }

        .pie-legend-item {
            display: grid;
            grid-template-columns: 16px 1fr auto;
            gap: 10px;
            align-items: center;
            font-size: 14px;
            color: var(--text-main);
        }

        .pie-color {
            width: 16px;
            height: 16px;
            border-radius: 4px;
        }

       .details-section {
            margin: 16px 18px 0;
            background: #ffffff;
            border: 1px solid #e4edf8;
            border-radius: 14px;
            overflow: hidden;
        }

       .details-title {
            padding: 14px 16px 8px;
            font-size: 15px;
            color: var(--text-dark);
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 96px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 13px;
        }

        .chip-present { background: #e8f7ee; color: #278a4d; }
        .chip-retard { background: #fff4df; color: #a56a00; }
        .chip-absent { background: #fdecec; color: #c94c4c; }
        .chip-conge { background: #edf2ff; color: #4869c9; }
        .chip-default { background: #eef2f7; color: #5f6f86; }

        .modal-footer {
            padding: 16px 18px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .export-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-export {
            height: 44px;
            padding: 0 18px;
            border: none;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .btn-export:hover {
            transform: translateY(-1px);
            opacity: 0.95;
        }

        .btn-export svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .btn-pdf { background: #d9534f; }
        .btn-excel { background: #2ca46f; }
        .btn-close-result {
            height: 44px;
            padding: 0 22px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: white;
            color: var(--text-main);
            cursor: pointer;
        }

        .details-section table th,
        .details-section table td {
            padding: 12px 16px;
            font-size: 13px;
        }

        .status-chip {
            min-width: 88px;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 12px;
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

        @keyframes spin {
            to { transform: rotate(360deg); }
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

            .report-cards {
                grid-template-columns: 1fr;
            }

            .result-stats,
            .result-charts,
            .result-filters {
                grid-template-columns: 1fr;
            }
        }


        .work-loading-icon-wrap {
            width: 84px;
            height: 84px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #eef8f4;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #43ad77;
            position: relative;
        }

        .work-loading-icon-wrap svg {
            width: 34px;
            height: 34px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
            z-index: 2;
        }

        .work-loading-ring {
            position: absolute;
            inset: -6px;
            border-radius: 50%;
            border: 4px solid #dff1e9;
            border-top-color: #43ad77;
            animation: spin 0.9s linear infinite;
        }

        .work-result-stats {
            margin: 18px 22px 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
        }

        .work-stat-card {
            border-radius: 14px;
            padding: 16px;
            color: white;
            min-height: 90px;
        }

        .work-total { background: #43ad77; }
        .work-normal { background: #2f7de1; }
        .work-supp { background: #eab14b; color: #4b3604; }

        .work-chart-section {
            margin: 18px 22px 0;
            background: #ffffff;
            border: 1px solid #e4edf8;
            border-radius: 16px;
            padding: 16px;
        }

        .work-chart-wrap {
            position: relative;
            height: 300px;
        }

        .work-details-section {
            margin: 18px 22px 0;
            background: #ffffff;
            border: 1px solid #e4edf8;
            border-radius: 16px;
            overflow: hidden;
        }

        .work-details-title {
            padding: 16px 18px 10px;
            font-size: 16px;
            color: var(--text-dark);
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

                <a href="{{ route('admin.rapports.index') }}" class="active">
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
                <div class="content-title">Rapports</div>
                <div class="breadcrumb">
                    <a href="{{ route('admin.dashboard') }}">Accueil</a>
                    &nbsp; / &nbsp;
                    <span>Rapports</span>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.rapports.index') }}" class="toolbar-card" id="reportFilterForm">
                <div class="toolbar-title">Enregistrements: {{ $totalRapports }} résultats</div>

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
                        <select name="employe_id" class="filter-select simple">
                            <option value="">Tous les employés</option>
                            @foreach($employes as $employe)
                                <option value="{{ $employe->id }}" {{ (string)$employeId === (string)$employe->id ? 'selected' : '' }}>
                                    {{ $employe->prenom }} {{ $employe->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn-filter">Filtrer</button>
                </div>
            </form>

            <div class="section-title">Générer un rapport</div>

            <div class="report-cards">
                <div class="report-card">
                    <div class="report-left">
                        <div class="report-icon icon-blue">
                            <svg viewBox="0 0 24 24">
                                <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                                <path d="M14 3V9H20"></path>
                                <circle cx="10" cy="14" r="2"></circle>
                                <path d="M14 18H18"></path>
                                <path d="M8 18H8.01"></path>
                            </svg>
                        </div>
                        <div class="report-text">
                            <h3>Rapport de présence</h3>
                            <p>Générer un rapport détaillé des présences des employés sur une période donnée</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button type="button" class="btn-generate" id="generatePresenceBtn">Générer</button>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-left">
                        <div class="report-icon icon-yellow">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 8V12L15 15"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <path d="M12 4V5"></path>
                            </svg>
                        </div>
                        <div class="report-text">
                            <h3>Rapport des heures de travail</h3>
                            <p>Consulter les heures travaillées par les employés et les heures supplémentaires</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button type="button" class="btn-generate" id="generateWorkBtn">Générer</button>
                    </div>
                </div>

                <div class="report-card">
                    <div class="report-left">
                        <div class="report-icon icon-green">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 3V12L18 18"></path>
                                <path d="M21 12A9 9 0 1 1 12 3"></path>
                            </svg>
                        </div>
                        <div class="report-text">
                            <h3>Rapport personnalisé</h3>
                            <p>Créer un rapport personnalisé en choisissant des filtres et des critères spécifiés</p>
                        </div>
                    </div>
                    <div class="report-actions">
                        <button type="button" class="btn-generate" id="generateCustomBtn">Générer</button>
                    </div>
                </div>
            </div>

            
        </main>
    </div>

    {{-- MODAL LOADING --}}
    <div class="modal-overlay" id="loadingModal">
        <div class="modal-box loading-modal-box">
            <div class="modal-header">
                <h2>Rapport de présence</h2>
                <button type="button" class="close-modal-btn" id="closeLoadingModal">&times;</button>
            </div>

            <div class="loading-body">
                <div class="loading-icon-wrap">
                    <div class="loading-ring"></div>
                    <svg viewBox="0 0 24 24">
                        <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                        <path d="M14 3V9H20"></path>
                        <path d="M8 13H16"></path>
                        <path d="M8 17H13"></path>
                    </svg>
                </div>

                <div class="loading-title">La génération est en cours</div>
                <div class="loading-text">
                    Veuillez patienter pendant la préparation du rapport de présence.
                </div>

                <div class="loading-actions">
                    <button type="button" class="btn-cancel" id="cancelGenerationBtn">Annuler</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="loadingWorkModal">
            <div class="modal-box loading-modal-box">
                <div class="modal-header">
                    <h2>Rapport des heures de travail</h2>
                    <button type="button" class="close-modal-btn" id="closeLoadingWorkModal">&times;</button>
                </div>

                <div class="loading-body">
                    <div class="work-loading-icon-wrap">
                        <div class="work-loading-ring"></div>
                        <svg viewBox="0 0 24 24">
                            <path d="M12 8V12L15 15"></path>
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M12 4V5"></path>
                        </svg>
                    </div>

                    <div class="loading-title">Génération du rapport en cours</div>
                    <div class="loading-text">
                        Veuillez patienter pendant la préparation du rapport des heures de travail.
                    </div>

                    <div class="loading-actions">
                        <button type="button" class="btn-cancel" id="cancelWorkGenerationBtn">Annuler</button>
                    </div>
                </div>
            </div>
    </div>


    {{-- MODAL RESULTAT --}}
    <div class="modal-overlay {{ $rapportPresence ? 'show' : '' }}" id="resultModal">
        <div class="modal-box">
            <div class="modal-header">
                <h2>Rapport de présence</h2>
                <button type="button" class="close-modal-btn" id="closeResultModal">&times;</button>
            </div>
            <div class="modal-content-scroll">
                @if($rapportPresence)
                    @php
                        $selectedEmployeLabel = 'Tous les employés';
                        if(!empty($employeId)) {
                            $selectedEmp = $employes->firstWhere('id', (int)$employeId);
                            if($selectedEmp) {
                                $selectedEmployeLabel = trim(($selectedEmp->prenom ?? '') . ' ' . ($selectedEmp->nom ?? ''));
                            }
                        }

                        $pieColors = ['#43ad77', '#eab14b', '#e56a6a', '#7a9cf5'];
                    @endphp

                    <div class="success-banner">
                        Le rapport de présence a été généré avec succès.
                        <p>Voici un resumé globale du rapport de presence</p>
                    </div>

                    <div class="result-filters">
                        <div class="result-filter-box">
                            <div class="result-filter-label">Période</div>
                            <div class="result-filter-value">
                                {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="result-filter-box">
                            <div class="result-filter-label">Employé</div>
                            <div class="result-filter-value">{{ $selectedEmployeLabel }}</div>
                        </div>
                    </div>

                    <div class="result-stats">
                        <div class="result-stat-card stat-present">
                            <div class="result-stat-title">Présents</div>
                            <div class="result-stat-value">{{ $rapportPresence['stats']['presents'] }}</div>
                        </div>
                        <div class="result-stat-card stat-retard">
                            <div class="result-stat-title">Retards</div>
                            <div class="result-stat-value">{{ $rapportPresence['stats']['retards'] }}</div>
                        </div>
                        <div class="result-stat-card stat-absent">
                            <div class="result-stat-title">Absents</div>
                            <div class="result-stat-value">{{ $rapportPresence['stats']['absents'] }}</div>
                        </div>
                        <div class="result-stat-card stat-conge">
                            <div class="result-stat-title">En congé</div>
                            <div class="result-stat-value">{{ $rapportPresence['stats']['conges'] }}</div>
                        </div>
                    </div>

                    <div class="result-charts">
                        <div class="chart-card">
                            <div class="chart-title">Évolution des statuts de présence</div>
                            <div class="chart-wrap">
                                <canvas id="presenceLineChart"></canvas>
                            </div>
                        </div>

                        <div class="chart-card">
                            <div class="chart-title">Répartition globale</div>
                            <div class="chart-wrap">
                                <canvas id="presencePieChart"></canvas>
                            </div>

                            <div class="pie-legend">
                                @foreach($rapportPresence['pieLabels'] as $index => $label)
                                    <div class="pie-legend-item">
                                        <span class="pie-color" style="background: {{ $pieColors[$index] }}"></span>
                                        <span>{{ $label }}</span>
                                        <strong>{{ $rapportPresence['pieValues'][$index] }}</strong>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="details-section">
                        <div class="details-title">Détails des présences</div>

                        <div class="table-wrap">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Date</th>
                                        <th>Heure arrivée</th>
                                        <th>Heure départ</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rapportPresence['details'] as $detail)
                                        @php
                                            $chipClass = match($detail['statut_code']) {
                                                'present', 'termine' => 'chip-present',
                                                'retard' => 'chip-retard',
                                                'absent' => 'chip-absent',
                                                'conge' => 'chip-conge',
                                                default => 'chip-default',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $detail['nom'] ?: 'Employé' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($detail['date'])->format('d/m/Y') }}</td>
                                            <td>{{ $detail['heure_arrivee'] ? \Carbon\Carbon::parse($detail['heure_arrivee'])->format('H:i') : '-' }}</td>
                                            <td>{{ $detail['heure_depart'] ? \Carbon\Carbon::parse($detail['heure_depart'])->format('H:i') : '-' }}</td>
                                            <td>
                                                <span class="status-chip {{ $chipClass }}">{{ $detail['statut'] }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-row">Aucune donnée disponible.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="export-actions">
                           <a 
                                href="{{ route('admin.rapports.export.pdf', [
                                    'date_debut' => $dateDebut->format('Y-m-d'),
                                    'date_fin' => $dateFin->format('Y-m-d'),
                                    'employe_id' => $employeId
                                ]) }}"
                                class="btn-export btn-pdf"
                            >
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                                    <path d="M14 3V9H20"></path>
                                    <path d="M8 13H10"></path>
                                    <path d="M8 17H16"></path>
                                </svg>
                                Exporter en PDF
                            </a>

                          {{--  <<button type="button" class="btn-export btn-excel">
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                                    <path d="M14 3V9H20"></path>
                                    <path d="M8 13L12 17"></path>
                                    <path d="M12 13L8 17"></path>
                                </svg>
                                Exporter en Excel
                            </button>--}}
                        </div>

                        <button type="button" class="btn-close-result" id="closeResultFooterBtn">Fermer</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal-overlay {{ $rapportWork ? 'show' : '' }}" id="resultWorkModal">
            <div class="modal-box">
                <div class="modal-header">
                    <h2>Rapport des heures de travail</h2>
                    <button type="button" class="close-modal-btn" id="closeWorkResultModal">&times;</button>
                </div>
                <div class="modal-content-scroll">
                    @if($rapportWork)
                        @php
                            $selectedEmployeLabelWork = 'Tous les employés';
                            if(!empty($employeId)) {
                                $selectedEmpWork = $employes->firstWhere('id', (int)$employeId);
                                if($selectedEmpWork) {
                                    $selectedEmployeLabelWork = trim(($selectedEmpWork->prenom ?? '') . ' ' . ($selectedEmpWork->nom ?? ''));
                                }
                            }
                        @endphp

                        <div class="success-banner">
                            Le rapport des heures de travail a été généré avec succès.
                        </div>

                        <div class="result-filters">
                            <div class="result-filter-box">
                                <div class="result-filter-label">Période</div>
                                <div class="result-filter-value">
                                    {{ $dateDebut->format('d/m/Y') }} au {{ $dateFin->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="result-filter-box">
                                <div class="result-filter-label">Employé</div>
                                <div class="result-filter-value">{{ $selectedEmployeLabelWork }}</div>
                            </div>
                        </div>

                        <div class="work-result-stats">
                            <div class="work-stat-card work-total">
                                <div class="result-stat-title">Heures travaillées</div>
                                <div class="result-stat-value">{{ $rapportWork['stats']['heures_travaillees'] }}</div>
                            </div>
                            <div class="work-stat-card work-normal">
                                <div class="result-stat-title">Heures normales</div>
                                <div class="result-stat-value">{{ $rapportWork['stats']['heures_normales'] }}</div>
                            </div>
                            <div class="work-stat-card work-supp">
                                <div class="result-stat-title">Heures supplémentaires</div>
                                <div class="result-stat-value">{{ $rapportWork['stats']['heures_supp'] }}</div>
                            </div>
                        </div>

                        <div class="work-chart-section">
                            <div class="chart-title">Total des heures travaillées par employé</div>
                            <div class="work-chart-wrap">
                                <canvas id="workBarChart"></canvas>
                            </div>
                        </div>

                        <div class="work-details-section">
                            <div class="work-details-title">Détails des heures de travail</div>

                            <div class="table-wrap">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Période</th>
                                            <th>Heures normales</th>
                                            <th>Heures supplémentaires</th>
                                            <th>Total heures</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rapportWork['details'] as $detail)
                                            <tr>
                                                <td>{{ $detail['nom'] }}</td>
                                                <td>{{ $detail['periode_debut'] }} → {{ $detail['periode_fin'] }}</td>
                                                <td>{{ $detail['heures_normales'] }}</td>
                                                <td>{{ $detail['heures_supp'] }}</td>
                                                <td>{{ $detail['total_heures'] }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="empty-row">Aucune donnée disponible.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <div class="export-actions">
                                <a 
                                    href="{{ route('admin.rapports.export.work_pdf', [
                                        'date_debut' => $dateDebut->format('Y-m-d'),
                                        'date_fin' => $dateFin->format('Y-m-d'),
                                        'employe_id' => $employeId
                                    ]) }}"
                                    class="btn-export btn-pdf">

                                    <svg viewBox="0 0 24 24">
                                        <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                                        <path d="M14 3V9H20"></path>
                                        <path d="M8 13H10"></path>
                                        <path d="M8 17H16"></path>
                                    </svg>
                                    Exporter en PDF
                                </a

                                {{--   
                                <button type="button" class="btn-export btn-excel">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M4 19V5A2 2 0 0 1 6 3H14L20 9V19A2 2 0 0 1 18 21H6A2 2 0 0 1 4 19Z"></path>
                                        <path d="M14 3V9H20"></path>
                                        <path d="M8 13L12 17"></path>
                                        <path d="M12 13L8 17"></path>
                                    </svg>
                                    Exporter en Excel
                                </button>
                                --}}
                            </div>

                            <button type="button" class="btn-close-result" id="closeWorkResultFooterBtn">Fermer</button>
                        </div>
                    @endif
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


            const loadingModal = document.getElementById('loadingModal');
            const resultModal = document.getElementById('resultModal');
            const generatePresenceBtn = document.getElementById('generatePresenceBtn');
            const closeLoadingModal = document.getElementById('closeLoadingModal');
            const cancelGenerationBtn = document.getElementById('cancelGenerationBtn');
            const closeResultModal = document.getElementById('closeResultModal');
            const closeResultFooterBtn = document.getElementById('closeResultFooterBtn');
            const filterForm = document.getElementById('reportFilterForm');

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

            function openLoadingModal() {
                loadingModal.classList.add('show');
            }

            function closeLoading() {
                loadingModal.classList.remove('show');
            }

            function closeResult() {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('generate');
                window.location.href = currentUrl.toString();
            }

            if (generatePresenceBtn) {
                generatePresenceBtn.addEventListener('click', function () {
                    openLoadingModal();

                    setTimeout(() => {
                        const formData = new FormData(filterForm);
                        const params = new URLSearchParams(formData);
                        params.set('generate', 'presence');
                        window.location.href = "{{ route('admin.rapports.index') }}?" + params.toString();
                    }, 1500);
                });
            }

            if (closeLoadingModal) {
                closeLoadingModal.addEventListener('click', closeLoading);
            }

            if (cancelGenerationBtn) {
                cancelGenerationBtn.addEventListener('click', closeLoading);
            }

            if (closeResultModal) {
                closeResultModal.addEventListener('click', closeResult);
            }

            if (closeResultFooterBtn) {
                closeResultFooterBtn.addEventListener('click', closeResult);
            }

            if (loadingModal) {
                loadingModal.addEventListener('click', function (e) {
                    if (e.target === loadingModal) {
                        closeLoading();
                    }
                });
            }

            if (resultModal) {
                resultModal.addEventListener('click', function (e) {
                    if (e.target === resultModal) {
                        closeResult();
                    }
                });
            }

            @if($rapportPresence)
                const lineCtx = document.getElementById('presenceLineChart');
                const pieCtx = document.getElementById('presencePieChart');

                if (lineCtx) {
                    new Chart(lineCtx, {
                        type: 'line',
                        data: {
                            labels: @json($rapportPresence['labels']),
                            datasets: [
                                {
                                    label: 'Présents',
                                    data: @json($rapportPresence['seriePresents']),
                                    borderColor: '#43ad77',
                                    backgroundColor: 'rgba(67, 173, 119, 0.12)',
                                    tension: 0.35,
                                    fill: false
                                },
                                {
                                    label: 'Retards',
                                    data: @json($rapportPresence['serieRetards']),
                                    borderColor: '#eab14b',
                                    backgroundColor: 'rgba(234, 177, 75, 0.12)',
                                    tension: 0.35,
                                    fill: false
                                },
                                {
                                    label: 'Absents',
                                    data: @json($rapportPresence['serieAbsents']),
                                    borderColor: '#e56a6a',
                                    backgroundColor: 'rgba(229, 106, 106, 0.12)',
                                    tension: 0.35,
                                    fill: false
                                },
                                {
                                    label: 'En congé',
                                    data: @json($rapportPresence['serieConges']),
                                    borderColor: '#7a9cf5',
                                    backgroundColor: 'rgba(122, 156, 245, 0.12)',
                                    tension: 0.35,
                                    fill: false
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

                if (pieCtx) {
                    new Chart(pieCtx, {
                        type: 'doughnut',
                        data: {
                            labels: @json($rapportPresence['pieLabels']),
                            datasets: [{
                                data: @json($rapportPresence['pieValues']),
                                backgroundColor: ['#43ad77', '#eab14b', '#e56a6a', '#7a9cf5'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '58%',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            @endif


            document.querySelectorAll('.btn-pdf').forEach(btn => {
                btn.addEventListener('click', () => {
                    const params = new URLSearchParams(new FormData(document.getElementById('reportFilterForm')));
                    window.open("/rapports/export/pdf?" + params.toString(), "_blank");
                });
            });

            function exportPDF() {
                const canvas = document.getElementById('presenceLineChart');
                const chartImage = canvas.toDataURL("image/png");

                const params = new URLSearchParams({
                    date_debut: "{{ $dateDebut->format('Y-m-d') }}",
                    date_fin: "{{ $dateFin->format('Y-m-d') }}",
                    employe_id: "{{ $employeId }}",
                    chart: chartImage
                });

                window.open("{{ route('admin.rapports.export.pdf') }}?" + params.toString(), "_blank");
            }
                        

            const loadingWorkModal = document.getElementById('loadingWorkModal');
            const resultWorkModal = document.getElementById('resultWorkModal');
            const generateWorkBtn = document.getElementById('generateWorkBtn');
            const closeLoadingWorkModal = document.getElementById('closeLoadingWorkModal');
            const cancelWorkGenerationBtn = document.getElementById('cancelWorkGenerationBtn');
            const closeWorkResultModal = document.getElementById('closeWorkResultModal');
            const closeWorkResultFooterBtn = document.getElementById('closeWorkResultFooterBtn');
           
            function openWorkLoadingModal() {
                loadingWorkModal.classList.add('show');
            }

            function closeWorkLoading() {
                loadingWorkModal.classList.remove('show');
            }

            function closeWorkResult() {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('generate');
                window.location.href = currentUrl.toString();
            }

            if (generateWorkBtn) {
                generateWorkBtn.addEventListener('click', function () {
                    openWorkLoadingModal();

                    setTimeout(() => {
                        const formData = new FormData(filterForm);
                        const params = new URLSearchParams(formData);
                        params.set('generate', 'work');
                        window.location.href = "{{ route('admin.rapports.index') }}?" + params.toString();
                    }, 1500);
                });
            }

            if (closeLoadingWorkModal) {
                closeLoadingWorkModal.addEventListener('click', closeWorkLoading);
            }

            if (cancelWorkGenerationBtn) {
                cancelWorkGenerationBtn.addEventListener('click', closeWorkLoading);
            }

            if (closeWorkResultModal) {
                closeWorkResultModal.addEventListener('click', closeWorkResult);
            }

            if (closeWorkResultFooterBtn) {
                closeWorkResultFooterBtn.addEventListener('click', closeWorkResult);
            }

            if (loadingWorkModal) {
                loadingWorkModal.addEventListener('click', function (e) {
                    if (e.target === loadingWorkModal) {
                        closeWorkLoading();
                    }
                });
            }

            if (resultWorkModal) {
                resultWorkModal.addEventListener('click', function (e) {
                    if (e.target === resultWorkModal) {
                        closeWorkResult();
                    }
                });
            }

            @if($rapportWork)
                const workBarCtx = document.getElementById('workBarChart');

                if (workBarCtx) {
                    new Chart(workBarCtx, {
                        type: 'bar',
                        data: {
                            labels: @json($rapportWork['labels']),
                            datasets: [
                                {
                                    label: 'Heures normales',
                                    data: @json($rapportWork['serieNormales']),
                                    backgroundColor: '#2f7de1',
                                    borderRadius: 6
                                },
                                {
                                    label: 'Heures supplémentaires',
                                    data: @json($rapportWork['serieSupp']),
                                    backgroundColor: '#eab14b',
                                    borderRadius: 6
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }
            @endif


            const generateCustomBtn = document.getElementById('generateCustomBtn');

            if (generateCustomBtn) {
                generateCustomBtn.addEventListener('click', function () {
                    alert("Nous allons brancher le rapport personnalisé juste après.");
                });
            }
        });
    </script>
</body>
</html>