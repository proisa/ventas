
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
</head>
<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Proisa</b></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <div id="config_form">
    <div class="form-group">
        <label for="">Impresion local</label>
        <select class="form-control" id="opt">
            <option value="no">No</option>
            <option value="si">Si</option>
        </select>
    </div>
    <div class="form-group">
        <input type="password" id="config_pass" class="form-control" placeholder="Clave de configuracion" name='config_pass'>
      </div>
      <button type="submit" id="config_login" class="btn btn-primary btn-block btn-flat">Entrar <i class="fa  fa-arrow-right"></i> </button><br>
      <a href="login.php" id="config" class="btn btn-default btn-block">Volver a login <i class="fa fa-lock"></i></a>
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
<script>
  // $("#config_login").click(function(){
  //   alert('funca');
  // });

  $("#config_login").click(function(){
    $.ajax({
        url: "process/AuthProcess.php",
        type:'post',
        dataType: "json",
        data: 'config_pass='+$("#config_pass").val()+'&imp='+$("#opt").val(),
        success: function(result){
          if(result.cod == '00'){
              localStorage.setItem('imp_local',result.opt); 
              alert('Configuracion guardada correctamente');
          }else{
            alert('Clave incorrecta');
          }
        }
    });
    $('#cliente').empty();
  });

</script>
</body>
</html>
