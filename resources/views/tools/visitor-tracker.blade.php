@extends('layouts.app')

@section('title', 'Laravel Visitor Tracker - Server-Side Analytics Demo | Dev Tools')
@section('meta_description', 'Laravel Visitor Tracker - A server-side analytics package for Laravel with bot detection, device parsing, and GDPR compliance. Unblockable by ad blockers.')
@section('meta_keywords', 'laravel visitor tracker, laravel analytics, server-side tracking, bot detection, user agent parser, gdpr analytics, laravel package')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Laravel Visitor Tracker",
    "description": "Server-side visitor analytics for Laravel applications",
    "url": "{{ route('tools.visitor-tracker') }}",
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
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Laravel Visitor Tracker</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Server-side analytics for Laravel - Unblockable by ad blockers</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <!-- Package Links -->
    <div class="flex flex-wrap gap-3">
        <a href="https://github.com/GhDj/laravel-visitor-tracker" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
            GitHub
        </a>
        <a href="https://packagist.org/packages/ghdj/laravel-visitor-tracker" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0L1.5 6v12L12 24l10.5-6V6L12 0zm0 2.25L19.5 6.75v10.5L12 21.75l-7.5-4.5V6.75L12 2.25z"/></svg>
            Packagist
        </a>
    </div>

    <!-- Live Stats from this site -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Live Statistics from dev-tools.online</h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Real data collected by this package running on this site:</p>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 text-center">
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($summary['total_visitors']) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Visitors</p>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 text-center">
                <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($summary['total_page_views']) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Page Views</p>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-4 text-center">
                <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($summary['online_visitors']) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Online Now</p>
            </div>
            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 text-center">
                <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($summary['today_visitors']) }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">Today</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Your Browser Detection -->
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Browser Detected</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">This is what the package detects about your current visit:</p>

            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-border">
                    <span class="text-gray-600 dark:text-gray-400">Browser</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $parsedUA['browser'] ?? 'Unknown' }} {{ $parsedUA['browser_version'] ?? '' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-border">
                    <span class="text-gray-600 dark:text-gray-400">Platform</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $parsedUA['platform'] ?? 'Unknown' }} {{ $parsedUA['platform_version'] ?? '' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-border">
                    <span class="text-gray-600 dark:text-gray-400">Device Type</span>
                    <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $parsedUA['device_type'] ?? 'Unknown' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-border">
                    <span class="text-gray-600 dark:text-gray-400">Is Bot?</span>
                    <span class="font-medium {{ $isBot ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                        {{ $isBot ? 'Yes' . ($botName ? " ({$botName})" : '') : 'No (Human)' }}
                    </span>
                </div>
                @if($botCategory)
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-border">
                    <span class="text-gray-600 dark:text-gray-400">Bot Category</span>
                    <span class="font-medium text-gray-900 dark:text-white capitalize">{{ str_replace('_', ' ', $botCategory) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2">
                    <span class="text-gray-600 dark:text-gray-400">IP Address</span>
                    <span class="font-medium text-gray-900 dark:text-white font-mono text-sm">{{ $visitorIp }}</span>
                </div>
            </div>

            <div class="mt-4 p-3 bg-gray-50 dark:bg-dark-bg rounded-lg">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">User Agent:</p>
                <p class="text-xs text-gray-700 dark:text-gray-300 font-mono break-all">{{ $userAgent }}</p>
            </div>
        </div>

        <!-- Browser & Device Stats -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Browser Stats</h2>
                <div class="space-y-3">
                    @php $totalBrowsers = $browsers->sum('count'); @endphp
                    @forelse($browsers as $browser)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300">{{ $browser->browser ?? 'Unknown' }}</span>
                            <span class="text-gray-500">{{ $totalBrowsers > 0 ? round($browser->count / $totalBrowsers * 100, 1) : 0 }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalBrowsers > 0 ? ($browser->count / $totalBrowsers * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No data yet</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Device Types</h2>
                <div class="space-y-3">
                    @php
                        $totalDevices = $devices->sum('count');
                        $deviceColors = ['desktop' => 'bg-purple-600', 'mobile' => 'bg-green-600', 'tablet' => 'bg-yellow-500'];
                    @endphp
                    @forelse($devices as $device)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-gray-700 dark:text-gray-300 capitalize">{{ $device->device_type ?? 'Unknown' }}</span>
                            <span class="text-gray-500">{{ number_format($device->count) }} ({{ $totalDevices > 0 ? round($device->count / $totalDevices * 100, 1) : 0 }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="{{ $deviceColors[$device->device_type] ?? 'bg-gray-500' }} h-2 rounded-full" style="width: {{ $totalDevices > 0 ? ($device->count / $totalDevices * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-sm text-gray-500">No data yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Top Pages -->
    @if($topPages->isNotEmpty())
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Pages</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-dark-border">
                        <th class="pb-2">Path</th>
                        <th class="pb-2 text-right">Views</th>
                        <th class="pb-2 text-right">Unique</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topPages as $page)
                    <tr class="border-b border-gray-100 dark:border-dark-border">
                        <td class="py-2 text-gray-900 dark:text-white font-mono text-xs">/{{ $page->path }}</td>
                        <td class="py-2 text-right text-gray-600 dark:text-gray-400">{{ number_format($page->visits) }}</td>
                        <td class="py-2 text-right text-gray-600 dark:text-gray-400">{{ number_format($page->unique_visitors) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Features -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Package Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Unblockable</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Server-side tracking can't be blocked by ad blockers</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Native Detection</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">100+ bot patterns, browser/device parsing - no dependencies</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">GDPR Compliant</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">GDPR Safe Mode, IP anonymization, DNT support</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Zero Dependencies</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Only uses Laravel's built-in features</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Geolocation</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">IP-based location via free APIs (optional)</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900 dark:text-white">Built-in Dashboard</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Tailwind CSS dashboard with token auth</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Installation -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Installation</h2>
        <div class="space-y-4">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">1. Install via Composer:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>composer require ghdj/laravel-visitor-tracker</code></pre>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">2. Publish config and run migrations:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>php artisan vendor:publish --tag="visitor-tracker-config"
php artisan migrate</code></pre>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">3. Add middleware to your routes:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>// In bootstrap/app.php (Laravel 11+)
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \Ghdj\VisitorTracker\Middleware\TrackVisitor::class,
    ]);
})</code></pre>
            </div>
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">4. Use it:</p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>use Ghdj\VisitorTracker\Facades\VisitorTracker;

// Get statistics
$stats = VisitorTracker::stats()->summary();
$online = VisitorTracker::stats()->onlineVisitors();
$topPages = VisitorTracker::stats()->mostVisitedPages(10);</code></pre>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-sm text-gray-500 dark:text-gray-400">
        <p>Laravel Visitor Tracker v1.0.0 - MIT License</p>
        <p class="mt-1">
            <a href="https://github.com/GhDj/laravel-visitor-tracker" class="text-indigo-600 dark:text-indigo-400 hover:underline">Documentation</a>
            &middot;
            <a href="https://packagist.org/packages/ghdj/laravel-visitor-tracker" class="text-indigo-600 dark:text-indigo-400 hover:underline">Packagist</a>
        </p>
    </div>
</div>
@endsection
