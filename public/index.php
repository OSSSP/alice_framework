<?php

// Load required objects
require '../vendor/alice/framework/src/Core/Application.php';
require '../vendor/alice/framework/src/Core/BaseController.php';
require '../vendor/alice/framework/src/Core/BaseModel.php';
require '../vendor/alice/framework/src/Core/BaseView.php';


//echo "I'm public/index.php<br />";

// Instantiate a new Application
$app = new Application();

// Run the application
$app->bindPaths(require '../bootstrap/paths.php');
$app->run();
