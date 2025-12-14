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
            <button id="btn-download" class="btn" title="Download current file">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download
            </button>
            <button id="btn-download-zip" class="btn" title="Download all files as ZIP">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                </svg>
                ZIP
            </button>
            <button id="btn-clear" class="btn" title="Clear editor">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Clear
            </button>
        </div>

        <div class="toolbar-group">
            <label title="Toggle word wrap">
                <input type="checkbox" id="word-wrap-toggle" checked>
                Wrap
            </label>
            <label title="Toggle minimap">
                <input type="checkbox" id="minimap-toggle">
                Minimap
            </label>
        </div>

        <div style="flex:1;"></div>

        <div class="toolbar-group">
            <button id="btn-shortcuts" class="btn" title="Keyboard shortcuts">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                </svg>
            </button>
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
            <div class="tabs" id="tabs-container">
                <button class="tab active" data-tab="html">
                    <span class="tab-name">index.html</span>
                    <span class="tab-close" data-tab="html" title="Close tab">&times;</span>
                </button>
                <button class="tab" data-tab="css">
                    <span class="tab-name">style.css</span>
                    <span class="tab-close" data-tab="css" title="Close tab">&times;</span>
                </button>
                <button class="tab" data-tab="js">
                    <span class="tab-name">script.js</span>
                    <span class="tab-close" data-tab="js" title="Close tab">&times;</span>
                </button>
                <button class="tab-add" id="btn-add-tab" title="Add new file">+</button>
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
            <div class="preview-container">
                <iframe id="preview-frame" sandbox="allow-scripts allow-modals"></iframe>
            </div>
            <!-- Console Panel -->
            <div id="console-panel" class="console-panel">
                <div class="console-header">
                    <span>Console</span>
                    <div class="console-actions">
                        <button id="btn-clear-console" class="btn-console" title="Clear console">Clear</button>
                        <button id="btn-toggle-console" class="btn-console" title="Toggle console">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="14" height="14">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="console-output" class="console-output"></div>
            </div>
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

<!-- Keyboard Shortcuts Modal -->
<div id="shortcuts-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Keyboard Shortcuts</h3>
            <button id="close-shortcuts" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <div class="shortcut-section">
                <h4>General</h4>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>S</kbd></span><span>Save (Download)</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>Z</kbd></span><span>Undo</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>Z</kbd></span><span>Redo</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>F1</kbd></span><span>Command Palette</span></div>
            </div>
            <div class="shortcut-section">
                <h4>Find & Replace</h4>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>F</kbd></span><span>Find</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>H</kbd></span><span>Find and Replace</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>G</kbd></span><span>Go to Line</span></div>
            </div>
            <div class="shortcut-section">
                <h4>Editing</h4>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>/</kbd></span><span>Toggle Comment</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>D</kbd></span><span>Add Selection to Next Match</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>Shift</kbd>+<kbd>K</kbd></span><span>Delete Line</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Alt</kbd>+<kbd>↑</kbd>/<kbd>↓</kbd></span><span>Move Line Up/Down</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>[</kbd>/<kbd>]</kbd></span><span>Indent/Outdent</span></div>
            </div>
            <div class="shortcut-section">
                <h4>Selection</h4>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>A</kbd></span><span>Select All</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Ctrl</kbd>+<kbd>L</kbd></span><span>Select Line</span></div>
                <div class="shortcut-row"><span class="keys"><kbd>Alt</kbd>+<kbd>Click</kbd></span><span>Add Cursor</span></div>
            </div>
        </div>
    </div>
</div>

<!-- New File Modal -->
<div id="new-file-modal" class="modal">
    <div class="modal-content modal-sm">
        <div class="modal-header">
            <h3>New File</h3>
            <button id="close-new-file" class="modal-close">&times;</button>
        </div>
        <div class="modal-body">
            <label for="new-file-name">Filename:</label>
            <input type="text" id="new-file-name" placeholder="e.g., app.js, utils.php" autocomplete="off">
            <p class="hint">Supported: .html, .css, .js, .json, .php, .sql</p>
        </div>
        <div class="modal-footer">
            <button id="cancel-new-file" class="btn">Cancel</button>
            <button id="confirm-new-file" class="btn btn-primary">Create</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- JSZip for Download All -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script>
