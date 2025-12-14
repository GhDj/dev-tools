@extends('layouts.app')

@section('title', 'Lorem Ipsum Generator - Placeholder Text Generator | Dev Tools')
@section('meta_description', 'Free online Lorem Ipsum generator. Generate placeholder text by paragraphs, sentences, or words. Perfect for mockups, wireframes, and design projects.')
@section('meta_keywords', 'lorem ipsum generator, placeholder text, dummy text, lipsum, filler text, mockup text, design placeholder, random text generator')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Lorem Ipsum Generator",
    "description": "Generate placeholder text for designs and mockups",
    "url": "{{ route('tools.lorem') }}",
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
<div x-data="loremIpsum()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lorem Ipsum Generator</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Generate placeholder text for designs and mockups</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Options</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <div class="flex gap-2">
                            <template x-for="t in types" :key="t.value">
                                <button
                                    @click="type = t.value; generate()"
                                    :class="type === t.value ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-bg text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'"
                                    class="flex-1 py-2 px-3 rounded-lg text-sm font-medium transition-colors"
                                    x-text="t.label"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Count: <span class="text-indigo-600 dark:text-indigo-400" x-text="count"></span>
                        </label>
                        <input
                            type="range"
                            x-model="count"
                            @input="generate()"
                            min="1"
                            :max="type === 'paragraphs' ? 10 : (type === 'sentences' ? 20 : 100)"
                            class="w-full h-2 bg-gray-200 dark:bg-dark-bg rounded-lg appearance-none cursor-pointer accent-indigo-600"
                        >
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>1</span>
                            <span x-text="type === 'paragraphs' ? '10' : (type === 'sentences' ? '20' : '100')"></span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            id="startWithLorem"
                            x-model="startWithLorem"
                            @change="generate()"
                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        >
                        <label for="startWithLorem" class="text-sm text-gray-700 dark:text-gray-300">
                            Start with "Lorem ipsum..."
                        </label>
                    </div>

                    <button
                        @click="generate()"
                        class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors"
                    >
                        Generate
                    </button>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Statistics</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-center">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="stats.paragraphs"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Paragraphs</div>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-center">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="stats.sentences"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Sentences</div>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-center">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="stats.words"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Words</div>
                    </div>
                    <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg text-center">
                        <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400" x-text="stats.characters"></div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Characters</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4 h-full flex flex-col">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Generated Text</label>
                    <div class="flex gap-2">
                        <button
                            @click="copy($event.currentTarget)"
                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                            title="Copy to clipboard"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                        </button>
                        <button
                            @click="download()"
                            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                            title="Download as TXT"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div
                    class="flex-1 p-4 bg-gray-50 dark:bg-dark-bg rounded-lg overflow-y-auto min-h-[400px] text-gray-700 dark:text-gray-300 leading-relaxed"
                    x-html="formattedOutput"
                ></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loremIpsum() {
    return {
        type: 'paragraphs',
        count: 3,
        startWithLorem: true,
        output: '',
        formattedOutput: '',
        types: [
            { value: 'paragraphs', label: 'Paragraphs' },
            { value: 'sentences', label: 'Sentences' },
            { value: 'words', label: 'Words' }
        ],
        stats: {
            paragraphs: 0,
            sentences: 0,
            words: 0,
            characters: 0
        },

        words: [
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit',
            'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore',
            'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud',
            'exercitation', 'ullamco', 'laboris', 'nisi', 'aliquip', 'ex', 'ea', 'commodo',
            'consequat', 'duis', 'aute', 'irure', 'in', 'reprehenderit', 'voluptate',
            'velit', 'esse', 'cillum', 'fugiat', 'nulla', 'pariatur', 'excepteur', 'sint',
            'occaecat', 'cupidatat', 'non', 'proident', 'sunt', 'culpa', 'qui', 'officia',
            'deserunt', 'mollit', 'anim', 'id', 'est', 'laborum', 'perspiciatis', 'unde',
            'omnis', 'iste', 'natus', 'error', 'voluptatem', 'accusantium', 'doloremque',
            'laudantium', 'totam', 'rem', 'aperiam', 'eaque', 'ipsa', 'quae', 'ab', 'illo',
            'inventore', 'veritatis', 'quasi', 'architecto', 'beatae', 'vitae', 'dicta',
            'explicabo', 'nemo', 'ipsam', 'quia', 'voluptas', 'aspernatur', 'aut', 'odit',
            'fugit', 'consequuntur', 'magni', 'dolores', 'eos', 'ratione', 'sequi',
            'nesciunt', 'neque', 'porro', 'quisquam', 'dolorem', 'adipisci', 'numquam',
            'eius', 'modi', 'tempora', 'magnam', 'quaerat', 'minima', 'nostrum',
            'exercitationem', 'ullam', 'corporis', 'suscipit', 'laboriosam', 'aliquid',
            'commodi', 'consequatur', 'autem', 'vel', 'eum', 'iure', 'quam', 'nihil',
            'molestiae', 'illum', 'quo', 'blanditiis', 'praesentium', 'voluptatum',
            'deleniti', 'atque', 'corrupti', 'quos', 'quas', 'molestias', 'excepturi',
            'recusandae', 'itaque', 'earum', 'rerum', 'hic', 'tenetur', 'sapiente',
            'delectus', 'reiciendis', 'voluptatibus', 'maiores', 'alias', 'perferendis',
            'doloribus', 'asperiores', 'repellat'
        ],

        loremStart: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',

        init() {
            this.generate();
        },

        randomWord() {
            return this.words[Math.floor(Math.random() * this.words.length)];
        },

        generateSentence(minWords = 8, maxWords = 15) {
            const length = Math.floor(Math.random() * (maxWords - minWords + 1)) + minWords;
            let sentence = [];
            for (let i = 0; i < length; i++) {
                sentence.push(this.randomWord());
            }
            sentence[0] = sentence[0].charAt(0).toUpperCase() + sentence[0].slice(1);
            return sentence.join(' ') + '.';
        },

        generateParagraph(minSentences = 4, maxSentences = 8) {
            const length = Math.floor(Math.random() * (maxSentences - minSentences + 1)) + minSentences;
            let sentences = [];
            for (let i = 0; i < length; i++) {
                sentences.push(this.generateSentence());
            }
            return sentences.join(' ');
        },

        generate() {
            let result = [];

            if (this.type === 'paragraphs') {
                for (let i = 0; i < this.count; i++) {
                    if (i === 0 && this.startWithLorem) {
                        result.push(this.loremStart + ' ' + this.generateParagraph(3, 6));
                    } else {
                        result.push(this.generateParagraph());
                    }
                }
                this.output = result.join('\n\n');
                this.formattedOutput = result.map(p => `<p class="mb-4">${p}</p>`).join('');
            } else if (this.type === 'sentences') {
                for (let i = 0; i < this.count; i++) {
                    if (i === 0 && this.startWithLorem) {
                        result.push(this.loremStart);
                    } else {
                        result.push(this.generateSentence());
                    }
                }
                this.output = result.join(' ');
                this.formattedOutput = `<p>${this.output}</p>`;
            } else {
                if (this.startWithLorem) {
                    result = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet'];
                    for (let i = 5; i < this.count; i++) {
                        result.push(this.randomWord());
                    }
                } else {
                    for (let i = 0; i < this.count; i++) {
                        result.push(this.randomWord());
                    }
                }
                this.output = result.slice(0, this.count).join(' ');
                this.formattedOutput = `<p>${this.output}</p>`;
            }

            this.updateStats();
        },

        updateStats() {
            const text = this.output;
            this.stats.characters = text.length;
            this.stats.words = text.split(/\s+/).filter(w => w.length > 0).length;
            this.stats.sentences = (text.match(/[.!?]+/g) || []).length;
            this.stats.paragraphs = text.split(/\n\n+/).filter(p => p.trim().length > 0).length;
        },

        copy(button) {
            if (this.output) {
                DevTools.copyToClipboard(this.output, button);
            }
        },

        download() {
            if (!this.output) return;

            const blob = new Blob([this.output], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'lorem-ipsum.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        }
    };
}
</script>
@endpush
