<?php 
require('../inc/conexion.php');
require('../inc/funciones.php');
require('../clases/Client.php');
require('../vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dompdf\Dompdf;
use Dompdf\Options;

$clientes = new Client($pdo);
// Crear
if(isset($_POST['accion']) && $_POST['accion'] == 'agregar'){
    //print_pre($_POST);
    //print_pre($_FILES);
    $ruta = '../uploads/';
    $ext = pathinfo($_FILES['foto_cliente']['name'], PATHINFO_EXTENSION);
    $fichero_subido = $ruta.md5($_FILES['foto_cliente']['name'].time()).'.'.$ext;
    move_uploaded_file($_FILES['foto_cliente']['tmp_name'], $fichero_subido);
    
    $_POST['foto'] = $fichero_subido;
    if($clientes->create($_POST)){
        header('Location: ../paginas/listado_clientes.php?msg=successfully');
    }else{
        header("Location: ../paginas/listado_clientes.php?msg=error&errodb={$clientes->error}");
    }
}

if(isset($_POST['accion']) && $_POST['accion'] == 'editar'){

    if($_FILES['foto_cliente']['error'] == 0){
        $ruta = '../uploads/';
        $ext = pathinfo($_FILES['foto_cliente']['name'], PATHINFO_EXTENSION);
        $fichero_subido = $ruta.md5($_FILES['foto_cliente']['name'].time()).'.'.$ext;
        move_uploaded_file($_FILES['foto_cliente']['tmp_name'], $fichero_subido);
        $_POST['foto'] = $fichero_subido;
    }else{
        $_POST['foto'] = $_POST['foto_cliente'];
    }
    //print_pre($_POST);
    //print_pre($_FILES);

    //exit();
    if($clientes->edit($_POST)){
        header('Location: ../paginas/listado_clientes.php?msg=successfully');
    }else{
        header('Location: ../paginas/listado_clientes.php?msg=error');
    }
}

if(isset($_GET['accion']) && $_GET['accion'] == 'eliminar'){
    if($clientes->delete($_GET['codigo'])){
        header('Location: ../paginas/listado_clientes.php?msg=successfully');
    }else{
        header('Location: ../paginas/listado_clientes.php?msg=error');
    }
}

if(isset($_GET['accion']) && $_GET['accion'] == 'enviar_reporte'){
    $codigo = trim($_GET['codigo']);

    $data_cliente =  $clientes->getClient($codigo);


    // $file =  file_get_contents('paginas/registro.html',true);

    // $file = str_replace('Correo','Mail',$file);

    // echo $file;
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    $cliente = 'Junior Suarez';
    $mensaje = 'Recuerde pagar su cuenta a tiempo';
    $balance = 'RD$'.number_format(120000,2);
    // Load Composer's autoloader
    $template =  file_get_contents('../email_templates/main.html',true);
    $template = str_replace('{{cliente}}',$cliente,$template);
    $template = str_replace('{{cuerpo}}',$mensaje,$template);
    $template = str_replace('{{balance}}',$balance,$template);

    $reporte_estado_cuenta = file_get_contents(url_base()."/reportes/estado_cuenta_mail.php?codigo={$codigo}",true);
    // Instantiation and passing `true` enables exceptions
    // PDF
    // reference the Dompdf namespace

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    // instantiate and use the dompdf class
    $dompdf = new Dompdf($options);
    $dompdf->set_base_path('./assets/css/');
    $dompdf->loadHtml($reporte_estado_cuenta);
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('letter', 'landscape');
    // Render the HTML as PDF
    $dompdf->render();


    $pdf ="../files/".str_replace(' ','_',trim($data_cliente['CL_NOMBRE'])).".pdf";
    file_put_contents($pdf, $dompdf->output());
    // Output the generated PDF to Browser
    //$dompdf->stream();
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';  // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'proisasoft@gmail.com';                     // SMTP username
        $mail->Password   = 'pr0i$$a1068';                               // SMTP password
        $mail->SMTPSecure = 'ssl';                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('proisasoft@gmail.com', 'Proisa');
        $mail->addAddress(trim($data_cliente['CL_EMAIL']),trim( $data_cliente['CL_NOMBRE']));     // Add a recipient
        //$mail->addAddress('wilrafa@hotmail.com', 'Wilson');     // Add a recipient
        // Attachments
        $mail->addAttachment($pdf);         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Estado de cuenta';
        $mail->Body    = $template;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

   // unlink($pdf);
}




