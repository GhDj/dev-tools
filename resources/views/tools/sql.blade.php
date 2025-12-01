@extends('layouts.app')

@section('title', 'SQL Formatter & Beautifier - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online SQL formatter and beautifier. Format, beautify, or minify SQL queries instantly. Supports complex queries with JOINs, subqueries, and more.')
@section('meta_keywords', 'sql formatter, sql beautifier, format sql online, sql minifier, beautify sql, sql pretty print, free sql formatter')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "SQL Formatter",
    "description": "Format, beautify, or compress SQL queries",
    "url": "{{ route('tools.sql') }}",
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
<div x-data="sqlFormatter()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">SQL Formatter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Format, beautify, or compress SQL queries</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SQL Input</label>
                <textarea
                    x-model="input"
                    class="textarea-code w-full h-64 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="SELECT u.id, u.name, COUNT(o.id) as order_count FROM users u LEFT JOIN orders o ON u.id = o.user_id WHERE u.status = 'active' GROUP BY u.id, u.name HAVING COUNT(o.id) > 5 ORDER BY order_count DESC LIMIT 10"
                ></textarea>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="format('format')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Format
                    </button>
                    <button
                        @click="format('compress')"
                        :disabled="loading || !input.trim()"
                        class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Compress
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Output</label>
                <button
                    x-show="output"
                    @click="copy($event.currentTarget)"
                    class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    title="Copy to clipboard"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                </button>
            </div>

            <div x-show="error" x-text="error" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm mb-2"></div>

            <textarea
                x-model="output"
                readonly
                class="textarea-code w-full h-80 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                placeholder="Formatted SQL will appear here..."
            ></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sqlFormatter() {
    return {
        input: '',
        output: '',
        error: '',
        loading: false,

        async format(mode) {
            if (!this.input.trim()) return;

            this.loading = true;
            this.error = '';
            this.output = '';

            try {
                const result = await DevTools.post('/api/v1/sql/format', {
                    sql: this.input,
                    mode: mode,
                });

                if (result.success) {
                    this.output = result.result;
                } else {
                    this.error = result.error || 'Formatting failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        copy(button) {
            DevTools.copyToClipboard(this.output, button);
        }
    };
}
</script>
@endpush
