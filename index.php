<?php
require_once 'includes/Router.php';

$router = new Router();

$router->get('/login','pages/login.php');
$router->get('/home','pages/home.php');

$router->resolve();
?>
