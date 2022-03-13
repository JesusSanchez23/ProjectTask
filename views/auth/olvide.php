<div class="contenedor olvide">
    <?php
    include_once __DIR__ . '/../templates/nombre-sitio.php';
    ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar Password</p>

        <?php
        include_once __DIR__ . '/../templates/alertas.php';
        ?>

        <form action="/olvide" class="formulario" method="POST">
            <div class="campo">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="tu email">
            </div>
            <input type="submit" value="Enviar Email" class="boton">
        </form>

        <div class="acciones">
            <a href="/">Iniciar Sesión</a>
            <a href="/crear">¿No tienes cuenta?Crear una</a>
        </div>
    </div>
    <!--Contenedor sm-->
</div>