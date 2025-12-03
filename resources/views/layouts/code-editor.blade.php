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

        #preview-frame {
            flex: 1;
            border: none;
            background: white;
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
