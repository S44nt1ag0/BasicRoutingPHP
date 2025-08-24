<?php

include __DIR__ . '/../vendor/autoload.php';

use SimpleApi\Application;
use SimpleApi\Database\SchemaSync;

try {
    SchemaSync::sync();
} catch (Exception $e) {
    error_log('Schema sync failed: ' . $e->getMessage());
    http_response_code(500);
    exit('Database schema synchronization error.');
}

$app = new Application();

$app->start();
