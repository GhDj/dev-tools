@extends('layouts.app')

@section('title', 'Base Converter - Binary, Octal, Decimal, Hex | Dev Tools')
@section('meta_description', 'Free online number base converter. Convert between binary, octal, decimal, and hexadecimal. Instant conversion with bit visualization.')
@section('meta_keywords', 'base converter, binary converter, hex converter, octal converter, decimal to binary, binary to hex, number base, radix converter')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Base Converter",
    "description": "Convert numbers between binary, octal, decimal, and hexadecimal bases",
    "url": "{{ route('tools.base-converter') }}",
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
<div x-data="baseConverter()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Base Converter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert between binary, octal, decimal, and hexadecimal</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input Section -->
        <div class="space-y-4">
            <!-- Binary -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Binary (Base 2)</label>
                    <span class="text-xs text-gray-500 dark:text-gray-400">0-1</span>
                </div>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        x-model="binary"
                        @input="convertFrom('binary')"
                        class="flex-1 p-3 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., 1010"
                    >
                    <button @click="copyValue(binary, $event.currentTarget)" class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500" x-show="errors.binary" x-text="errors.binary"></p>
            </div>

            <!-- Octal -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Octal (Base 8)</label>
                    <span class="text-xs text-gray-500 dark:text-gray-400">0-7</span>
                </div>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        x-model="octal"
                        @input="convertFrom('octal')"
                        class="flex-1 p-3 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., 12"
                    >
                    <button @click="copyValue(octal, $event.currentTarget)" class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500" x-show="errors.octal" x-text="errors.octal"></p>
            </div>

            <!-- Decimal -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Decimal (Base 10)</label>
                    <span class="text-xs text-gray-500 dark:text-gray-400">0-9</span>
                </div>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        x-model="decimal"
                        @input="convertFrom('decimal')"
                        class="flex-1 p-3 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="e.g., 10"
                    >
                    <button @click="copyValue(decimal, $event.currentTarget)" class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500" x-show="errors.decimal" x-text="errors.decimal"></p>
            </div>

            <!-- Hexadecimal -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Hexadecimal (Base 16)</label>
                    <span class="text-xs text-gray-500 dark:text-gray-400">0-9, A-F</span>
                </div>
                <div class="flex space-x-2">
                    <input
                        type="text"
                        x-model="hex"
                        @input="convertFrom('hex')"
                        class="flex-1 p-3 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent uppercase"
                        placeholder="e.g., A"
                    >
                    <button @click="copyValue(hex, $event.currentTarget)" class="p-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-red-500" x-show="errors.hex" x-text="errors.hex"></p>
            </div>

            <!-- Clear Button -->
            <button
                @click="clearAll()"
                class="w-full py-2 px-4 bg-gray-200 dark:bg-dark-bg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors"
            >
                Clear All
            </button>
        </div>

        <!-- Info Section -->
        <div class="space-y-4">
            <!-- Bit Visualization -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Bit Visualization</label>
                <div class="flex flex-wrap gap-1" x-show="binary">
                    <template x-for="(bit, index) in paddedBinary.split('')" :key="index">
                        <div
                            class="w-8 h-8 flex items-center justify-center font-mono text-sm rounded"
                            :class="bit === '1' ? 'bg-indigo-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400'"
                            x-text="bit"
                        ></div>
                    </template>
                </div>
                <p x-show="!binary" class="text-sm text-gray-500 dark:text-gray-400">Enter a number to see bit visualization</p>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400" x-show="binary">
                    <span x-text="paddedBinary.length"></span> bits
                </p>
            </div>

            <!-- Quick Examples -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Examples</label>
                <div class="grid grid-cols-2 gap-2">
                    <button @click="setDecimal(255)" class="px-3 py-2 text-sm bg-gray-100 dark:bg-dark-bg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-left">
                        <span class="font-medium">255</span>
                        <span class="text-xs text-gray-500 block">Max byte</span>
                    </button>
                    <button @click="setDecimal(256)" class="px-3 py-2 text-sm bg-gray-100 dark:bg-dark-bg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-left">
                        <span class="font-medium">256</span>
                        <span class="text-xs text-gray-500 block">2^8</span>
                    </button>
                    <button @click="setDecimal(1024)" class="px-3 py-2 text-sm bg-gray-100 dark:bg-dark-bg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-left">
                        <span class="font-medium">1024</span>
                        <span class="text-xs text-gray-500 block">1 KB</span>
                    </button>
                    <button @click="setDecimal(65535)" class="px-3 py-2 text-sm bg-gray-100 dark:bg-dark-bg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors text-left">
                        <span class="font-medium">65535</span>
                        <span class="text-xs text-gray-500 block">Max 16-bit</span>
                    </button>
                </div>
            </div>

            <!-- Formatted Output -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Formatted Output</label>
                <div class="space-y-2 font-mono text-sm" x-show="decimal">
                    <div class="flex justify-between p-2 bg-gray-50 dark:bg-dark-bg rounded">
                        <span class="text-gray-600 dark:text-gray-400">Binary:</span>
                        <span class="text-gray-900 dark:text-gray-100">0b<span x-text="binary"></span></span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 dark:bg-dark-bg rounded">
                        <span class="text-gray-600 dark:text-gray-400">Octal:</span>
                        <span class="text-gray-900 dark:text-gray-100">0o<span x-text="octal"></span></span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 dark:bg-dark-bg rounded">
                        <span class="text-gray-600 dark:text-gray-400">Decimal:</span>
                        <span class="text-gray-900 dark:text-gray-100" x-text="decimal"></span>
                    </div>
                    <div class="flex justify-between p-2 bg-gray-50 dark:bg-dark-bg rounded">
                        <span class="text-gray-600 dark:text-gray-400">Hex:</span>
                        <span class="text-gray-900 dark:text-gray-100">0x<span x-text="hex"></span></span>
                    </div>
                </div>
                <p x-show="!decimal" class="text-sm text-gray-500 dark:text-gray-400">Enter a number to see formatted output</p>
            </div>

            <!-- Reference -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Base Reference</h3>
                <div class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                    <p><strong>Binary (2):</strong> Uses 0 and 1</p>
                    <p><strong>Octal (8):</strong> Uses 0-7</p>
                    <p><strong>Decimal (10):</strong> Uses 0-9</p>
                    <p><strong>Hexadecimal (16):</strong> Uses 0-9 and A-F</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function baseConverter() {
    return {
        binary: '',
        octal: '',
        decimal: '',
        hex: '',
        errors: {
            binary: '',
            octal: '',
            decimal: '',
            hex: ''
        },

        get paddedBinary() {
            if (!this.binary) return '';
            const len = this.binary.length;
            const padTo = Math.ceil(len / 8) * 8;
            return this.binary.padStart(padTo, '0');
        },

        convertFrom(source) {
            this.clearErrors();

            let value = this[source].trim();
            if (!value) {
                this.clearAll();
                return;
            }

            let decimalValue;

            try {
                switch (source) {
                    case 'binary':
                        if (!/^[01]+$/.test(value)) {
                            this.errors.binary = 'Invalid binary number (use only 0 and 1)';
                            return;
                        }
                        decimalValue = parseInt(value, 2);
                        break;
                    case 'octal':
                        if (!/^[0-7]+$/.test(value)) {
                            this.errors.octal = 'Invalid octal number (use only 0-7)';
                            return;
                        }
                        decimalValue = parseInt(value, 8);
                        break;
                    case 'decimal':
                        if (!/^\d+$/.test(value)) {
                            this.errors.decimal = 'Invalid decimal number (use only 0-9)';
                            return;
                        }
                        decimalValue = parseInt(value, 10);
                        break;
                    case 'hex':
                        if (!/^[0-9A-Fa-f]+$/.test(value)) {
                            this.errors.hex = 'Invalid hex number (use only 0-9 and A-F)';
                            return;
                        }
                        decimalValue = parseInt(value, 16);
                        break;
                }

                if (isNaN(decimalValue) || decimalValue < 0) {
                    this.errors[source] = 'Invalid number';
                    return;
                }

                // Update all fields except the source
                if (source !== 'binary') this.binary = decimalValue.toString(2);
                if (source !== 'octal') this.octal = decimalValue.toString(8);
                if (source !== 'decimal') this.decimal = decimalValue.toString(10);
                if (source !== 'hex') this.hex = decimalValue.toString(16).toUpperCase();

            } catch (e) {
                this.errors[source] = 'Conversion error';
            }
        },

        setDecimal(value) {
            this.decimal = value.toString();
            this.convertFrom('decimal');
        },

        clearAll() {
            this.binary = '';
            this.octal = '';
            this.decimal = '';
            this.hex = '';
            this.clearErrors();
        },

        clearErrors() {
            this.errors = { binary: '', octal: '', decimal: '', hex: '' };
        },

        copyValue(value, button) {
            if (!value) return;
            navigator.clipboard.writeText(value).then(() => {
                const svg = button.innerHTML;
                button.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => { button.innerHTML = svg; }, 1500);
            });
        }
    };
}
</script>
@endpush
