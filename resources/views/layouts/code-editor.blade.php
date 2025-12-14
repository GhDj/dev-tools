<!DOCTYPE html>
<html lang="en" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Code Editor | Dev Tools')</title>
    <meta name="description" content="@yield('meta_description', 'Free online code editor with live preview for HTML, CSS, and JavaScript.')">
    <meta name="keywords" content="@yield('meta_keywords', 'online code editor, live code editor, html editor, css editor, javascript editor')">
    <meta name="author" content="Ghabri Djalel">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Code Editor | Dev Tools')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:site_name" content="Dev Tools">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Code Editor | Dev Tools')">
    <meta name="twitter:description" content="@yield('meta_description')">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-99SGEVL1J9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-99SGEVL1J9');
    </script>

    <!-- JSON-LD -->
    @stack('schema')

    <!-- Monaco Editor CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.52.0/min/vs/editor/editor.main.min.css">

    <style>
        :root {
            --bg-primary: #f9fafb;
            --bg-secondary: #ffffff;
            --bg-tertiary: #f3f4f6;
            --border-color: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --accent: #4f46e5;
            --accent-hover: #4338ca;
        }

        .dark {
            --bg-primary: #0f0f0f;
            --bg-secondary: #1a1a1a;
            --bg-tertiary: #262626;
            --border-color: #333333;
            --text-primary: #ffffff;
            --text-secondary: #a3a3a3;
            --accent: #818cf8;
            --accent-hover: #6366f1;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background-color 0.2s, color 0.2s;
        }

        /* Navigation */
        .nav {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 0 1rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 600;
        }

        .nav-brand svg {
            color: var(--accent);
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Buttons */
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            cursor: pointer;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.15s;
        }

        .btn:hover {
            background: var(--bg-tertiary);
        }

        .btn-icon {
            padding: 0.5rem;
            border: none;
            background: transparent;
        }

        .btn-icon:hover {
            background: var(--bg-tertiary);
        }

        .btn svg {
            width: 1rem;
            height: 1rem;
        }

        /* Main Layout */
        .main-container {
            display: flex;
            flex-direction: column;
            height: calc(100vh - 56px);
        }

        /* Toolbar */
        .toolbar {
            background: var(--bg-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .toolbar-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .toolbar-divider {
            width: 1px;
            height: 1.5rem;
            background: var(--border-color);
        }

        .toolbar label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }

        .toolbar select {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            border: 1px solid var(--border-color);
            background: var(--bg-secondary);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .toolbar input[type="checkbox"] {
            accent-color: var(--accent);
        }

        /* Editor Layout */
        .editor-layout {
            display: grid;
            grid-template-columns: 1fr 1fr;
            flex: 1;
            overflow: hidden;
        }

        .editor-layout.preview-hidden {
            grid-template-columns: 1fr;
        }

        /* Editor Panel */
        .editor-panel {
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-color);
            overflow: hidden;
        }

        .editor-layout.preview-hidden .editor-panel {
            border-right: none;
        }

        /* Tabs */
        .tabs {
            display: flex;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            overflow-x: auto;
        }

        .tab {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            border-bottom: 2px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
        }

        .tab:hover {
            color: var(--text-primary);
            background: var(--bg-secondary);
        }

        .tab.active {
            color: var(--accent);
            background: var(--bg-secondary);
            border-bottom-color: var(--accent);
        }

        .tab-close {
            display: none;
            width: 16px;
            height: 16px;
            line-height: 14px;
            text-align: center;
            border-radius: 3px;
            font-size: 14px;
        }

        .tab:hover .tab-close {
            display: inline-block;
        }

        .tab-close:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .tab-add {
            padding: 0.75rem 1rem;
            font-size: 1.25rem;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
        }

        .tab-add:hover {
            color: var(--accent);
            background: var(--bg-secondary);
        }

        /* Monaco Container */
        #monaco-container {
            flex: 1;
            overflow: hidden;
        }

        /* Preview Panel */
        .preview-panel {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
        }

        .preview-header span {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .btn-run {
            background: #16a34a;
            color: white;
            border-color: #16a34a;
        }

        .btn-run:hover {
            background: #15803d;
        }

        .preview-container {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        #preview-frame {
            flex: 1;
            border: none;
            background: white;
        }

        /* Console Panel */
        .console-panel {
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            max-height: 200px;
            transition: max-height 0.2s;
        }

        .console-panel.collapsed {
            max-height: 32px;
        }

        .console-panel.collapsed .console-output {
            display: none;
        }

        .console-panel.collapsed #btn-toggle-console svg {
            transform: rotate(180deg);
        }

        .console-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.75rem;
            background: var(--bg-tertiary);
            border-bottom: 1px solid var(--border-color);
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .console-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-console {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            border-radius: 3px;
            display: flex;
            align-items: center;
        }

        .btn-console:hover {
            background: var(--bg-secondary);
            color: var(--text-primary);
        }

        .console-output {
            flex: 1;
            overflow-y: auto;
            padding: 0.5rem;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.75rem;
            background: var(--bg-secondary);
        }

        .console-line {
            padding: 0.25rem 0.5rem;
            border-radius: 3px;
            margin-bottom: 2px;
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .console-line.log {
            color: var(--text-primary);
        }

        .console-line.info {
            color: #3b82f6;
            background: rgba(59, 130, 246, 0.1);
        }

        .console-line.warn {
            color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
        }

        .console-line.error {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .console-line-prefix {
            opacity: 0.6;
            min-width: 40px;
        }

        .console-line-content {
            flex: 1;
            word-break: break-all;
        }

        /* Status Bar */
        .status-bar {
            background: var(--bg-secondary);
            border-top: 1px solid var(--border-color);
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-between;
            font-size: 0.75rem;
            color: var(--text-secondary);
        }

        .status-bar-left {
            display: flex;
            gap: 1rem;
        }

        /* Toast */
        .toast {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: white;
            font-size: 0.875rem;
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
            display: none;
        }

        .toast.show {
            display: block;
        }

        .toast.success {
            background: #16a34a;
        }

        .toast.error {
            background: #dc2626;
        }

        @keyframes slideIn {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--bg-secondary);
            border-radius: 0.75rem;
            max-width: 600px;
            width: 100%;
            max-height: 80vh;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-sm {
            max-width: 400px;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .modal-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            color: var(--text-secondary);
            cursor: pointer;
            line-height: 1;
            padding: 0;
        }

        .modal-close:hover {
            color: var(--text-primary);
        }

        .modal-body {
            padding: 1.25rem;
            overflow-y: auto;
            max-height: 60vh;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
        }

        /* Shortcuts Modal */
        .shortcut-section {
            margin-bottom: 1.5rem;
        }

        .shortcut-section:last-child {
            margin-bottom: 0;
        }

        .shortcut-section h4 {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 0.75rem;
        }

        .shortcut-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            font-size: 0.875rem;
            border-bottom: 1px solid var(--border-color);
        }

        .shortcut-row:last-child {
            border-bottom: none;
        }

        .keys {
            display: flex;
            gap: 0.25rem;
        }

        kbd {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: var(--bg-tertiary);
            border: 1px solid var(--border-color);
            border-radius: 0.25rem;
            font-family: inherit;
            font-size: 0.75rem;
        }

        /* New File Modal */
        .modal-body label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .modal-body input[type="text"] {
            width: 100%;
            padding: 0.625rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            background: var(--bg-primary);
            color: var(--text-primary);
            font-size: 0.875rem;
        }

        .modal-body input[type="text"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .modal-body .hint {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }

        .btn-primary {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .editor-layout {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 1fr;
            }

            .editor-panel {
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }

            .toolbar {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    @yield('content')

    <!-- Monaco Editor Loader -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.52.0/min/vs/loader.min.js"></script>

    @stack('scripts')
</body>
</html>
