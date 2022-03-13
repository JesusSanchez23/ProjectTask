<div class="contenedor crear">
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>


    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear Cuenta</p>
        <?php
    include_once __DIR__ . '/../templates/alertas.php';
    ?>

        <form action="/crear" class="formulario" method="POST">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" placeholder="tu nombre"
                value="<?php echo $usuario->nombre?>">
            </div>
            
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu email" value="<?php echo $usuario->email?>">
            </div>

            <div class="campo">
                <label for=password">Password</label>
                <input type="password" name="password" id="password" placeholder="tu password">
            </div>

            <div class="campo">
                <label for=password2">Repetir Password</label>
                <input type="password" name="password2" id="password2" placeholder="Repite tu password">
            </div>

            <input type="submit" value="Crear Cuenta" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div>
    <!--Contenedor sm-->
</div>