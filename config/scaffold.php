<?php

return [
    /**
     * Paths
     *
     * Specify the path to the following folders
     */
    'paths' => [
        'templates' => 'resources/views/vendor/templates',
        'datatables' => 'app/DataTables',
        'controllers' => 'app/Http/Controllers',
        'requests' => 'app/Http/Requests',
        'migrations' => 'database/migrations',
        'models' => 'app/Models',
        'repositories' => 'app/Repositories',
        'views' => 'resources/views',
        'routes' => 'routes'
    ],

    /**
     * Dynamic Names
     *
     * Create your own named variable and include in it the templates!
     * Eg: 'myName' => '[Model] is super fantastic!'
     * Then place [myName] in your template file and it will output "Book is super fantastic!"
     * [model], [models], [Model], or [Models] are valid in the dynamic name
     */
    'names' => [
        'controller' => '[Model]Controller',
        'modelName' => '[Model]',
        'repository' => '[Models]Repository',
        'viewFolder' => '[models]',
    ],

    /**
     * Views
     *
     * Specify the names of your views.
     */
    'views' => [
        'create',
        'datatables_actions',
        'edit',
        'fields',
        'index',
        'show'
    ]
];
