@extends('layouts.app')

@section('title', 'YAML/JSON Converter - Dev Tools')

@section('content')
<div x-data="yamlConverter()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">YAML/JSON Converter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert between YAML and JSON formats</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <div class="flex items-center justify-center space-x-4 mb-4">
            <button
                @click="direction = 'yaml-to-json'"
                :class="direction === 'yaml-to-json' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                YAML &rarr; JSON
            </button>
            <button
                @click="direction = 'json-to-yaml'"
                :class="direction === 'json-to-yaml' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                JSON &rarr; YAML
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <span x-text="direction === 'yaml-to-json' ? 'YAML Input' : 'JSON Input'"></span>
            </label>
            <textarea
                x-model="input"
                class="textarea-code w-full h-72 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                :placeholder="direction === 'yaml-to-json' ? 'name: John Doe\nage: 30\nskills:\n  - Python\n  - JavaScript' : '{\n  &quot;name&quot;: &quot;John Doe&quot;,\n  &quot;age&quot;: 30\n}'"
            ></textarea>

            <div class="mt-4">
                <button
                    @click="convert()"
                    :disabled="loading || !input.trim()"
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                >
                    <span x-show="!loading">Convert</span>
                    <span x-show="loading">Converting...</span>
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    <span x-text="direction === 'yaml-to-json' ? 'JSON Output' : 'YAML Output'"></span>
                </label>
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
                class="textarea-code w-full h-72 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                placeholder="Output will appear here..."
            ></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function yamlConverter() {
    return {
        input: '',
        output: '',
        error: '',
        loading: false,
        direction: 'yaml-to-json',

        async convert() {
            if (!this.input.trim()) return;

            this.loading = true;
            this.error = '';
            this.output = '';

            try {
                const result = await DevTools.post('/api/v1/yaml/convert', {
                    input: this.input,
                    direction: this.direction,
                });

                if (result.success) {
                    this.output = result.result;
                } else {
                    this.error = result.error || 'Conversion failed';
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
