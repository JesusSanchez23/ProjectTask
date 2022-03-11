<div class="contenedor login">
<?php 
  include_once __DIR__. '/../templates/nombre-sitio.php';
?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <form action="/" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu email">
            </div>

            <div class="campo">
                <label for=password">Password</label>
                <input type="password" name="password" id="password" placeholder="tu password">
            </div>

            <input type="submit" value="Iniciar sesión" class="boton">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener Una</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div> <!--Contenedor sm-->
</div>