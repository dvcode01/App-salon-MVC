<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus datos</p>

<?php  include_once __DIR__ . '/../templates/alertas.php';  ?>

<form method="post" class="formulario" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu email">
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="Tu password">
    </div>

    <input type="submit" class="btn" value="Iniciar sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una</a>
    <a href="/olvide">¿Olvidaste tu contraseña?</a>
</div>