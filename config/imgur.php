<?php

return [
    /*
     * Public client id provided by Imgur
     */
    'client_id' => '4a5c9268ed3de93',

    /**
     * Client secret provided by Imgur
     */
    'client_secret' => 'dc4af1f676f93eecb9e0b811cb0d21841fd14ed9',

    /**
     * The storage facility to be used to store a user's token.
     * Should be a name of a class implementing the
     *   Redeman\Imgur\TokenStorage\Storage
     * interface.
     */
    'token_storage' => 'Redeman\Imgur\TokenStorage\SessionStorage',
];
