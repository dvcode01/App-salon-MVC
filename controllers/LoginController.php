<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                // Validar si el email existe
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario){
                    // Validar si el password existe
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Creando sesion
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1"){
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                        }else{
                            header("Location: /cita");
                        }

                    }
                }else{
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }

            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'pagina' => 'Login',
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            // Email existe
            if(empty($alertas)){
                // Buscando usuario
                $usuario = Usuario::where('email', $auth->email);
                
                if($usuario && $usuario->confirmado === '1'){
                    // Generando token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Envio email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alerta exito email
                    Usuario::setAlerta('exito', 'Revisa tu email');
                }else{
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                    
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password', [
            'pagina' => 'Olvido contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            $error = true;
            Usuario::setAlerta('error', 'Token No Valido');
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            if(empty($alertas)){
                // Eliminando password anterior
                $usuario->password = null;

                // Agregando nuevo password
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                // Guardando
                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
                
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/recuperar-password', [
            'pagina' => 'Reestablecer Password',
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router){
        $usuario = new Usuario;

        // Alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarCuentaNueva();

            // En caso de no haber alertas, envia datos a DB
            if(empty($alertas)){
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    // El usuario no esta registrado
                    // hasheando password
                    $usuario->hashPassword();

                    // Generando token unico
                    $usuario->crearToken();

                    // Envio email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear usuario
                    $resultado = $usuario->guardar();

                    if($resultado){
                        header("Location: /mensaje");
                    }
                }
            }
        }

        $router->render('auth/crear-cuenta', [
            'pagina' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function confirmar(Router $router){
        $alertas = [];
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);
       
        if(empty($usuario)){
            // Mostrar error
            Usuario::setAlerta('error', 'Token No Válido');
        }else{
            // Modificar usuario confirmado
            $usuario->confirmado = "1";

            // Eliminando token
            $usuario->token = null;

            // Guardando y enviando alerta
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        // Obteniendo alertas
        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar-cuenta', [
            'pagina' => 'Envio de confirmacion',
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje', [
            'pagina' => 'Envio de confirmacion'
        ]);
    }
}