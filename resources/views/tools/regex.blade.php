@extends('layouts.app')

@section('title', 'Regex Tester - Test Regular Expressions Online | Dev Tools')
@section('meta_description', 'Free online regex tester. Test and debug regular expressions with live matching, syntax highlighting, and match details. Supports JavaScript regex flags.')
@section('meta_keywords', 'regex tester, regular expression tester, regex online, regex debugger, pattern matching, regex validator, javascript regex')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Regex Tester",
    "description": "Test and debug regular expressions with live matching",
    "url": "{{ route('tools.regex') }}",
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
<div x-data="regexTester()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Regex Tester</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Test and debug regular expressions with live matching</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Left Column: Pattern & Test String -->
        <div class="space-y-4">
            <!-- Pattern Input -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Regular Expression</label>
                <div class="flex items-center gap-2">
                    <span class="text-gray-400 text-lg">/</span>
                    <input
                        type="text"
                        x-model="pattern"
                        @input="testRegex()"
                        class="flex-1 p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Enter regex pattern..."
                    >
                    <span class="text-gray-400 text-lg">/</span>
                    <input
                        type="text"
                        x-model="flags"
                        @input="testRegex()"
                        class="w-16 p-2 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-center focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="gi"
                        maxlength="6"
                    >
                </div>
                <div class="mt-2 flex flex-wrap gap-2">
                    <template x-for="flag in availableFlags" :key="flag.value">
                        <button
                            @click="toggleFlag(flag.value)"
                            :class="flags.includes(flag.value) ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-indigo-300 dark:border-indigo-700' : 'bg-gray-50 dark:bg-dark-bg text-gray-600 dark:text-gray-400 border-gray-300 dark:border-dark-border'"
                            class="px-2 py-1 text-xs font-medium rounded border transition-colors"
                            :title="flag.description"
                            x-text="flag.label"
                        ></button>
                    </template>
                </div>
                <div x-show="error" class="mt-2 p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded text-sm" x-text="error"></div>
            </div>

            <!-- Test String Input -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Test String</label>
                <textarea
                    x-model="testString"
                    @input="testRegex()"
                    class="textarea-code w-full h-40 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                    placeholder="Enter text to test against the regex..."
                ></textarea>
            </div>

            <!-- Common Patterns -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Common Patterns</label>
                <div class="grid grid-cols-2 gap-2">
                    <template x-for="example in examples" :key="example.name">
                        <button
                            @click="loadExample(example)"
                            class="text-left p-2 text-sm bg-gray-50 dark:bg-dark-bg hover:bg-gray-100 dark:hover:bg-dark-border rounded-lg transition-colors"
                        >
                            <span class="font-medium text-gray-900 dark:text-gray-100" x-text="example.name"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Right Column: Results -->
        <div class="space-y-4">
            <!-- Highlighted Result -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Highlighted Matches</label>
                    <span x-show="matches.length > 0" class="text-xs text-gray-500 dark:text-gray-400">
                        <span x-text="matches.length"></span> match<span x-show="matches.length !== 1">es</span>
                    </span>
                </div>
                <div
                    class="w-full h-40 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-gray-50 dark:bg-dark-bg overflow-auto font-mono text-sm whitespace-pre-wrap break-all"
                    x-html="highlightedText"
                ></div>
            </div>

            <!-- Match Details -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Match Details</label>
                <div class="max-h-64 overflow-auto">
                    <div x-show="matches.length === 0 && !error" class="text-gray-500 dark:text-gray-400 text-sm">
                        No matches found
                    </div>
                    <div x-show="matches.length > 0" class="space-y-2">
                        <template x-for="(match, index) in matches" :key="index">
                            <div class="p-2 bg-gray-50 dark:bg-dark-bg rounded-lg border border-gray-200 dark:border-dark-border">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                        Match <span x-text="index + 1"></span>
                                    </span>
                                    <span class="text-xs text-gray-400">
                                        Index: <span x-text="match.index"></span>
                                    </span>
                                </div>
                                <code class="block text-sm text-green-600 dark:text-green-400 break-all" x-text="match.value"></code>
                                <template x-if="match.groups && match.groups.length > 0">
                                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-dark-border">
                                        <span class="text-xs text-gray-500 dark:text-gray-400">Groups:</span>
                                        <template x-for="(group, gIndex) in match.groups" :key="gIndex">
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-xs text-gray-400" x-text="'$' + (gIndex + 1) + ':'"></span>
                                                <code class="text-xs text-blue-600 dark:text-blue-400" x-text="group || '(empty)'"></code>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Quick Reference -->
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <button
                    @click="showReference = !showReference"
                    class="w-full flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    <span>Quick Reference</span>
                    <svg :class="showReference ? 'rotate-180' : ''" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="showReference" x-collapse class="mt-3 grid grid-cols-2 gap-x-4 gap-y-1 text-xs">
                    <template x-for="ref in reference" :key="ref.pattern">
                        <div class="flex items-center gap-2">
                            <code class="text-indigo-600 dark:text-indigo-400 font-medium" x-text="ref.pattern"></code>
                            <span class="text-gray-600 dark:text-gray-400" x-text="ref.description"></span>
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
function regexTester() {
    return {
        pattern: '',
        flags: 'g',
        testString: '',
        matches: [],
        error: '',
        highlightedText: '',
        showReference: false,

        availableFlags: [
            { value: 'g', label: 'global', description: 'Find all matches' },
            { value: 'i', label: 'ignore case', description: 'Case-insensitive matching' },
            { value: 'm', label: 'multiline', description: '^ and $ match line boundaries' },
            { value: 's', label: 'dotAll', description: '. matches newlines' },
            { value: 'u', label: 'unicode', description: 'Enable Unicode support' },
        ],

        examples: [
            { name: 'Email', pattern: '[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\\.[a-zA-Z]{2,}', flags: 'g', test: 'Contact us at hello@example.com or support@test.org' },
            { name: 'URL', pattern: 'https?://[\\w.-]+(?:/[\\w.-]*)*/?', flags: 'g', test: 'Visit https://example.com or http://test.org/path' },
            { name: 'Phone (US)', pattern: '\\(?\\d{3}\\)?[-.\\s]?\\d{3}[-.\\s]?\\d{4}', flags: 'g', test: 'Call (555) 123-4567 or 555.987.6543' },
            { name: 'IP Address', pattern: '\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b', flags: 'g', test: 'Server IPs: 192.168.1.1 and 10.0.0.255' },
            { name: 'Date (YYYY-MM-DD)', pattern: '\\d{4}-\\d{2}-\\d{2}', flags: 'g', test: 'Dates: 2024-01-15 and 2023-12-25' },
            { name: 'HTML Tag', pattern: '<([a-z]+)[^>]*>(.*?)</\\1>', flags: 'gi', test: '<div>Hello</div> and <span>World</span>' },
        ],

        reference: [
            { pattern: '.', description: 'Any character' },
            { pattern: '\\d', description: 'Digit [0-9]' },
            { pattern: '\\w', description: 'Word char' },
            { pattern: '\\s', description: 'Whitespace' },
            { pattern: '^', description: 'Start of string' },
            { pattern: '$', description: 'End of string' },
            { pattern: '*', description: '0 or more' },
            { pattern: '+', description: '1 or more' },
            { pattern: '?', description: '0 or 1' },
            { pattern: '{n,m}', description: 'n to m times' },
            { pattern: '[abc]', description: 'Character set' },
            { pattern: '[^abc]', description: 'Negated set' },
            { pattern: '(abc)', description: 'Capture group' },
            { pattern: 'a|b', description: 'Alternation' },
            { pattern: '\\b', description: 'Word boundary' },
            { pattern: '(?:abc)', description: 'Non-capture' },
        ],

        toggleFlag(flag) {
            if (this.flags.includes(flag)) {
                this.flags = this.flags.replace(flag, '');
            } else {
                this.flags += flag;
            }
            this.testRegex();
        },

        loadExample(example) {
            this.pattern = example.pattern;
            this.flags = example.flags;
            this.testString = example.test;
            this.testRegex();
        },

        testRegex() {
            this.error = '';
            this.matches = [];
            this.highlightedText = this.escapeHtml(this.testString);

            if (!this.pattern || !this.testString) {
                return;
            }

            try {
                const regex = new RegExp(this.pattern, this.flags);
                const text = this.testString;
                let match;
                const matchPositions = [];

                if (this.flags.includes('g')) {
                    while ((match = regex.exec(text)) !== null) {
                        this.matches.push({
                            value: match[0],
                            index: match.index,
                            groups: match.slice(1)
                        });
                        matchPositions.push({
                            start: match.index,
                            end: match.index + match[0].length
                        });
                        if (match[0].length === 0) {
                            regex.lastIndex++;
                        }
                    }
                } else {
                    match = regex.exec(text);
                    if (match) {
                        this.matches.push({
                            value: match[0],
                            index: match.index,
                            groups: match.slice(1)
                        });
                        matchPositions.push({
                            start: match.index,
                            end: match.index + match[0].length
                        });
                    }
                }

                this.highlightedText = this.highlightMatches(text, matchPositions);
            } catch (e) {
                this.error = e.message;
            }
        },

        highlightMatches(text, positions) {
            if (positions.length === 0) {
                return this.escapeHtml(text);
            }

            positions.sort((a, b) => b.start - a.start);

            let result = text;
            for (const pos of positions) {
                const before = result.substring(0, pos.start);
                const match = result.substring(pos.start, pos.end);
                const after = result.substring(pos.end);
                result = before + '\u0000MARK_START\u0000' + match + '\u0000MARK_END\u0000' + after;
            }

            result = this.escapeHtml(result);
            result = result.replace(/\u0000MARK_START\u0000/g, '<mark class="bg-yellow-300 dark:bg-yellow-600 text-gray-900 dark:text-white px-0.5 rounded">');
            result = result.replace(/\u0000MARK_END\u0000/g, '</mark>');

            return result;
        },

        escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };
}
</script>
@endpush
