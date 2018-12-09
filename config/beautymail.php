<?php

return [

    // These CSS rules will be applied after the regular template CSS

    
        'css' => [
            '.button-content .button { 
                background: #6600ff 
            }',
        ],
    

    'colors' => [

        'highlight' => '#004ca3',
        'button'    => '#004cad',

    ],

    'view' => [
        'senderName'  => null,
        'reminder'    => null,
        'unsubscribe' => null,
        'address'     => null,

        'logo'        => [
            'path'   => '%PUBLIC%/image/logo.png',
            'width'  => '',
            'height' => '',
        ],

        'twitter'  => null,
        'facebook' => null,
        'flickr'   => null,
    ],

];
