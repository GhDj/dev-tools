@extends('layouts.app')

@section('title', 'JWT Decoder - Decode JSON Web Tokens Online | Dev Tools')
@section('meta_description', 'Free online JWT decoder. Decode and inspect JSON Web Tokens, view header and payload, check expiration status. No data sent to server.')
@section('meta_keywords', 'jwt decoder, json web token, jwt parser, jwt debugger, decode jwt, jwt viewer, token decoder, jwt online')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "JWT Decoder",
    "description": "Decode and inspect JSON Web Tokens",
    "url": "{{ route('tools.jwt') }}",
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
<div x-data="jwtDecoder()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">JWT Decoder</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Decode and inspect JSON Web Tokens</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: JWT Input -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">JWT Token</label>
                    <button
                        @click="loadSample()"
                        class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline"
                    >
                        Load sample
                    </button>
                </div>
                <textarea
                    x-model="token"
                    @input="decode()"
                    class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Paste your JWT token here..."
                ></textarea>
                <div x-show="error" class="mt-2 p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded text-sm" x-text="error"></div>
            </div>

            <!-- Token Structure -->
            <div x-show="decoded" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Token Structure</label>
                <div class="font-mono text-xs break-all">
                    <span class="text-red-500 dark:text-red-400" x-text="parts.header"></span><span class="text-gray-400">.</span><span class="text-purple-500 dark:text-purple-400" x-text="parts.payload"></span><span class="text-gray-400">.</span><span class="text-cyan-500 dark:text-cyan-400" x-text="parts.signature"></span>
                </div>
                <div class="mt-3 flex gap-4 text-xs">
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-red-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Header</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-purple-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Payload</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="w-3 h-3 rounded bg-cyan-500"></span>
                        <span class="text-gray-600 dark:text-gray-400">Signature</span>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-sm text-amber-800 dark:text-amber-200">
                        <p class="font-medium">Security Note</p>
                        <p class="mt-1 text-amber-700 dark:text-amber-300">JWTs are decoded client-side. No data is sent to any server. Never share tokens containing sensitive information.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Decoded Data -->
        <div class="space-y-4">
            <!-- Expiration Status -->
            <div x-show="decoded && expiration.hasExp" class="rounded-lg border p-4" :class="expiration.isExpired ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800'">
                <div class="flex items-center gap-2">
                    <template x-if="expiration.isExpired">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </template>
                    <template x-if="!expiration.isExpired">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </template>
                    <span class="font-medium" :class="expiration.isExpired ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300'" x-text="expiration.isExpired ? 'Token Expired' : 'Token Valid'"></span>
                </div>
                <p class="mt-1 text-sm" :class="expiration.isExpired ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'" x-text="expiration.message"></p>
            </div>

            <!-- Header -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-red-600 dark:text-red-400">Header</label>
                    <button
                        x-show="decoded"
                        @click="copySection('header', $event.currentTarget)"
                        class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Copy header"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <pre x-show="decoded" class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-sm overflow-auto max-h-40 font-mono text-gray-900 dark:text-gray-100" x-text="headerJson"></pre>
                <div x-show="!decoded" class="text-gray-500 dark:text-gray-400 text-sm">Paste a JWT to see the header</div>
            </div>

            <!-- Payload -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-purple-600 dark:text-purple-400">Payload</label>
                    <button
                        x-show="decoded"
                        @click="copySection('payload', $event.currentTarget)"
                        class="p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        title="Copy payload"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <pre x-show="decoded" class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-sm overflow-auto max-h-64 font-mono text-gray-900 dark:text-gray-100" x-text="payloadJson"></pre>
                <div x-show="!decoded" class="text-gray-500 dark:text-gray-400 text-sm">Paste a JWT to see the payload</div>
            </div>

            <!-- Claims Reference -->
            <div x-show="decoded && Object.keys(claims).length > 0" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Registered Claims</label>
                <div class="space-y-2">
                    <template x-for="(info, key) in claims" :key="key">
                        <div class="flex items-start gap-3 text-sm">
                            <code class="px-1.5 py-0.5 bg-gray-100 dark:bg-dark-bg rounded text-indigo-600 dark:text-indigo-400 font-medium" x-text="key"></code>
                            <div class="flex-1">
                                <span class="text-gray-600 dark:text-gray-400" x-text="info.label + ':'"></span>
                                <span class="text-gray-900 dark:text-gray-100 ml-1" x-text="info.value"></span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function jwtDecoder() {
    return {
        token: '',
        decoded: false,
        error: '',
        header: null,
        payload: null,
        headerJson: '',
        payloadJson: '',
        parts: { header: '', payload: '', signature: '' },
        expiration: { hasExp: false, isExpired: false, message: '' },
        claims: {},

        registeredClaims: {
            iss: 'Issuer',
            sub: 'Subject',
            aud: 'Audience',
            exp: 'Expiration Time',
            nbf: 'Not Before',
            iat: 'Issued At',
            jti: 'JWT ID'
        },

        loadSample() {
            // Sample JWT that expires in the future (payload: {"sub":"1234567890","name":"John Doe","iat":1516239022,"exp":9999999999})
            this.token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyLCJleHAiOjk5OTk5OTk5OTl9.Vg30C57s3l90JNap_VgMhKZjfc-p7SoBXaSAy8c28HA';
            this.decode();
        },

        decode() {
            this.error = '';
            this.decoded = false;
            this.header = null;
            this.payload = null;
            this.headerJson = '';
            this.payloadJson = '';
            this.parts = { header: '', payload: '', signature: '' };
            this.expiration = { hasExp: false, isExpired: false, message: '' };
            this.claims = {};

            if (!this.token.trim()) {
                return;
            }

            try {
                const parts = this.token.trim().split('.');
                if (parts.length !== 3) {
                    throw new Error('Invalid JWT format. Expected 3 parts separated by dots.');
                }

                this.parts = {
                    header: parts[0],
                    payload: parts[1],
                    signature: parts[2]
                };

                // Decode header
                this.header = JSON.parse(this.base64UrlDecode(parts[0]));
                this.headerJson = JSON.stringify(this.header, null, 2);

                // Decode payload
                this.payload = JSON.parse(this.base64UrlDecode(parts[1]));
                this.payloadJson = JSON.stringify(this.payload, null, 2);

                // Check expiration
                this.checkExpiration();

                // Parse registered claims
                this.parseClaims();

                this.decoded = true;
            } catch (e) {
                this.error = e.message;
            }
        },

        base64UrlDecode(str) {
            // Replace URL-safe characters
            let base64 = str.replace(/-/g, '+').replace(/_/g, '/');
            // Pad with '=' if necessary
            while (base64.length % 4) {
                base64 += '=';
            }
            return decodeURIComponent(atob(base64).split('').map(function(c) {
                return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
            }).join(''));
        },

        checkExpiration() {
            if (this.payload && this.payload.exp) {
                this.expiration.hasExp = true;
                const expDate = new Date(this.payload.exp * 1000);
                const now = new Date();
                this.expiration.isExpired = expDate < now;

                if (this.expiration.isExpired) {
                    const diff = now - expDate;
                    this.expiration.message = `Expired ${this.formatTimeDiff(diff)} ago (${this.formatDate(expDate)})`;
                } else {
                    const diff = expDate - now;
                    this.expiration.message = `Expires in ${this.formatTimeDiff(diff)} (${this.formatDate(expDate)})`;
                }
            }
        },

        parseClaims() {
            if (!this.payload) return;

            for (const [key, label] of Object.entries(this.registeredClaims)) {
                if (this.payload[key] !== undefined) {
                    let value = this.payload[key];

                    // Format timestamps
                    if (['exp', 'nbf', 'iat'].includes(key) && typeof value === 'number') {
                        value = this.formatDate(new Date(value * 1000));
                    }

                    // Format arrays
                    if (Array.isArray(value)) {
                        value = value.join(', ');
                    }

                    this.claims[key] = { label, value };
                }
            }
        },

        formatDate(date) {
            return date.toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            });
        },

        formatTimeDiff(ms) {
            const seconds = Math.floor(ms / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            if (days > 0) return `${days} day${days > 1 ? 's' : ''}`;
            if (hours > 0) return `${hours} hour${hours > 1 ? 's' : ''}`;
            if (minutes > 0) return `${minutes} minute${minutes > 1 ? 's' : ''}`;
            return `${seconds} second${seconds !== 1 ? 's' : ''}`;
        },

        copySection(section, button) {
            const text = section === 'header' ? this.headerJson : this.payloadJson;
            DevTools.copyToClipboard(text, button);
        }
    };
}
</script>
@endpush
