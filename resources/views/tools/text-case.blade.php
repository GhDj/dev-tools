@extends('layouts.app')

@section('title', 'Text Case Converter - camelCase, snake_case, Title Case | Dev Tools')
@section('meta_description', 'Free online text case converter. Convert text to lowercase, UPPERCASE, Title Case, camelCase, snake_case, kebab-case, PascalCase, and more.')
@section('meta_keywords', 'text case converter, camelcase converter, snake case, kebab case, title case, uppercase, lowercase, pascal case, text transformer')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Text Case Converter",
    "description": "Convert text between different case formats",
    "url": "{{ route('tools.text-case') }}",
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
<div x-data="textCaseConverter()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Text Case Converter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert text between different case formats</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Input Text</label>
                    <button
                        @click="clear()"
                        class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                    >
                        Clear
                    </button>
                </div>
                <textarea
                    x-model="input"
                    class="w-full h-40 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none font-mono"
                    placeholder="Enter or paste your text here..."
                ></textarea>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex gap-4">
                        <span><span x-text="stats.characters"></span> characters</span>
                        <span><span x-text="stats.words"></span> words</span>
                        <span><span x-text="stats.lines"></span> lines</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Output</label>
                    <button
                        @click="copy($event.currentTarget)"
                        :disabled="!output"
                        class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline disabled:opacity-50 disabled:no-underline"
                    >
                        Copy to clipboard
                    </button>
                </div>
                <textarea
                    x-model="output"
                    readonly
                    class="w-full h-40 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none font-mono"
                    placeholder="Converted text will appear here..."
                ></textarea>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Convert To</h2>
                <div class="space-y-2">
                    <template x-for="converter in converters" :key="converter.id">
                        <button
                            @click="convert(converter.id)"
                            class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors border border-transparent"
                            :class="activeConverter === converter.id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : ''"
                        >
                            <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="converter.name"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 font-mono" x-text="converter.example"></div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="convert('lower')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        lowercase
                    </button>
                    <button
                        @click="convert('upper')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        UPPERCASE
                    </button>
                    <button
                        @click="convert('camel')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        camelCase
                    </button>
                    <button
                        @click="convert('snake')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        snake_case
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function textCaseConverter() {
    return {
        input: '',
        output: '',
        activeConverter: null,
        converters: [
            { id: 'lower', name: 'lowercase', example: 'hello world' },
            { id: 'upper', name: 'UPPERCASE', example: 'HELLO WORLD' },
            { id: 'title', name: 'Title Case', example: 'Hello World' },
            { id: 'sentence', name: 'Sentence case', example: 'Hello world. This is text.' },
            { id: 'camel', name: 'camelCase', example: 'helloWorld' },
            { id: 'pascal', name: 'PascalCase', example: 'HelloWorld' },
            { id: 'snake', name: 'snake_case', example: 'hello_world' },
            { id: 'kebab', name: 'kebab-case', example: 'hello-world' },
            { id: 'constant', name: 'CONSTANT_CASE', example: 'HELLO_WORLD' },
            { id: 'dot', name: 'dot.case', example: 'hello.world' },
            { id: 'path', name: 'path/case', example: 'hello/world' },
            { id: 'alternating', name: 'aLtErNaTiNg', example: 'hElLo WoRlD' },
            { id: 'inverse', name: 'Inverse Case', example: 'hELLO wORLD' },
        ],

        get stats() {
            const text = this.input;
            return {
                characters: text.length,
                words: text.trim() ? text.trim().split(/\s+/).length : 0,
                lines: text ? text.split(/\n/).length : 0
            };
        },

        convert(type) {
            this.activeConverter = type;
            const text = this.input;

            switch (type) {
                case 'lower':
                    this.output = text.toLowerCase();
                    break;
                case 'upper':
                    this.output = text.toUpperCase();
                    break;
                case 'title':
                    this.output = this.toTitleCase(text);
                    break;
                case 'sentence':
                    this.output = this.toSentenceCase(text);
                    break;
                case 'camel':
                    this.output = this.toCamelCase(text);
                    break;
                case 'pascal':
                    this.output = this.toPascalCase(text);
                    break;
                case 'snake':
                    this.output = this.toSnakeCase(text);
                    break;
                case 'kebab':
                    this.output = this.toKebabCase(text);
                    break;
                case 'constant':
                    this.output = this.toConstantCase(text);
                    break;
                case 'dot':
                    this.output = this.toDotCase(text);
                    break;
                case 'path':
                    this.output = this.toPathCase(text);
                    break;
                case 'alternating':
                    this.output = this.toAlternatingCase(text);
                    break;
                case 'inverse':
                    this.output = this.toInverseCase(text);
                    break;
                default:
                    this.output = text;
            }
        },

        toTitleCase(text) {
            return text.toLowerCase().replace(/(?:^|\s|[-_])\w/g, match => match.toUpperCase());
        },

        toSentenceCase(text) {
            return text.toLowerCase().replace(/(^\s*\w|[.!?]\s+\w)/g, match => match.toUpperCase());
        },

        getWords(text) {
            return text
                .replace(/([a-z])([A-Z])/g, '$1 $2')
                .replace(/([A-Z]+)([A-Z][a-z])/g, '$1 $2')
                .replace(/[-_./\\]+/g, ' ')
                .toLowerCase()
                .trim()
                .split(/\s+/)
                .filter(w => w.length > 0);
        },

        toCamelCase(text) {
            const words = this.getWords(text);
            if (words.length === 0) return '';
            return words[0] + words.slice(1).map(w => w.charAt(0).toUpperCase() + w.slice(1)).join('');
        },

        toPascalCase(text) {
            const words = this.getWords(text);
            return words.map(w => w.charAt(0).toUpperCase() + w.slice(1)).join('');
        },

        toSnakeCase(text) {
            return this.getWords(text).join('_');
        },

        toKebabCase(text) {
            return this.getWords(text).join('-');
        },

        toConstantCase(text) {
            return this.getWords(text).join('_').toUpperCase();
        },

        toDotCase(text) {
            return this.getWords(text).join('.');
        },

        toPathCase(text) {
            return this.getWords(text).join('/');
        },

        toAlternatingCase(text) {
            let result = '';
            let upper = false;
            for (let char of text) {
                if (/[a-zA-Z]/.test(char)) {
                    result += upper ? char.toUpperCase() : char.toLowerCase();
                    upper = !upper;
                } else {
                    result += char;
                }
            }
            return result;
        },

        toInverseCase(text) {
            return text.split('').map(char => {
                if (char === char.toUpperCase()) {
                    return char.toLowerCase();
                }
                return char.toUpperCase();
            }).join('');
        },

        copy(button) {
            if (this.output) {
                DevTools.copyToClipboard(this.output, button);
            }
        },

        clear() {
            this.input = '';
            this.output = '';
            this.activeConverter = null;
        }
    };
}
</script>
@endpush
