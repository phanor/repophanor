<?php

return array(

    'multi' => array(
       
        'user' => array(
            'email' => 'emails.auth.reminder',
            'driver' => 'eloquent',
            'model' => 'user',
        ),
        
        'admin' => array(
            'email' => 'emails.auth.reminder',
            'driver' => 'database',
            'table' => 'admin'
        ),
    ),

    'reminder' => array(

        'email' => 'emails.auth.reminder',
        'table' => 'password_reminders',
        'expire' => 60,

    ),

);