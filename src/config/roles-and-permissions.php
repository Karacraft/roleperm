<?php

return [

     /**
     * These methods will be seeded, so add whatever you like and then seed the database
     */

    'permission-methods' => [
        'Create','Update','Delete','Edit','Show','List','View','Tile','Approve','Reject','Cancel','Hold'
    ],

    /**
     * User information for Super Admin. This will be created at the time of Seeding.
     * Don't remove it, just change as per your liking
     */
    'user-info-1' => [
        'name' => 'Ali Jibran',
        'email' => 'dukejib@gmail.com',
        'password' => 'abc123',
    ],
    /**
     * Seeding Number, Used throught out Controllers
     */
    'paging-number' => 5,
    'unauthorized_access_string' => 'Unauthorzied Access',
    
    /**
     * Models to be loaded for Permissions
     */
    'models' => [
        'User','Role','Permission','Method'
    ]
];