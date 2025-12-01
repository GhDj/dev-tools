@extends('layouts.app')

@section('title', 'Hash Generator (MD5, SHA-256, SHA-512) - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online hash generator. Generate MD5, SHA-1, SHA-256, SHA-384, SHA-512 hashes instantly. Verify hashes with auto-detection of algorithm.')
@section('meta_keywords', 'hash generator, md5 generator, sha256 generator, sha512 hash, generate hash online, hash calculator, checksum generator, free hash tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Hash Generator",
    "description": "Generate MD5, SHA-1, SHA-256, SHA-384, SHA-512 hashes",
    "url": "{{ route('tools.hash') }}",
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
<div x-data="hashTool()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Hash Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate MD5, SHA-1, SHA-256, SHA-384, SHA-512 hashes</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input Text</label>
                <textarea
                    x-model="input"
                    class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Enter text to hash..."
                ></textarea>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <button
                    @click="generateAll()"
                    :disabled="loading || !input"
                    class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                >
                    Generate All Hashes
                </button>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Verify Hash</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Hash to verify</label>
                        <input
                            type="text"
                            x-model="verifyHash"
                            placeholder="Enter hash to verify against input..."
                            class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono text-sm"
                        >
                    </div>

                    <button
                        @click="verify()"
                        :disabled="loading || !input || !verifyHash"
                        class="w-full py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Verify
                    </button>

                    <div x-show="verifyResult" class="p-3 rounded-lg" :class="verifyResult?.match ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400'">
                        <template x-if="verifyResult?.match">
                            <div>
                                <p class="font-medium">Hash matches!</p>
                                <p class="text-sm">Algorithm: <span x-text="verifyResult.algorithm.toUpperCase()"></span></p>
                            </div>
                        </template>
                        <template x-if="!verifyResult?.match">
                            <p>Hash does not match any algorithm</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Generated Hashes</label>

            <div x-show="error" x-text="error" class="p-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm mb-4"></div>

            <div class="space-y-4">
                <template x-for="algo in algorithms" :key="algo">
                    <div class="space-y-1">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 uppercase" x-text="algo"></label>
                            <button
                                x-show="hashes[algo]"
                                @click="copyHash(algo, $event.currentTarget)"
                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                                title="Copy to clipboard"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                        <input
                            type="text"
                            :value="hashes[algo] || ''"
                            readonly
                            class="w-full p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-xs"
                            placeholder="Hash will appear here..."
                        >
                    </div>
                </template>
            </div>

            <div x-show="inputLength !== null" class="mt-4 pt-4 border-t border-gray-200 dark:border-dark-border">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Input length: <span x-text="inputLength"></span> bytes
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function hashTool() {
    return {
        input: '',
        algorithms: ['md5', 'sha1', 'sha256', 'sha384', 'sha512'],
        hashes: {},
        error: '',
        loading: false,
        inputLength: null,
        verifyHash: '',
        verifyResult: null,

        async generateAll() {
            if (!this.input) return;

            this.loading = true;
            this.error = '';
            this.hashes = {};

            try {
                const result = await DevTools.post('/api/v1/hash/generate', {
                    input: this.input,
                });

                if (result.success) {
                    this.hashes = result.hashes;
                    this.inputLength = result.input_length;
                } else {
                    this.error = result.error || 'Hash generation failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        async verify() {
            if (!this.input || !this.verifyHash) return;

            this.loading = true;
            this.verifyResult = null;

            try {
                const result = await DevTools.post('/api/v1/hash/verify', {
                    input: this.input,
                    hash: this.verifyHash,
                });

                if (result.success) {
                    this.verifyResult = result;
                } else {
                    this.error = result.error || 'Verification failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        copyHash(algo, button) {
            if (this.hashes[algo]) {
                DevTools.copyToClipboard(this.hashes[algo], button);
            }
        }
    };
}
</script>
@endpush
