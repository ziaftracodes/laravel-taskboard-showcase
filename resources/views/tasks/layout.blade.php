<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskBoard — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Host+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet" />
    <style>
        :root {
            --bg-color: #f8fafc;
            --surface: #ffffff;
            --primary: #1a73e8;
            --primary-hover: #1557b0;
            --primary-light: #e8f0fe;
            --danger: #ea4335;
            --danger-hover: #d33426;
            --success: #34a853;
            --warning: #f29900;
            --text-main: #202124;
            --text-muted: #5f6368;
            --text-light: #9aa0a6;
            --border: #e2e8f0;
            --border-light: #f1f3f4;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.1);
            --shadow-md: 0 4px 6px -1px rgba(0,0,0,0.07), 0 2px 4px -1px rgba(0,0,0,0.04);
            --shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.04);
            --radius-xl: 16px;
            --radius-lg: 12px;
            --radius-md: 8px;
            --radius-sm: 6px;
            --radius-full: 100px;
            --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Host Grotesk', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* ======================== NAVBAR ======================== */
        .navbar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .navbar-brand .material-symbols-outlined { color: var(--primary); }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* ======================== CONTAINER ======================== */
        .container {
            max-width: 1120px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
        }

        /* ======================== METRICS ROW ======================== */
        .metrics-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .metric-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .metric-icon .material-symbols-outlined { color: white; font-size: 22px; }

        .metric-value {
            font-size: 1.75rem;
            font-weight: 700;
            line-height: 1;
            color: var(--text-main);
        }

        .metric-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-top: 2px;
        }

        /* ======================== FILTERS ======================== */
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.625rem 1rem 0.625rem 2.5rem;
            font-size: 0.875rem;
            font-family: inherit;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            transition: var(--transition);
            color: var(--text-main);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.12);
        }

        .search-wrapper {
            position: relative;
            flex: 1;
            min-width: 200px;
        }

        .search-wrapper .material-symbols-outlined {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--text-light);
            pointer-events: none;
        }

        .filter-select {
            padding: 0.625rem 2rem 0.625rem 0.875rem;
            font-size: 0.8125rem;
            font-family: inherit;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            color: var(--text-main);
            cursor: pointer;
            transition: var(--transition);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235f6368' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* ======================== BUTTONS ======================== */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.625rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: var(--radius-full);
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: var(--transition);
            font-family: inherit;
            white-space: nowrap;
        }

        .btn:active { transform: scale(0.97); }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 1px 3px rgba(26, 115, 232, 0.3);
        }

        .btn-secondary {
            background-color: var(--surface);
            color: var(--text-main);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: var(--bg-color);
            border-color: #cbd5e1;
        }

        .btn-sm {
            padding: 0.375rem 0.875rem;
            font-size: 0.8125rem;
        }

        .btn-icon {
            padding: 0.5rem;
            border-radius: 50%;
            background: transparent;
            color: var(--text-muted);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-icon:hover {
            background: rgba(0,0,0,0.05);
            color: var(--text-main);
        }

        .btn-icon.danger:hover {
            background: #fce8e6;
            color: var(--danger);
        }

        /* ======================== ALERTS ======================== */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.875rem;
            animation: slideDown 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .alert-success {
            background-color: #e6f4ea;
            color: #137333;
            border: 1px solid #ceead6;
        }

        .alert-error {
            background-color: #fce8e6;
            color: #c5221f;
            border: 1px solid #fad2cf;
        }

        /* ======================== CARDS ======================== */
        .card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            padding: 2rem;
        }

        /* ======================== TASK GRID ======================== */
        .task-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 1rem;
        }

        .task-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 1.25rem;
            border: 1px solid var(--border);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .task-card:hover {
            box-shadow: var(--shadow-md);
            border-color: #d1d9e0;
        }

        .task-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
            gap: 0.75rem;
        }

        .task-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.4;
        }

        .task-title.completed {
            text-decoration: line-through;
            color: var(--text-light);
        }

        .task-desc {
            color: var(--text-muted);
            font-size: 0.8125rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Priority / Status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 0.2rem 0.625rem;
            border-radius: var(--radius-full);
            font-size: 0.6875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }

        .badge-priority {
            color: white;
        }

        .badge-status-todo { background: #f1f3f4; color: var(--text-muted); }
        .badge-status-in_progress { background: var(--primary-light); color: var(--primary); }
        .badge-status-completed { background: #e6f4ea; color: var(--success); }

        /* Tags */
        .tag-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.125rem 0.5rem;
            border-radius: var(--radius-full);
            font-size: 0.6875rem;
            font-weight: 500;
            border: 1px solid;
        }

        .tags-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.375rem;
            margin-bottom: 0.75rem;
        }

        .task-meta-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
            flex-wrap: wrap;
        }

        .category-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .category-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border-light);
            margin-top: auto;
        }

        .task-date {
            font-size: 0.6875rem;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .task-date.overdue { color: var(--danger); font-weight: 500; }

        .task-actions { display: flex; gap: 2px; }

        /* Subtask progress */
        .subtask-progress {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
        }

        .progress-bar {
            flex: 1;
            height: 4px;
            background: var(--border-light);
            border-radius: 2px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: var(--success);
            border-radius: 2px;
            transition: width 0.3s ease;
        }

        /* ======================== FORMS ======================== */
        .form-group { margin-bottom: 1.5rem; }

        .form-label {
            display: block;
            margin-bottom: 0.375rem;
            font-weight: 500;
            font-size: 0.8125rem;
            color: var(--text-main);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.9375rem;
            font-family: inherit;
            color: var(--text-main);
            background-color: var(--bg-color);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            transition: var(--transition);
        }

        textarea.form-control { resize: vertical; min-height: 100px; }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background-color: var(--surface);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.12);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        /* Checkbox tags */
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .checkbox-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: 0.8125rem;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid var(--border);
            transition: var(--transition);
            user-select: none;
        }

        .checkbox-chip:hover {
            background: var(--bg-color);
        }

        .checkbox-chip input {
            display: none;
        }

        .checkbox-chip:has(input:checked) {
            background: var(--primary-light);
            border-color: var(--primary);
            color: var(--primary);
        }

        /* ======================== EMPTY STATE ======================== */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 2px dashed var(--border);
            color: var(--text-muted);
        }

        .empty-state .material-symbols-outlined {
            font-size: 56px;
            color: #d1d9e0;
            margin-bottom: 1rem;
        }

        /* ======================== ANIMATIONS ======================== */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in { animation: fadeIn 0.4s ease-out; }

        /* ======================== RESPONSIVE ======================== */
        @media (max-width: 640px) {
            .navbar { padding: 0 1rem; }
            .container { padding: 1rem; }
            .task-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
            .metrics-row { grid-template-columns: repeat(2, 1fr); }
            .toolbar { flex-direction: column; }
            .search-wrapper { width: 100%; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('tasks.index') }}" class="navbar-brand">
            <span class="material-symbols-outlined">task_alt</span>
            TaskBoard
        </a>
        <div class="navbar-actions">
            <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                New Task
            </a>
        </div>
    </nav>

    <div class="container fade-in">
        @if(session('success'))
            <div class="alert alert-success">
                <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <span class="material-symbols-outlined" style="font-size: 20px;">error</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')
    </div>
</body>
</html>
