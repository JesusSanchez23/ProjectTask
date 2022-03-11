<?php
namespace Controllers;

use MVC\Router;

class LoginController{

    public static function login(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        //render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión'
        ]);
    }

    public static function logout(){

    }


    public static function crear(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/crear', [
            'titulo' => 'Crear Cuenta'
        ]);
    }

    public static function olvide(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        $router->render('auth/olvide',[
            'titulo' => 'Recuperar Password',
        ]);
    }

    public static function reestablecer(Router $router){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

        }

        // Muestra la vista
        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer password',
        ]);
    }

    public static function mensaje(Router $router){
$router -> render('auth/mensaje',[
    'titulo' => 'confirma tu cuenta'
]);
        
    }

    public static function confirmar(Router $router){

        $router->render('auth/confirmar',[
            'titulo' => 'Cuanta verificada',
        ]);
    }

}