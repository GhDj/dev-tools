@extends('layouts.app')

@section('title', 'Slug Generator - Create URL-Friendly Slugs | Dev Tools')
@section('meta_description', 'Free online slug generator. Convert text to URL-friendly slugs. Customize separators, case, and transliteration options.')
@section('meta_keywords', 'slug generator, url slug, seo slug, friendly url, text to slug, slug maker, url generator, permalink generator')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Slug Generator",
    "description": "Convert text to URL-friendly slugs with customizable options",
    "url": "{{ route('tools.slug-generator') }}",
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
<div x-data="slugGenerator()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Slug Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert text to URL-friendly slugs</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Input Section -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Input Text</label>
                <textarea
                    x-model="input"
                    @input="generateSlug()"
                    class="w-full h-40 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Enter text to convert to slug..."
                ></textarea>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    <span x-text="input.length"></span> characters
                </p>
            </div>

            <!-- Options -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Options</label>

                <div class="space-y-4">
                    <!-- Separator -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Separator</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                @click="separator = '-'; generateSlug()"
                                :class="separator === '-' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                Hyphen (-)
                            </button>
                            <button
                                @click="separator = '_'; generateSlug()"
                                :class="separator === '_' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                Underscore (_)
                            </button>
                            <button
                                @click="separator = '.'; generateSlug()"
                                :class="separator === '.' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                Dot (.)
                            </button>
                            <button
                                @click="separator = ''; generateSlug()"
                                :class="separator === '' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                None
                            </button>
                        </div>
                    </div>

                    <!-- Case -->
                    <div>
                        <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Case</label>
                        <div class="flex flex-wrap gap-2">
                            <button
                                @click="textCase = 'lower'; generateSlug()"
                                :class="textCase === 'lower' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                lowercase
                            </button>
                            <button
                                @click="textCase = 'upper'; generateSlug()"
                                :class="textCase === 'upper' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                UPPERCASE
                            </button>
                            <button
                                @click="textCase = 'preserve'; generateSlug()"
                                :class="textCase === 'preserve' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300'"
                                class="px-3 py-1.5 text-sm font-medium rounded-lg transition-colors"
                            >
                                Preserve
                            </button>
                        </div>
                    </div>

                    <!-- Toggles -->
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" x-model="transliterate" @change="generateSlug()" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Transliterate accents (é → e, ñ → n)</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" x-model="removeNumbers" @change="generateSlug()" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Remove numbers</span>
                        </label>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" x-model="maxLength" @change="generateSlug()" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Limit length</span>
                        </label>
                        <div x-show="maxLength" class="ml-7">
                            <input
                                type="number"
                                x-model.number="maxLengthValue"
                                @input="generateSlug()"
                                min="1"
                                max="200"
                                class="w-24 p-2 text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100"
                            >
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">characters</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Output Section -->
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Generated Slug</label>
                <div class="relative">
                    <input
                        type="text"
                        :value="slug"
                        readonly
                        class="w-full p-3 pr-24 font-mono text-sm border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100"
                        placeholder="Slug will appear here..."
                    >
                    <button
                        @click="copySlug($event.currentTarget)"
                        x-show="slug"
                        class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded transition-colors"
                    >
                        Copy
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    <span x-text="slug.length"></span> characters
                </p>
            </div>

            <!-- URL Preview -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">URL Preview</label>
                <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg font-mono text-sm text-gray-600 dark:text-gray-400 break-all">
                    <span class="text-gray-400 dark:text-gray-500">https://example.com/</span><span class="text-indigo-600 dark:text-indigo-400" x-text="slug || 'your-slug-here'"></span>
                </div>
            </div>

            <!-- Examples -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Try Examples</label>
                <div class="space-y-2">
                    <button
                        @click="input = 'Hello World! This is a Test'; generateSlug()"
                        class="w-full text-left px-3 py-2 text-sm bg-gray-50 dark:bg-dark-bg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
                    >
                        Hello World! This is a Test
                    </button>
                    <button
                        @click="input = 'Les écoles françaises sont géniales'; generateSlug()"
                        class="w-full text-left px-3 py-2 text-sm bg-gray-50 dark:bg-dark-bg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
                    >
                        Les écoles françaises sont géniales
                    </button>
                    <button
                        @click="input = 'Product #123 - Special Edition (2024)'; generateSlug()"
                        class="w-full text-left px-3 py-2 text-sm bg-gray-50 dark:bg-dark-bg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors"
                    >
                        Product #123 - Special Edition (2024)
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-4">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">What is a Slug?</h3>
                <p class="text-xs text-blue-700 dark:text-blue-400">
                    A slug is a URL-friendly version of a string, typically used in permalinks. It contains only lowercase letters, numbers, and hyphens, making URLs more readable and SEO-friendly.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function slugGenerator() {
    return {
        input: '',
        slug: '',
        separator: '-',
        textCase: 'lower',
        transliterate: true,
        removeNumbers: false,
        maxLength: false,
        maxLengthValue: 50,

        generateSlug() {
            let text = this.input;

            // Transliterate accented characters
            if (this.transliterate) {
                text = this.transliterateText(text);
            }

            // Remove numbers if option is checked
            if (this.removeNumbers) {
                text = text.replace(/[0-9]/g, '');
            }

            // Remove special characters except spaces
            text = text.replace(/[^\w\s-]/g, '');

            // Replace multiple spaces/hyphens with single space
            text = text.replace(/[\s_-]+/g, ' ').trim();

            // Apply case transformation
            if (this.textCase === 'lower') {
                text = text.toLowerCase();
            } else if (this.textCase === 'upper') {
                text = text.toUpperCase();
            }

            // Replace spaces with separator
            text = text.replace(/\s+/g, this.separator);

            // Remove leading/trailing separators
            if (this.separator) {
                const escapedSep = this.separator.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                text = text.replace(new RegExp(`^${escapedSep}+|${escapedSep}+$`, 'g'), '');
            }

            // Apply max length
            if (this.maxLength && this.maxLengthValue > 0) {
                text = text.substring(0, this.maxLengthValue);
                // Remove trailing separator if cut off
                if (this.separator && text.endsWith(this.separator)) {
                    text = text.slice(0, -this.separator.length);
                }
            }

            this.slug = text;
        },

        transliterateText(text) {
            const map = {
                'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae',
                'ç': 'c', 'č': 'c', 'ć': 'c',
                'đ': 'd', 'ď': 'd',
                'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ě': 'e',
                'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i',
                'ñ': 'n', 'ň': 'n',
                'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ø': 'o', 'œ': 'oe',
                'ř': 'r',
                'š': 's', 'ś': 's',
                'ť': 't',
                'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ů': 'u',
                'ý': 'y', 'ÿ': 'y',
                'ž': 'z', 'ź': 'z', 'ż': 'z',
                'ß': 'ss',
                'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE',
                'Ç': 'C', 'Č': 'C', 'Ć': 'C',
                'Đ': 'D', 'Ď': 'D',
                'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ě': 'E',
                'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I',
                'Ñ': 'N', 'Ň': 'N',
                'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ø': 'O', 'Œ': 'OE',
                'Ř': 'R',
                'Š': 'S', 'Ś': 'S',
                'Ť': 'T',
                'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ů': 'U',
                'Ý': 'Y', 'Ÿ': 'Y',
                'Ž': 'Z', 'Ź': 'Z', 'Ż': 'Z'
            };

            return text.split('').map(char => map[char] || char).join('');
        },

        copySlug(button) {
            navigator.clipboard.writeText(this.slug).then(() => {
                const original = button.textContent;
                button.textContent = 'Copied!';
                setTimeout(() => { button.textContent = original; }, 1500);
            });
        }
    };
}
</script>
@endpush
