<?php

/**
 * All hyn-me/webserver related configuration options.
 *
 * @warning please be advised, read the documentation on http://hyn.me before editing
 *
 * None of the generated configurations will work as long as you don't add the paths to the corresponding webservice
 * configuration file. See documentation for more info.
 */
return [
    'webservers' => ['nginx', 'apache'],

    /*
     * If tenant files should belong to a certain user, set the `default-user` value to that username
     *      - true will generate a username automatically based on the website
     *      - <string> will use the specified existing username for the website
     *      - null will disable generating users
     */
    'default-user' => null,

    /*
     * The group the tenant files should belong to
     */
    'group' => 'www-data',

    /*
     * SSL
     */
    'ssl'   => [
        'path' => storage_path('webserver/ssl'),
    ],
    /*
     * Logging specific settings
     */
    'log' => [
        // path where to store the webserver logs
        'path' => storage_path('logs'),
    ],
    /*
     * Apache
     */
    'apache' => [
        'path' => storage_path('webserver/apache/'),
        // class that runs functionality for this service
        'class'   => 'Hyn\Webserver\Generators\Webserver\Apache',
        'enabled' => true,
        'port'    => [
            'http'  => 80,
            'https' => 443,
        ],
        // path to service daemon, used to verify service exists
        'service' => '/etc/init.d/apache2',
        // how to run actions for this service
        'actions' => [
            'configtest' => 'apache2ctl -t',
            'reload'     => 'apache2ctl graceful',
        ],
        // system wide configuration directory
        'conf' => [
            // location for ubuntu 14.04 systems
            '/etc/apache2/sites-enabled/',
        ],
        // mask for auto-generated config file that includes the tenant configurations
        'mask' => '%s.conf',
        // include format using sprintf to include the location of the storage/webserver/apache directory
        'include' => 'IncludeOptional %s*',
    ],
    /*
     * Nginx
     */
    'nginx' => [
        'path'    => storage_path('webserver/nginx/'),
        'class'   => 'Hyn\Webserver\Generators\Webserver\Nginx',
        'enabled' => true,
        'port'    => [
            'http'  => 80,
            'https' => 443,
        ],
        // path to service daemon, used to verify service exists
        'service' => '/etc/init.d/nginx',
        // how to run actions for this service
        'actions' => [
            'configtest' => '/etc/init.d/nginx configtest',
            'reload'     => '/etc/init.d/nginx reload',
        ],
        'conf'    => ['/etc/nginx/sites-enabled/'],
        'mask'    => '%s.conf',
        'include' => 'include %s*;',
        /*
         * the nginx service depends on fpm
         * during changes we will automatically trigger fpm as well
         */
        'depends' => [
            'fpm',
        ],
    ],
    /*
     * PHP FPM
     */
    'fpm' => [
        'path'    => storage_path('webserver/fpm/'),
        'class'   => 'Hyn\Webserver\Generators\Webserver\Fpm',
        'enabled' => true,
        'conf'    => [
            '/etc/php7/fpm/pool.d/'
        ],
        // path to service daemon, used to verify service exists
        'service' => '/etc/init.d/php7-fpm',
        // how to run actions for this service
        'actions' => [
            'configtest' => '/etc/init.d/php7-fpm -t',
            'reload'     => '/etc/init.d/php7-fpm reload',
        ],
        'mask'    => '%s.conf',
        'include' => 'include=%s*;',
        /*
         * base modifier for fpm pool port
         * @example if base is 9000, will generate pool file for website Id 5 with port 9005
         * @info this port is used in Nginx configurations for the PHP proxy
         */
        'port' => 9000,
    ],
];
