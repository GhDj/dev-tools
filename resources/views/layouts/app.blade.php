<!DOCTYPE html>
<html lang="en" x-data="{
    darkMode: localStorage.getItem('darkMode') !== null
        ? localStorage.getItem('darkMode') === 'true'
        : window.matchMedia('(prefers-color-scheme: dark)').matches
}" x-init="
    $watch('darkMode', val => localStorage.setItem('darkMode', val));
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (localStorage.getItem('darkMode') === null) darkMode = e.matches;
    });
" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dev Tools - Free Online Developer Utilities')</title>
    <meta name="description" content="@yield('meta_description', 'Free online developer tools for JSON formatting, CSV conversion, Base64 encoding, UUID generation, hash generation, SQL formatting, and more. No signup required.')">
    <meta name="keywords" content="@yield('meta_keywords', 'developer tools, json formatter, csv converter, base64 encoder, uuid generator, hash generator, sql formatter, online tools')">
    <meta name="author" content="Ghabri Djalel">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Dev Tools - Free Online Developer Utilities')">
    <meta property="og:description" content="@yield('meta_description', 'Free online developer tools for JSON formatting, CSV conversion, Base64 encoding, UUID generation, and more.')">
    <meta property="og:site_name" content="Dev Tools">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', 'Dev Tools - Free Online Developer Utilities')">
    <meta name="twitter:description" content="@yield('meta_description', 'Free online developer tools for JSON formatting, CSV conversion, Base64 encoding, UUID generation, and more.')">

    <!-- JSON-LD Structured Data -->
    @stack('schema')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-gray-50 dark:bg-dark-bg transition-colors duration-200">
    <nav class="bg-white dark:bg-dark-card border-b border-gray-200 dark:border-dark-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                        </svg>
                        <span class="font-semibold text-gray-900 dark:text-white">Dev Tools</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        @click="darkMode = !darkMode"
                        class="p-2 rounded-lg bg-gray-100 dark:bg-dark-border hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <footer class="border-t border-gray-200 dark:border-dark-border mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-600 dark:text-gray-400">
                <div class="flex items-center space-x-1">
                    <span>&copy; {{ date('Y') }}</span>
                    <a href="https://github.com/GhDj" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline">Ghabri Djalel</a>
                </div>
                <span class="text-gray-500 dark:text-gray-500">v1.1.0</span>
            </div>
        </div>
    </footer>

    <script>
        const DevTools = {
            csrfToken: document.querySelector('meta[name="csrf-token"]').content,

            async post(url, data) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify(data),
                });
                return response.json();
            },

            async postForm(url, formData) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: formData,
                });
                return response.json();
            },

            async copyToClipboard(text, button) {
                try {
                    await navigator.clipboard.writeText(text);
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>';
                    setTimeout(() => button.innerHTML = originalText, 1500);
                } catch (err) {
                    console.error('Copy failed:', err);
                }
            },

            downloadFile(content, filename, mimeType = 'text/plain') {
                const blob = new Blob([content], { type: mimeType });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        };
    </script>
    @stack('scripts')
</body>
</html>
