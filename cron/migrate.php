<?php

/**
 * OVH cron entrypoint: runs `php artisan migrate --force` from a panel-scheduled
 * task. The OVH cron form expects a path-to-script + PHP version, not a raw
 * shell command, so this wrapper bootstraps Laravel and dispatches the
 * migrate command directly.
 *
 * Configured in: OVH manager → Web Cloud → Hosting → Tasks
 *   - Command: www/cron/migrate.php   (or wherever the app is rooted)
 *   - Language: PHP <version matching composer.json>
 *   - Frequency: hourly is plenty (migrations are infrequent and idempotent)
 *
 * Output (Artisan::output()) is echoed and OVH captures it in the task's
 * execution log inside the OVH manager. Migrations are idempotent — running
 * this on an empty migration queue is a no-op.
 */

$root = dirname(__DIR__);
chdir($root);

require $root.'/vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require $root.'/bootstrap/app.php';

/** @var \Illuminate\Contracts\Console\Kernel $kernel */
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('migrate', ['--force' => true]);

echo $kernel->output();

exit($status);
