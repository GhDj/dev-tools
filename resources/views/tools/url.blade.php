@extends('layouts.app')

@section('title', 'URL Encoder/Decoder & Parser - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online URL encoder, decoder, and parser. URL encode/decode strings, parse URLs into components, and build URLs from parts. Supports both component and full encoding.')
@section('meta_keywords', 'url encoder, url decoder, urlencode, urldecode, url parser, parse url online, encode url, free url tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "URL Encoder/Decoder",
    "description": "Encode, decode, and parse URLs",
    "url": "{{ route('tools.url') }}",
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
<div x-data="urlTool()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">URL Encoder/Decoder</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Encode, decode, and parse URLs</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <div class="flex items-center justify-center space-x-4">
            <button
                @click="mode = 'encode'"
                :class="mode === 'encode' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                Encode/Decode
            </button>
            <button
                @click="mode = 'parse'"
                :class="mode === 'parse' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                Parse URL
            </button>
        </div>
    </div>

    <template x-if="mode === 'encode'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input</label>
                    <textarea
                        x-model="input"
                        class="textarea-code w-full h-32 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                        placeholder="Enter text to encode or URL-encoded text to decode..."
                    ></textarea>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Encoding Mode</label>
                    <select
                        x-model="encodeMode"
                        class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent mb-4"
                    >
                        <option value="component">Component (recommended)</option>
                        <option value="full">Full URL encoding</option>
                    </select>

                    <div class="flex gap-2">
                        <button
                            @click="encode()"
                            :disabled="loading || !input.trim()"
                            class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                        >
                            Encode
                        </button>
                        <button
                            @click="decode()"
                            :disabled="loading || !input.trim()"
                            class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                        >
                            Decode
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Output</label>
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
                    class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                    placeholder="Result will appear here..."
                ></textarea>
            </div>
        </div>
    </template>

    <template x-if="mode === 'parse'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL to Parse</label>
                    <textarea
                        x-model="parseInput"
                        class="textarea-code w-full h-24 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                        placeholder="https://example.com/path?query=value#fragment"
                    ></textarea>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <button
                        @click="parse()"
                        :disabled="loading || !parseInput.trim()"
                        class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Parse URL
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">URL Components</label>

                <div x-show="parseError" x-text="parseError" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm mb-4"></div>

                <div x-show="parsedUrl" class="space-y-3">
                    <template x-for="(value, key) in parsedUrl" :key="key">
                        <div x-show="key !== 'query_params'" class="flex items-start space-x-3">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 w-24 flex-shrink-0 capitalize" x-text="key.replace('_', ' ')"></span>
                            <div class="flex-1 flex items-center space-x-2">
                                <code class="flex-1 p-2 bg-gray-100 dark:bg-dark-bg rounded text-sm text-gray-900 dark:text-gray-100 break-all" x-text="typeof value === 'object' ? JSON.stringify(value) : value"></code>
                                <button
                                    @click="copyComponent(value, $event.currentTarget)"
                                    class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 flex-shrink-0"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="parsedUrl?.query_params && Object.keys(parsedUrl.query_params).length > 0">
                        <div class="pt-3 border-t border-gray-200 dark:border-dark-border">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Query Parameters</p>
                            <div class="space-y-2">
                                <template x-for="(value, key) in parsedUrl.query_params" :key="key">
                                    <div class="flex items-center space-x-2 pl-4">
                                        <code class="text-sm text-indigo-600 dark:text-indigo-400" x-text="key"></code>
                                        <span class="text-gray-500">=</span>
                                        <code class="text-sm text-gray-900 dark:text-gray-100" x-text="value"></code>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>

                <div x-show="!parsedUrl && !parseError" class="text-gray-500 dark:text-gray-400 text-sm">
                    Enter a URL and click Parse to see its components
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function urlTool() {
    return {
        mode: 'encode',
        input: '',
        output: '',
        error: '',
        encodeMode: 'component',
        parseInput: '',
        parsedUrl: null,
        parseError: '',
        loading: false,

        async encode() {
            if (!this.input.trim()) return;

            this.loading = true;
            this.error = '';
            this.output = '';

            try {
                const result = await DevTools.post('/api/v1/url/encode', {
                    input: this.input,
                    mode: this.encodeMode,
                });

                if (result.success) {
                    this.output = result.result;
                } else {
                    this.error = result.error || 'Encoding failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        async decode() {
            if (!this.input.trim()) return;

            this.loading = true;
            this.error = '';
            this.output = '';

            try {
                const result = await DevTools.post('/api/v1/url/decode', {
                    input: this.input,
                });

                if (result.success) {
                    this.output = result.result;
                } else {
                    this.error = result.error || 'Decoding failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        async parse() {
            if (!this.parseInput.trim()) return;

            this.loading = true;
            this.parseError = '';
            this.parsedUrl = null;

            try {
                const result = await DevTools.post('/api/v1/url/parse', {
                    url: this.parseInput,
                });

                if (result.success) {
                    this.parsedUrl = result.components;
                } else {
                    this.parseError = result.error || 'Parsing failed';
                }
            } catch (e) {
                this.parseError = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        copyOutput(button) {
            DevTools.copyToClipboard(this.output, button);
        },

        copyComponent(value, button) {
            const text = typeof value === 'object' ? JSON.stringify(value) : value;
            DevTools.copyToClipboard(text, button);
        }
    };
}
</script>
@endpush
