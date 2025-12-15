@extends('layouts.app')

@section('title', 'Unix Timestamp Converter - Convert Timestamps Online | Dev Tools')
@section('meta_description', 'Free online Unix timestamp converter. Convert timestamps to human-readable dates and vice versa. Supports seconds, milliseconds, and multiple timezones.')
@section('meta_keywords', 'unix timestamp, timestamp converter, epoch converter, date to timestamp, timestamp to date, unix time, epoch time')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Unix Timestamp Converter",
    "description": "Convert Unix timestamps to dates and vice versa",
    "url": "{{ route('tools.timestamp') }}",
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
<div x-data="timestampConverter()" x-init="startClock()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Timestamp Converter</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Convert Unix timestamps to dates and vice versa</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <!-- Current Time -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white">
        <div class="text-center">
            <p class="text-sm opacity-80 mb-1">Current Unix Timestamp</p>
            <div class="flex items-center justify-center gap-3">
                <span class="text-4xl font-mono font-bold" x-text="currentTimestamp"></span>
                <button
                    @click="copyTimestamp(currentTimestamp, $event.currentTarget)"
                    class="p-2 rounded-lg bg-white/20 hover:bg-white/30 transition-colors"
                    title="Copy timestamp"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm opacity-80 mt-2" x-text="currentDateTime"></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Timestamp to Date -->
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Timestamp to Date</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Unix Timestamp</label>
                    <input
                        type="text"
                        x-model="timestampInput"
                        @input="convertTimestampToDate()"
                        class="w-full p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Enter timestamp (e.g., 1702569600)"
                    >
                </div>

                <div class="flex gap-2">
                    <button
                        @click="timestampUnit = 'seconds'; convertTimestampToDate()"
                        :class="timestampUnit === 'seconds' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                        class="flex-1 py-2 px-3 rounded-lg font-medium text-sm transition-colors"
                    >
                        Seconds
                    </button>
                    <button
                        @click="timestampUnit = 'milliseconds'; convertTimestampToDate()"
                        :class="timestampUnit === 'milliseconds' ? 'bg-indigo-600 text-white' : 'bg-gray-100 dark:bg-dark-border text-gray-700 dark:text-gray-300'"
                        class="flex-1 py-2 px-3 rounded-lg font-medium text-sm transition-colors"
                    >
                        Milliseconds
                    </button>
                </div>

                <div x-show="timestampError" class="p-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded text-sm" x-text="timestampError"></div>

                <div x-show="convertedDate" class="space-y-3 p-4 bg-gray-50 dark:bg-dark-bg rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Local Time</span>
                        <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedDate.local"></code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">UTC</span>
                        <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedDate.utc"></code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">ISO 8601</span>
                        <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedDate.iso"></code>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Relative</span>
                        <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedDate.relative"></code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date to Timestamp -->
        <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Date to Timestamp</h2>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date</label>
                        <input
                            type="date"
                            x-model="dateInput"
                            @input="convertDateToTimestamp()"
                            class="w-full p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Time</label>
                        <input
                            type="time"
                            x-model="timeInput"
                            @input="convertDateToTimestamp()"
                            step="1"
                            class="w-full p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Timezone</label>
                    <select
                        x-model="timezone"
                        @change="convertDateToTimestamp()"
                        class="w-full p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    >
                        <option value="local">Local Time</option>
                        <option value="UTC">UTC</option>
                    </select>
                </div>

                <button
                    @click="setToNow()"
                    class="w-full py-2 px-4 bg-gray-100 dark:bg-dark-border hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors"
                >
                    Set to Now
                </button>

                <div x-show="convertedTimestamp" class="space-y-3 p-4 bg-gray-50 dark:bg-dark-bg rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Seconds</span>
                        <div class="flex items-center gap-2">
                            <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedTimestamp.seconds"></code>
                            <button
                                @click="copyTimestamp(convertedTimestamp.seconds, $event.currentTarget)"
                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Milliseconds</span>
                        <div class="flex items-center gap-2">
                            <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="convertedTimestamp.milliseconds"></code>
                            <button
                                @click="copyTimestamp(convertedTimestamp.milliseconds, $event.currentTarget)"
                                class="p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Common Timestamps -->
    <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Reference</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <template x-for="ref in quickRefs" :key="ref.label">
                <div class="p-3 bg-gray-50 dark:bg-dark-bg rounded-lg">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1" x-text="ref.label"></p>
                    <div class="flex items-center justify-between">
                        <code class="text-sm font-mono text-gray-900 dark:text-gray-100" x-text="ref.value"></code>
                        <button
                            @click="useTimestamp(ref.value)"
                            class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline"
                        >
                            Use
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function timestampConverter() {
    return {
        currentTimestamp: '',
        currentDateTime: '',
        clockInterval: null,

        timestampInput: '',
        timestampUnit: 'seconds',
        timestampError: '',
        convertedDate: null,

        dateInput: '',
        timeInput: '',
        timezone: 'local',
        convertedTimestamp: null,

        quickRefs: [],

        startClock() {
            this.updateClock();
            this.clockInterval = setInterval(() => this.updateClock(), 1000);
            this.updateQuickRefs();
        },

        updateClock() {
            const now = new Date();
            this.currentTimestamp = Math.floor(now.getTime() / 1000);
            this.currentDateTime = now.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            });
        },

        updateQuickRefs() {
            const now = new Date();
            const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const startOfWeek = new Date(now);
            startOfWeek.setDate(now.getDate() - now.getDay());
            startOfWeek.setHours(0, 0, 0, 0);
            const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
            const startOfYear = new Date(now.getFullYear(), 0, 1);

            this.quickRefs = [
                { label: 'Start of Today', value: Math.floor(startOfDay.getTime() / 1000) },
                { label: 'Start of Week', value: Math.floor(startOfWeek.getTime() / 1000) },
                { label: 'Start of Month', value: Math.floor(startOfMonth.getTime() / 1000) },
                { label: 'Start of Year', value: Math.floor(startOfYear.getTime() / 1000) },
            ];
        },

        convertTimestampToDate() {
            this.timestampError = '';
            this.convertedDate = null;

            if (!this.timestampInput.trim()) return;

            const ts = parseInt(this.timestampInput.trim(), 10);
            if (isNaN(ts)) {
                this.timestampError = 'Invalid timestamp. Please enter a number.';
                return;
            }

            const ms = this.timestampUnit === 'milliseconds' ? ts : ts * 1000;
            const date = new Date(ms);

            if (isNaN(date.getTime())) {
                this.timestampError = 'Invalid timestamp. Date out of range.';
                return;
            }

            this.convertedDate = {
                local: date.toLocaleString('en-US', {
                    weekday: 'short',
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    timeZoneName: 'short'
                }),
                utc: date.toUTCString(),
                iso: date.toISOString(),
                relative: this.getRelativeTime(date)
            };
        },

        convertDateToTimestamp() {
            this.convertedTimestamp = null;

            if (!this.dateInput) return;

            const timeStr = this.timeInput || '00:00:00';
            let date;

            if (this.timezone === 'UTC') {
                date = new Date(this.dateInput + 'T' + timeStr + 'Z');
            } else {
                date = new Date(this.dateInput + 'T' + timeStr);
            }

            if (isNaN(date.getTime())) return;

            this.convertedTimestamp = {
                seconds: Math.floor(date.getTime() / 1000),
                milliseconds: date.getTime()
            };
        },

        setToNow() {
            const now = new Date();
            this.dateInput = now.toISOString().split('T')[0];
            this.timeInput = now.toTimeString().split(' ')[0];
            this.convertDateToTimestamp();
        },

        useTimestamp(ts) {
            this.timestampInput = ts.toString();
            this.timestampUnit = 'seconds';
            this.convertTimestampToDate();
        },

        getRelativeTime(date) {
            const now = new Date();
            const diffMs = date - now;
            const diffSec = Math.round(diffMs / 1000);
            const diffMin = Math.round(diffSec / 60);
            const diffHour = Math.round(diffMin / 60);
            const diffDay = Math.round(diffHour / 24);

            if (Math.abs(diffSec) < 60) {
                return diffSec >= 0 ? `in ${diffSec} seconds` : `${Math.abs(diffSec)} seconds ago`;
            } else if (Math.abs(diffMin) < 60) {
                return diffMin >= 0 ? `in ${diffMin} minutes` : `${Math.abs(diffMin)} minutes ago`;
            } else if (Math.abs(diffHour) < 24) {
                return diffHour >= 0 ? `in ${diffHour} hours` : `${Math.abs(diffHour)} hours ago`;
            } else if (Math.abs(diffDay) < 30) {
                return diffDay >= 0 ? `in ${diffDay} days` : `${Math.abs(diffDay)} days ago`;
            } else {
                const diffMonth = Math.round(diffDay / 30);
                if (Math.abs(diffMonth) < 12) {
                    return diffMonth >= 0 ? `in ${diffMonth} months` : `${Math.abs(diffMonth)} months ago`;
                } else {
                    const diffYear = Math.round(diffMonth / 12);
                    return diffYear >= 0 ? `in ${diffYear} years` : `${Math.abs(diffYear)} years ago`;
                }
            }
        },

        copyTimestamp(value, button) {
            DevTools.copyToClipboard(value.toString(), button);
        }
    };
}
</script>
@endpush
