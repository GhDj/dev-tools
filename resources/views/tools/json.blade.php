@extends('layouts.app')

@section('title', 'JSON Formatter, Validator & Tree Viewer - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online JSON formatter, validator, and tree viewer. Format, beautify, minify, validate, repair JSON data and visualize structure with interactive tree view. Copy paths and values easily.')
@section('meta_keywords', 'json formatter, json validator, json beautifier, json minifier, json tree view, json visualizer, format json online, validate json, json parser, json path, free json tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "JSON Parser & Tree Viewer",
    "description": "Format, minify, validate, repair JSON data and visualize with interactive tree view",
    "url": "{{ route('tools.json') }}",
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
<div x-data="jsonParser()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">JSON Parser</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Format, minify, validate, and repair JSON</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">JSON Input</label>
                <textarea
                    x-model="input"
                    @input="clearValidation()"
                    class="textarea-code w-full h-64 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder='{"name": "John", "age": 30, "active": true}'
                ></textarea>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="process('format')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Format
                    </button>
                    <button
                        @click="process('minify')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Minify
                    </button>
                    <button
                        @click="process('validate')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-green-600 hover:bg-green-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Validate
                    </button>
                </div>
                <div class="flex flex-wrap gap-2 mt-2">
                    <button
                        @click="process('sort')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-purple-600 hover:bg-purple-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Sort Keys
                    </button>
                    <button
                        @click="process('repair')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-orange-600 hover:bg-orange-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Repair
                    </button>
                    <button
                        @click="showTreeView()"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-teal-600 hover:bg-teal-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Tree View
                    </button>
                </div>
            </div>

            <!-- Validation Result -->
            <div x-show="validation" x-cloak class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Validation Result</h3>
                <template x-if="validation && validation.valid">
                    <div class="space-y-2">
                        <div class="flex items-center text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span class="font-medium">Valid JSON</span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            <p><span class="font-medium">Type:</span> <span x-text="validation.type"></span></p>
                            <template x-if="validation.stats">
                                <div class="grid grid-cols-2 gap-1 mt-2">
                                    <p><span class="font-medium">Objects:</span> <span x-text="validation.stats.objects"></span></p>
                                    <p><span class="font-medium">Arrays:</span> <span x-text="validation.stats.arrays"></span></p>
                                    <p><span class="font-medium">Strings:</span> <span x-text="validation.stats.strings"></span></p>
                                    <p><span class="font-medium">Numbers:</span> <span x-text="validation.stats.numbers"></span></p>
                                    <p><span class="font-medium">Booleans:</span> <span x-text="validation.stats.booleans"></span></p>
                                    <p><span class="font-medium">Nulls:</span> <span x-text="validation.stats.nulls"></span></p>
                                    <p class="col-span-2"><span class="font-medium">Max Depth:</span> <span x-text="validation.stats.max_depth"></span></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
                <template x-if="validation && !validation.valid">
                    <div class="flex items-start text-red-600 dark:text-red-400">
                        <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <div>
                            <span class="font-medium">Invalid JSON</span>
                            <p class="text-sm mt-1" x-text="validation.error"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <!-- Output Header with View Toggle -->
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Output</label>
                    <div x-show="treeData" class="flex rounded-lg border border-gray-300 dark:border-dark-border overflow-hidden">
                        <button
                            @click="viewMode = 'text'"
                            :class="viewMode === 'text' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-dark-bg text-gray-600 dark:text-gray-400'"
                            class="px-2 py-1 text-xs font-medium transition-colors"
                        >
                            Text
                        </button>
                        <button
                            @click="viewMode = 'tree'"
                            :class="viewMode === 'tree' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-dark-bg text-gray-600 dark:text-gray-400'"
                            class="px-2 py-1 text-xs font-medium transition-colors"
                        >
                            Tree
                        </button>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Expand/Collapse All for Tree View -->
                    <template x-if="viewMode === 'tree' && treeData">
                        <div class="flex gap-1">
                            <button
                                @click="expandAll()"
                                class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                title="Expand all"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                </svg>
                            </button>
                            <button
                                @click="collapseAll()"
                                class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                title="Collapse all"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9V4.5M9 9H4.5M9 9L3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5l5.25 5.25"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                    <button
                        x-show="output && viewMode === 'text'"
                        @click="copy($event.currentTarget)"
                        class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Copy to clipboard"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="error" x-text="error" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm mb-2"></div>

            <!-- Text Output -->
            <textarea
                x-show="viewMode === 'text'"
                x-model="output"
                readonly
                class="textarea-code w-full h-80 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                placeholder="Formatted JSON will appear here..."
            ></textarea>

            <!-- Tree View Output -->
            <div
                x-show="viewMode === 'tree'"
                x-cloak
                class="w-full h-80 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 overflow-auto font-mono text-sm"
            >
                <template x-if="treeData !== null">
                    <div x-html="renderTree(treeData, '$')"></div>
                </template>
                <template x-if="treeData === null && !treeError">
                    <p class="text-gray-400 dark:text-gray-500">Click "Tree View" to visualize JSON structure...</p>
                </template>
                <template x-if="treeError">
                    <p class="text-red-500" x-text="treeError"></p>
                </template>
            </div>
        </div>
    </div>

    <!-- Copy Notification Toast -->
    <div
        x-show="copyNotification"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="fixed bottom-4 right-4 px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white text-sm rounded-lg shadow-lg z-50"
        x-text="copyNotification"
    ></div>
</div>
@endsection

@push('scripts')
<script>
// Global reference for tree view interactions
window.jsonParserInstance = null;

function jsonParser() {
    return {
        input: '',
        output: '',
        error: '',
        loading: false,
        validation: null,
        viewMode: 'text',
        treeData: null,
        treeError: '',
        expandedNodes: new Set(),
        copyNotification: '',

        init() {
            window.jsonParserInstance = this;
        },

        clearValidation() {
            this.validation = null;
        },

        async process(mode) {
            if (!this.input.trim()) return;

            this.loading = true;
            this.error = '';
            this.output = '';
            this.validation = null;

            try {
                const result = await DevTools.post('/api/v1/json/format', {
                    json: this.input,
                    mode: mode,
                });

                if (result.success) {
                    if (mode === 'validate') {
                        this.validation = result.validation;
                    } else {
                        this.output = result.result;
                    }
                } else {
                    this.error = result.error || 'Processing failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        showTreeView() {
            if (!this.input.trim()) return;

            this.treeError = '';
            try {
                this.treeData = JSON.parse(this.input);
                this.viewMode = 'tree';
                // Expand first level by default
                this.expandedNodes = new Set(['$']);
            } catch (e) {
                this.treeError = 'Invalid JSON: ' + e.message;
                this.treeData = null;
                this.viewMode = 'tree';
            }
        },

        toggleNode(path) {
            if (this.expandedNodes.has(path)) {
                this.expandedNodes.delete(path);
            } else {
                this.expandedNodes.add(path);
            }
            // Force re-render
            this.treeData = JSON.parse(JSON.stringify(this.treeData));
        },

        expandAll() {
            this.collectAllPaths(this.treeData, '$');
            this.treeData = JSON.parse(JSON.stringify(this.treeData));
        },

        collectAllPaths(data, path) {
            this.expandedNodes.add(path);
            if (data && typeof data === 'object') {
                if (Array.isArray(data)) {
                    data.forEach((item, index) => {
                        this.collectAllPaths(item, `${path}[${index}]`);
                    });
                } else {
                    Object.keys(data).forEach(key => {
                        const childPath = `${path}.${key}`;
                        this.collectAllPaths(data[key], childPath);
                    });
                }
            }
        },

        collapseAll() {
            this.expandedNodes = new Set();
            this.treeData = JSON.parse(JSON.stringify(this.treeData));
        },

        copyPath(path) {
            navigator.clipboard.writeText(path).then(() => {
                this.showCopyNotification('Path copied!');
            });
        },

        copyValue(value) {
            const text = typeof value === 'object' ? JSON.stringify(value, null, 2) : String(value);
            navigator.clipboard.writeText(text).then(() => {
                this.showCopyNotification('Value copied!');
            });
        },

        showCopyNotification(message) {
            this.copyNotification = message;
            setTimeout(() => {
                this.copyNotification = '';
            }, 2000);
        },

        getType(value) {
            if (value === null) return 'null';
            if (Array.isArray(value)) return 'array';
            return typeof value;
        },

        getTypeColor(type) {
            const colors = {
                'string': 'text-green-600 dark:text-green-400',
                'number': 'text-blue-600 dark:text-blue-400',
                'boolean': 'text-purple-600 dark:text-purple-400',
                'null': 'text-gray-500 dark:text-gray-400',
                'object': 'text-yellow-600 dark:text-yellow-400',
                'array': 'text-orange-600 dark:text-orange-400'
            };
            return colors[type] || 'text-gray-600 dark:text-gray-300';
        },

        getTypeIcon(type) {
            const icons = {
                'string': '"S"',
                'number': '#',
                'boolean': '?',
                'null': '0',
                'object': '{}',
                'array': '[]'
            };
            return icons[type] || '?';
        },

        escapeHtml(str) {
            const div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        },

        formatValue(value) {
            const type = this.getType(value);
            if (type === 'string') {
                const escaped = this.escapeHtml(value);
                const truncated = escaped.length > 50 ? escaped.substring(0, 50) + '...' : escaped;
                return `"${truncated}"`;
            }
            if (type === 'null') return 'null';
            if (type === 'boolean') return value ? 'true' : 'false';
            if (type === 'number') return String(value);
            return '';
        },

        renderTree(data, path, depth = 0) {
            const type = this.getType(data);
            const isExpanded = this.expandedNodes.has(path);
            const indent = depth * 16;

            if (type !== 'object' && type !== 'array') {
                return this.renderLeaf(data, path, type);
            }

            const isArray = type === 'array';
            const items = isArray ? data : Object.entries(data);
            const count = isArray ? data.length : Object.keys(data).length;
            const bracket = isArray ? ['[', ']'] : ['{', '}'];

            let html = `<div class="tree-node" style="padding-left: ${indent}px;">`;

            // Toggle button and bracket
            html += `<span class="inline-flex items-center">`;
            if (count > 0) {
                html += `<button onclick="window.jsonParserInstance.toggleNode('${path}')" class="w-4 h-4 mr-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 focus:outline-none">`;
                html += isExpanded
                    ? `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>`
                    : `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>`;
                html += `</button>`;
            } else {
                html += `<span class="w-4 h-4 mr-1"></span>`;
            }

            html += `<span class="${this.getTypeColor(type)} font-medium">${bracket[0]}</span>`;
            html += `<span class="text-gray-400 text-xs ml-1">${count} ${isArray ? 'items' : 'keys'}</span>`;

            // Copy buttons
            html += `<button onclick="window.jsonParserInstance.copyPath('${path}')" class="ml-2 text-gray-400 hover:text-indigo-500 text-xs" title="Copy path">path</button>`;
            html += `<button onclick="window.jsonParserInstance.copyValue(${this.escapeHtml(JSON.stringify(data))})" class="ml-1 text-gray-400 hover:text-indigo-500 text-xs" title="Copy value">value</button>`;

            html += `</span></div>`;

            // Children
            if (isExpanded && count > 0) {
                if (isArray) {
                    data.forEach((item, index) => {
                        const childPath = `${path}[${index}]`;
                        const childType = this.getType(item);
                        html += `<div style="padding-left: ${indent + 16}px;" class="tree-item flex items-start py-0.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">`;
                        html += `<span class="text-gray-500 mr-2">${index}:</span>`;

                        if (childType === 'object' || childType === 'array') {
                            html += `</div>`;
                            html += this.renderTree(item, childPath, depth + 1);
                        } else {
                            html += `<span class="${this.getTypeColor(childType)}">${this.formatValue(item)}</span>`;
                            html += `<button onclick="window.jsonParserInstance.copyPath('${childPath}')" class="ml-2 text-gray-400 hover:text-indigo-500 text-xs opacity-0 group-hover:opacity-100" title="Copy path">path</button>`;
                            html += `</div>`;
                        }
                    });
                } else {
                    Object.entries(data).forEach(([key, value]) => {
                        const childPath = `${path}.${key}`;
                        const childType = this.getType(value);
                        html += `<div style="padding-left: ${indent + 16}px;" class="tree-item flex items-start py-0.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">`;
                        html += `<span class="text-indigo-600 dark:text-indigo-400 mr-2">"${this.escapeHtml(key)}":</span>`;

                        if (childType === 'object' || childType === 'array') {
                            html += `</div>`;
                            html += this.renderTree(value, childPath, depth + 1);
                        } else {
                            html += `<span class="${this.getTypeColor(childType)}">${this.formatValue(value)}</span>`;
                            html += `<button onclick="window.jsonParserInstance.copyPath('${childPath}')" class="ml-2 text-gray-400 hover:text-indigo-500 text-xs" title="Copy path">path</button>`;
                            html += `</div>`;
                        }
                    });
                }

                // Closing bracket
                html += `<div style="padding-left: ${indent}px;"><span class="${this.getTypeColor(type)} font-medium">${bracket[1]}</span></div>`;
            } else if (!isExpanded && count > 0) {
                // Collapsed indicator
                html += `<div style="padding-left: ${indent}px;"><span class="text-gray-400">...</span> <span class="${this.getTypeColor(type)} font-medium">${bracket[1]}</span></div>`;
            } else {
                // Empty object/array
                html += `<div style="padding-left: ${indent}px;"><span class="${this.getTypeColor(type)} font-medium">${bracket[1]}</span></div>`;
            }

            return html;
        },

        renderLeaf(data, path, type) {
            let html = `<div class="tree-leaf flex items-center py-0.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded">`;
            html += `<span class="${this.getTypeColor(type)}">${this.formatValue(data)}</span>`;
            html += `<button onclick="window.jsonParserInstance.copyPath('${path}')" class="ml-2 text-gray-400 hover:text-indigo-500 text-xs" title="Copy path">path</button>`;
            html += `<button onclick="window.jsonParserInstance.copyValue(${JSON.stringify(JSON.stringify(data))})" class="ml-1 text-gray-400 hover:text-indigo-500 text-xs" title="Copy value">value</button>`;
            html += `</div>`;
            return html;
        },

        copy(button) {
            DevTools.copyToClipboard(this.output, button);
        }
    };
}
</script>
@endpush
