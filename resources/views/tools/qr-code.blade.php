@extends('layouts.app')

@section('title', 'QR Code Generator - Create QR Codes Online Free | Dev Tools')
@section('meta_description', 'Free online QR code generator. Create QR codes for URLs, text, email, phone numbers. Customize colors and size. Download as PNG instantly.')
@section('meta_keywords', 'qr code generator, create qr code, qr code maker, free qr code, qr code online, generate qr code, custom qr code, qr code download')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "QR Code Generator",
    "description": "Generate QR codes for URLs, text, and more with customizable colors and sizes",
    "url": "{{ route('tools.qr-code') }}",
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
<div x-data="qrCodeGenerator()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">QR Code Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate QR codes for URLs, text, and more</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input Section -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content</label>
                <textarea
                    x-model="content"
                    @input="generateQR()"
                    class="w-full h-32 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none font-mono text-sm"
                    placeholder="Enter URL, text, or any content..."
                ></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <span x-text="content.length"></span> characters
                </p>
            </div>

            <!-- Quick Templates -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Quick Templates</label>
                <div class="flex flex-wrap gap-2">
                    <button @click="applyTemplate('url')" class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        URL
                    </button>
                    <button @click="applyTemplate('email')" class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        Email
                    </button>
                    <button @click="applyTemplate('phone')" class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        Phone
                    </button>
                    <button @click="applyTemplate('sms')" class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        SMS
                    </button>
                    <button @click="applyTemplate('wifi')" class="px-3 py-1.5 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
                        WiFi
                    </button>
                </div>
            </div>

            <!-- Options -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Options</label>

                <div class="space-y-4">
                    <!-- Size -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">
                            Size: <span x-text="size + 'x' + size"></span> px
                        </label>
                        <input
                            type="range"
                            x-model="size"
                            @input="generateQR()"
                            min="128"
                            max="512"
                            step="32"
                            class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer accent-indigo-600"
                        >
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>128px</span>
                            <span>512px</span>
                        </div>
                    </div>

                    <!-- Colors -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Foreground</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    x-model="foreground"
                                    @input="generateQR()"
                                    class="w-10 h-10 rounded cursor-pointer border border-gray-300 dark:border-dark-border"
                                >
                                <input
                                    type="text"
                                    x-model="foreground"
                                    @input="generateQR()"
                                    class="flex-1 p-2 text-xs font-mono border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 uppercase"
                                >
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Background</label>
                            <div class="flex items-center space-x-2">
                                <input
                                    type="color"
                                    x-model="background"
                                    @input="generateQR()"
                                    class="w-10 h-10 rounded cursor-pointer border border-gray-300 dark:border-dark-border"
                                >
                                <input
                                    type="text"
                                    x-model="background"
                                    @input="generateQR()"
                                    class="flex-1 p-2 text-xs font-mono border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 uppercase"
                                >
                            </div>
                        </div>
                    </div>

                    <!-- Error Correction -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Error Correction</label>
                        <select
                            x-model="errorCorrection"
                            @change="generateQR()"
                            class="w-full p-2 text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500"
                        >
                            <option value="L">Low (7%)</option>
                            <option value="M">Medium (15%)</option>
                            <option value="Q">Quartile (25%)</option>
                            <option value="H">High (30%)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Output Section -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Generated QR Code</label>

                <div class="flex flex-col items-center justify-center">
                    <!-- QR Code Container -->
                    <div
                        id="qr-container"
                        class="p-4 bg-white rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 min-h-[200px] flex items-center justify-center"
                        :style="{ backgroundColor: background }"
                    >
                        <template x-if="!content">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Enter content to generate QR code</p>
                        </template>
                        <canvas id="qr-canvas" x-show="content" class="max-w-full"></canvas>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-2 mt-4" x-show="content">
                        <button
                            @click="downloadPNG()"
                            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Download PNG</span>
                        </button>
                        <button
                            @click="downloadSVG()"
                            class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center space-x-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            <span>Download SVG</span>
                        </button>
                        <button
                            @click="copyToClipboard($event.currentTarget)"
                            class="px-4 py-2 bg-gray-100 dark:bg-dark-bg hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors flex items-center space-x-2 border border-gray-300 dark:border-dark-border"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            <span>Copy Image</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">About QR Codes</h3>
                <div class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                    <p><strong>URL:</strong> Link directly to websites</p>
                    <p><strong>Email:</strong> Use mailto:email@example.com</p>
                    <p><strong>Phone:</strong> Use tel:+1234567890</p>
                    <p><strong>SMS:</strong> Use sms:+1234567890?body=message</p>
                    <p><strong>WiFi:</strong> Use WIFI:T:WPA;S:NetworkName;P:Password;;</p>
                </div>
            </div>

            <!-- Error Correction Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Error Correction Levels</h3>
                <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                    <li><strong>L (Low):</strong> 7% - Smallest QR code</li>
                    <li><strong>M (Medium):</strong> 15% - Good balance</li>
                    <li><strong>Q (Quartile):</strong> 25% - Better recovery</li>
                    <li><strong>H (High):</strong> 30% - Best for logos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
