@extends('layouts.app')

@section('title', 'About - Dev Tools')

@section('meta_description', 'About Dev Tools - Free online developer utilities built with Laravel. Learn about the project, its creator, and the tools available.')

@section('meta_keywords', 'about dev tools, developer utilities, free tools, open source, Ghabri Djalel')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "AboutPage",
    "name": "About Dev Tools",
    "description": "About Dev Tools - Free online developer utilities",
    "url": "{{ route('about') }}",
    "mainEntity": {
        "@@type": "SoftwareApplication",
        "name": "Dev Tools",
        "applicationCategory": "DeveloperApplication",
        "operatingSystem": "Web Browser",
        "offers": {
            "@@type": "Offer",
            "price": "0",
            "priceCurrency": "USD"
        },
        "author": {
            "@@type": "Person",
            "name": "Ghabri Djalel",
            "url": "https://github.com/GhDj"
        }
    }
}
</script>
@endpush

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('home') }}" class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 mb-4">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Tools
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">About Dev Tools</h1>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-gray-200 dark:border-dark-border p-6 sm:p-8 space-y-8">

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">What is Dev Tools?</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Dev Tools is a free collection of online utilities designed to help developers with everyday tasks. Whether you need to format JSON, convert CSV files, encode Base64, generate UUIDs, or format SQL queries - we've got you covered.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mt-3">
                Our goal is simple: provide fast, reliable, and easy-to-use tools without requiring signups, subscriptions, or storing your data.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Available Tools</h2>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">CSV Converter</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">YAML/JSON Converter</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">JSON Parser & Formatter</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Markdown Preview</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">SQL Formatter</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Base64 Encoder/Decoder</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">UUID Generator</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">Hash Generator</span>
                </div>
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-gray-700 dark:text-gray-300">URL Encoder/Decoder</span>
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Key Features</h2>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                <li><strong>No signup required</strong> - Use all tools instantly</li>
                <li><strong>No data stored</strong> - Your input is never saved</li>
                <li><strong>Fast & reliable</strong> - Built with modern technologies</li>
                <li><strong>Dark mode</strong> - Easy on the eyes</li>
                <li><strong>Mobile friendly</strong> - Works on any device</li>
                <li><strong>Copy to clipboard</strong> - One-click copying on all outputs</li>
                <li><strong>API access</strong> - Integrate tools into your workflow</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Technology Stack</h2>
            <p class="text-gray-700 dark:text-gray-300 mb-3">
                Dev Tools is built with modern, reliable technologies:
            </p>
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-border rounded-full text-sm text-gray-700 dark:text-gray-300">Laravel 12</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-border rounded-full text-sm text-gray-700 dark:text-gray-300">PHP 8.2+</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-border rounded-full text-sm text-gray-700 dark:text-gray-300">Tailwind CSS</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-border rounded-full text-sm text-gray-700 dark:text-gray-300">Alpine.js</span>
                <span class="px-3 py-1 bg-gray-100 dark:bg-dark-border rounded-full text-sm text-gray-700 dark:text-gray-300">Vite</span>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">About the Creator</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Dev Tools is created and maintained by <strong>Ghabri Djalel</strong>, a software developer passionate about building useful tools for the developer community.
            </p>
            <div class="mt-4 flex items-center space-x-4">
                <a href="https://github.com/GhDj" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    GitHub Profile
                </a>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Open Source</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Dev Tools is open source! You can view the code, report issues, or contribute on GitHub:
            </p>
            <div class="mt-4">
                <a href="https://github.com/GhDj/dev-tools" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.17 6.839 9.49.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.604-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.464-1.11-1.464-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.115 2.504.337 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.167 22 16.418 22 12c0-5.523-4.477-10-10-10z"/>
                    </svg>
                    github.com/GhDj/dev-tools
                </a>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Feedback & Support</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Have a suggestion, found a bug, or want to request a new tool? Please open an issue on our
                <a href="https://github.com/GhDj/dev-tools/issues" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline">GitHub Issues</a> page.
            </p>
        </section>

    </div>
</div>
@endsection
