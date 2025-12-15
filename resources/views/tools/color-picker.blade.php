@extends('layouts.app')

@section('title', 'Color Picker & Converter - HEX, RGB, HSL, CMYK | Dev Tools')
@section('meta_description', 'Free online color picker and converter. Convert between HEX, RGB, HSL, and CMYK color formats. Generate color palettes and complementary colors.')
@section('meta_keywords', 'color picker, color converter, hex to rgb, rgb to hex, hsl converter, cmyk converter, color palette, color tool, web colors')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Color Picker & Converter",
    "description": "Convert between HEX, RGB, HSL, and CMYK color formats with visual picker",
    "url": "{{ route('tools.color-picker') }}",
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
<div x-data="colorPicker()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Color Picker & Converter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert between HEX, RGB, HSL, and CMYK formats</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Color Picker Section -->
        <div class="space-y-4">
            <!-- Visual Picker -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Color Picker</label>

                <div class="flex items-center space-x-4">
                    <input
                        type="color"
                        x-model="hex"
                        @input="updateFromHex()"
                        class="w-24 h-24 rounded-lg cursor-pointer border-2 border-gray-300 dark:border-dark-border"
                    >
                    <div class="flex-1">
                        <div
                            class="w-full h-24 rounded-lg border-2 border-gray-300 dark:border-dark-border"
                            :style="{ backgroundColor: hex }"
                        ></div>
                    </div>
                </div>

                <div class="flex space-x-2 mt-4">
                    <button
                        @click="randomColor()"
                        class="flex-1 py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors"
                    >
                        Random Color
                    </button>
                    <button
                        @click="resetColor()"
                        class="py-2 px-4 bg-gray-200 dark:bg-dark-bg hover:bg-gray-300 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Color Inputs -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Color Values</label>

                <div class="space-y-4">
                    <!-- HEX -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">HEX</label>
                        <div class="flex space-x-2">
                            <input
                                type="text"
                                x-model="hex"
                                @input="updateFromHex()"
                                class="flex-1 p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 uppercase"
                                placeholder="#000000"
                            >
                            <button @click="copyValue(hex, $event.currentTarget)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- RGB -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">RGB</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 grid grid-cols-3 gap-2">
                                <input type="number" x-model.number="rgb.r" @input="updateFromRgb()" min="0" max="255" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="R">
                                <input type="number" x-model.number="rgb.g" @input="updateFromRgb()" min="0" max="255" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="G">
                                <input type="number" x-model.number="rgb.b" @input="updateFromRgb()" min="0" max="255" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="B">
                            </div>
                            <button @click="copyValue(rgbString, $event.currentTarget)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="rgbString"></p>
                    </div>

                    <!-- HSL -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">HSL</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 grid grid-cols-3 gap-2">
                                <input type="number" x-model.number="hsl.h" @input="updateFromHsl()" min="0" max="360" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="H">
                                <input type="number" x-model.number="hsl.s" @input="updateFromHsl()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="S">
                                <input type="number" x-model.number="hsl.l" @input="updateFromHsl()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="L">
                            </div>
                            <button @click="copyValue(hslString, $event.currentTarget)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="hslString"></p>
                    </div>

                    <!-- CMYK -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">CMYK</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 grid grid-cols-4 gap-2">
                                <input type="number" x-model.number="cmyk.c" @input="updateFromCmyk()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="C">
                                <input type="number" x-model.number="cmyk.m" @input="updateFromCmyk()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="M">
                                <input type="number" x-model.number="cmyk.y" @input="updateFromCmyk()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="Y">
                                <input type="number" x-model.number="cmyk.k" @input="updateFromCmyk()" min="0" max="100" class="p-2 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 text-center" placeholder="K">
                            </div>
                            <button @click="copyValue(cmykString, $event.currentTarget)" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 border border-gray-300 dark:border-dark-border rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="cmykString"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Color Palette Section -->
        <div class="space-y-4">
            <!-- Complementary Colors -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Color Harmony</label>

                <div class="space-y-4">
                    <!-- Complementary -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Complementary</label>
                        <div class="flex space-x-2">
                            <div class="flex-1 h-12 rounded-lg border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: hex }" @click="copyValue(hex, $event.currentTarget)"></div>
                            <div class="flex-1 h-12 rounded-lg border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: complementary }" @click="copyValue(complementary, $event.currentTarget)"></div>
                        </div>
                    </div>

                    <!-- Triadic -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Triadic</label>
                        <div class="flex space-x-2">
                            <template x-for="color in triadic" :key="color">
                                <div class="flex-1 h-12 rounded-lg border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: color }" @click="copyValue(color, $event.currentTarget)"></div>
                            </template>
                        </div>
                    </div>

                    <!-- Analogous -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Analogous</label>
                        <div class="flex space-x-2">
                            <template x-for="color in analogous" :key="color">
                                <div class="flex-1 h-12 rounded-lg border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: color }" @click="copyValue(color, $event.currentTarget)"></div>
                            </template>
                        </div>
                    </div>

                    <!-- Shades -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Shades</label>
                        <div class="flex space-x-1">
                            <template x-for="shade in shades" :key="shade">
                                <div class="flex-1 h-12 rounded border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: shade }" @click="copyValue(shade, $event.currentTarget)"></div>
                            </template>
                        </div>
                    </div>

                    <!-- Tints -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Tints</label>
                        <div class="flex space-x-1">
                            <template x-for="tint in tints" :key="tint">
                                <div class="flex-1 h-12 rounded border border-gray-300 dark:border-dark-border cursor-pointer" :style="{ backgroundColor: tint }" @click="copyValue(tint, $event.currentTarget)"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Click any color to copy its HEX value</p>
            </div>

            <!-- Color Info -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Color Info</label>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Brightness:</span>
                        <span class="font-mono ml-2" x-text="brightness + '%'"></span>
                    </div>
                    <div>
                        <span class="text-gray-600 dark:text-gray-400">Luminance:</span>
                        <span class="font-mono ml-2" x-text="luminance.toFixed(3)"></span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-gray-600 dark:text-gray-400">Suggested Text:</span>
                        <span class="font-mono ml-2 px-2 py-1 rounded" :style="{ backgroundColor: hex, color: textColor }" x-text="textColor"></span>
                    </div>
                </div>
            </div>

            <!-- Common Colors -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Common Colors</label>

                <div class="grid grid-cols-8 gap-2">
                    <template x-for="color in commonColors" :key="color">
                        <button
                            @click="hex = color; updateFromHex()"
                            class="w-full aspect-square rounded-lg border-2 border-transparent hover:border-gray-400 dark:hover:border-gray-500 transition-colors"
                            :style="{ backgroundColor: color }"
                            :title="color"
                        ></button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function colorPicker() {
    return {
        hex: '#6366f1',
        rgb: { r: 99, g: 102, b: 241 },
        hsl: { h: 239, s: 84, l: 67 },
        cmyk: { c: 59, m: 58, y: 0, k: 5 },

        commonColors: [
            '#ef4444', '#f97316', '#eab308', '#22c55e',
            '#14b8a6', '#3b82f6', '#8b5cf6', '#ec4899',
            '#000000', '#374151', '#6b7280', '#9ca3af',
            '#d1d5db', '#e5e7eb', '#f3f4f6', '#ffffff'
        ],

        init() {
            this.updateFromHex();
        },

        get rgbString() {
            return `rgb(${this.rgb.r}, ${this.rgb.g}, ${this.rgb.b})`;
        },

        get hslString() {
            return `hsl(${this.hsl.h}, ${this.hsl.s}%, ${this.hsl.l}%)`;
        },

        get cmykString() {
            return `cmyk(${this.cmyk.c}%, ${this.cmyk.m}%, ${this.cmyk.y}%, ${this.cmyk.k}%)`;
        },

        get complementary() {
            const h = (this.hsl.h + 180) % 360;
            return this.hslToHex(h, this.hsl.s, this.hsl.l);
        },

        get triadic() {
            return [
                this.hex,
                this.hslToHex((this.hsl.h + 120) % 360, this.hsl.s, this.hsl.l),
                this.hslToHex((this.hsl.h + 240) % 360, this.hsl.s, this.hsl.l)
            ];
        },

        get analogous() {
            return [
                this.hslToHex((this.hsl.h - 30 + 360) % 360, this.hsl.s, this.hsl.l),
                this.hex,
                this.hslToHex((this.hsl.h + 30) % 360, this.hsl.s, this.hsl.l)
            ];
        },

        get shades() {
            const shades = [];
            for (let i = 0; i < 7; i++) {
                const l = Math.max(0, this.hsl.l - (i * 10));
                shades.push(this.hslToHex(this.hsl.h, this.hsl.s, l));
            }
            return shades;
        },

        get tints() {
            const tints = [];
            for (let i = 0; i < 7; i++) {
                const l = Math.min(100, this.hsl.l + (i * 5));
                tints.push(this.hslToHex(this.hsl.h, this.hsl.s, l));
            }
            return tints;
        },

        get brightness() {
            return Math.round((this.rgb.r * 299 + this.rgb.g * 587 + this.rgb.b * 114) / 1000 / 255 * 100);
        },

        get luminance() {
            const r = this.rgb.r / 255;
            const g = this.rgb.g / 255;
            const b = this.rgb.b / 255;
            const toLinear = (c) => c <= 0.03928 ? c / 12.92 : Math.pow((c + 0.055) / 1.055, 2.4);
            return 0.2126 * toLinear(r) + 0.7152 * toLinear(g) + 0.0722 * toLinear(b);
        },

        get textColor() {
            return this.luminance > 0.179 ? '#000000' : '#ffffff';
        },

        updateFromHex() {
            let hex = this.hex.replace('#', '');
            if (hex.length === 3) {
                hex = hex.split('').map(c => c + c).join('');
            }
            if (hex.length !== 6) return;

            this.hex = '#' + hex.toLowerCase();
            const r = parseInt(hex.substr(0, 2), 16);
            const g = parseInt(hex.substr(2, 2), 16);
            const b = parseInt(hex.substr(4, 2), 16);

            if (isNaN(r) || isNaN(g) || isNaN(b)) return;

            this.rgb = { r, g, b };
            this.hsl = this.rgbToHsl(r, g, b);
            this.cmyk = this.rgbToCmyk(r, g, b);
        },

        updateFromRgb() {
            const { r, g, b } = this.rgb;
            if (r < 0 || r > 255 || g < 0 || g > 255 || b < 0 || b > 255) return;

            this.hex = this.rgbToHex(r, g, b);
            this.hsl = this.rgbToHsl(r, g, b);
            this.cmyk = this.rgbToCmyk(r, g, b);
        },

        updateFromHsl() {
            const { h, s, l } = this.hsl;
            if (h < 0 || h > 360 || s < 0 || s > 100 || l < 0 || l > 100) return;

            const rgb = this.hslToRgb(h, s, l);
            this.rgb = rgb;
            this.hex = this.rgbToHex(rgb.r, rgb.g, rgb.b);
            this.cmyk = this.rgbToCmyk(rgb.r, rgb.g, rgb.b);
        },

        updateFromCmyk() {
            const { c, m, y, k } = this.cmyk;
            if (c < 0 || c > 100 || m < 0 || m > 100 || y < 0 || y > 100 || k < 0 || k > 100) return;

            const rgb = this.cmykToRgb(c, m, y, k);
            this.rgb = rgb;
            this.hex = this.rgbToHex(rgb.r, rgb.g, rgb.b);
            this.hsl = this.rgbToHsl(rgb.r, rgb.g, rgb.b);
        },

        rgbToHex(r, g, b) {
            return '#' + [r, g, b].map(x => x.toString(16).padStart(2, '0')).join('');
        },

        rgbToHsl(r, g, b) {
            r /= 255; g /= 255; b /= 255;
            const max = Math.max(r, g, b), min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;

            if (max === min) {
                h = s = 0;
            } else {
                const d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r: h = ((g - b) / d + (g < b ? 6 : 0)) / 6; break;
                    case g: h = ((b - r) / d + 2) / 6; break;
                    case b: h = ((r - g) / d + 4) / 6; break;
                }
            }

            return { h: Math.round(h * 360), s: Math.round(s * 100), l: Math.round(l * 100) };
        },

        hslToRgb(h, s, l) {
            h /= 360; s /= 100; l /= 100;
            let r, g, b;

            if (s === 0) {
                r = g = b = l;
            } else {
                const hue2rgb = (p, q, t) => {
                    if (t < 0) t += 1;
                    if (t > 1) t -= 1;
                    if (t < 1/6) return p + (q - p) * 6 * t;
                    if (t < 1/2) return q;
                    if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
                    return p;
                };
                const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
                const p = 2 * l - q;
                r = hue2rgb(p, q, h + 1/3);
                g = hue2rgb(p, q, h);
                b = hue2rgb(p, q, h - 1/3);
            }

            return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255) };
        },

        hslToHex(h, s, l) {
            const rgb = this.hslToRgb(h, s, l);
            return this.rgbToHex(rgb.r, rgb.g, rgb.b);
        },

        rgbToCmyk(r, g, b) {
            r /= 255; g /= 255; b /= 255;
            const k = 1 - Math.max(r, g, b);
            if (k === 1) return { c: 0, m: 0, y: 0, k: 100 };
            const c = (1 - r - k) / (1 - k);
            const m = (1 - g - k) / (1 - k);
            const y = (1 - b - k) / (1 - k);
            return { c: Math.round(c * 100), m: Math.round(m * 100), y: Math.round(y * 100), k: Math.round(k * 100) };
        },

        cmykToRgb(c, m, y, k) {
            c /= 100; m /= 100; y /= 100; k /= 100;
            const r = 255 * (1 - c) * (1 - k);
            const g = 255 * (1 - m) * (1 - k);
            const b = 255 * (1 - y) * (1 - k);
            return { r: Math.round(r), g: Math.round(g), b: Math.round(b) };
        },

        randomColor() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            this.hex = this.rgbToHex(r, g, b);
            this.updateFromHex();
        },

        resetColor() {
            this.hex = '#6366f1';
            this.updateFromHex();
        },

        copyValue(value, button) {
            navigator.clipboard.writeText(value).then(() => {
                const original = button.innerHTML;
                button.innerHTML = '<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                setTimeout(() => { button.innerHTML = original; }, 1500);
            });
        }
    };
}
</script>
@endpush
