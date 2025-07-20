<?php

namespace Controllers;

use MVC\Router;

class CitaController{
    public static function index(Router $router){
        session_start();

        isAuth();
        
        $router->render('cita/index', [
            'pagina' => 'Agendar cita', 
            'nombre' => $_SESSION['nombre'],
            'id' => $_SESSION['id']
        ]);
    }
}