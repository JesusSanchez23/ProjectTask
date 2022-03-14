<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;


class DashboardController
{
    public static function index(Router $router)
    {
        session_start();

        isAuth();

        $proyectos = Proyecto::belongsTo('propietarioId', $_SESSION['id']);


        $router->render('dashboard/index', [
            'nombre' => $_SESSION['nombre'],
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] ==='POST'){
            $proyecto = new Proyecto($_POST);

            // validación
            $alertas = $proyecto->validarProyecto();
         
            if(empty($alertas)){

                // generar una url unica
                $proyecto->url =md5(uniqid());

                // almacenar el creador del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                //guardar el proyecto
                $proyecto->guardar();

                //redireccionar

                header('Location: /proyecto?id='.$proyecto->url);
            }
        }
        $router->render('dashboard/crear-proyecto',[
            'titulo' => 'Crear proyecto',
            'alertas' => $alertas
        ]);
    }

    
    public static function perfil(Router $router){
        session_start();

        isAuth();
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil'
        ]);
    }

    public static function proyecto(Router $router){
        session_start();

        isAuth();

        // revisra que la perona que visita el proyecto es quien lo creo
        $token = $_GET['id'];

        if(!$token){
            header('Location: /dashboard');
        }

        $proyecto = Proyecto::where('url',$token);
    

        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }
}