(function() {
    'use strict';

    // Language mappings
    const EXTENSIONS = {
        'html': { language: 'html', mime: 'text/html' },
        'htm': { language: 'html', mime: 'text/html' },
        'css': { language: 'css', mime: 'text/css' },
        'js': { language: 'javascript', mime: 'text/javascript' },
        'json': { language: 'json', mime: 'application/json' },
        'php': { language: 'php', mime: 'application/x-php' },
        'sql': { language: 'sql', mime: 'text/x-sql' }
    };

    // State
    const state = {
        editor: null,
        monaco: null,
        activeTab: 'html',
        tabCounter: 3,
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
        wordWrapToggle: document.getElementById('word-wrap-toggle'),
        minimapToggle: document.getElementById('minimap-toggle'),
        btnCopy: document.getElementById('btn-copy'),
        btnDownload: document.getElementById('btn-download'),
        btnDownloadZip: document.getElementById('btn-download-zip'),
        btnClear: document.getElementById('btn-clear'),
        btnRun: document.getElementById('btn-run'),
        btnShortcuts: document.getElementById('btn-shortcuts'),
        btnAddTab: document.getElementById('btn-add-tab'),
        tabsContainer: document.getElementById('tabs-container'),
        // Modals
        shortcutsModal: document.getElementById('shortcuts-modal'),
        closeShortcuts: document.getElementById('close-shortcuts'),
        newFileModal: document.getElementById('new-file-modal'),
        closeNewFile: document.getElementById('close-new-file'),
        cancelNewFile: document.getElementById('cancel-new-file'),
        confirmNewFile: document.getElementById('confirm-new-file'),
        newFileName: document.getElementById('new-file-name'),
        // Console
        consolePanel: document.getElementById('console-panel'),
        consoleOutput: document.getElementById('console-output'),
        btnClearConsole: document.getElementById('btn-clear-console'),
        btnToggleConsole: document.getElementById('btn-toggle-console')
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
                minimap: { enabled: elements.minimapToggle.checked },
                wordWrap: elements.wordWrapToggle.checked ? 'on' : 'off',
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

            // Add Ctrl+S keybinding for save/download
            state.editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KeyS, function() {
                downloadCode();
            });

            // Initial preview
            setTimeout(runPreview, 100);
        });
    }

    // Tab Management
    function renderTabs() {
        const tabsHtml = Object.keys(state.tabs).map(function(tabId) {
            const tab = state.tabs[tabId];
            const isActive = tabId === state.activeTab;
            return `<button class="tab ${isActive ? 'active' : ''}" data-tab="${tabId}">
                <span class="tab-name">${tab.name}</span>
                <span class="tab-close" data-tab="${tabId}" title="Close tab">&times;</span>
            </button>`;
        }).join('');

        elements.tabsContainer.innerHTML = tabsHtml +
            '<button class="tab-add" id="btn-add-tab" title="Add new file">+</button>';

        // Re-bind events
        bindTabEvents();
    }

    function bindTabEvents() {
        // Tab click events
        document.querySelectorAll('.tab').forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                if (!e.target.classList.contains('tab-close')) {
                    switchTab(this.dataset.tab);
                }
            });
        });

        // Close tab events
        document.querySelectorAll('.tab-close').forEach(function(closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                closeTab(this.dataset.tab);
            });
        });

        // Add tab button
        const addBtn = document.getElementById('btn-add-tab');
        if (addBtn) {
            addBtn.addEventListener('click', showNewFileModal);
        }
    }

    function switchTab(tabId) {
        if (!state.tabs[tabId] || tabId === state.activeTab) return;

        // Save current content
        if (state.editor) {
            state.tabs[state.activeTab].content = state.editor.getValue();
        }

        // Update active tab
        state.activeTab = tabId;
        const tab = state.tabs[tabId];

        // Update UI
        document.querySelectorAll('.tab').forEach(function(t) {
            t.classList.toggle('active', t.dataset.tab === tabId);
        });
        elements.currentFile.textContent = tab.name;

        // Update editor
        if (state.editor && state.monaco) {
            const model = state.monaco.editor.createModel(tab.content, tab.language);
            state.editor.setModel(model);
        }
    }

    function closeTab(tabId) {
        const tabKeys = Object.keys(state.tabs);
        if (tabKeys.length <= 1) {
            showToast('Cannot close the last tab', 'error');
            return;
        }

        // Find next tab to switch to
        const currentIndex = tabKeys.indexOf(tabId);
        let nextTab;
        if (tabId === state.activeTab) {
            nextTab = tabKeys[currentIndex === 0 ? 1 : currentIndex - 1];
        }

        // Remove the tab
        delete state.tabs[tabId];
        renderTabs();

        // Switch to next tab if needed
        if (nextTab) {
            switchTab(nextTab);
        }

        showToast('Tab closed', 'success');
    }

    function addNewTab(filename) {
        // Get extension and language
        const ext = filename.split('.').pop().toLowerCase();
        const extInfo = EXTENSIONS[ext];

        if (!extInfo) {
            showToast('Unsupported file type', 'error');
            return false;
        }

        // Check for duplicate names
        for (const key in state.tabs) {
            if (state.tabs[key].name === filename) {
                showToast('File already exists', 'error');
                return false;
            }
        }

        // Create unique ID
        state.tabCounter++;
        const tabId = 'tab_' + state.tabCounter;

        // Add the tab
        state.tabs[tabId] = {
            name: filename,
            language: extInfo.language,
            content: getDefaultContent(extInfo.language)
        };

        renderTabs();
        switchTab(tabId);
        showToast('Created ' + filename, 'success');
        return true;
    }

    function getDefaultContent(language) {
        switch (language) {
            case 'html': return '<!DOCTYPE html>\n<html>\n<head>\n    <title></title>\n</head>\n<body>\n    \n</body>\n</html>';
            case 'css': return '/* Styles */\n';
            case 'javascript': return '// JavaScript\n';
            case 'json': return '{\n    \n}';
            case 'php': return '<?php\n\n';
            case 'sql': return '-- SQL Query\n';
            default: return '';
        }
    }

    // Preview with Console Capture
    function runPreview() {
        // Clear console
        elements.consoleOutput.innerHTML = '';

        const html = state.tabs.html ? state.tabs.html.content : '';
        const css = state.tabs.css ? state.tabs.css.content : '';
        const js = state.tabs.js ? state.tabs.js.content : '';

        // Extract body content or use full HTML
        let bodyContent = html;
        const bodyMatch = html.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
        if (bodyMatch) {
            bodyContent = bodyMatch[1];
        }

        // Console capture script
        const consoleCapture = `
<script>
(function() {
    const originalConsole = {
        log: console.log,
        info: console.info,
        warn: console.warn,
        error: console.error
    };

    function sendToParent(type, args) {
        try {
            const message = Array.from(args).map(function(arg) {
                if (typeof arg === 'object') {
                    try { return JSON.stringify(arg, null, 2); }
                    catch (e) { return String(arg); }
                }
                return String(arg);
            }).join(' ');

            window.parent.postMessage({ type: 'console', level: type, message: message }, '*');
        } catch (e) {}
    }

    console.log = function() { sendToParent('log', arguments); originalConsole.log.apply(console, arguments); };
    console.info = function() { sendToParent('info', arguments); originalConsole.info.apply(console, arguments); };
    console.warn = function() { sendToParent('warn', arguments); originalConsole.warn.apply(console, arguments); };
    console.error = function() { sendToParent('error', arguments); originalConsole.error.apply(console, arguments); };

    window.onerror = function(msg, url, line, col, error) {
        sendToParent('error', [msg + ' (line ' + line + ')']);
        return false;
    };
})();
<\/script>`;

        const previewHtml = `<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    ${consoleCapture}
    <style>${css}</style>
</head>
<body>
    ${bodyContent}
    <script>${js}<\/script>
</body>
</html>`;

        elements.previewFrame.srcdoc = previewHtml;
    }

    function addConsoleLine(level, message) {
        const line = document.createElement('div');
        line.className = 'console-line ' + level;

        const prefix = document.createElement('span');
        prefix.className = 'console-line-prefix';
        prefix.textContent = level === 'log' ? '>' : level.toUpperCase();

        const content = document.createElement('span');
        content.className = 'console-line-content';
        content.textContent = message;

        line.appendChild(prefix);
        line.appendChild(content);
        elements.consoleOutput.appendChild(line);
        elements.consoleOutput.scrollTop = elements.consoleOutput.scrollHeight;
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

    function downloadAllAsZip() {
        if (typeof JSZip === 'undefined') {
            showToast('JSZip not loaded', 'error');
            return;
        }

        // Save current editor content
        if (state.editor) {
            state.tabs[state.activeTab].content = state.editor.getValue();
        }

        const zip = new JSZip();

        // Add all files to zip
        for (const tabId in state.tabs) {
            const tab = state.tabs[tabId];
            zip.file(tab.name, tab.content);
        }

        // Generate and download
        zip.generateAsync({ type: 'blob' }).then(function(blob) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'project.zip';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
            showToast('Downloaded project.zip', 'success');
        }).catch(function() {
            showToast('Failed to create ZIP', 'error');
        });
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

    function toggleWordWrap() {
        if (state.editor) {
            state.editor.updateOptions({ wordWrap: elements.wordWrapToggle.checked ? 'on' : 'off' });
        }
    }

    function toggleMinimap() {
        if (state.editor) {
            state.editor.updateOptions({ minimap: { enabled: elements.minimapToggle.checked } });
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

    function toggleConsole() {
        elements.consolePanel.classList.toggle('collapsed');
    }

    function clearConsole() {
        elements.consoleOutput.innerHTML = '';
    }

    // Modals
    function showShortcutsModal() {
        elements.shortcutsModal.classList.add('show');
    }

    function hideShortcutsModal() {
        elements.shortcutsModal.classList.remove('show');
    }

    function showNewFileModal() {
        elements.newFileName.value = '';
        elements.newFileModal.classList.add('show');
        setTimeout(function() {
            elements.newFileName.focus();
        }, 100);
    }

    function hideNewFileModal() {
        elements.newFileModal.classList.remove('show');
    }

    function handleNewFileConfirm() {
        const filename = elements.newFileName.value.trim();
        if (!filename) {
            showToast('Please enter a filename', 'error');
            return;
        }

        if (addNewTab(filename)) {
            hideNewFileModal();
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
        // Theme
        elements.themeToggle.addEventListener('click', toggleTheme);

        // Editor options
        elements.fontSize.addEventListener('change', updateFontSize);
        elements.wordWrapToggle.addEventListener('change', toggleWordWrap);
        elements.minimapToggle.addEventListener('change', toggleMinimap);
        elements.previewToggle.addEventListener('change', togglePreview);

        // Actions
        elements.btnCopy.addEventListener('click', copyCode);
        elements.btnDownload.addEventListener('click', downloadCode);
        elements.btnDownloadZip.addEventListener('click', downloadAllAsZip);
        elements.btnClear.addEventListener('click', clearEditor);
        elements.btnRun.addEventListener('click', runPreview);
        elements.btnShortcuts.addEventListener('click', showShortcutsModal);

        // Shortcuts modal
        elements.closeShortcuts.addEventListener('click', hideShortcutsModal);
        elements.shortcutsModal.addEventListener('click', function(e) {
            if (e.target === elements.shortcutsModal) hideShortcutsModal();
        });

        // New file modal
        elements.closeNewFile.addEventListener('click', hideNewFileModal);
        elements.cancelNewFile.addEventListener('click', hideNewFileModal);
        elements.confirmNewFile.addEventListener('click', handleNewFileConfirm);
        elements.newFileName.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') handleNewFileConfirm();
        });
        elements.newFileModal.addEventListener('click', function(e) {
            if (e.target === elements.newFileModal) hideNewFileModal();
        });

        // Console
        elements.btnClearConsole.addEventListener('click', clearConsole);
        elements.btnToggleConsole.addEventListener('click', toggleConsole);

        // Listen for console messages from iframe
        window.addEventListener('message', function(e) {
            if (e.data && e.data.type === 'console') {
                addConsoleLine(e.data.level, e.data.message);
            }
        });

        // Initial tab bindings
        bindTabEvents();

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Escape closes modals
            if (e.key === 'Escape') {
                hideShortcutsModal();
                hideNewFileModal();
            }
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
