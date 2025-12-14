@extends('layouts.app')

@section('title', 'Password Generator - Secure Random Passwords | Dev Tools')
@section('meta_description', 'Free online secure password generator. Create strong, random passwords with customizable length and character options. Uses cryptographic randomness.')
@section('meta_keywords', 'password generator, secure password, random password, strong password, password creator, crypto random, password tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Password Generator",
    "description": "Generate secure random passwords with customizable options",
    "url": "{{ route('tools.password') }}",
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
<div x-data="passwordGenerator()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Password Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate secure random passwords</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Generated Password</label>
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <input
                            type="text"
                            x-model="password"
                            readonly
                            class="w-full p-3 pr-20 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Click Generate"
                        >
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex gap-1">
                            <button
                                @click="copy($event.currentTarget)"
                                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                title="Copy to clipboard"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                            <button
                                @click="generate()"
                                class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                                title="Regenerate"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">Strength</span>
                        <span :class="strengthColor" x-text="strengthLabel"></span>
                    </div>
                    <div class="h-2 bg-gray-200 dark:bg-dark-bg rounded-full overflow-hidden">
                        <div
                            class="h-full transition-all duration-300"
                            :class="strengthBarColor"
                            :style="'width: ' + strengthPercent + '%'"
                        ></div>
                    </div>
                </div>

                <button
                    @click="generate()"
                    class="mt-4 w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                >
                    Generate Password
                </button>
            </div>

            <div x-show="passwords.length > 0" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Password History</label>
                    <button
                        @click="clearHistory()"
                        class="text-xs text-red-600 dark:text-red-400 hover:underline"
                    >
                        Clear
                    </button>
                </div>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    <template x-for="(pwd, index) in passwords" :key="index">
                        <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-dark-bg rounded-lg group">
                            <code class="text-sm text-gray-700 dark:text-gray-300 font-mono truncate flex-1" x-text="pwd"></code>
                            <button
                                @click="copyPassword(pwd, $event.currentTarget)"
                                class="ml-2 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 opacity-0 group-hover:opacity-100 transition-opacity"
                                title="Copy"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Bulk Generate</h2>
                <div class="flex gap-2">
                    <input
                        type="number"
                        x-model.number="bulkCount"
                        min="2"
                        max="50"
                        class="w-24 p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center"
                    >
                    <button
                        @click="generateBulk()"
                        class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors"
                    >
                        Generate Multiple
                    </button>
                </div>
                <div x-show="bulkPasswords.length > 0" class="mt-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400" x-text="bulkPasswords.length + ' passwords generated'"></span>
                        <button
                            @click="copyAllBulk($event.currentTarget)"
                            class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                        >
                            Copy All
                        </button>
                    </div>
                    <div class="max-h-48 overflow-y-auto space-y-1 p-2 bg-gray-50 dark:bg-dark-bg rounded-lg">
                        <template x-for="(pwd, index) in bulkPasswords" :key="'bulk-' + index">
                            <div class="font-mono text-sm text-gray-700 dark:text-gray-300" x-text="pwd"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Options</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Length: <span class="text-indigo-600 dark:text-indigo-400" x-text="length"></span>
                        </label>
                        <input
                            type="range"
                            x-model="length"
                            @input="generate()"
                            min="4"
                            max="64"
                            class="w-full h-2 bg-gray-200 dark:bg-dark-bg rounded-lg appearance-none cursor-pointer accent-indigo-600"
                        >
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>4</span>
                            <span>64</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="options.uppercase"
                                @change="generate()"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">Uppercase (A-Z)</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="options.lowercase"
                                @change="generate()"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">Lowercase (a-z)</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="options.numbers"
                                @change="generate()"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">Numbers (0-9)</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="options.symbols"
                                @change="generate()"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">Symbols (!@#$%...)</span>
                        </label>

                        <label class="flex items-center gap-3 cursor-pointer">
                            <input
                                type="checkbox"
                                x-model="options.excludeAmbiguous"
                                @change="generate()"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            >
                            <span class="text-sm text-gray-700 dark:text-gray-300">Exclude ambiguous (0OIl1)</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Quick Presets</h2>
                <div class="space-y-2">
                    <button
                        @click="applyPreset('pin')"
                        class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                    >
                        <div class="font-medium text-sm text-gray-900 dark:text-white">PIN Code</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">4 digits, numbers only</div>
                    </button>
                    <button
                        @click="applyPreset('simple')"
                        class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                    >
                        <div class="font-medium text-sm text-gray-900 dark:text-white">Simple</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">8 chars, letters & numbers</div>
                    </button>
                    <button
                        @click="applyPreset('strong')"
                        class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                    >
                        <div class="font-medium text-sm text-gray-900 dark:text-white">Strong</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">16 chars, all character types</div>
                    </button>
                    <button
                        @click="applyPreset('paranoid')"
                        class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                    >
                        <div class="font-medium text-sm text-gray-900 dark:text-white">Paranoid</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">32 chars, all character types</div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function passwordGenerator() {
    return {
        password: '',
        passwords: [],
        length: 16,
        bulkCount: 10,
        bulkPasswords: [],
        options: {
            uppercase: true,
            lowercase: true,
            numbers: true,
            symbols: true,
            excludeAmbiguous: false
        },
        strength: 0,

        chars: {
            uppercase: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
            lowercase: 'abcdefghijklmnopqrstuvwxyz',
            numbers: '0123456789',
            symbols: '!@#$%^&*()_+-=[]{}|;:,.<>?'
        },

        ambiguous: '0OIl1',

        init() {
            this.generate();
        },

        getCharset() {
            let charset = '';
            if (this.options.uppercase) charset += this.chars.uppercase;
            if (this.options.lowercase) charset += this.chars.lowercase;
            if (this.options.numbers) charset += this.chars.numbers;
            if (this.options.symbols) charset += this.chars.symbols;

            if (this.options.excludeAmbiguous) {
                charset = charset.split('').filter(c => !this.ambiguous.includes(c)).join('');
            }

            return charset || this.chars.lowercase;
        },

        generate() {
            const charset = this.getCharset();
            let result = '';

            const array = new Uint32Array(this.length);
            crypto.getRandomValues(array);

            for (let i = 0; i < this.length; i++) {
                result += charset[array[i] % charset.length];
            }

            this.password = result;
            this.calculateStrength();

            if (result && !this.passwords.includes(result)) {
                this.passwords.unshift(result);
                if (this.passwords.length > 10) {
                    this.passwords.pop();
                }
            }
        },

        generateBulk() {
            this.bulkPasswords = [];
            for (let i = 0; i < this.bulkCount; i++) {
                const charset = this.getCharset();
                let result = '';
                const array = new Uint32Array(this.length);
                crypto.getRandomValues(array);
                for (let j = 0; j < this.length; j++) {
                    result += charset[array[j] % charset.length];
                }
                this.bulkPasswords.push(result);
            }
        },

        calculateStrength() {
            let score = 0;
            const pwd = this.password;

            // Length score
            if (pwd.length >= 8) score += 1;
            if (pwd.length >= 12) score += 1;
            if (pwd.length >= 16) score += 1;
            if (pwd.length >= 24) score += 1;

            // Character variety
            if (/[a-z]/.test(pwd)) score += 1;
            if (/[A-Z]/.test(pwd)) score += 1;
            if (/[0-9]/.test(pwd)) score += 1;
            if (/[^a-zA-Z0-9]/.test(pwd)) score += 1;

            this.strength = Math.min(score, 8);
        },

        get strengthPercent() {
            return (this.strength / 8) * 100;
        },

        get strengthLabel() {
            if (this.strength <= 2) return 'Weak';
            if (this.strength <= 4) return 'Fair';
            if (this.strength <= 6) return 'Strong';
            return 'Very Strong';
        },

        get strengthColor() {
            if (this.strength <= 2) return 'text-red-600 dark:text-red-400';
            if (this.strength <= 4) return 'text-yellow-600 dark:text-yellow-400';
            if (this.strength <= 6) return 'text-green-600 dark:text-green-400';
            return 'text-emerald-600 dark:text-emerald-400';
        },

        get strengthBarColor() {
            if (this.strength <= 2) return 'bg-red-500';
            if (this.strength <= 4) return 'bg-yellow-500';
            if (this.strength <= 6) return 'bg-green-500';
            return 'bg-emerald-500';
        },

        applyPreset(preset) {
            switch (preset) {
                case 'pin':
                    this.length = 4;
                    this.options = { uppercase: false, lowercase: false, numbers: true, symbols: false, excludeAmbiguous: false };
                    break;
                case 'simple':
                    this.length = 8;
                    this.options = { uppercase: true, lowercase: true, numbers: true, symbols: false, excludeAmbiguous: true };
                    break;
                case 'strong':
                    this.length = 16;
                    this.options = { uppercase: true, lowercase: true, numbers: true, symbols: true, excludeAmbiguous: false };
                    break;
                case 'paranoid':
                    this.length = 32;
                    this.options = { uppercase: true, lowercase: true, numbers: true, symbols: true, excludeAmbiguous: false };
                    break;
            }
            this.generate();
        },

        copy(button) {
            if (this.password) {
                DevTools.copyToClipboard(this.password, button);
            }
        },

        copyPassword(pwd, button) {
            DevTools.copyToClipboard(pwd, button);
        },

        copyAllBulk(button) {
            if (this.bulkPasswords.length) {
                DevTools.copyToClipboard(this.bulkPasswords.join('\n'), button);
            }
        },

        clearHistory() {
            this.passwords = [];
        }
    };
}
</script>
@endpush
