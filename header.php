<?php 
require 'inc/funciones.php';
require 'inc/config.php';


if($_SESSION['login'] !== true){
  header('Location: login.php');
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Proisa | Ventas</title>

  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="Tokens">
  <link rel="apple-touch-startup-image" href="img/icon/apple-touch-icon-180x180.png">
  <link rel="shortcut icon" href="img/icon/favicon.ico" type="image/x-icon" />
  <link rel="apple-touch-icon" href="img/icon/apple-touch-icon-180x180.png" />
  <link rel="apple-touch-icon" sizes="57x57" href="img/icon/apple-touch-icon-57x57.png" />
  <link rel="apple-touch-icon" sizes="72x72" href="img/icon/apple-touch-icon-72x72.png" />
  <link rel="apple-touch-icon" sizes="76x76" href="img/icon/apple-touch-icon-76x76.png" />
  <link rel="apple-touch-icon" sizes="114x114" href="img/icon/apple-touch-icon-114x114.png" />
  <link rel="apple-touch-icon" sizes="120x120" href="img/icon/apple-touch-icon-120x120.png" />
  <link rel="apple-touch-icon" sizes="144x144" href="img/icon/apple-touch-icon-144x144.png" />
  <link rel="apple-touch-icon" sizes="152x152" href="img/icon/apple-touch-icon-152x152.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="img/icon/apple-touch-icon-180x180.png" />
  <link rel="apple-touch-icon-precomposed" sizes="128x128" href="niceicon.png">

  <link rel="manifest" href="manifest.json">
  <link rel="icon" sizes="192x192" href="img/icon/apple-touch-icon-180x180.png">
  <link rel="icon" sizes="128x128" href="img/con/apple-touch-icon-120x120.png">


  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=url_base()?>/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?=url_base()?>/dist/css/app.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?=url_base()?>/dist/css/skins/_all-skins.min.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/morris.js/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/jvectormap/jquery-jvectormap.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="<?=url_base()?>/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?=url_base()?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <style>
  
  .control-sidebar-bg, .control-sidebar {
    width:50% !important;
    right: -50%;
    padding:60px 10px 0px;
  }

#cart-btn {
  position: fixed;
  bottom: 0;
  right: 10px;
  font-size: 20px;
  padding:10px;
  cursor: pointer;
  border-radius:5px;
  margin-bottom:10px;
}

.sidebar-menu, .main-sidebar .user-panel, .sidebar-menu>li.header {
  white-space: initial !important;
}


  @media (max-width: 580px) and (min-width:1px){
      .control-sidebar-bg, .control-sidebar {
      width:100% !important;
      right: -100%;
      padding:110px 10px 0px;
    }
  }

  
  </style>
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>P</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Proisa</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=url_base()?>/dist/img/user.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$_SESSION['nombre']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?=url_base()?>/dist/img/user.png" class="img-circle" alt="User Image">
                <p>
                <?=$_SESSION['nombre']?>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="<?=url_base()?>/process/AuthProcess.php?logout=true" class="btn btn-default btn-flat">Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-shopping-cart"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
 
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=url_base()?>/dist/img/user.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?=$_SESSION['nombre']?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <!-- <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php if(empty(trim($_SESSION['mo_codigo']))): ?>
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU PRINCIPAL</li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Inicio</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
        
              <li class="active"><a href="<?=url_base()?>/pages/cuadre_caja.php"><i class="fa fa-circle-o"></i>Cuadre de caja</li>
              <li><a href="<?=url_base()?>/pages/pedidos.php"><i class="fa fa-cutlery"></i>Ordenes en cocina</a></li>
              <li><a href="<?=url_base()?>/pages/mesas_abiertas.php"><i class="fa fa-circle-o"></i>Mesa abiertas</a></li>
        
          </ul>
          <!-- <li><a href="<?=url_base()?>/pages/camareros.php"><i class="fa fa-users"></i>Camareros</a></li>
          <li><a href="<?=url_base()?>/pages/mesas.php"><i class="fa fa-circle-o"></i>Mesas</a></li> -->
          <!-- <li><a href="<?=url_base()?>/index.php"><i class="fa fa-file-text-o"></i>Menu</a></li> -->
        </li>
        <li class="active treeview">
          <a href="#">
            <i class="fa fa-dashboard"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="active"><a href="<?=url_base()?>/pages/resumen_ventas.php"><i class="fa fa-circle-o"></i>Resumen de ventas</a></li>
              <li><a href="<?=url_base()?>/pages/ventas_departamentos.php"><i class="fa fa-circle-o"></i>Ventas por Departamentos</a></li>
              <li><a href="<?=url_base()?>/pages/ventas_categorias.php"><i class="fa fa-circle-o"></i>Ventas por Categorias</a></li>
              <li><a href="<?=url_base()?>/pages/reporte_comparativo.php"><i class="fa fa-circle-o"></i>Comparativo Entre Periodos</a></li>
          </ul>
          <!-- <li><a href="<?=url_base()?>/pages/camareros.php"><i class="fa fa-users"></i>Camareros</a></li>
          <li><a href="<?=url_base()?>/pages/mesas.php"><i class="fa fa-circle-o"></i>Mesas</a></li> -->
          <!-- <li><a href="<?=url_base()?>/index.php"><i class="fa fa-file-text-o"></i>Menu</a></li> -->
        </li>
      </ul>
      <?php endif;?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <?php if(isset($_GET['ma']) && isset($_GET['cliente'])): ?>
      <h2>Mesa: <?=$_GET['ma']?> - <span id="cliente_nombre" data-nombre="<?=$_GET['cliente']?>"><?=$_GET['cliente']?></span> </h2>
      <?php endif;?>

    </section>
    <!-- Main content -->
    <section class="content">