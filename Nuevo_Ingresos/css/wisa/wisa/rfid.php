<?php include "initialize.php";?>
<?php include "header.php";?>
<?php include "navbar.php";?>

<style>
    .dataTables_filter{display:none;}
</style>

<div id='contenido' >
    <!-- COMIENZA CONTENIDO -->
    <div id="page-wrapper">
        <div class="container-fluid">
            <div class='row bg-title'>
                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12" style="display:flex; justify-content:space-between;align-items:center;">
                    <h4 class='page-title'>Confirmacion de Inventario - RFID</h4>
                </div>
                <div class='col-lg-9 col-sm-8 col-md-8 col-xs-12'>
                    <ol class='breadcrumb'></ol>
                </div>
            </div>
            <?php
            if (isset($_POST['btnBuscar'])) {
                $fechaInicio = $_POST['fecha_inicio'];
                $fechaFin = $_POST['fecha_fin'];
                $sql = " [SP_CONSULTA_CONFIRMACION_INVENTARIO_RFID] '$fechaInicio', '$fechaFin 23:59:59'";
                $proc_result = sqlsrv_prepare($connCI, $sql);
                sqlsrv_execute($proc_result);
            }
            ?>
            <div class="white-box">
                <div class="form-group">
                    <div class="label-inverse">
                        <label class="text-white">Tabla de Confirmaciones</label>
                    </div>
                    <br>
                    <div style="display:flex; justify-content:space-between;">
                        <p><span class="glyphicon glyphicon-filter"></span>Filtros:</p>
                    </div>
                    <div class="form-row my-3" id="position">
                        <input style="cursor: pointer;" class="form-check-input mx-4" type="checkbox" name="srev" id="opc1" value="" checked>
                        <label style="cursor: pointer; user-select: none;" class="form-check-label mx-4" for="opc1">Solo confirmadas</label>
                    </div>

                    <hr class="hr" />

                    <form method="POST">
                        <div style="display:flex; justify-content:space-between;">
                            <div style="display:block;width:49%;">
                                <p>Fecha Inicial:</p>
                                <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio"   Value ="<?php if (isset($fechaInicio)) {echo $fechaInicio;} ?>">                            
                            </div>
                            <div style="display:block;width:49%;">
                                <p>Fecha Final:</p>
                                <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" value="<?php if (isset($fechaFin)) {echo $fechaFin;} ?>">    
                            </div>
                        </div>
                        <br/>
                        <div style="display:flex; justify-content:space-between;">
                            <div style="display:block;width:100%;">
                                <button class="form-control" id="btnBuscar" name="btnBuscar">Consulta</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="form-group">
                    <table id="tablaRFID" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <td style="border-top: solid 1px;"></td>
                                <td style="border-top: solid 1px;"></td>
                                <td style="border-top: solid 1px;"></td>
                                <td style="border-top: solid 1px;"></td>
                                <td style="border-top: solid 1px;"></td>
                            </tr>
                            <tr class="label-inverse" >
                                <th style="color:white;">  Nombre de equipo       </th>
                                <th style="color:white;">  Localizacion           </th>
                                <th style="color:white;">  No. Serie              </th>
                                <th style="color:white;">  RFID                   </th>
                                <th style="color:white;">  Fecha de Confirmacion  </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($proc_result)) {while($row3=sqlsrv_fetch_array($proc_result, SQLSRV_FETCH_ASSOC)) { ?>
                            <tr <?php if (utf8_encode($row3['FechaConfirmacion']) == 'PENDIENTE') { echo 'style="background-color:#ffffe0;"'; } else { echo 'style="background-color:#b6f2b7;"'; } ?> >
                                <td>  <?php echo utf8_encode($row3['Nombre_Eq']); ?>          </td>
                                <td>  <?php echo utf8_encode($row3['Localizacion']); ?>       </td>
                                <td>  <?php echo utf8_encode($row3['No_Serie']); ?>           </td>
                                <td>  <?php echo utf8_encode($row3['RFID']); ?>               </td>
                                <td>  <?php echo utf8_encode($row3['FechaConfirmacion']); ?>  </td>
                            </tr>
                            <?php }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TERMINA CONTENIDO -->
<?php include "footer.php";?>

<script>
    $(document).ready( function () {
        var fechaAct = new Date();
        var fechaPrimero = new Date(fechaAct.getFullYear(), fechaAct.getMonth(), 1);

        document.getElementById('fecha_inicio').valueAsDate = fechaPrimero;
        document.getElementById('fecha_fin').valueAsDate = fechaAct;

        var table = $('#tablaRFID').DataTable({
            paging:false,
            ordering: false,
            sorting: false,
            initComplete: function() {
            var table = this.api();

            table.columns().every(function() {
                var column = this;

                var input = $('<input type="text" class="form-control"/>')
                .appendTo($("thead tr:eq(0) td").eq(this.index()))
                .on("keyup", function() {
                    column.search($(this).val()).draw();
                });

            });
            }
        });

        if($('#opc1').is(':checked')) {
            table.column(4).search('-').draw(false);
        }else{
            table.column(4).search('').draw(false);
        }

        $('#opc1').on('change', function () {
            if($('#opc1').is(':checked')){
                table.column(4).search('-').draw(false);
            }else{
                table.column(4).search('').draw(false);
            }
        });
    });
</script>
</body>
</html>