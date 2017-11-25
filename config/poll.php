<?php

return [

    // The maximum number of options a poll can be created with
    'max_options' => 10,

    // Number of polls per page
    'per_page' => 10,

    // The middleware used to protect certain routes
    'admin_middleware' => 'admin',

    // To use the default views from the package use 'poll::'
    // If you publish these views and want to modify them set
    // this to 'vendor.abstracteverything.poll.'
    'views_base_path' => 'poll::',

];
