<script>
    function agregar() {
        $('#formulario').modal('show');
    }

    function modificar(nop) {
        $('#formulario' + nop).modal('show');
    }
</script>
<script src="https://select2.org/getting-started/installation"></script>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<!DOCTYPE html>
<html lang="en">
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
<!-- Left navbar-header end -->
<!-- Page Content -->
<style>
    td {
        padding: 5;
    }
</style>
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-6 col-md-6 col-sm-10 col-xs-12">
                <h4 class="page-title">Consulta de estatus en Jobs. </h4>
            </div>

            <!-- /.col-lg-12 -->
        </div>

        <div class="row animated fadeIn">
            <div class="col-sm-12">
                <div class="white-box">

                    <!-- FORMULARIO SOLICITUD -->
                    <!-- <form class="form-horizontal form-material" action="ticket_add.php" method="post"> -->

                    <!-- <div class="label-inverse">
                        <label class="text-white">Detalle de la linea</label> </br>
                    </div> -->
                    <!-- <br> -->
                    <div class="form-group">

                        <div class="col-md-12">
                            <label class="col-md-1">Planta:</label>
                            <select class="col-md-4" name="selPlanta" id="selPlanta"></select>
                            <label class="col-md-1"></label>
                            <button class="btn btn-info waves-effect waves-light m-t-10" onclick="agregaConsulta()">Consultar</button>
                            <br><br>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br>

                    <style>
                        table {
                            border: 1px solid #ddd;
                            border-collapse: collapse;
                            border-spacing: 2px;
                            /* Set spacing between cells and borders */
                            padding: 5px;
                            /* Add spacing between cell content and border */
                            border-top: none;
                            /* Remove default top border */
                            border-left: none;
                            /* Remove default left border */
                            background-color: #f2f2f2;
                            /* Add background color for contrast */
                        }

                        td {
                            padding: 5;
                        }

                        th,
                        td {
                            border: 1px solid #ddd;
                            /* Add border to each cell */
                        }
                    </style>


                    <table id="TablaSurtido" style="width: 100%">
                        <thead>
                            <tr style="text-align: center">
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Job_name</td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Strart date</td>
                                <db></db>
                                <db></db>
                                <db></db>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Stop date</td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Message</td>
                                <td style="text-align: center; border-bottom: 1px solid #ddd; width: 250px;">Run_status</td>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<br>
<footer class="footer text-center"> <?php echo date("Y"); ?> &copy; ATR AutoSistemas de Torreon SA de CV
</footer>


<!-- /#wrapper -->

<!-- Bootstrap Core JavaScript -->
<script src="bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Menu Plugin JavaScript -->
<script src="plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
<!--slimscroll JavaScript -->
<script src="js/jquery.slimscroll.js"></script>
<!--Wave Effects -->
<script src="js/waves.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/custom.js"></script>
<!-- Date Picker Plugin JavaScript -->
<script src="plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<!-- Date range Plugin JavaScript -->
<script src="plugins/bower_components/timepicker/bootstrap-timepicker.min.js"></script>
<script src="./js/scripts/jobstatus.js"></script>
<!--Style Switcher -->
</body>

Script funcional:
<!-- FUNCION PARA EXPORTAR LAS TABLAS DE LAS BASE DE DATOS 
A UN ARCHIVO EN EXCEL, DE ESTA MANERA PODER GENERAR LOS
REPORTES DE CADA MODULO -->

<script>
    function exportExcel(type, fn, dl) {
        var elt = document.getElementById('TablaSurtido');
        var wb = XLSX.utils.table_to_book(elt, {
            sheet: "sheet1"
        });
        return dl ?
            XLSX.write(wb, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            }) :
            XLSX.writeFile(wb, fn || ('Reporte de captura de material.' + (type || 'xlsx')));
    }
</script>


</html>