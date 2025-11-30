@extends('layouts.app')

@section('title', 'JSON Parser - Dev Tools')

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
                placeholder="Formatted JSON will appear here..."
            ></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function jsonParser() {
    return {
        input: '',
        output: '',
        error: '',
        loading: false,
        validation: null,

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

        copy(button) {
            DevTools.copyToClipboard(this.output, button);
        }
    };
}
</script>
@endpush
