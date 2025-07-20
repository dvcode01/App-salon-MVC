<?php

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{
    public static function index(){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){
        // Almacenando cita y devuelve id
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        // Almacenando citas y con el id del servicios
        $idServicios = explode(',', $_POST['servicios']);

        foreach ($idServicios as $idServicio) {
            $args = [
                'cita_id' => $resultado['id'],
                'servicio_id' => $idServicio
            ];

            $cita_servicio = new CitaServicio($args);
            $cita_servicio->guardar();
        }

        echo json_encode([ 'resultado' => $resultado]);
    }

    public static function eliminar(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);

            $cita->eliminar();
            header("Location:" . $_SERVER['HTTP_REFERER']);
        }
    }
}



