
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <strong>Copyright &copy; <?=date('Y')?> <a href="https://proisard.com">Proisa</a>.</strong> All rights
    reserved.
  </footer>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" id="carrito">
    <table class="table" style="color:#fff">
    <thead>
      <th width="20%">Cant.</th>
      <th>Desc.</th>
      <th width="30%">Precio</th>
      <th class="text-right"></th>
    </thead>
    <tbody id="tabla-carrito"> 
    </tbody>
   
    </table>
  

    <table class="table" style="color:#fff; font-size: 16px;">
    <tr>
      <td class="text-right">
      Sub-total: <br>
      Itbis:<br>
      % de Ley:<br>
      Total a pagar:
      </td>
      <td class="text-right">
        <span id="subtotal"></span><br>
        <span id="itbis"></span><br>
        <span id="ley"></span> <br>
        <span id="total_pagar"></span>
      </td>
    </tr>
    </table>
    <hr>
    <label for="">Nombre de cliente</label>
    <input type="text" class="form-control" id="cliente" maxlength="20">
    <br>
    <button id="crear_pedido" disabled='true' class="btn btn-success btn-block btn-lg">Hacer pedido</button>
  </aside>
  <!-- /.control-sidebar -->


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>


<a href="#" id="cart-btn" data-toggle="control-sidebar" class="btn btn-success">
<i class="fa fa-shopping-cart"></i>
</a>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?=url_base()?>/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?=url_base()?>/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=url_base()?>/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="<?=url_base()?>/bower_components/raphael/raphael.min.js"></script>
<script src="<?=url_base()?>/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="<?=url_base()?>/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?=url_base()?>/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=url_base()?>/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?=url_base()?>/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="<?=url_base()?>/bower_components/moment/min/moment.min.js"></script>
<script src="<?=url_base()?>/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?=url_base()?>/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?=url_base()?>/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?=url_base()?>/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?=url_base()?>/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?=url_base()?>/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->
<script src="<?=url_base()?>/dist/js/demo.js"></script>
<script>
  $(document).ready(function(){
    fillCart();   
  });

  function fillCart(){
    $("#tabla-carrito").empty();
    $('#subtotal').empty();
    $('#itbis').empty();
    $('#ley').empty();
    $('#total_pagar').empty();

    var data = JSON.parse(sessionStorage.getItem('item'));
    var subtotal = 0;
    var itbis = 0;
    var total_pagar = 0;

    $("#crear_pedido").attr('disabled',!(data.length > 0));
    console.log(data.length > 0);

    $.each(data, function(key,value){
        var lista = '';
        value.cantidad = parseInt(value.cantidad);
        value.precio = parseFloat(value.precio);

        subtotal += value.cantidad*value.precio;
        console.log(subtotal);
        if(value.guarnicion_nombre){
          lista += `<li>CON ${value.guarnicion_nombre}</li>`;
        }

        if(value.termino_nombre){
          lista += `<li>${value.termino_nombre}</li>`;
        }

        if(value.ingrediente != 'undefined' || value.ingrediente.length > 0){
            for (i = 0; i < value.ingrediente.length; i++){
                lista += `<li> SIN ${value.ingrediente[i]}</li>`;
            }
        }
        var template = `<tr>
        <td>
        <input type="hidden" name="art_id[]" value="${value.id}">
        ${value.cantidad}
        </td>
        <td>${value.descripcion}
            <ul>
              ${lista}
            </ul>
            <i>${value.nota}</i>
        </td>
        <td>${value.precio}</td>
        <td><a class="btn btn-danger btn-sm remove-item" data-id="${key}"><i class="fa fa-trash"></i></a></td>
        </tr>`;
        $("#tabla-carrito").append(template);
       
    });

    itbis = subtotal*(parseFloat(sessionStorage.getItem('itbis')) / 100);
    ley = subtotal*(parseFloat(sessionStorage.getItem('ley')) / 100);
    total_pagar = subtotal+itbis+ley;
    $('#subtotal').append(subtotal.toFixed(2));
    $('#itbis').append(itbis.toFixed(2));
    $('#ley').append(ley.toFixed(2));
    $('#total_pagar').append(total_pagar.toFixed(2));

    var header_data = {};
    if(sessionStorage.getItem('header') != null){
        header_data = JSON.parse(sessionStorage.getItem('header'));
    }

    // header_data = {
    //   'subtotal':parseFloat(subtotal.toFixed(2)),
    //   'itbis':parseFloat(itbis.toFixed(2)),
    //   'ley':parseFloat(ley.toFixed(2)),
    //   'total':parseFloat(total_pagar.toFixed(2))
    // }

    header_data.subtotal = parseFloat(subtotal.toFixed(2)),
    header_data.itbis = parseFloat(itbis.toFixed(2)),
    header_data.ley = parseFloat(ley.toFixed(2)),
    header_data.total = parseFloat(total_pagar.toFixed(2)),
    header_data.cliente = $('#cliente').val();
 
    sessionStorage.setItem('header',JSON.stringify(header_data));

    $('.remove-item').on('click',function(){
      var confirmacion = confirm('Esta seguro de eliminar este articulo?');
      if(confirmacion == true){
          indice = $(this).attr('data-id');
          removeItem(indice);
      }
    });

    function removeItem(indice){
      var data = JSON.parse(sessionStorage.getItem('item'));
      data.splice(indice,1);
      sessionStorage.setItem('item',JSON.stringify(data));
      fillCart();
      //console.log(data[indice]);
    }
  }


  $("#crear_pedido").click(function(){
    fillCart();
    $.ajax({
        url: "<?=url_base()?>/pages/guardar_pedido.php",
        type:'post',
        data: 'header='+sessionStorage.getItem('header')+'&data='+sessionStorage.getItem('item'),
        success: function(result){
            $("#main-content").html(result);
            sessionStorage.removeItem('item');
            $("#cart-btn").click();
            fillCart();
        }
    });
    $('#cliente').empty();

  });

</script>
</body>
</html>
