@extends('layouts.app')

@section('title', 'Sort Lines - Sort, Dedupe, Reverse, Shuffle Text | Dev Tools')
@section('meta_description', 'Free online line sorter. Sort lines alphabetically, numerically, remove duplicates, reverse order, or shuffle randomly. Fast and private - no data stored.')
@section('meta_keywords', 'sort lines, line sorter, sort text, remove duplicates, dedupe lines, reverse lines, shuffle lines, alphabetical sort, natural sort')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Sort Lines",
    "description": "Sort, deduplicate, reverse, and shuffle text lines",
    "url": "{{ route('tools.sort-lines') }}",
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
<div x-data="sortLines()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sort Lines</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Sort, deduplicate, reverse, and shuffle text lines</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="lg:col-span-3 space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Input Text</label>
                    <div class="flex items-center gap-3">
                        <button
                            @click="loadSample()"
                            class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        >
                            Sample
                        </button>
                        <button
                            @click="clear()"
                            class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                        >
                            Clear
                        </button>
                    </div>
                </div>
                <textarea
                    x-model="input"
                    class="w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none font-mono text-sm"
                    placeholder="Enter text with multiple lines...&#10;Each line will be sorted separately"
                ></textarea>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex gap-4">
                        <span><span x-text="stats.lines"></span> lines</span>
                        <span><span x-text="stats.unique"></span> unique</span>
                        <span><span x-text="stats.duplicates"></span> duplicates</span>
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
                    class="w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg text-gray-900 dark:text-gray-100 resize-none font-mono text-sm"
                    placeholder="Sorted output will appear here..."
                ></textarea>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <div class="flex gap-4">
                        <span><span x-text="outputStats.lines"></span> lines</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Sort Options</h2>
                <div class="space-y-2">
                    <template x-for="action in sortActions" :key="action.id">
                        <button
                            @click="sort(action.id)"
                            class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors border border-transparent"
                            :class="activeAction === action.id ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-800' : ''"
                        >
                            <div class="font-medium text-sm text-gray-900 dark:text-white" x-text="action.name"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="action.desc"></div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Options</h2>
                <div class="space-y-3">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="options.caseSensitive" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500 dark:bg-dark-bg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Case sensitive</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="options.trimWhitespace" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500 dark:bg-dark-bg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Trim whitespace</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="options.removeEmpty" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500 dark:bg-dark-bg">
                        <span class="text-sm text-gray-700 dark:text-gray-300">Remove empty lines</span>
                    </label>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Quick Actions</h2>
                <div class="grid grid-cols-2 gap-2">
                    <button
                        @click="sort('az')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        A-Z
                    </button>
                    <button
                        @click="sort('za')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        Z-A
                    </button>
                    <button
                        @click="sort('dedupe')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        Dedupe
                    </button>
                    <button
                        @click="sort('shuffle')"
                        class="p-2 text-xs font-medium bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                    >
                        Shuffle
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sortLines() {
    return {
        input: '',
        output: '',
        activeAction: null,
        options: {
            caseSensitive: false,
            trimWhitespace: true,
            removeEmpty: true
        },
        sortActions: [
            { id: 'az', name: 'Sort A-Z', desc: 'Alphabetical ascending' },
            { id: 'za', name: 'Sort Z-A', desc: 'Alphabetical descending' },
            { id: 'natural', name: 'Natural Sort', desc: 'Smart alphanumeric (file1, file2, file10)' },
            { id: 'numeric', name: 'Sort Numeric', desc: 'By number value (ascending)' },
            { id: 'numeric-desc', name: 'Sort Numeric Desc', desc: 'By number value (descending)' },
            { id: 'length', name: 'Sort by Length', desc: 'Shortest to longest' },
            { id: 'length-desc', name: 'Sort by Length Desc', desc: 'Longest to shortest' },
            { id: 'reverse', name: 'Reverse Lines', desc: 'Flip line order' },
            { id: 'dedupe', name: 'Remove Duplicates', desc: 'Keep unique lines only' },
            { id: 'shuffle', name: 'Shuffle', desc: 'Randomize line order' },
        ],

        get stats() {
            const lines = this.getLines(this.input);
            const uniqueSet = new Set(lines.map(l => this.options.caseSensitive ? l : l.toLowerCase()));
            return {
                lines: lines.length,
                unique: uniqueSet.size,
                duplicates: lines.length - uniqueSet.size
            };
        },

        get outputStats() {
            const lines = this.output ? this.output.split('\n').filter(l => l.length > 0) : [];
            return {
                lines: lines.length
            };
        },

        getLines(text) {
            let lines = text.split('\n');

            if (this.options.trimWhitespace) {
                lines = lines.map(l => l.trim());
            }

            if (this.options.removeEmpty) {
                lines = lines.filter(l => l.length > 0);
            }

            return lines;
        },

        sort(type) {
            this.activeAction = type;
            let lines = this.getLines(this.input);

            if (lines.length === 0) {
                this.output = '';
                return;
            }

            switch (type) {
                case 'az':
                    lines = this.sortAlpha(lines, true);
                    break;
                case 'za':
                    lines = this.sortAlpha(lines, false);
                    break;
                case 'natural':
                    lines = this.sortNatural(lines);
                    break;
                case 'numeric':
                    lines = this.sortNumeric(lines, true);
                    break;
                case 'numeric-desc':
                    lines = this.sortNumeric(lines, false);
                    break;
                case 'length':
                    lines = this.sortByLength(lines, true);
                    break;
                case 'length-desc':
                    lines = this.sortByLength(lines, false);
                    break;
                case 'reverse':
                    lines = lines.reverse();
                    break;
                case 'dedupe':
                    lines = this.removeDuplicates(lines);
                    break;
                case 'shuffle':
                    lines = this.shuffleArray(lines);
                    break;
            }

            this.output = lines.join('\n');
        },

        sortAlpha(lines, ascending) {
            return [...lines].sort((a, b) => {
                const compareA = this.options.caseSensitive ? a : a.toLowerCase();
                const compareB = this.options.caseSensitive ? b : b.toLowerCase();
                const result = compareA.localeCompare(compareB);
                return ascending ? result : -result;
            });
        },

        sortNatural(lines) {
            return [...lines].sort((a, b) => {
                const compareA = this.options.caseSensitive ? a : a.toLowerCase();
                const compareB = this.options.caseSensitive ? b : b.toLowerCase();
                return compareA.localeCompare(compareB, undefined, { numeric: true, sensitivity: 'base' });
            });
        },

        sortNumeric(lines, ascending) {
            return [...lines].sort((a, b) => {
                const numA = parseFloat(a.replace(/[^\d.-]/g, '')) || 0;
                const numB = parseFloat(b.replace(/[^\d.-]/g, '')) || 0;
                return ascending ? numA - numB : numB - numA;
            });
        },

        sortByLength(lines, ascending) {
            return [...lines].sort((a, b) => {
                return ascending ? a.length - b.length : b.length - a.length;
            });
        },

        removeDuplicates(lines) {
            if (this.options.caseSensitive) {
                return [...new Set(lines)];
            }
            const seen = new Map();
            return lines.filter(line => {
                const key = line.toLowerCase();
                if (seen.has(key)) return false;
                seen.set(key, true);
                return true;
            });
        },

        shuffleArray(array) {
            const shuffled = [...array];
            for (let i = shuffled.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
            }
            return shuffled;
        },

        copy(button) {
            if (this.output) {
                DevTools.copyToClipboard(this.output, button);
            }
        },

        clear() {
            this.input = '';
            this.output = '';
            this.activeAction = null;
        },

        loadSample() {
            this.input = `banana
Apple
cherry
apple
Date
banana
Fig
grape
Cherry
date`;
            this.output = '';
            this.activeAction = null;
        }
    };
}
</script>
@endpush
