<?php

class Router
{

    // routes va arata asa:

    /*
    $routes = [
        'GET' => [
            '/home' => 'pages/home.php',
            '/login' => 'pages/login.php'
        ],
        'POST' => []
    ];
    */

//    array asociativ
    private $routes = [
        "GET" => [],
        "POST" => []
    ];

    public function get($url,$file){
        $this->routes['GET'][$url] = $file;
    }

    public function post($url,$file){
        $this->routes['POST'][$url] = $file;
    }

    public function resolve(){
        //iau metoda din request get/post
        $method  = $_SERVER['REQUEST_METHOD'];

        //iau uri-ul /kim/login?msg=Error
        $request_url = $_SERVER['REQUEST_URI'];         // /kim/login?msg=Error

        // extrag din el doar path-ul, fara parametrii
        $request_url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);  // /kim/login

        //iau doar endpoint-ul din routes
        $request_url = str_replace('/kim','',$request_url); // /login

        //daca exista in map /login in routes['GET']
        if(array_key_exists($request_url,$this->routes[$method])){
            // luam rute ca fiind routes['GET']['/login'] care va avea path-ul catre fisier
            $found_route = $this->routes[$method][$request_url];

            require $found_route;
        } else {
            http_response_code(404);

            require 'views/404.php';
        }

    }
}
