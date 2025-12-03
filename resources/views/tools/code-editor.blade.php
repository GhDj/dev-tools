@extends('layouts.app')

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

@push('styles')
<style>
    .ace-editor-container {
        height: 500px;
        width: 100%;
    }
    .tab-active {
        border-bottom: 2px solid #4f46e5 !important;
        color: #4f46e5 !important;
    }
    .dark .tab-active {
        border-bottom-color: #818cf8 !important;
        color: #818cf8 !important;
    }
    .toast {
        animation: slideIn 0.3s ease-out;
    }
    @keyframes slideIn {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endpush

@section('content')
<div x-data="codeEditor()" class="space-y-4">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Code Editor</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Write HTML, CSS, JS with live preview</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <!-- Toolbar -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-3">
        <div class="flex flex-wrap items-center gap-3">
            <!-- Font Size -->
            <div class="flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Font:</label>
                <select x-model="fontSize" @change="updateFontSize()" class="text-sm px-2 py-1 rounded border border-gray-300 dark:border-dark-border bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100">
                    <option value="12">12px</option>
                    <option value="14">14px</option>
                    <option value="16">16px</option>
                    <option value="18">18px</option>
                    <option value="20">20px</option>
                </select>
            </div>

            <div class="h-6 w-px bg-gray-300 dark:bg-dark-border hidden sm:block"></div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <button @click="copyCode()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-dark-border transition-colors" title="Copy Code">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </button>
                <button @click="downloadCode()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-dark-border transition-colors" title="Download File">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </button>
                <button @click="clearEditor()" class="p-2 rounded hover:bg-gray-100 dark:hover:bg-dark-border transition-colors" title="Clear Editor">
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>

            <div class="flex-1"></div>

            <!-- Preview Toggle -->
            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 cursor-pointer">
                <input type="checkbox" x-model="showPreview" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500">
                Live Preview
            </label>
        </div>
    </div>

    <!-- Main Editor Area -->
    <div class="grid gap-4" :class="showPreview ? 'lg:grid-cols-2' : 'grid-cols-1'">
        <!-- Editor Panel -->
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border overflow-hidden">
            <!-- Tabs -->
            <div class="flex items-center border-b border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg">
                <template x-for="tab in tabs" :key="tab.id">
                    <button
                        @click="switchTab(tab.id)"
                        class="px-4 py-2 text-sm font-medium border-b-2 border-transparent hover:bg-gray-100 dark:hover:bg-dark-border transition-colors"
                        :class="activeTab === tab.id ? 'tab-active bg-white dark:bg-dark-card' : 'text-gray-600 dark:text-gray-400'"
                        x-text="tab.name"
                    ></button>
                </template>
            </div>

            <!-- Ace Editor Container -->
            <div id="ace-editor" class="ace-editor-container"></div>
        </div>

        <!-- Preview Panel -->
        <div x-show="showPreview" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border overflow-hidden">
            <div class="flex items-center justify-between px-4 py-2 border-b border-gray-200 dark:border-dark-border bg-gray-50 dark:bg-dark-bg">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Preview</span>
                <button @click="runPreview()" class="flex items-center gap-1 px-3 py-1 text-sm bg-green-600 hover:bg-green-700 text-white rounded transition-colors">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    Run
                </button>
            </div>
            <div style="height: 500px;" class="bg-white">
                <iframe
                    id="preview-frame"
                    style="width: 100%; height: 100%; border: 0;"
                    sandbox="allow-scripts allow-modals"
                ></iframe>
            </div>
        </div>
    </div>

    <!-- Status Bar -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border px-4 py-2">
        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
            <div class="flex items-center gap-4">
                <span>Ln <span x-text="cursorLine">1</span>, Col <span x-text="cursorColumn">1</span></span>
                <span x-text="tabs.find(t => t.id === activeTab)?.name || ''"></span>
            </div>
            <span>UTF-8</span>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="toast.show" x-transition class="fixed bottom-4 right-4 z-50 toast">
        <div class="px-4 py-2 rounded-lg shadow-lg text-sm text-white" :class="toast.type === 'success' ? 'bg-green-600' : 'bg-red-600'">
            <span x-text="toast.message"></span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.32.2/src-min-noconflict/ace.min.js"></script>
<script>
function codeEditor() {
    return {
        editor: null,
        activeTab: 'html',
        tabs: [
            { id: 'html', name: 'index.html', mode: 'html', content: '<!DOCTYPE html>\n<html lang="en">\n<head>\n    <meta charset="UTF-8">\n    <title>My Page</title>\n</head>\n<body>\n    <h1>Hello, World!</h1>\n    <p>Start editing to see the magic happen.</p>\n</body>\n</html>' },
            { id: 'css', name: 'style.css', mode: 'css', content: '/* Add your styles here */\n\nbody {\n    font-family: system-ui, sans-serif;\n    max-width: 800px;\n    margin: 0 auto;\n    padding: 2rem;\n    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);\n    min-height: 100vh;\n}\n\nh1 {\n    color: white;\n}\n\np {\n    color: rgba(255,255,255,0.9);\n}' },
            { id: 'js', name: 'script.js', mode: 'javascript', content: '// Add your JavaScript here\n\nconsole.log("Hello from the code editor!");' }
        ],
        fontSize: '14',
        showPreview: true,
        cursorLine: 1,
        cursorColumn: 1,
        toast: { show: false, message: '', type: 'success' },
        debounceTimer: null,

        init() {
            this.$nextTick(() => {
                this.initAce();
            });
        },

        initAce() {
            // Initialize Ace Editor
            this.editor = ace.edit('ace-editor');

            // Set theme based on dark mode
            const isDark = document.documentElement.classList.contains('dark');
            this.editor.setTheme(isDark ? 'ace/theme/monokai' : 'ace/theme/chrome');

            // Set initial content
            const tab = this.getCurrentTab();
            this.editor.session.setMode('ace/mode/' + tab.mode);
            this.editor.setValue(tab.content, -1);

            // Editor options
            this.editor.setOptions({
                fontSize: this.fontSize + 'px',
                showPrintMargin: false,
                tabSize: 4,
                useSoftTabs: true,
                wrap: true
            });

            // Track cursor position
            this.editor.selection.on('changeCursor', () => {
                const pos = this.editor.getCursorPosition();
                this.cursorLine = pos.row + 1;
                this.cursorColumn = pos.column + 1;
            });

            // Auto-update preview on change
            this.editor.session.on('change', () => {
                // Save to current tab
                const tab = this.getCurrentTab();
                if (tab) {
                    tab.content = this.editor.getValue();
                }

                // Debounced preview update
                if (this.showPreview) {
                    clearTimeout(this.debounceTimer);
                    this.debounceTimer = setTimeout(() => {
                        this.runPreview();
                    }, 500);
                }
            });

            // Watch for theme changes
            const observer = new MutationObserver(() => {
                const isDark = document.documentElement.classList.contains('dark');
                this.editor.setTheme(isDark ? 'ace/theme/monokai' : 'ace/theme/chrome');
            });
            observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

            // Initial preview
            setTimeout(() => this.runPreview(), 100);
        },

        getCurrentTab() {
            return this.tabs.find(t => t.id === this.activeTab);
        },

        switchTab(tabId) {
            if (tabId === this.activeTab) return;

            // Save current content
            const currentTab = this.getCurrentTab();
            if (currentTab && this.editor) {
                currentTab.content = this.editor.getValue();
            }

            // Switch tab
            this.activeTab = tabId;
            const newTab = this.getCurrentTab();

            if (newTab && this.editor) {
                this.editor.session.setMode('ace/mode/' + newTab.mode);
                this.editor.setValue(newTab.content, -1);
            }
        },

        updateFontSize() {
            if (this.editor) {
                this.editor.setFontSize(this.fontSize + 'px');
            }
        },

        runPreview() {
            // Save current editor content
            const currentTab = this.getCurrentTab();
            if (currentTab && this.editor) {
                currentTab.content = this.editor.getValue();
            }

            const htmlTab = this.tabs.find(t => t.id === 'html');
            const cssTab = this.tabs.find(t => t.id === 'css');
            const jsTab = this.tabs.find(t => t.id === 'js');

            const html = htmlTab ? htmlTab.content : '';
            const css = cssTab ? cssTab.content : '';
            const js = jsTab ? jsTab.content : '';

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

            const iframe = document.getElementById('preview-frame');
            if (iframe) {
                iframe.srcdoc = previewHtml;
            }
        },

        copyCode() {
            if (!this.editor) return;

            const content = this.editor.getValue();
            navigator.clipboard.writeText(content).then(() => {
                this.showToast('Copied to clipboard', 'success');
            }).catch(() => {
                this.showToast('Failed to copy', 'error');
            });
        },

        downloadCode() {
            if (!this.editor) return;

            const tab = this.getCurrentTab();
            if (!tab) return;

            const content = this.editor.getValue();
            DevTools.downloadFile(content, tab.name);
            this.showToast(`Downloaded ${tab.name}`, 'success');
        },

        clearEditor() {
            if (this.editor) {
                this.editor.setValue('');
                this.showToast('Editor cleared', 'success');
            }
        },

        showToast(message, type = 'success') {
            this.toast = { show: true, message, type };
            setTimeout(() => {
                this.toast.show = false;
            }, 2000);
        }
    };
}
</script>
@endpush
