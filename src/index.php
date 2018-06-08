<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'application/lib/Db.php';
require 'application/lib/phpQuery.php';

use Pars as Pars;

spl_autoload_register(function($class) {
    $path = str_replace('\\', '/', $class.'.php');
    if (file_exists($path)) {
        require $path;
    }
});

$pars = new Pars();
$pars->parsing();
