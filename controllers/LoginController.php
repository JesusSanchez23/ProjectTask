<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController
{

    public static function login(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                // verificar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                if (!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'el usuario no existe');
                } else {
                    // el usuario existe
                    if (password_verify($_POST['password'], $usuario->password)) {

                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        // redireccionar
                        header('Location: /dashboard');
                    } else {
                        Usuario::setAlerta('error', 'La contraseña es incorrecta');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        //render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas,
        ]);
    }

    public static function logout()
    {
        session_start();

        $_SESSION = [];
        header('Location: /');
    }


    public static function crear(Router $router)
    {

        $alertas = [];
        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarNuevaCuenta();

            if (empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);
                if ($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya esta registrado');
                    $alertas = Usuario::getAlertas();
                } else {

                    // hashear el psw
                    $usuario->hashPassword();

                    //eliminar password 2(se creo solo para validaci+ón de contraseña repetida)
                    unset($usuario->password2);

                    // generar el token
                    $usuario->crearToken();

                    //crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    // enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarConfirmacion();

                    if ($resultado) {
                        header('Location: /mensaje');
                        // Usuario::setAlerta('exito','Usuario Creado');
                    }
                    // $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide(Router $router)
    {

        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();
            if (empty($alertas)) {
                // buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if ($usuario && $usuario->confirmado === '1') {
                    unset($usuario->password2);
                    //generar un nuevo token
                    $usuario->crearToken();
                    // actualizar el usuario
                    $usuario->guardar();

                    // enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->reestablecerPassword();
                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Se han enviado instrucciones a tu correo');
                    // debuguear($usuario);
                } else {
                    Usuario::setAlerta('error', 'No existe el usuario o no esta confirmado');
                }
            }

            $alertas = Usuario::getAlertas();
        }

        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Password',
            'alertas' => $alertas,
        ]);
    }

    public static function reestablecer(Router $router)
    {
        $token = s($_GET['token']);
        $alertas = [];
        $mostrar = true;

        if (!$token) {
            header('Location: /');
        }

        // identificar el usuario con este token

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // añadir el nuevo password
            $usuario->sincronizar($_POST);

            // validar el password
            $alertas = $usuario->validarPassword();
            if (empty($alertas)) {
                //    hashear el nuevo password
                $usuario->hashPassword();

                // eliminar token
                $usuario->token = null;

                // guardar en BD
                $resultado = $usuario->guardar();

                // REDIRECCIONAR
                if ($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        // Muestra la vista
        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router)
    {
        $router->render('auth/mensaje', [
            'titulo' => 'confirma tu cuenta'
        ]);
    }

    public static function confirmar(Router $router)
    {
        $token = s($_GET['token']);

        if (!$token) {
            header('Location: /');
        }

        // encontrar al usuario con este token
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // no se encontroun usuario con ese token
            Usuario::setAlerta('error', 'token no valido');
        } else {
            // confirmar la cuenta
            $usuario->confirmado = 1;
            unset($usuario->password2);
            $usuario->token = null;

            // guardar en la base de datos
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta comprobada correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Cuanta verificada',
            'alertas' => $alertas,
        ]);
    }
}
