
  <?php
    $UserType= "";
    if (isset( $_SESSION['Usuario']))
    { 
      $User= $_SESSION['Usuario'];
      $UserType= "ADMIN";
    }
?>
  
  
  <!-- Left navbar-header -->
  <div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse slimscrollsidebar">
      <div class="user-profile">
        <div class="dropdown user-pro-body">
          <div><img src="plugins/images/users/profile.png" alt="user-img" class="img-circle"></div>
          <a class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
             <?php echo $_SERVER['REMOTE_ADDR'];?> <span class="caret"></span></a>
              <ul class="dropdown-menu animated flipInX">    
                <li><a href="login.php"><i class="fa fa-power-off"></i> Iniciar Sesión</a></li>
              </ul>
              <br>
        </div>
      </div>
      <ul class="nav" id="side-menu">
        <li class="sidebar-search hidden-sm hidden-md hidden-lg">
          <!-- input-group -->
          <div class="input-group custom-search-form">
            <input type="text" class="form-control" placeholder="Buscar...">
            <span class="input-group-btn">
            <button class="btn btn-default" type="button"> <i class="fa fa-search"></i> </button>
            </span> </div>
          <!-- /input-group -->
        </li> 

        <?php  
        if($UserType == "Administrador")
        {    
        ?>

        <li class="nav-small-cap">-- SISTEMAS ATR</li>
        <li>
        <a class="waves-effect"><i data-icon="&#xe004;" class="linea-icon linea-basic fa-fw"></i> 
        <span class="hide-menu">Administrador<span class="fa arrow"></span>
        </span>
        </a>
          <ul class="nav nav-second-level"> 
            <li><a href="usuarios.php">Administrar Usuarios</a></li>              
          </ul>
        </li>   
        <?php  
        }     
        ?>

        <li>
        <a class="waves-effect"><i data-icon="&#xe006;" class="linea-icon linea-basic fa-fw"></i> 
        <span class="hide-menu">Solicitudes<span class="fa arrow"></span>
        </span>
        </a>
          <ul class="nav nav-second-level"> 
            <li><a href="ticket.php" >Solicitud de Soporte</a></li>  
            <li><a href="Formateo.php" >Solicitud de formateo</a></li>  
            <li><a href="solicitud_cc.php" >Solicitudes Carpeta Compartida</a></li> 
            <li><a href="solicitud_cambio_sistema.php">Solicitud de Cambio o Desarrollo</a></li>
            <li><a href="solicitud_cambio_sistema_menuList.php">Consulta de Solicitud</a></li>
            <li><a href="solicitud_cambio_sistema_planTrabajoConsulta.php">Consulta de Plan de Trabajo</a></li>
            <!-- <li><a href="consultas.php"  >Estatus de solicitudes</a></li> -->
          </ul>
        </li>
        <?php  

          
        ?>

        <?php  
        if($UserType == "Usuario")
        {    
        ?>
        <li> 
        <a class="waves-effect"><i data-icon="&#xe002;" class="linea-icon linea-basic fa-fw"></i>         
        <span class="hide-menu">Autorizador<span class="fa arrow"></span>
        </span>
        </a>
          <ul class="nav nav-second-level"> 
            <li><a href="aPendientes.php" >Pendientes</a></li>          
          </ul>
        </li>
        </li>
        <?php  
        }    
        ?>


        <li class="nav-small-cap">-- Soporte</li>
        <li><a href="login.php" class="waves-effect"><i class="icon-logout fa-fw"></i> <span class="hide-menu">Cerrar sesión</span></a></li>
        </ul>
    </div>    
  </div>