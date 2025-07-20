<?php

namespace Controllers;

use Model\Servicio;
use MVC\Router;

class ServicioController{
    public static function index(Router $router){
        session_start();

        isAdmin();

        // Trayendo todos los servicios
        $servicios = Servicio::all();

        $router->render('servicios/index', [
            'pagina' => 'Nuestros Servicios',
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    public static function crear(Router $router){
        session_start();

        $servicio = new Servicio;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            // Todos los campos fueron llenados exitosamente
            if(empty($alertas)){
                $servicio->guardar();
                header("Location: /servicios");
            }
        }

        $router->render('servicios/crear', [
            'pagina' => 'Crear Servicios',
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);

    }


    public static function actualizar(Router $router){
        session_start();

        if(!is_numeric($_GET['id'])) return;

        $servicio = Servicio::find($_GET['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();

            if(empty($alertas)){
                $servicio->guardar();
                header("Location: /servicios");
            }
        }

        $router->render('servicios/actualizar', [
            'pagina' => 'Actualizar Servicios',
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar(){
        session_start();

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $servicio = Servicio::find($_POST['id']);
            $servicio->eliminar();
            
            header("Location: /servicios");
        }
    }

}