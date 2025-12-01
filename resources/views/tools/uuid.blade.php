@extends('layouts.app')

@section('title', 'UUID Generator - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online UUID v4 generator. Generate single or bulk UUIDs with format options: lowercase, uppercase, no-hyphens, braces, URN. Validate existing UUIDs.')
@section('meta_keywords', 'uuid generator, generate uuid, uuid v4, bulk uuid generator, uuid validator, random uuid, guid generator, free uuid tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "UUID Generator",
    "description": "Generate, validate, and format UUIDs (v4)",
    "url": "{{ route('tools.uuid') }}",
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
<div x-data="uuidTool()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">UUID Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate, validate, and format UUIDs (v4)</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Generate UUIDs</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Count</label>
                        <input
                            type="number"
                            x-model.number="count"
                            min="1"
                            max="100"
                            class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Format</label>
                        <select
                            x-model="format"
                            class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                            <option value="lowercase">lowercase (default)</option>
                            <option value="uppercase">UPPERCASE</option>
                            <option value="no-hyphens">No hyphens</option>
                            <option value="braces">{Braces}</option>
                            <option value="urn">URN format</option>
                        </select>
                    </div>

                    <button
                        @click="generate()"
                        :disabled="loading"
                        class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Generate UUID<span x-show="count > 1">s</span>
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Validate UUID</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">UUID to validate</label>
                        <input
                            type="text"
                            x-model="validateInput"
                            placeholder="Enter UUID to validate..."
                            class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm"
                        >
                    </div>

                    <button
                        @click="validate()"
                        :disabled="loading || !validateInput.trim()"
                        class="w-full py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Validate
                    </button>

                    <div x-show="validationResult" class="p-3 rounded-lg" :class="validationResult?.valid ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'">
                        <template x-if="validationResult?.valid">
                            <div>
                                <p class="font-medium">Valid UUID</p>
                                <p class="text-sm">Version: <span x-text="validationResult.version"></span></p>
                                <p class="text-sm">Variant: <span x-text="validationResult.variant"></span></p>
                            </div>
                        </template>
                        <template x-if="!validationResult?.valid">
                            <p x-text="validationResult?.error"></p>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Generated UUIDs</label>
                <button
                    x-show="output"
                    @click="copyOutput($event.currentTarget)"
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
                class="textarea-code w-full h-96 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none font-mono text-sm"
                placeholder="Generated UUIDs will appear here..."
            ></textarea>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function uuidTool() {
    return {
        count: 1,
        format: 'lowercase',
        output: '',
        error: '',
        loading: false,
        validateInput: '',
        validationResult: null,

        async generate() {
            this.loading = true;
            this.error = '';

            try {
                const result = await DevTools.post('/api/v1/uuid/generate', {
                    count: this.count,
                    format: this.format,
                });

                if (result.success) {
                    if (result.uuid) {
                        this.output = result.uuid;
                    } else if (result.uuids) {
                        this.output = result.uuids.join('\n');
                    }
                } else {
                    this.error = result.error || 'Generation failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        async validate() {
            if (!this.validateInput.trim()) return;

            this.loading = true;
            this.validationResult = null;

            try {
                const result = await DevTools.post('/api/v1/uuid/validate', {
                    uuid: this.validateInput,
                });

                this.validationResult = result;
            } catch (e) {
                this.validationResult = { valid: false, error: 'Request failed: ' + e.message };
            } finally {
                this.loading = false;
            }
        },

        copyOutput(button) {
            DevTools.copyToClipboard(this.output, button);
        }
    };
}
</script>
@endpush
