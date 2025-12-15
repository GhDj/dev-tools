@extends('layouts.app')

@section('title', 'HTML Entity Encoder/Decoder - Free Online Tool | Dev Tools')
@section('meta_description', 'Free online HTML entity encoder and decoder. Convert special characters to HTML entities and decode them back. Supports named and numeric entities.')
@section('meta_keywords', 'html entity encoder, html entity decoder, encode html, decode html entities, html special characters, free html tool')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "HTML Entity Encoder/Decoder",
    "description": "Encode special characters to HTML entities or decode HTML entities back to text",
    "url": "{{ route('tools.html-entity') }}",
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
<div x-data="htmlEntityTool()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">HTML Entity Encoder/Decoder</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Encode special characters to HTML entities or decode them back to text</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <!-- Options -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Encoding Mode:</span>
                <select
                    x-model="encodeMode"
                    class="px-3 py-1.5 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                    <option value="named">Named Entities (&amp;amp;)</option>
                    <option value="numeric">Numeric Entities (&amp;#38;)</option>
                    <option value="hex">Hex Entities (&amp;#x26;)</option>
                </select>
            </div>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" x-model="encodeAll" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">Encode all characters</span>
            </label>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Input</label>
                    <button
                        @click="clearInput()"
                        x-show="input.length > 0"
                        class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        Clear
                    </button>
                </div>
                <textarea
                    x-model="input"
                    class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Enter text to encode or HTML entities to decode..."
                ></textarea>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex gap-2">
                    <button
                        @click="encode()"
                        :disabled="!input.trim()"
                        class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Encode
                    </button>
                    <button
                        @click="decode()"
                        :disabled="!input.trim()"
                        class="flex-1 py-2 px-4 bg-gray-600 hover:bg-gray-700 disabled:bg-gray-400 text-white font-medium rounded-lg transition-colors"
                    >
                        Decode
                    </button>
                </div>
            </div>
        </div>

        <!-- Output -->
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Output</label>
                <div class="flex items-center space-x-2">
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
            </div>

            <textarea
                x-model="output"
                readonly
                class="textarea-code w-full h-56 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none"
                placeholder="Output will appear here..."
            ></textarea>

            <!-- Statistics -->
            <div x-show="output" class="mt-3 pt-3 border-t border-gray-200 dark:border-dark-border">
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div class="text-gray-600 dark:text-gray-400">
                        Input length: <span class="font-medium text-gray-900 dark:text-white" x-text="input.length"></span>
                    </div>
                    <div class="text-gray-600 dark:text-gray-400">
                        Output length: <span class="font-medium text-gray-900 dark:text-white" x-text="output.length"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Entities Reference -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Common HTML Entities</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <template x-for="entity in commonEntities" :key="entity.char">
                <button
                    @click="insertEntity(entity)"
                    class="p-2 border border-gray-200 dark:border-dark-border rounded-lg hover:bg-gray-50 dark:hover:bg-dark-border transition-colors text-center"
                >
                    <div class="text-xl font-mono text-gray-900 dark:text-white" x-text="entity.char"></div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate" x-text="entity.name"></div>
                </button>
            </template>
        </div>
    </div>

    <!-- Entity Categories -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Entity Reference</h2>

        <div x-data="{ activeTab: 'special' }" class="space-y-4">
            <div class="flex flex-wrap gap-2">
                <button
                    @click="activeTab = 'special'"
                    :class="activeTab === 'special' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                >
                    Special Characters
                </button>
                <button
                    @click="activeTab = 'punctuation'"
                    :class="activeTab === 'punctuation' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                >
                    Punctuation
                </button>
                <button
                    @click="activeTab = 'math'"
                    :class="activeTab === 'math' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                >
                    Math Symbols
                </button>
                <button
                    @click="activeTab = 'currency'"
                    :class="activeTab === 'currency' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                >
                    Currency
                </button>
                <button
                    @click="activeTab = 'arrows'"
                    :class="activeTab === 'arrows' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                    class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors"
                >
                    Arrows
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-200 dark:border-dark-border">
                            <th class="text-left py-2 px-3 font-medium text-gray-700 dark:text-gray-300">Character</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 dark:text-gray-300">Named</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 dark:text-gray-300">Numeric</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 dark:text-gray-300">Hex</th>
                            <th class="text-left py-2 px-3 font-medium text-gray-700 dark:text-gray-300">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="entity in entityCategories[activeTab]" :key="entity.code">
                            <tr class="border-b border-gray-100 dark:border-dark-border/50 hover:bg-gray-50 dark:hover:bg-dark-border/30">
                                <td class="py-2 px-3 text-lg font-mono text-gray-900 dark:text-white" x-text="entity.char"></td>
                                <td class="py-2 px-3 font-mono text-indigo-600 dark:text-indigo-400 cursor-pointer hover:underline" @click="copyToClipboard(entity.named, $event.currentTarget)" x-text="entity.named"></td>
                                <td class="py-2 px-3 font-mono text-gray-600 dark:text-gray-400 cursor-pointer hover:underline" @click="copyToClipboard(entity.numeric, $event.currentTarget)" x-text="entity.numeric"></td>
                                <td class="py-2 px-3 font-mono text-gray-600 dark:text-gray-400 cursor-pointer hover:underline" @click="copyToClipboard(entity.hex, $event.currentTarget)" x-text="entity.hex"></td>
                                <td class="py-2 px-3 text-gray-600 dark:text-gray-400" x-text="entity.description"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function htmlEntityTool() {
    return {
        input: '',
        output: '',
        encodeMode: 'named',
        encodeAll: false,

        commonEntities: [
            { char: '&', name: '&amp;', code: 38 },
            { char: '<', name: '&lt;', code: 60 },
            { char: '>', name: '&gt;', code: 62 },
            { char: '"', name: '&quot;', code: 34 },
            { char: "'", name: '&apos;', code: 39 },
            { char: ' ', name: '&nbsp;', code: 160 },
            { char: '©', name: '&copy;', code: 169 },
            { char: '®', name: '&reg;', code: 174 },
            { char: '™', name: '&trade;', code: 8482 },
            { char: '€', name: '&euro;', code: 8364 },
            { char: '£', name: '&pound;', code: 163 },
            { char: '¥', name: '&yen;', code: 165 },
        ],

        entityCategories: {
            special: [
                { char: '&', named: '&amp;', numeric: '&#38;', hex: '&#x26;', code: 38, description: 'Ampersand' },
                { char: '<', named: '&lt;', numeric: '&#60;', hex: '&#x3C;', code: 60, description: 'Less than' },
                { char: '>', named: '&gt;', numeric: '&#62;', hex: '&#x3E;', code: 62, description: 'Greater than' },
                { char: '"', named: '&quot;', numeric: '&#34;', hex: '&#x22;', code: 34, description: 'Double quote' },
                { char: "'", named: '&apos;', numeric: '&#39;', hex: '&#x27;', code: 39, description: 'Single quote' },
                { char: ' ', named: '&nbsp;', numeric: '&#160;', hex: '&#xA0;', code: 160, description: 'Non-breaking space' },
                { char: '©', named: '&copy;', numeric: '&#169;', hex: '&#xA9;', code: 169, description: 'Copyright' },
                { char: '®', named: '&reg;', numeric: '&#174;', hex: '&#xAE;', code: 174, description: 'Registered trademark' },
                { char: '™', named: '&trade;', numeric: '&#8482;', hex: '&#x2122;', code: 8482, description: 'Trademark' },
            ],
            punctuation: [
                { char: '–', named: '&ndash;', numeric: '&#8211;', hex: '&#x2013;', code: 8211, description: 'En dash' },
                { char: '—', named: '&mdash;', numeric: '&#8212;', hex: '&#x2014;', code: 8212, description: 'Em dash' },
                { char: '"', named: '&ldquo;', numeric: '&#8220;', hex: '&#x201C;', code: 8220, description: 'Left double quote' },
                { char: '"', named: '&rdquo;', numeric: '&#8221;', hex: '&#x201D;', code: 8221, description: 'Right double quote' },
                { char: ''', named: '&lsquo;', numeric: '&#8216;', hex: '&#x2018;', code: 8216, description: 'Left single quote' },
                { char: ''', named: '&rsquo;', numeric: '&#8217;', hex: '&#x2019;', code: 8217, description: 'Right single quote' },
                { char: '…', named: '&hellip;', numeric: '&#8230;', hex: '&#x2026;', code: 8230, description: 'Horizontal ellipsis' },
                { char: '•', named: '&bull;', numeric: '&#8226;', hex: '&#x2022;', code: 8226, description: 'Bullet' },
                { char: '·', named: '&middot;', numeric: '&#183;', hex: '&#xB7;', code: 183, description: 'Middle dot' },
            ],
            math: [
                { char: '±', named: '&plusmn;', numeric: '&#177;', hex: '&#xB1;', code: 177, description: 'Plus-minus' },
                { char: '×', named: '&times;', numeric: '&#215;', hex: '&#xD7;', code: 215, description: 'Multiplication' },
                { char: '÷', named: '&divide;', numeric: '&#247;', hex: '&#xF7;', code: 247, description: 'Division' },
                { char: '≠', named: '&ne;', numeric: '&#8800;', hex: '&#x2260;', code: 8800, description: 'Not equal' },
                { char: '≤', named: '&le;', numeric: '&#8804;', hex: '&#x2264;', code: 8804, description: 'Less or equal' },
                { char: '≥', named: '&ge;', numeric: '&#8805;', hex: '&#x2265;', code: 8805, description: 'Greater or equal' },
                { char: '∞', named: '&infin;', numeric: '&#8734;', hex: '&#x221E;', code: 8734, description: 'Infinity' },
                { char: '√', named: '&radic;', numeric: '&#8730;', hex: '&#x221A;', code: 8730, description: 'Square root' },
                { char: '∑', named: '&sum;', numeric: '&#8721;', hex: '&#x2211;', code: 8721, description: 'Summation' },
            ],
            currency: [
                { char: '€', named: '&euro;', numeric: '&#8364;', hex: '&#x20AC;', code: 8364, description: 'Euro' },
                { char: '£', named: '&pound;', numeric: '&#163;', hex: '&#xA3;', code: 163, description: 'Pound' },
                { char: '¥', named: '&yen;', numeric: '&#165;', hex: '&#xA5;', code: 165, description: 'Yen' },
                { char: '¢', named: '&cent;', numeric: '&#162;', hex: '&#xA2;', code: 162, description: 'Cent' },
                { char: '¤', named: '&curren;', numeric: '&#164;', hex: '&#xA4;', code: 164, description: 'Currency' },
                { char: '₹', named: null, numeric: '&#8377;', hex: '&#x20B9;', code: 8377, description: 'Indian Rupee' },
                { char: '₿', named: null, numeric: '&#8383;', hex: '&#x20BF;', code: 8383, description: 'Bitcoin' },
            ],
            arrows: [
                { char: '←', named: '&larr;', numeric: '&#8592;', hex: '&#x2190;', code: 8592, description: 'Left arrow' },
                { char: '→', named: '&rarr;', numeric: '&#8594;', hex: '&#x2192;', code: 8594, description: 'Right arrow' },
                { char: '↑', named: '&uarr;', numeric: '&#8593;', hex: '&#x2191;', code: 8593, description: 'Up arrow' },
                { char: '↓', named: '&darr;', numeric: '&#8595;', hex: '&#x2193;', code: 8595, description: 'Down arrow' },
                { char: '↔', named: '&harr;', numeric: '&#8596;', hex: '&#x2194;', code: 8596, description: 'Left-right arrow' },
                { char: '↵', named: '&crarr;', numeric: '&#8629;', hex: '&#x21B5;', code: 8629, description: 'Carriage return' },
                { char: '⇐', named: '&lArr;', numeric: '&#8656;', hex: '&#x21D0;', code: 8656, description: 'Double left arrow' },
                { char: '⇒', named: '&rArr;', numeric: '&#8658;', hex: '&#x21D2;', code: 8658, description: 'Double right arrow' },
            ],
        },

        // Named entity map for encoding
        namedEntities: {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&apos;',
            '\u00A0': '&nbsp;',
            '©': '&copy;',
            '®': '&reg;',
            '™': '&trade;',
            '€': '&euro;',
            '£': '&pound;',
            '¥': '&yen;',
            '¢': '&cent;',
            '–': '&ndash;',
            '—': '&mdash;',
            '"': '&ldquo;',
            '"': '&rdquo;',
            ''': '&lsquo;',
            ''': '&rsquo;',
            '…': '&hellip;',
            '•': '&bull;',
            '±': '&plusmn;',
            '×': '&times;',
            '÷': '&divide;',
            '≠': '&ne;',
            '≤': '&le;',
            '≥': '&ge;',
            '∞': '&infin;',
            '←': '&larr;',
            '→': '&rarr;',
            '↑': '&uarr;',
            '↓': '&darr;',
        },

        encode() {
            if (!this.input.trim()) return;

            if (this.encodeAll) {
                this.output = this.encodeAllChars(this.input);
            } else {
                this.output = this.encodeSpecialChars(this.input);
            }
        },

        encodeAllChars(text) {
            let result = '';
            for (let i = 0; i < text.length; i++) {
                const code = text.charCodeAt(i);
                if (this.encodeMode === 'hex') {
                    result += '&#x' + code.toString(16).toUpperCase() + ';';
                } else {
                    result += '&#' + code + ';';
                }
            }
            return result;
        },

        encodeSpecialChars(text) {
            let result = '';
            for (let i = 0; i < text.length; i++) {
                const char = text[i];
                const code = text.charCodeAt(i);

                // Check if we should encode this character
                if (this.encodeMode === 'named' && this.namedEntities[char]) {
                    result += this.namedEntities[char];
                } else if (code > 127 || char === '&' || char === '<' || char === '>' || char === '"' || char === "'") {
                    if (this.encodeMode === 'named' && this.namedEntities[char]) {
                        result += this.namedEntities[char];
                    } else if (this.encodeMode === 'hex') {
                        result += '&#x' + code.toString(16).toUpperCase() + ';';
                    } else {
                        result += '&#' + code + ';';
                    }
                } else {
                    result += char;
                }
            }
            return result;
        },

        decode() {
            if (!this.input.trim()) return;

            // Create a temporary element to decode HTML entities
            const textarea = document.createElement('textarea');
            textarea.innerHTML = this.input;
            this.output = textarea.value;
        },

        insertEntity(entity) {
            this.input += entity.char;
        },

        copyToClipboard(text, button) {
            DevTools.copyToClipboard(text, button);
        },

        copyOutput(button) {
            DevTools.copyToClipboard(this.output, button);
        },

        clearInput() {
            this.input = '';
            this.output = '';
        }
    };
}
</script>
@endpush
