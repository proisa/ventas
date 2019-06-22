
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
  <aside class="control-sidebar control-sidebar-dark" id="carrito" style="padding: 60px 10px 0px;">
    <table class="table" style="color:#fff">
    <thead>
      <th width="20%">Cant.</th>
      <th>Desc.</th>
      <th width="30%">Precio</th>
      <th class="text-right"></th>
    </thead>
    <tbody id="tabla-carrito">
    
    </tbody>
    <tfoot>
      <tr>
        <td>Total</td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tfoot>
    </table>
  </aside>
  <!-- /.control-sidebar -->


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>


<a href="#" id="cart-btn" class="btn btn-success">
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
<script src="<?=url_base()?>/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?=url_base()?>/dist/js/demo.js"></script>


<script>
  $(document).ready(function(){
    fillCart();
  });



  function fillCart(){
    $("#tabla-carrito").empty();
    var data = JSON.parse(localStorage.getItem('item'));
    $.each(data, function(key,value){
        lista = '';
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
        </td>
        <td>${value.precio}</td>
        <td><a class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a></td>
        </tr>`;
        $("#tabla-carrito").append(template);
        console.log(value.id);
    });
}
</script>
</body>
</html>
