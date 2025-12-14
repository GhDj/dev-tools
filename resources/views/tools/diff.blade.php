@extends('layouts.app')

@section('title', 'Diff Checker - Compare Text Online | Dev Tools')
@section('meta_description', 'Free online diff checker. Compare two texts side by side, highlight differences, and see additions, deletions, and changes. Perfect for code review.')
@section('meta_keywords', 'diff checker, text compare, diff tool, compare files, code diff, text difference, online diff')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Diff Checker",
    "description": "Compare two texts and highlight differences",
    "url": "{{ route('tools.diff') }}",
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
<div x-data="diffChecker()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Diff Checker</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Compare two texts and highlight differences</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <!-- Input Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Original Text</label>
                <button
                    @click="original = ''; compare()"
                    class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    Clear
                </button>
            </div>
            <textarea
                x-model="original"
                class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                placeholder="Paste original text here..."
            ></textarea>
        </div>

        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Modified Text</label>
                <button
                    @click="modified = ''; compare()"
                    class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                >
                    Clear
                </button>
            </div>
            <textarea
                x-model="modified"
                class="textarea-code w-full h-48 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                placeholder="Paste modified text here..."
            ></textarea>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <button
                @click="compare()"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
            >
                Compare
            </button>
            <button
                @click="swap()"
                class="px-4 py-2 bg-gray-100 dark:bg-dark-border hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors"
            >
                Swap
            </button>
            <button
                @click="loadSample()"
                class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
            >
                Load sample
            </button>
        </div>

        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 dark:text-gray-400">Ignore:</label>
            <label class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" x-model="ignoreWhitespace" @change="compare()" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500">
                Whitespace
            </label>
            <label class="flex items-center gap-1 text-sm text-gray-700 dark:text-gray-300">
                <input type="checkbox" x-model="ignoreCase" @change="compare()" class="rounded border-gray-300 dark:border-dark-border text-indigo-600 focus:ring-indigo-500">
                Case
            </label>
        </div>
    </div>

    <!-- Stats -->
    <div x-show="hasCompared" class="flex flex-wrap gap-4">
        <div class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg text-sm">
            <span class="font-medium" x-text="stats.added"></span> added
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-sm">
            <span class="font-medium" x-text="stats.removed"></span> removed
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300 rounded-lg text-sm">
            <span class="font-medium" x-text="stats.unchanged"></span> unchanged
        </div>
    </div>

    <!-- Diff Output -->
    <div x-show="hasCompared" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border overflow-hidden">
        <div class="grid grid-cols-2 border-b border-gray-200 dark:border-dark-border">
            <div class="px-4 py-2 bg-gray-50 dark:bg-dark-bg text-sm font-medium text-gray-700 dark:text-gray-300 border-r border-gray-200 dark:border-dark-border">
                Original
            </div>
            <div class="px-4 py-2 bg-gray-50 dark:bg-dark-bg text-sm font-medium text-gray-700 dark:text-gray-300">
                Modified
            </div>
        </div>
        <div class="overflow-auto max-h-[500px]">
            <div class="grid grid-cols-2 font-mono text-sm">
                <!-- Original side -->
                <div class="border-r border-gray-200 dark:border-dark-border">
                    <template x-for="(line, index) in diffResult.left" :key="'left-' + index">
                        <div
                            class="flex"
                            :class="{
                                'bg-red-100 dark:bg-red-900/30': line.type === 'removed',
                                'bg-gray-50 dark:bg-dark-bg': line.type === 'unchanged'
                            }"
                        >
                            <span class="w-12 flex-shrink-0 px-2 py-0.5 text-right text-gray-400 dark:text-gray-500 select-none border-r border-gray-200 dark:border-dark-border" x-text="line.lineNum || ''"></span>
                            <span class="w-6 flex-shrink-0 px-1 py-0.5 text-center select-none" :class="line.type === 'removed' ? 'text-red-600 dark:text-red-400' : 'text-gray-300 dark:text-gray-600'" x-text="line.type === 'removed' ? '-' : ''"></span>
                            <pre class="flex-1 px-2 py-0.5 whitespace-pre-wrap break-all" :class="line.type === 'removed' ? 'text-red-800 dark:text-red-200' : 'text-gray-800 dark:text-gray-200'" x-text="line.content"></pre>
                        </div>
                    </template>
                </div>
                <!-- Modified side -->
                <div>
                    <template x-for="(line, index) in diffResult.right" :key="'right-' + index">
                        <div
                            class="flex"
                            :class="{
                                'bg-green-100 dark:bg-green-900/30': line.type === 'added',
                                'bg-gray-50 dark:bg-dark-bg': line.type === 'unchanged'
                            }"
                        >
                            <span class="w-12 flex-shrink-0 px-2 py-0.5 text-right text-gray-400 dark:text-gray-500 select-none border-r border-gray-200 dark:border-dark-border" x-text="line.lineNum || ''"></span>
                            <span class="w-6 flex-shrink-0 px-1 py-0.5 text-center select-none" :class="line.type === 'added' ? 'text-green-600 dark:text-green-400' : 'text-gray-300 dark:text-gray-600'" x-text="line.type === 'added' ? '+' : ''"></span>
                            <pre class="flex-1 px-2 py-0.5 whitespace-pre-wrap break-all" :class="line.type === 'added' ? 'text-green-800 dark:text-green-200' : 'text-gray-800 dark:text-gray-200'" x-text="line.content"></pre>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div x-show="!hasCompared" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-12 text-center">
        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="text-gray-500 dark:text-gray-400">Enter text in both fields and click Compare to see differences</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