function qrCodeGenerator() {
    return {
        content: '',
        size: 256,
        foreground: '#000000',
        background: '#ffffff',
        errorCorrection: 'M',
        qrCode: null,
        debounceTimer: null,

        init() {
            // Generate initial QR if content exists
            if (this.content) {
                this.generateQR();
            }
        },

        applyTemplate(type) {
            const templates = {
                url: 'https://example.com',
                email: 'mailto:hello@example.com?subject=Hello&body=Hi there!',
                phone: 'tel:+1234567890',
                sms: 'sms:+1234567890?body=Hello!',
                wifi: 'WIFI:T:WPA;S:NetworkName;P:YourPassword;;'
            };
            this.content = templates[type] || '';
            this.generateQR();
        },

        generateQR() {
            // Debounce the generation
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this._doGenerateQR();
            }, 150);
        },

        _doGenerateQR() {
            if (!this.content) return;

            const canvas = document.getElementById('qr-canvas');
            if (!canvas) return;

            // Clear previous
            const ctx = canvas.getContext('2d');

            // Create temporary container for QRCode library
            const tempDiv = document.createElement('div');

            try {
                // QRCode.js creates a canvas inside the div
                new QRCode(tempDiv, {
                    text: this.content,
                    width: parseInt(this.size),
                    height: parseInt(this.size),
                    colorDark: this.foreground,
                    colorLight: this.background,
                    correctLevel: QRCode.CorrectLevel[this.errorCorrection]
                });

                // Copy the generated canvas to our canvas
                const generatedCanvas = tempDiv.querySelector('canvas');
                if (generatedCanvas) {
                    canvas.width = parseInt(this.size);
                    canvas.height = parseInt(this.size);
                    ctx.drawImage(generatedCanvas, 0, 0);
                }
            } catch (e) {
                console.error('QR generation failed:', e);
            }
        },

        downloadPNG() {
            const canvas = document.getElementById('qr-canvas');
            if (!canvas) return;

            const link = document.createElement('a');
            link.download = 'qrcode.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        },

        downloadSVG() {
            if (!this.content) return;

            // Generate SVG using a simple approach
            const size = parseInt(this.size);
            const moduleCount = this._getModuleCount();
            const cellSize = size / moduleCount;

            // Create temporary QR to get the modules
            const tempDiv = document.createElement('div');
            new QRCode(tempDiv, {
                text: this.content,
                width: size,
                height: size,
                colorDark: this.foreground,
                colorLight: this.background,
                correctLevel: QRCode.CorrectLevel[this.errorCorrection]
            });

            // Get the canvas data
            const canvas = tempDiv.querySelector('canvas');
            const ctx = canvas.getContext('2d');

            let svg = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${size} ${size}" width="${size}" height="${size}">`;
            svg += `<rect width="100%" height="100%" fill="${this.background}"/>`;

            // Sample pixels to create SVG rectangles
            const imageData = ctx.getImageData(0, 0, size, size);
            const step = Math.max(1, Math.floor(size / moduleCount));

            for (let y = 0; y < size; y += step) {
                for (let x = 0; x < size; x += step) {
                    const i = (y * size + x) * 4;
                    const r = imageData.data[i];
                    const g = imageData.data[i + 1];
                    const b = imageData.data[i + 2];

                    // Check if this is a dark module
                    if (r < 128 && g < 128 && b < 128) {
                        svg += `<rect x="${x}" y="${y}" width="${step}" height="${step}" fill="${this.foreground}"/>`;
                    }
                }
            }

            svg += '</svg>';

            const blob = new Blob([svg], { type: 'image/svg+xml' });
            const link = document.createElement('a');
            link.download = 'qrcode.svg';
            link.href = URL.createObjectURL(blob);
            link.click();
            URL.revokeObjectURL(link.href);
        },

        _getModuleCount() {
            // Approximate module count based on content length and error correction
            const len = this.content.length;
            if (len <= 25) return 21;
            if (len <= 47) return 25;
            if (len <= 77) return 29;
            if (len <= 114) return 33;
            if (len <= 154) return 37;
            return 41;
        },

        async copyToClipboard(button) {
            const canvas = document.getElementById('qr-canvas');
            if (!canvas) return;

            try {
                const blob = await new Promise(resolve => canvas.toBlob(resolve, 'image/png'));
                await navigator.clipboard.write([
                    new ClipboardItem({ 'image/png': blob })
                ]);

                // Visual feedback
                const originalText = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Copied!</span>
                `;
                setTimeout(() => {
                    button.innerHTML = originalText;
                }, 2000);
            } catch (e) {
                console.error('Copy failed:', e);
                alert('Copy to clipboard failed. Try downloading instead.');
            }
        }
    };
}
</script>
@endpush
