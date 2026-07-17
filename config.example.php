<?php
/**
 * Template for database credentials.
 *
 *   1. Copy this file to config.local.php
 *   2. Fill in the real values there
 *
 * config.local.php is gitignored. This file is committed — keep it fake.
 * PHP has no built-in .env support, so a returned array is the simplest
 * way to keep secrets out of the repo without adding Composer.
 */

return [
    // 'mysql' = local XAMPP (what the app used originally)
    // 'pgsql' = Supabase
    'driver' => 'pgsql',

    'mysql' => [
        'host'     => 'localhost',
        'dbname'   => 'project_db',
        'username' => 'root',
        'password' => '',
    ],

    'pgsql' => [
        // Supabase Dashboard -> Connect -> Session pooler.
        //
        // Use the SESSION POOLER, not the direct connection:
        //   - Direct (db.<ref>.supabase.co) is IPv6-only on the free tier;
        //     most home networks and XAMPP cannot reach it.
        //   - The pooler gives you IPv4 and still supports prepared
        //     statements, which every query in this app relies on.
        //
        // Host looks like: aws-0-<region>.pooler.supabase.com
        'host'     => 'aws-0-REGION.pooler.supabase.com',
        'port'     => 5432,
        'dbname'   => 'postgres',

        // For the pooler the username is postgres.<project-ref>
        'username' => 'postgres.smimfrevydwjiagxckvk',

        // Dashboard -> Settings -> Database -> Database password.
        // Reset it there if you never saved it.
        'password' => 'YOUR_SUPABASE_DB_PASSWORD',
    ],
];
