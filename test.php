<?php

// Include Composer's autoloader
require 'vendor/autoload.php';

// Make sure the module's namespace is registered and autoloaded
use Modules\FacebookPostingModule\Http\Controllers\FacebookController;

try {
    // Try to instantiate the controller
    $controller = new FacebookController();
    echo "FacebookController loaded successfully!";
} catch (Exception $e) {
    // Handle any errors that may occur
    echo "Error: " . $e->getMessage();
}

?>
