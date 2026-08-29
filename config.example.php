<?php

$companyDefaults = require __DIR__.'/includes/company.defaults.php';

return [
    'app_name' => 'AAP Engineering',
    'app_url' => 'https://aapengineerings.com',
    'debug' => false,

    'db' => [
        'host' => 'localhost',
        'name' => 'u922228303_aapengineering',
        'user' => 'u922228303_aap_user',
        'pass' => 'YOUR_DB_PASSWORD',
        'charset' => 'utf8mb4',
    ],

    'company' => $companyDefaults,

    'install_key' => 'AapSetup2026',
];
