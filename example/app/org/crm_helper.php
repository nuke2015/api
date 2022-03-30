<?php
use nuke2015\api\org;

$config = [
    'url_api'  => 'https://api.example.com/api_worker/',
    'url_jump' => '//' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] . '?',
];

// var_dump($config);exit;
list($err, $data) = org\crm_helper::check_login($config);
if ($err == 0) {
    // var_dump($data);
    //goon
} else {
    if ($err == 1) {
        org\crm_helper::show_login_code($config);
        exit;
    }
    var_dump($data);
    exit;
}
