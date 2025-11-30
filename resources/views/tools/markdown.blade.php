@extends('layouts.app')

@section('title', 'Markdown Preview - Dev Tools')

@push('styles')
<style>
    .markdown-preview {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        line-height: 1.6;
    }
    .markdown-preview h1 { font-size: 2em; font-weight: bold; margin: 0.67em 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.3em; }
    .markdown-preview h2 { font-size: 1.5em; font-weight: bold; margin: 0.83em 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.3em; }
    .markdown-preview h3 { font-size: 1.17em; font-weight: bold; margin: 1em 0; }
    .markdown-preview p { margin: 1em 0; }
    .markdown-preview pre { background: #f4f4f5; padding: 1rem; border-radius: 6px; overflow-x: auto; }
    .markdown-preview code { background: #f4f4f5; padding: 0.2em 0.4em; border-radius: 3px; font-family: 'SF Mono', Monaco, monospace; font-size: 0.9em; }
    .markdown-preview pre code { background: none; padding: 0; }
    .markdown-preview blockquote { border-left: 4px solid #d1d5db; margin: 0; padding-left: 1em; color: #6b7280; }
    .markdown-preview ul, .markdown-preview ol { margin: 1em 0; padding-left: 2em; }
    .markdown-preview li { margin: 0.5em 0; }
    .markdown-preview table { border-collapse: collapse; width: 100%; margin: 1em 0; }
    .markdown-preview th, .markdown-preview td { border: 1px solid #d1d5db; padding: 0.5em; text-align: left; }
    .markdown-preview th { background: #f4f4f5; }
    .markdown-preview a { color: #4f46e5; }
    .markdown-preview hr { border: none; border-top: 1px solid #e5e7eb; margin: 2em 0; }
    .markdown-preview img { max-width: 100%; height: auto; }

    .dark .markdown-preview h1, .dark .markdown-preview h2 { border-bottom-color: #374151; }
    .dark .markdown-preview pre, .dark .markdown-preview code { background: #1f2937; }
    .dark .markdown-preview pre code { background: none; }
    .dark .markdown-preview blockquote { border-left-color: #4b5563; color: #9ca3af; }
    .dark .markdown-preview th, .dark .markdown-preview td { border-color: #4b5563; }
    .dark .markdown-preview th { background: #1f2937; }
    .dark .markdown-preview a { color: #818cf8; }
    .dark .markdown-preview hr { border-top-color: #374151; }
</style>
@endpush

@section('content')
<div x-data="markdownPreview()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Markdown Preview</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Write Markdown and preview it as HTML</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Markdown Input</label>
            <textarea
                x-model="input"
                @input.debounce.300ms="convert()"
                class="textarea-code w-full h-96 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                placeholder="# Hello World

This is a **markdown** preview tool.

- Item 1
- Item 2
- Item 3

```javascript
console.log('Hello!');
```"
            ></textarea>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preview</label>
                <div class="flex items-center space-x-2">
                    <button
                        x-show="html"
                        @click="copyHtml($event.currentTarget)"
                        class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Copy HTML"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                    <button
                        x-show="html"
                        @click="exportHtml()"
                        class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Export as HTML file"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div x-show="error" x-text="error" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm mb-2"></div>

            <div
                x-html="html"
                class="markdown-preview w-full h-96 p-4 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 overflow-y-auto"
            ></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function markdownPreview() {
    return {
        input: '',
        html: '',
        error: '',
        loading: false,

        async convert() {
            if (!this.input.trim()) {
                this.html = '';
                return;
            }

            this.loading = true;
            this.error = '';

            try {
                const result = await DevTools.post('/api/v1/markdown/convert', {
                    markdown: this.input,
                    full_page: false,
                });

                if (result.success) {
                    this.html = result.result;
                } else {
                    this.error = result.error || 'Conversion failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        copyHtml(button) {
            DevTools.copyToClipboard(this.html, button);
        },

        async exportHtml() {
            try {
                const result = await DevTools.post('/api/v1/markdown/convert', {
                    markdown: this.input,
                    full_page: true,
                    title: 'Markdown Export',
                });

                if (result.success) {
                    DevTools.downloadFile(result.result, 'markdown-export.html', 'text/html');
                }
            } catch (e) {
                this.error = 'Export failed: ' + e.message;
            }
        }
    };
}
</script>
@endpush
