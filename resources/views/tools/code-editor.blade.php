@extends('layouts.code-editor')

@section('title', 'Online Code Editor - Live HTML/CSS/JS Editor | Dev Tools')
@section('meta_description', 'Free online code editor with live preview. Write HTML, CSS, JavaScript with real-time preview in your browser.')
@section('meta_keywords', 'online code editor, live code editor, html editor, css editor, javascript editor, code playground, free code editor, web editor')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Online Code Editor",
    "description": "Free online code editor with live preview for HTML, CSS, and JavaScript",
    "url": "{{ route('tools.code-editor') }}",
    "applicationCategory": "DeveloperApplication",
    "operatingSystem": "Any",
    "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    },
    "author": {
        "@@type": "Person",
        "name": "Ghabri Djalel"
    }
}
</script>
@endpush

@section('content')
<!-- Navigation -->
<nav class="nav">
    <a href="{{ route('home') }}" class="nav-brand">
        <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
        </svg>
        <span>Dev Tools</span>
    </a>
    <div class="nav-actions">
        <button id="theme-toggle" class="btn btn-icon" title="Toggle theme">
            <svg id="theme-icon-light" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
            <svg id="theme-icon-dark" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>
    </div>
</nav>

<!-- Main Container -->
<div class="main-container">
    <!-- Toolbar -->
    <div class="toolbar">
        <div class="toolbar-group">
            <label for="font-size">Font:</label>
            <select id="font-size">
                <option value="12">12px</option>
                <option value="14" selected>14px</option>
                <option value="16">16px</option>
                <option value="18">18px</option>
                <option value="20">20px</option>
            </select>
        </div>

        <div class="toolbar-divider"></div>

        <div class="toolbar-group">
            <button id="btn-copy" class="btn" title="Copy code">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Copy
            </button>
            <button id="btn-download" class="btn" title="Download file">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download
            </button>
            <button id="btn-clear" class="btn" title="Clear editor">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Clear
            </button>
        </div>

        <div style="flex:1;"></div>

        <div class="toolbar-group">
            <label>
                <input type="checkbox" id="preview-toggle" checked>
                Live Preview
            </label>
        </div>
    </div>

    <!-- Editor Layout -->
    <div id="editor-layout" class="editor-layout">
        <!-- Editor Panel -->
        <div class="editor-panel">
            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" data-tab="html">index.html</button>
                <button class="tab" data-tab="css">style.css</button>
                <button class="tab" data-tab="js">script.js</button>
            </div>

            <!-- Monaco Editor -->
            <div id="monaco-container"></div>
        </div>

        <!-- Preview Panel -->
        <div id="preview-panel" class="preview-panel">
            <div class="preview-header">
                <span>Preview</span>
                <button id="btn-run" class="btn btn-run">
                    <svg fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    Run
                </button>
            </div>
            <iframe id="preview-frame" sandbox="allow-scripts allow-modals"></iframe>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="status-bar">
        <div class="status-bar-left">
            <span>Ln <span id="cursor-line">1</span>, Col <span id="cursor-col">1</span></span>
            <span id="current-file">index.html</span>
        </div>
        <span>UTF-8</span>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="toast"></div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';

    // State
    const state = {
        editor: null,
        monaco: null,
        activeTab: 'html',
        tabs: {
            html: {
                name: 'index.html',
                language: 'html',
                content: `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Page</title>
</head>
<body>
    <h1>Hello, World!</h1>
    <p>Start editing to see the magic happen.</p>
</body>
</html>`
            },
            css: {
                name: 'style.css',
                language: 'css',
                content: `/* Add your styles here */

body {
    font-family: system-ui, sans-serif;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

h1 {
    color: white;
}

p {
    color: rgba(255,255,255,0.9);
}`
            },
            js: {
                name: 'script.js',
                language: 'javascript',
                content: `// Add your JavaScript here

console.log("Hello from the code editor!");`
            }
        },
        debounceTimer: null
    };

    // DOM Elements
    const elements = {
        monacoContainer: document.getElementById('monaco-container'),
        previewFrame: document.getElementById('preview-frame'),
        previewPanel: document.getElementById('preview-panel'),
        editorLayout: document.getElementById('editor-layout'),
        cursorLine: document.getElementById('cursor-line'),
        cursorCol: document.getElementById('cursor-col'),
        currentFile: document.getElementById('current-file'),
        toast: document.getElementById('toast'),
        themeToggle: document.getElementById('theme-toggle'),
        themeIconLight: document.getElementById('theme-icon-light'),
        themeIconDark: document.getElementById('theme-icon-dark'),
        fontSize: document.getElementById('font-size'),
        previewToggle: document.getElementById('preview-toggle'),
        btnCopy: document.getElementById('btn-copy'),
        btnDownload: document.getElementById('btn-download'),
        btnClear: document.getElementById('btn-clear'),
        btnRun: document.getElementById('btn-run'),
        tabs: document.querySelectorAll('.tab')
    };

    // Theme Management
    function initTheme() {
        const savedTheme = localStorage.getItem('darkMode');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const isDark = savedTheme !== null ? savedTheme === 'true' : prefersDark;
        setTheme(isDark);
    }

    function setTheme(isDark) {
        document.documentElement.classList.toggle('dark', isDark);
        elements.themeIconLight.style.display = isDark ? 'none' : 'block';
        elements.themeIconDark.style.display = isDark ? 'block' : 'none';
        localStorage.setItem('darkMode', isDark);

        if (state.monaco) {
            state.monaco.editor.setTheme(isDark ? 'vs-dark' : 'vs');
        }
    }

    function toggleTheme() {
        const isDark = !document.documentElement.classList.contains('dark');
        setTheme(isDark);
    }

    // Monaco Editor
    function initMonaco() {
        require.config({
            paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.52.0/min/vs' }
        });

        require(['vs/editor/editor.main'], function(monaco) {
            state.monaco = monaco;
            const isDark = document.documentElement.classList.contains('dark');
            const tab = state.tabs[state.activeTab];

            state.editor = monaco.editor.create(elements.monacoContainer, {
                value: tab.content,
                language: tab.language,
                theme: isDark ? 'vs-dark' : 'vs',
                fontSize: parseInt(elements.fontSize.value),
                minimap: { enabled: false },
                wordWrap: 'on',
                automaticLayout: true,
                tabSize: 4,
                scrollBeyondLastLine: false,
                lineNumbers: 'on',
                roundedSelection: true,
                renderLineHighlight: 'line',
                cursorBlinking: 'smooth',
                cursorSmoothCaretAnimation: 'on',
                smoothScrolling: true,
                padding: { top: 10 }
            });

            // Cursor position tracking
            state.editor.onDidChangeCursorPosition(function(e) {
                elements.cursorLine.textContent = e.position.lineNumber;
                elements.cursorCol.textContent = e.position.column;
            });

            // Content change handler
            state.editor.onDidChangeModelContent(function() {
                state.tabs[state.activeTab].content = state.editor.getValue();

                if (elements.previewToggle.checked) {
                    clearTimeout(state.debounceTimer);
                    state.debounceTimer = setTimeout(runPreview, 500);
                }
            });

            // Initial preview
            setTimeout(runPreview, 100);
        });
    }

    // Tab Switching
    function switchTab(tabId) {
        if (tabId === state.activeTab) return;

        // Save current content
        if (state.editor) {
            state.tabs[state.activeTab].content = state.editor.getValue();
        }

        // Update active tab
        state.activeTab = tabId;
        const tab = state.tabs[tabId];

        // Update UI
        elements.tabs.forEach(t => {
            t.classList.toggle('active', t.dataset.tab === tabId);
        });
        elements.currentFile.textContent = tab.name;

        // Update editor
        if (state.editor && state.monaco) {
            const model = state.monaco.editor.createModel(tab.content, tab.language);
            state.editor.setModel(model);
        }
    }

    // Preview
    function runPreview() {
        const html = state.tabs.html.content;
        const css = state.tabs.css.content;
        const js = state.tabs.js.content;

        // Extract body content or use full HTML
        let bodyContent = html;
        const bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
        if (bodyMatch) {
            bodyContent = bodyMatch[1];
        }

        const previewHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>${css}</style>
</head>
<body>
    ${bodyContent}
    <script>${js}<\/script>
</body>
</html>`;

        elements.previewFrame.srcdoc = previewHtml;
    }

    // Actions
    function copyCode() {
        const content = state.editor ? state.editor.getValue() : state.tabs[state.activeTab].content;
        navigator.clipboard.writeText(content).then(function() {
            showToast('Copied to clipboard', 'success');
        }).catch(function() {
            showToast('Failed to copy', 'error');
        });
    }

    function downloadCode() {
        const tab = state.tabs[state.activeTab];
        const content = state.editor ? state.editor.getValue() : tab.content;
        const blob = new Blob([content], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = tab.name;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        showToast('Downloaded ' + tab.name, 'success');
    }

    function clearEditor() {
        if (state.editor) {
            state.editor.setValue('');
            showToast('Editor cleared', 'success');
        }
    }

    function updateFontSize() {
        if (state.editor) {
            state.editor.updateOptions({ fontSize: parseInt(elements.fontSize.value) });
        }
    }

    function togglePreview() {
        const show = elements.previewToggle.checked;
        elements.previewPanel.style.display = show ? 'flex' : 'none';
        elements.editorLayout.classList.toggle('preview-hidden', !show);
        if (show) {
            runPreview();
        }
    }

    // Toast
    function showToast(message, type) {
        elements.toast.textContent = message;
        elements.toast.className = 'toast show ' + type;
        setTimeout(function() {
            elements.toast.classList.remove('show');
        }, 2000);
    }

    // Event Listeners
    function initEventListeners() {
        elements.themeToggle.addEventListener('click', toggleTheme);
        elements.fontSize.addEventListener('change', updateFontSize);
        elements.previewToggle.addEventListener('change', togglePreview);
        elements.btnCopy.addEventListener('click', copyCode);
        elements.btnDownload.addEventListener('click', downloadCode);
        elements.btnClear.addEventListener('click', clearEditor);
        elements.btnRun.addEventListener('click', runPreview);

        elements.tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                switchTab(this.dataset.tab);
            });
        });

        // System theme change listener
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            if (localStorage.getItem('darkMode') === null) {
                setTheme(e.matches);
            }
        });
    }

    // Initialize
    function init() {
        initTheme();
        initEventListeners();
        initMonaco();
    }

    // Start when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
</script>
@endpush
