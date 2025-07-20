<div class="barra">
    <p>Hola: <?php echo $nombre ?? '';  ?></p>
    <a href="/logout" class="btn">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])): ?>
    <div class="barra-servicios">
        <a href="/admin" class="btn">Ver citas</a>
        <a href="/servicios" class="btn">Ver servicios</a>
        <a href="/servicios/crear" class="btn">Nuevo Servicio</a>
    </div>

<?php endif; ?>