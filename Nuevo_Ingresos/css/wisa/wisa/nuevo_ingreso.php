<?php include "initialize.php";?>
<?php include "header.php";?>
<?php include "navbar.php";?>

<div class="container">

    <!-- FORMULARIO DE DATOS -->
    <h4 class="bg-primary text-white p-2">
        SEA TAN AMABLE DE LLENAR LOS SIGUIENTES DATOS
    </h4>

    <form method="post" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nombre completo</label>
            <input type="text" name="nombre" class="form-control">
        </div>

        <div class="form-group">
            <label>Parentesco</label>
            <input type="text" name="parentesco" class="form-control">
        </div>

        <div class="form-group">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control">
        </div>

        <div class="form-group">
            <label>Calle y número</label>
            <input type="text" name="direccion" class="form-control">
        </div>

        <div class="form-group">
            <label>Colonia</label>
            <input type="text" name="colonia" class="form-control">
        </div>

        <div class="form-group">
            <label>Ciudad</label>
            <input type="text" name="ciudad" class="form-control">
        </div>

        <div class="form-group">
            <label>Estado</label>
            <input type="text" name="estado" class="form-control">
        </div>

        <div class="form-group">
            <label>Código Postal</label>
            <input type="text" name="cp" class="form-control">
        </div>

        <!-- PAPELERÍA -->
        <h4 class="bg-primary text-white p-2 mt-4">PAPELERÍA</h4>

        <div class="form-group">
            <label>Acta de nacimiento</label>
            <input type="file" name="acta" class="form-control">
        </div>

        <div class="form-group">
            <label>Credencial de elector</label>
            <input type="file" name="ine" class="form-control">
        </div>

        <div class="form-group">
            <label>CURP</label>
            <input type="file" name="curp" class="form-control">
        </div>

        <div class="form-group">
            <label>Comprobante de domicilio</label>
            <input type="file" name="domicilio" class="form-control">
        </div>

        <div class="form-group">
            <label>Número de afiliación IMSS</label>
            <input type="file" name="imss" class="form-control">
        </div>

        <div class="form-group">
            <label>Comprobante de estudios</label>
            <input type="file" name="estudios" class="form-control">
        </div>

        <div class="form-group">
            <label>Carta de recomendación</label>
            <input type="file" name="recomendacion" class="form-control">
        </div>

        <div class="form-group">
            <label>Estado de cuenta INFONAVIT</label>
            <input type="file" name="infonavit" class="form-control">
        </div>

        <div class="form-group">
            <label>Constancia fiscal (RFC)</label>
            <input type="file" name="rfc" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">
            Guardar solicitud
        </button>

    </form>

</div>

<?php include 'footer.php'; ?>

