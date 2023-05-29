<?php

include 'components/connect.php';
session_start();

//Generando clave aleatoria
$logitudPass = 4;
$miPassword  = substr( md5(microtime()), 1, $logitudPass);
$clave       = sha1($miPassword);
var_dump($clave);


$correo             = trim($_REQUEST['email']); //Quitamos algun espacion en blanco
//var_dump($_REQUEST['email']);
$consulta           = $conn->prepare("SELECT * FROM users WHERE email ='".$correo."'");
$consulta->execute();
//$queryconsulta      = mysqli_query($conn, $consulta);
$cantidadConsulta   = $consulta->rowCount();
$dataConsulta       = $consulta->fetch(PDO::FETCH_ASSOC);
// var_dump($dataConsulta);
// var_dump($cantidadConsulta);
// die();

if($cantidadConsulta ==0){ 
    
    $_SESSION['message'] = "<script>
    Swal.fire({
        position: 'top-end',
        icon: 'error',
        title: '¡El correo no existe!',
        showConfirmButton: false,
        timer: 3500
    });
</script>";
  header("Location:home.php");
    
}else{
$updateClave    =$conn->prepare("UPDATE users SET password='$clave' WHERE email='".$correo."' ");
$updateClave->execute();
//$queryResult    = mysqli_query($conn,$updateClave); 

$destinatario = $correo; 
$asunto       = "Recuperando Clave - agua la Colpa";
$cuerpo = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
    <title>Recuperar Clave de Usuario</title>';
$cuerpo .= ' 
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: "Roboto", sans-serif;
        font-size: 16px;
        font-weight: 300;
        color: #888;
        background-color:rgba(230, 225, 225, 0.5);
        line-height: 30px;
        text-align: center;
    }
    .contenedor{
        width: 80%;
        min-height:auto;
        text-align: center;
        margin: 0 auto;
        background: #ececec;
        border-top: 3px solid #E64A19;
    }
    .btnlink{
        padding:15px 30px;
        text-align:center;
        background-color:#cecece;
        color: crimson !important;
        font-weight: 600;
        text-decoration: blue;
    }
    .btnlink:hover{
        color: #fff !important;
    }
    .imgBanner{
        width:100%;
        margin-left: auto;
        margin-right: auto;
        display: block;
        padding:0px;
    }
    .misection{
        color: #34495e;
        margin: 4% 10% 2%;
        text-align: center;
        font-family: sans-serif;
    }
    .mt-5{
        margin-top:50px;
    }
    .mb-5{
        margin-bottom:50px;
    }
    </style>
';

$cuerpo .= '
</head>
<body>
    <div class="contenedor">
    
        <p>&nbsp;</p>
        <p>&nbsp;</p>
    <table style="max-width: 600px; padding: 10px; margin:0 auto; border-collapse: collapse;">
    <tr>
        
    </tr>
    
    <tr>
        <td style="background-color: #ffffff;">
            <div class="misection">
                <h2 style="color: red; margin: 0 0 7px">Hola, '.$dataConsulta['name'].'</h2>
                <p style="margin: 2px; font-size: 18px">te hemos creado una nueva clave temporal para que puedas iniciar sesión, la clave temporal es: <strong>'.$miPassword.'</strong> </p>
                <p>&nbsp;</p>
                <p>&nbsp;</p>
                
                <p>&nbsp;</p>
                 <p>&nbsp;</p>
                
                <p>&nbsp;</p>
            </div>
        </td>
    </tr>
    <tr>
        <td style="background-color: #ffffff;">
        <div class="misection">
            
           
        </div>
        
        <div class="mb-5 misection">  
          <p>&nbsp;</p>
           
        </div>
        </td>
    </tr>
    <tr>
        <td style="padding: 0;">
           
        </td>
    </tr>
</table>'; 

$cuerpo .= '
      </div>
    </body>
  </html>';
    
    $headers  = "MIME-Version: 1.0\r\n"; 
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
    $headers .= "From: agua la Colpa\r\n"; 
    $headers .= "Reply-To: "; 
    $headers .= "Return-path:"; 
    $headers .= "Cc:"; 
    $headers .= "Bcc:"; 
    // ini_set('SMTP', '587');
    // ini_set('smtp_port', '25');
    //(mail($destinatario,$asunto,$cuerpo,$headers));
    if (mail($destinatario,$asunto,$cuerpo,$headers)) {
        
        $_SESSION['message'] = "<script>
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: '¡el corre fue enviado!',
            showConfirmButton: false,
            timer: 3500
        });
    </script>";
        header("Location:home.php");
    } else {
        echo 'Error al enviar el correo';
    }
    
    
}

?>


