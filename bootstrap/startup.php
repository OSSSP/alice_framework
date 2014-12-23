<?php

use Alice\Core\Application;


// Require composer autoload
require_once __DIR__ . '/../vendor/autoload.php';

// Create a new Application and setup necessary paths
$app = new Application;
$app->bindPaths(require __DIR__ . '/../bootstrap/paths.php');
