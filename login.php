
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Proisa | Inicio de sesion</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <style>
      #config_form {
        display:none;
      }
    </style>
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Proisa</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <div id="login_form">
    <p class="login-box-msg">Inicio de sesion</p>
    <form action="process/AuthProcess.php" method="post">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" name='usuario' placeholder="Email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" name="clave" placeholder="Password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Entrar <i class="fa  fa-arrow-right"></i> </button><br>
          <button type="button" id="config" class="btn btn-default btn-block">Configuracion <i class="fa fa-gear"></i></button>
          <?php if(isset($_GET['auth']) && $_GET['auth'] == 'failed'): ?> 
          <hr>
          <div class="alert alert-danger">
              <h4>
                  <i class="fa fa-ban"></i> Error de acceso
              </h4>
              Usuario o clave incorrecto
          </div>
            <?php endif;?>
        </div>
        <!-- /.col -->
      </div>
    </form>
    </div>
    
    <div id="config_form">
    <div class="form-group">
        <input type="text" id="config_pass" class="form-control" placeholder="Clave de configuracion" name='config_pass'>
      </div>
      <button id="config_login" class="btn btn-primary btn-block btn-flat">Entrar <i class="fa  fa-arrow-right"></i> </button><br>
    </div>

    <hr>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>

  $("#config").click(function(){
    alert('funca');
    $('#login_form').hide();
    $('#config_form').show();
  });

  // $("#config_login").click(function(){
  //   $.ajax({
  //       url: "<?=url_base()?>/process/AuthProcess.php",
  //       type:'post',
  //       data: 'config_pass='+$("#config_pass").val(),
  //       success: function(result){
  //         alert(result.cod);
  //       }
  //   });
  //   $('#cliente').empty();
  // });

</script>
</body>
</html>
