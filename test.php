<?php

require __DIR__ . '/vendor/autoload.php';

use Modules\FacebookPostingModule\Services\FacebookApiService;

$service = new FacebookApiService();
echo 'Class loaded successfully!';
