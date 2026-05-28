<?php
session_start();
require_once 'core/Router.php';
require_once 'config/database.php';

$router = new Router();

$router->get('/login','views/login.php');
$router->get('/home','views/home.php');

$router->get('/test','config/test_db.php');

$router->resolve();
?>
