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

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-99SGEVL1J9"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-99SGEVL1J9');
    </script>
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
                    <!-- Day/Night Toggle -->
                    <button
                        @click="darkMode = !darkMode"
                        class="theme-toggle group relative w-16 h-8 rounded-full transition-all duration-500 overflow-hidden"
                        :class="darkMode ? 'bg-indigo-950' : 'bg-sky-400'"
                        :title="darkMode ? 'Switch to light mode' : 'Switch to dark mode'"
                    >
                        <!-- Stars (visible in dark mode) -->
                        <div class="absolute inset-0 transition-opacity duration-500" :class="darkMode ? 'opacity-100' : 'opacity-0'">
                            <span class="absolute w-1 h-1 bg-white rounded-full top-2 left-3 animate-pulse"></span>
                            <span class="absolute w-0.5 h-0.5 bg-white rounded-full top-4 left-6 animate-pulse delay-100"></span>
                            <span class="absolute w-1 h-1 bg-white rounded-full top-1.5 left-9 animate-pulse delay-200"></span>
                            <span class="absolute w-0.5 h-0.5 bg-white rounded-full top-5 left-4 animate-pulse delay-300"></span>
                        </div>
                        <!-- Clouds (visible in light mode) -->
                        <div class="absolute inset-0 transition-opacity duration-500" :class="darkMode ? 'opacity-0' : 'opacity-100'">
                            <span class="absolute w-4 h-2 bg-white/60 rounded-full top-1 left-7 blur-[1px]"></span>
                            <span class="absolute w-3 h-1.5 bg-white/40 rounded-full top-5 left-2 blur-[1px]"></span>
                        </div>
                        <!-- Sun/Moon Orb -->
                        <div
                            class="absolute top-1 w-6 h-6 rounded-full shadow-lg transition-all duration-500 flex items-center justify-center"
                            :class="darkMode
                                ? 'left-9 bg-gray-200 shadow-gray-400/30'
                                : 'left-1 bg-yellow-300 shadow-yellow-500/50'"
                        >
                            <!-- Sun rays (light mode) -->
                            <div class="absolute inset-0 transition-opacity duration-300" :class="darkMode ? 'opacity-0' : 'opacity-100'">
                                <span class="absolute w-full h-0.5 bg-yellow-400/60 top-1/2 -translate-y-1/2 -left-1 scale-x-125"></span>
                                <span class="absolute h-full w-0.5 bg-yellow-400/60 left-1/2 -translate-x-1/2 -top-1 scale-y-125"></span>
                            </div>
                            <!-- Moon craters (dark mode) -->
                            <div class="absolute inset-0 transition-opacity duration-300" :class="darkMode ? 'opacity-100' : 'opacity-0'">
                                <span class="absolute w-1.5 h-1.5 bg-gray-300 rounded-full top-1 left-1"></span>
                                <span class="absolute w-1 h-1 bg-gray-300 rounded-full bottom-1.5 right-1.5"></span>
                                <span class="absolute w-0.5 h-0.5 bg-gray-300 rounded-full top-3 right-2"></span>
                            </div>
                        </div>
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
                <div class="flex items-center space-x-4">
                    <a href="{{ route('about') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">About</a>
                    <a href="{{ route('privacy') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Privacy</a>
                    <a href="https://github.com/GhDj/dev-tools" target="_blank" rel="noopener noreferrer" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">GitHub</a>
                    <span class="text-gray-500 dark:text-gray-500">v1.2.1</span>
                </div>
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
