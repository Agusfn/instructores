<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Recipients addresses
    |--------------------------------------------------------------------------
    |
    | Emails of persons who will recieve bussiness related important e-mail notifications.
    |
    */

    'recipients' => [
        [
            "email" => env('ADMIN_NOTIF_MAIL'),
            "name" => env('ADMIN_NOTIF_NAME')
        ],

    ]



];