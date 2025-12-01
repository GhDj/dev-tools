@extends('layouts.app')

@section('title', 'Base64 Encoder/Decoder - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online Base64 encoder and decoder. Encode or decode text and files to/from Base64 instantly. Supports file uploads up to 5MB.')
@section('meta_keywords', 'base64 encoder, base64 decoder, encode base64, decode base64, base64 online, file to base64, free base64 tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Base64 Encoder/Decoder",
    "description": "Encode or decode text and files to/from Base64",
    "url": "{{ route('tools.base64') }}",
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
<div x-data="base64Tool()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Base64 Encoder/Decoder</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Encode or decode text and files to/from Base64</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <div class="flex items-center justify-center space-x-4">
            <button
                @click="mode = 'text'"
                :class="mode === 'text' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                Text
            </button>
            <button
                @click="mode = 'file'"
                :class="mode === 'file' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                class="px-4 py-2 rounded-lg font-medium transition-colors"
            >
                File Upload
            </button>
        </div>
    </div>

    <template x-if="mode === 'text'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input</label>
                    <textarea
                        x-model="textInput"
                        class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                        placeholder="Enter text to encode or Base64 to decode..."
                    ></textarea>
                </div>

                <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                    <div class="flex gap-2">
                        <button
                            @click="encode()"
                            :disabled="loading || !textInput.trim()"
                            class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                        >
                            Encode
                        </button>
                        <button
                            @click="decode()"
                            :disabled="loading || !textInput.trim()"
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
                    <div class="flex items-center space-x-2">
                        <span x-show="isBinary" class="text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 px-2 py-1 rounded">Binary data</span>
                        <button
                            x-show="textOutput && !isBinary"
                            @click="copyText($event.currentTarget)"
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

                <textarea
                    x-model="textOutput"
                    readonly
                    class="textarea-code w-full h-56 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                    placeholder="Output will appear here..."
                ></textarea>
            </div>
        </div>
    </template>

    <template x-if="mode === 'file'">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload File</label>
                <div
                    class="border-2 border-dashed border-gray-300 dark:border-dark-border rounded-lg p-8 text-center hover:border-indigo-400 transition-colors cursor-pointer"
                    @click="$refs.fileInput.click()"
                    @dragover.prevent="$event.currentTarget.classList.add('border-indigo-400')"
                    @dragleave.prevent="$event.currentTarget.classList.remove('border-indigo-400')"
                    @drop.prevent="handleFileDrop($event)"
                >
                    <input type="file" x-ref="fileInput" @change="handleFileSelect($event)" class="hidden">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400">Click or drag file here</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Max 5MB</p>
                </div>

                <div x-show="fileName" class="mt-4 p-3 bg-gray-100 dark:bg-dark-border rounded-lg">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium" x-text="fileName"></span>
                        <span class="text-gray-500 dark:text-gray-400" x-text="'(' + fileSize + ')'"></span>
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base64 Output</label>
                    <button
                        x-show="fileOutput"
                        @click="copyFile($event.currentTarget)"
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
                    x-model="fileOutput"
                    readonly
                    class="textarea-code w-full h-56 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                    placeholder="Base64 encoded file will appear here..."
                ></textarea>

                <div x-show="fileMimeType" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    MIME type: <span x-text="fileMimeType"></span>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection

@push('scripts')
<script>
function base64Tool() {
    return {
        mode: 'text',
        textInput: '',
        textOutput: '',
        fileOutput: '',
        error: '',
        loading: false,
        isBinary: false,
        fileName: '',
        fileSize: '',
        fileMimeType: '',

        async encode() {
            if (!this.textInput.trim()) return;

            this.loading = true;
            this.error = '';
            this.textOutput = '';
            this.isBinary = false;

            try {
                const result = await DevTools.post('/api/v1/base64/encode', {
                    input: this.textInput,
                });

                if (result.success) {
                    this.textOutput = result.result;
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
            if (!this.textInput.trim()) return;

            this.loading = true;
            this.error = '';
            this.textOutput = '';
            this.isBinary = false;

            try {
                const result = await DevTools.post('/api/v1/base64/decode', {
                    input: this.textInput,
                });

                if (result.success) {
                    this.textOutput = result.result;
                    this.isBinary = result.is_binary;
                } else {
                    this.error = result.error || 'Decoding failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) this.uploadFile(file);
        },

        handleFileDrop(event) {
            event.currentTarget.classList.remove('border-indigo-400');
            const file = event.dataTransfer.files[0];
            if (file) this.uploadFile(file);
        },

        async uploadFile(file) {
            if (file.size > 5 * 1024 * 1024) {
                this.error = 'File too large. Maximum size is 5MB.';
                return;
            }

            this.loading = true;
            this.error = '';
            this.fileOutput = '';
            this.fileName = file.name;
            this.fileSize = this.formatBytes(file.size);

            const formData = new FormData();
            formData.append('file', file);

            try {
                const result = await DevTools.postForm('/api/v1/base64/encode-file', formData);

                if (result.success) {
                    this.fileOutput = result.result;
                    this.fileMimeType = result.mime_type;
                } else {
                    this.error = result.error || 'File encoding failed';
                }
            } catch (e) {
                this.error = 'Request failed: ' + e.message;
            } finally {
                this.loading = false;
            }
        },

        formatBytes(bytes) {
            if (bytes === 0) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        copyText(button) {
            DevTools.copyToClipboard(this.textOutput, button);
        },

        copyFile(button) {
            DevTools.copyToClipboard(this.fileOutput, button);
        }
    };
}
</script>
@endpush
