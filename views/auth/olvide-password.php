<h1 class="nombre-pagina">Olvide Contraseña</h1>
<p class="descripcion-pagina">Reestablece tu contraseña escribiendo tu email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php';  ?>

<form action="/olvide" method="post" class="formulario">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Ej: correo@correo.com">
    </div>

    <input type="submit" value="Enviar" class="btn">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia sesión</a>
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
</div>