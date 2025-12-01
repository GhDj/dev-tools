@extends('layouts.app')

@section('title', 'Privacy Policy - Dev Tools')

@section('meta_description', 'Privacy Policy for Dev Tools. Learn how we handle your data - spoiler: we don\'t collect or store any personal information.')

@section('meta_keywords', 'privacy policy, dev tools privacy, data protection, no data collection')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Privacy Policy",
    "description": "Privacy Policy for Dev Tools - Free online developer utilities",
    "url": "{{ route('privacy') }}",
    "isPartOf": {
        "@@type": "WebSite",
        "name": "Dev Tools",
        "url": "{{ url('/') }}"
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
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Privacy Policy</h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">Last updated: {{ date('F j, Y') }}</p>
    </div>

    <div class="bg-white dark:bg-dark-card rounded-xl shadow-sm border border-gray-200 dark:border-dark-border p-6 sm:p-8 space-y-8">

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Overview</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Dev Tools is committed to protecting your privacy. This policy explains how we handle information when you use our free online developer utilities at <strong>dev-tools.online</strong>.
            </p>
            <p class="text-gray-700 dark:text-gray-300 mt-3">
                <strong>The short version:</strong> We don't collect, store, or share your personal data. All processing happens in your browser or temporarily on our servers without any data retention.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Data We Don't Collect</h2>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2">
                <li>We do <strong>not</strong> require user accounts or registration</li>
                <li>We do <strong>not</strong> store any data you input into our tools</li>
                <li>We do <strong>not</strong> use tracking cookies</li>
                <li>We do <strong>not</strong> sell or share any information with third parties</li>
                <li>We do <strong>not</strong> store your IP address</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">How Our Tools Work</h2>
            <p class="text-gray-700 dark:text-gray-300">
                When you use our tools (JSON formatter, CSV converter, Base64 encoder, etc.):
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 mt-3">
                <li>Your input is processed temporarily to generate the output</li>
                <li>Data is transmitted securely over HTTPS</li>
                <li>No data is logged or stored on our servers</li>
                <li>Processing results are returned immediately and then discarded</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Local Storage</h2>
            <p class="text-gray-700 dark:text-gray-300">
                We use your browser's local storage only to remember your theme preference (dark/light mode). This data never leaves your device and can be cleared by clearing your browser data.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Analytics</h2>
            <p class="text-gray-700 dark:text-gray-300">
                We may use privacy-respecting analytics services (such as Google Analytics or Cloudflare Analytics) to understand general usage patterns. These services may collect:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 mt-3">
                <li>Pages visited</li>
                <li>General geographic region (country level)</li>
                <li>Device type and browser</li>
                <li>Referral source</li>
            </ul>
            <p class="text-gray-700 dark:text-gray-300 mt-3">
                This data is aggregated and anonymized. It helps us understand which tools are most useful and how to improve the service.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Third-Party Services</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Our website may use the following third-party services:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 mt-3">
                <li><strong>Cloudflare</strong> - For CDN and security (see <a href="https://www.cloudflare.com/privacypolicy/" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline">Cloudflare's Privacy Policy</a>)</li>
                <li><strong>Google Analytics</strong> - For usage analytics (see <a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline">Google's Privacy Policy</a>)</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Security</h2>
            <p class="text-gray-700 dark:text-gray-300">
                We take security seriously:
            </p>
            <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 mt-3">
                <li>All connections are encrypted using HTTPS/TLS</li>
                <li>We use Cloudflare for DDoS protection and security</li>
                <li>Our application follows security best practices</li>
            </ul>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Children's Privacy</h2>
            <p class="text-gray-700 dark:text-gray-300">
                Our service is intended for developers and professionals. We do not knowingly collect any information from children under 13 years of age.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Changes to This Policy</h2>
            <p class="text-gray-700 dark:text-gray-300">
                We may update this privacy policy from time to time. Any changes will be posted on this page with an updated revision date.
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Contact</h2>
            <p class="text-gray-700 dark:text-gray-300">
                If you have any questions about this privacy policy, you can reach us through our GitHub repository:
                <a href="https://github.com/GhDj/dev-tools" target="_blank" rel="noopener noreferrer" class="text-indigo-600 dark:text-indigo-400 hover:underline">github.com/GhDj/dev-tools</a>
            </p>
        </section>

    </div>
</div>
@endsection
