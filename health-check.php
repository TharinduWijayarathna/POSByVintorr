<?php

/**
 * Simple health check script for Laravel application
 * This can be used by load balancers or monitoring systems
 */

// Check if Laravel is properly configured
try {
    // Basic Laravel checks
    if (! file_exists(__DIR__.'/vendor/autoload.php')) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Composer dependencies not installed']);
        exit;
    }

    require_once __DIR__.'/vendor/autoload.php';

    // Check if .env file exists
    if (! file_exists(__DIR__.'/.env')) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Environment file not found']);
        exit;
    }

    // Check if storage directory is writable
    if (! is_writable(__DIR__.'/storage')) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Storage directory not writable']);
        exit;
    }

    // Check if bootstrap/cache directory is writable
    if (! is_writable(__DIR__.'/bootstrap/cache')) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Bootstrap cache directory not writable']);
        exit;
    }

    // Basic database connectivity check (optional)
    if (isset($_GET['db_check']) && $_GET['db_check'] === 'true') {
        try {
            $app = require_once __DIR__.'/bootstrap/app.php';
            $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

            // Test database connection
            \DB::connection()->getPdo();
            echo json_encode(['status' => 'success', 'message' => 'Database connection successful']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Database connection failed: '.$e->getMessage()]);
        }
    } else {
        // Basic health check without database
        echo json_encode([
            'status' => 'success',
            'message' => 'Application is healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Health check failed: '.$e->getMessage()]);
}
