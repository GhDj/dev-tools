@extends('layouts.app')

@section('title', 'Cron Expression Parser - Explain & Validate Cron Jobs | Dev Tools')
@section('meta_description', 'Free online cron expression parser. Understand cron syntax, validate expressions, see next run times, and get human-readable explanations of your cron schedules.')
@section('meta_keywords', 'cron parser, cron expression, cron validator, cron schedule, crontab, cron syntax, cron job, cron generator, cron explainer, cron next run')

@push('schema')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "SoftwareApplication",
    "name": "Cron Expression Parser",
    "description": "Parse and explain cron expressions with next run times",
    "url": "{{ route('tools.cron') }}",
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
<div x-data="cronParser()" class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cron Expression Parser</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Parse and explain cron expressions with next run times</p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">&larr; Back</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cron Expression</label>
                <div class="flex gap-2">
                    <input
                        type="text"
                        x-model="expression"
                        @input="parse()"
                        class="flex-1 p-3 border border-gray-300 dark:border-dark-border rounded-lg bg-white dark:bg-dark-bg text-gray-900 dark:text-gray-100 font-mono text-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="* * * * *"
                    >
                    <button
                        @click="clear()"
                        class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                        title="Clear"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mt-4 grid grid-cols-5 gap-2 text-center text-xs">
                    <template x-for="(field, index) in fields" :key="index">
                        <div class="space-y-1">
                            <div
                                class="p-2 rounded font-mono text-sm"
                                :class="parsed.valid ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-gray-100 dark:bg-gray-800 text-gray-500'"
                                x-text="parsed.parts[index] || field.placeholder"
                            ></div>
                            <div class="text-gray-500 dark:text-gray-400" x-text="field.name"></div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="parsed.valid" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Human Readable</h2>
                <p class="text-lg text-indigo-600 dark:text-indigo-400 font-medium" x-text="parsed.description"></p>
            </div>

            <div x-show="parsed.error" class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-4">
                <div class="flex items-center gap-2 text-red-700 dark:text-red-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span x-text="parsed.error"></span>
                </div>
            </div>

            <div x-show="parsed.valid" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Field Breakdown</h2>
                <div class="space-y-3">
                    <template x-for="(field, index) in fields" :key="'breakdown-' + index">
                        <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-dark-bg rounded-lg">
                            <div class="flex-shrink-0 w-24 text-sm font-medium text-gray-700 dark:text-gray-300" x-text="field.name"></div>
                            <div class="flex-shrink-0 w-16 font-mono text-sm text-indigo-600 dark:text-indigo-400" x-text="parsed.parts[index]"></div>
                            <div class="flex-1 text-sm text-gray-600 dark:text-gray-400" x-text="parsed.explanations[index]"></div>
                        </div>
                    </template>
                </div>
            </div>

            <div x-show="parsed.valid" class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Next Run Times</h2>
                <div class="space-y-2">
                    <template x-for="(time, index) in nextRuns" :key="'run-' + index">
                        <div class="flex items-center justify-between p-2 rounded" :class="index === 0 ? 'bg-green-50 dark:bg-green-900/20' : 'bg-gray-50 dark:bg-dark-bg'">
                            <span class="text-sm text-gray-600 dark:text-gray-400" x-text="'Run #' + (index + 1)"></span>
                            <span class="font-mono text-sm" :class="index === 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-700 dark:text-gray-300'" x-text="time"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Common Patterns</h2>
                <div class="space-y-2">
                    <template x-for="pattern in commonPatterns" :key="pattern.expression">
                        <button
                            @click="expression = pattern.expression; parse()"
                            class="w-full text-left p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-bg transition-colors"
                        >
                            <div class="font-mono text-sm text-indigo-600 dark:text-indigo-400" x-text="pattern.expression"></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400" x-text="pattern.description"></div>
                        </button>
                    </template>
                </div>
            </div>

            <div class="bg-white dark:bg-dark-card rounded-lg border border-gray-200 dark:border-dark-border p-4">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Syntax Reference</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-1">Field Values</h3>
                        <table class="w-full text-xs">
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border">
                                <tr>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Minute</td>
                                    <td class="py-1 font-mono text-gray-700 dark:text-gray-300">0-59</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Hour</td>
                                    <td class="py-1 font-mono text-gray-700 dark:text-gray-300">0-23</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Day of Month</td>
                                    <td class="py-1 font-mono text-gray-700 dark:text-gray-300">1-31</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Month</td>
                                    <td class="py-1 font-mono text-gray-700 dark:text-gray-300">1-12</td>
                                </tr>
                                <tr>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Day of Week</td>
                                    <td class="py-1 font-mono text-gray-700 dark:text-gray-300">0-6 (Sun-Sat)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-1">Special Characters</h3>
                        <table class="w-full text-xs">
                            <tbody class="divide-y divide-gray-200 dark:divide-dark-border">
                                <tr>
                                    <td class="py-1 font-mono text-indigo-600 dark:text-indigo-400">*</td>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Any value</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-mono text-indigo-600 dark:text-indigo-400">,</td>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Value list (1,3,5)</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-mono text-indigo-600 dark:text-indigo-400">-</td>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Range (1-5)</td>
                                </tr>
                                <tr>
                                    <td class="py-1 font-mono text-indigo-600 dark:text-indigo-400">/</td>
                                    <td class="py-1 text-gray-500 dark:text-gray-400">Step (*/15)</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cronParser() {
    return {
        expression: '0 9 * * 1-5',
        fields: [
            { name: 'Minute', placeholder: '*', min: 0, max: 59 },
            { name: 'Hour', placeholder: '*', min: 0, max: 23 },
            { name: 'Day (Month)', placeholder: '*', min: 1, max: 31 },
            { name: 'Month', placeholder: '*', min: 1, max: 12 },
            { name: 'Day (Week)', placeholder: '*', min: 0, max: 6 }
        ],
        parsed: {
            valid: false,
            parts: [],
            description: '',
            explanations: [],
            error: ''
        },
        nextRuns: [],
        commonPatterns: [
            { expression: '* * * * *', description: 'Every minute' },
            { expression: '*/5 * * * *', description: 'Every 5 minutes' },
            { expression: '0 * * * *', description: 'Every hour' },
            { expression: '0 0 * * *', description: 'Every day at midnight' },
            { expression: '0 9 * * 1-5', description: 'Weekdays at 9 AM' },
            { expression: '0 0 * * 0', description: 'Every Sunday at midnight' },
            { expression: '0 0 1 * *', description: 'First day of every month' },
            { expression: '0 0 1 1 *', description: 'Every January 1st' },
            { expression: '*/15 * * * *', description: 'Every 15 minutes' },
            { expression: '0 */2 * * *', description: 'Every 2 hours' },
            { expression: '0 9,17 * * *', description: 'At 9 AM and 5 PM' },
            { expression: '30 4 1,15 * *', description: '4:30 AM on 1st and 15th' }
        ],

        init() {
            this.parse();
        },

        clear() {
            this.expression = '';
            this.parsed = { valid: false, parts: [], description: '', explanations: [], error: '' };
            this.nextRuns = [];
        },

        parse() {
            const expr = this.expression.trim();
            if (!expr) {
                this.parsed = { valid: false, parts: [], description: '', explanations: [], error: '' };
                this.nextRuns = [];
                return;
            }

            const parts = expr.split(/\s+/);
            if (parts.length !== 5) {
                this.parsed = {
                    valid: false,
                    parts: parts,
                    description: '',
                    explanations: [],
                    error: `Expected 5 fields, got ${parts.length}. Format: minute hour day month weekday`
                };
                this.nextRuns = [];
                return;
            }

            const validations = parts.map((part, index) => this.validateField(part, this.fields[index]));
            const errors = validations.filter(v => v !== true);

            if (errors.length > 0) {
                this.parsed = {
                    valid: false,
                    parts: parts,
                    description: '',
                    explanations: [],
                    error: errors[0]
                };
                this.nextRuns = [];
                return;
            }

            const explanations = parts.map((part, index) => this.explainField(part, this.fields[index]));
            const description = this.generateDescription(parts);

            this.parsed = {
                valid: true,
                parts: parts,
                description: description,
                explanations: explanations,
                error: ''
            };

            this.calculateNextRuns(parts);
        },

        validateField(value, field) {
            if (value === '*') return true;

            // Check for step values (*/n or n/m)
            if (value.includes('/')) {
                const [base, step] = value.split('/');
                if (base !== '*' && !this.isValidValue(base, field)) {
                    return `Invalid base value "${base}" for ${field.name}`;
                }
                if (!/^\d+$/.test(step) || parseInt(step) < 1) {
                    return `Invalid step value "${step}" for ${field.name}`;
                }
                return true;
            }

            // Check for ranges (n-m)
            if (value.includes('-') && !value.includes(',')) {
                const [start, end] = value.split('-');
                if (!this.isValidValue(start, field) || !this.isValidValue(end, field)) {
                    return `Invalid range "${value}" for ${field.name}`;
                }
                if (parseInt(start) > parseInt(end)) {
                    return `Invalid range "${value}" for ${field.name}: start > end`;
                }
                return true;
            }

            // Check for lists (n,m,o)
            if (value.includes(',')) {
                const values = value.split(',');
                for (const v of values) {
                    if (v.includes('-')) {
                        const [start, end] = v.split('-');
                        if (!this.isValidValue(start, field) || !this.isValidValue(end, field)) {
                            return `Invalid range "${v}" in list for ${field.name}`;
                        }
                    } else if (!this.isValidValue(v, field)) {
                        return `Invalid value "${v}" in list for ${field.name}`;
                    }
                }
                return true;
            }

            // Single value
            if (!this.isValidValue(value, field)) {
                return `Value "${value}" out of range for ${field.name} (${field.min}-${field.max})`;
            }

            return true;
        },

        isValidValue(value, field) {
            if (!/^\d+$/.test(value)) return false;
            const num = parseInt(value);
            return num >= field.min && num <= field.max;
        },

        explainField(value, field) {
            if (value === '*') return `Every ${field.name.toLowerCase()}`;

            if (value.includes('/')) {
                const [base, step] = value.split('/');
                if (base === '*') {
                    return `Every ${step} ${field.name.toLowerCase()}${parseInt(step) > 1 ? 's' : ''}`;
                }
                return `Every ${step} ${field.name.toLowerCase()}${parseInt(step) > 1 ? 's' : ''} starting at ${base}`;
            }

            if (value.includes('-') && !value.includes(',')) {
                const [start, end] = value.split('-');
                if (field.name === 'Day (Week)') {
                    return `${this.getDayName(start)} through ${this.getDayName(end)}`;
                }
                if (field.name === 'Month') {
                    return `${this.getMonthName(start)} through ${this.getMonthName(end)}`;
                }
                return `From ${start} through ${end}`;
            }

            if (value.includes(',')) {
                const values = value.split(',');
                if (field.name === 'Day (Week)') {
                    return values.map(v => this.getDayName(v)).join(', ');
                }
                if (field.name === 'Month') {
                    return values.map(v => this.getMonthName(v)).join(', ');
                }
                return `At ${values.join(', ')}`;
            }

            if (field.name === 'Day (Week)') {
                return this.getDayName(value);
            }
            if (field.name === 'Month') {
                return this.getMonthName(value);
            }
            return `At ${value}`;
        },

        getDayName(num) {
            const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            return days[parseInt(num)] || num;
        },

        getMonthName(num) {
            const months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            return months[parseInt(num)] || num;
        },

        generateDescription(parts) {
            const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;

            let desc = '';

            // Time part
            if (minute === '*' && hour === '*') {
                desc = 'Every minute';
            } else if (minute.includes('/')) {
                const step = minute.split('/')[1];
                desc = `Every ${step} minutes`;
            } else if (hour === '*') {
                desc = `At minute ${minute} of every hour`;
            } else if (minute === '0' && !hour.includes('/') && !hour.includes(',') && !hour.includes('-')) {
                const hourNum = parseInt(hour);
                const period = hourNum >= 12 ? 'PM' : 'AM';
                const displayHour = hourNum === 0 ? 12 : (hourNum > 12 ? hourNum - 12 : hourNum);
                desc = `At ${displayHour}:00 ${period}`;
            } else if (hour.includes(',')) {
                const hours = hour.split(',').map(h => {
                    const hourNum = parseInt(h);
                    const period = hourNum >= 12 ? 'PM' : 'AM';
                    const displayHour = hourNum === 0 ? 12 : (hourNum > 12 ? hourNum - 12 : hourNum);
                    return `${displayHour}:${minute.padStart(2, '0')} ${period}`;
                });
                desc = `At ${hours.join(' and ')}`;
            } else if (hour.includes('/')) {
                const step = hour.split('/')[1];
                desc = `At minute ${minute} every ${step} hours`;
            } else {
                const hourNum = parseInt(hour);
                const period = hourNum >= 12 ? 'PM' : 'AM';
                const displayHour = hourNum === 0 ? 12 : (hourNum > 12 ? hourNum - 12 : hourNum);
                desc = `At ${displayHour}:${minute.padStart(2, '0')} ${period}`;
            }

            // Day part
            if (dayOfMonth !== '*' && month !== '*') {
                if (dayOfMonth.includes(',')) {
                    desc += ` on day ${dayOfMonth} of ${this.getMonthName(month)}`;
                } else {
                    desc += ` on ${this.getMonthName(month)} ${dayOfMonth}`;
                }
            } else if (dayOfMonth !== '*') {
                if (dayOfMonth.includes(',')) {
                    desc += ` on day ${dayOfMonth} of the month`;
                } else {
                    desc += ` on day ${dayOfMonth} of the month`;
                }
            } else if (month !== '*') {
                desc += ` in ${this.getMonthName(month)}`;
            }

            // Day of week
            if (dayOfWeek !== '*') {
                if (dayOfWeek.includes('-')) {
                    const [start, end] = dayOfWeek.split('-');
                    desc += `, ${this.getDayName(start)} through ${this.getDayName(end)}`;
                } else if (dayOfWeek.includes(',')) {
                    const days = dayOfWeek.split(',').map(d => this.getDayName(d));
                    desc += `, on ${days.join(', ')}`;
                } else {
                    desc += `, on ${this.getDayName(dayOfWeek)}`;
                }
            }

            return desc;
        },

        calculateNextRuns(parts) {
            const runs = [];
            const now = new Date();
            let current = new Date(now);

            // Limit iterations to prevent infinite loops
            let iterations = 0;
            const maxIterations = 10000;

            while (runs.length < 5 && iterations < maxIterations) {
                iterations++;
                current = new Date(current.getTime() + 60000); // Add 1 minute
                current.setSeconds(0);
                current.setMilliseconds(0);

                if (this.matchesCron(current, parts)) {
                    runs.push(this.formatDate(current));
                }
            }

            this.nextRuns = runs;
        },

        matchesCron(date, parts) {
            const [minute, hour, dayOfMonth, month, dayOfWeek] = parts;

            if (!this.matchesField(date.getMinutes(), minute, 0, 59)) return false;
            if (!this.matchesField(date.getHours(), hour, 0, 23)) return false;
            if (!this.matchesField(date.getDate(), dayOfMonth, 1, 31)) return false;
            if (!this.matchesField(date.getMonth() + 1, month, 1, 12)) return false;
            if (!this.matchesField(date.getDay(), dayOfWeek, 0, 6)) return false;

            return true;
        },

        matchesField(value, pattern, min, max) {
            if (pattern === '*') return true;

            if (pattern.includes('/')) {
                const [base, step] = pattern.split('/');
                const stepNum = parseInt(step);
                const startVal = base === '*' ? min : parseInt(base);
                return (value - startVal) % stepNum === 0 && value >= startVal;
            }

            if (pattern.includes(',')) {
                const values = pattern.split(',');
                for (const v of values) {
                    if (v.includes('-')) {
                        const [start, end] = v.split('-').map(Number);
                        if (value >= start && value <= end) return true;
                    } else if (parseInt(v) === value) {
                        return true;
                    }
                }
                return false;
            }

            if (pattern.includes('-')) {
                const [start, end] = pattern.split('-').map(Number);
                return value >= start && value <= end;
            }

            return parseInt(pattern) === value;
        },

        formatDate(date) {
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            };
            return date.toLocaleString('en-US', options);
        }
    };
}
</script>
@endpush