function diffChecker() {
    return {
        original: '',
        modified: '',
        ignoreWhitespace: false,
        ignoreCase: false,
        hasCompared: false,
        diffResult: { left: [], right: [] },
        stats: { added: 0, removed: 0, unchanged: 0 },

        loadSample() {
            this.original = `function greet(name) {
    console.log("Hello, " + name);
    return true;
}

const message = "Welcome";
greet(message);`;

            this.modified = `function greet(name, greeting = "Hello") {
    console.log(greeting + ", " + name + "!");
    return true;
}

const message = "Welcome";
const customGreeting = "Hi";
greet(message, customGreeting);`;

            this.compare();
        },

        swap() {
            const temp = this.original;
            this.original = this.modified;
            this.modified = temp;
            if (this.hasCompared) {
                this.compare();
            }
        },

        compare() {
            const originalLines = this.original.split('\n');
            const modifiedLines = this.modified.split('\n');

            const processLine = (line) => {
                let processed = line;
                if (this.ignoreWhitespace) {
                    processed = processed.replace(/\s+/g, ' ').trim();
                }
                if (this.ignoreCase) {
                    processed = processed.toLowerCase();
                }
                return processed;
            };

            // Simple LCS-based diff
            const diff = this.computeDiff(
                originalLines.map(processLine),
                modifiedLines.map(processLine),
                originalLines,
                modifiedLines
            );

            this.diffResult = diff;
            this.hasCompared = true;

            // Calculate stats
            this.stats = {
                added: diff.right.filter(l => l.type === 'added').length,
                removed: diff.left.filter(l => l.type === 'removed').length,
                unchanged: diff.left.filter(l => l.type === 'unchanged').length
            };
        },

        computeDiff(processedOrig, processedMod, originalLines, modifiedLines) {
            const m = processedOrig.length;
            const n = processedMod.length;

            // Build LCS table
            const dp = Array(m + 1).fill(null).map(() => Array(n + 1).fill(0));
            for (let i = 1; i <= m; i++) {
                for (let j = 1; j <= n; j++) {
                    if (processedOrig[i - 1] === processedMod[j - 1]) {
                        dp[i][j] = dp[i - 1][j - 1] + 1;
                    } else {
                        dp[i][j] = Math.max(dp[i - 1][j], dp[i][j - 1]);
                    }
                }
            }

            // Backtrack to find diff
            const left = [];
            const right = [];
            let i = m, j = n;
            let leftLineNum = m;
            let rightLineNum = n;

            const result = [];

            while (i > 0 || j > 0) {
                if (i > 0 && j > 0 && processedOrig[i - 1] === processedMod[j - 1]) {
                    result.unshift({
                        type: 'unchanged',
                        leftContent: originalLines[i - 1],
                        rightContent: modifiedLines[j - 1],
                        leftNum: i,
                        rightNum: j
                    });
                    i--;
                    j--;
                } else if (j > 0 && (i === 0 || dp[i][j - 1] >= dp[i - 1][j])) {
                    result.unshift({
                        type: 'added',
                        leftContent: '',
                        rightContent: modifiedLines[j - 1],
                        leftNum: null,
                        rightNum: j
                    });
                    j--;
                } else {
                    result.unshift({
                        type: 'removed',
                        leftContent: originalLines[i - 1],
                        rightContent: '',
                        leftNum: i,
                        rightNum: null
                    });
                    i--;
                }
            }

            // Convert to left/right format
            for (const item of result) {
                if (item.type === 'unchanged') {
                    left.push({ type: 'unchanged', content: item.leftContent, lineNum: item.leftNum });
                    right.push({ type: 'unchanged', content: item.rightContent, lineNum: item.rightNum });
                } else if (item.type === 'removed') {
                    left.push({ type: 'removed', content: item.leftContent, lineNum: item.leftNum });
                    right.push({ type: 'empty', content: '', lineNum: null });
                } else if (item.type === 'added') {
                    left.push({ type: 'empty', content: '', lineNum: null });
                    right.push({ type: 'added', content: item.rightContent, lineNum: item.rightNum });
                }
            }

            return { left, right };
        }
    };
}
</script>
@endpush
