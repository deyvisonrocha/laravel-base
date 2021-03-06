<?php

return [

    'namespace' => 'App',

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

    'namespaces' => [
        'datatables' => 'App\DataTables',
        'controllers' => 'App\Http\Controllers',
        'requests' => 'App\Http\Requests',
        'models' => 'App\Models',
        'repositories' => 'App\Repositories'
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
        'controller' => '[Models]Controller',
        'datatables' => '[Models]DataTable',
        'modelName' => '[Model]',
        'repository' => '[Models]Repository',
        'request' => '[Models]Request',
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
        'index',
        'show',
        'fields',
    ]
];
