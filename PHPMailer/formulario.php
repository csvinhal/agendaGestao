<?php 
/*apenas dispara o envio da mensagem caso houver/existir $_POST['enviar']*/
if (isset($_REQUEST['enviar']))
{
/*
/**/ $destinatarios = 'cristiano@gestao.com.br';

/**/ $nomeDestinatario = 'Cristiano de Souza';

/**/ $usuario = 'no-reply@gestao.com.br';

/**/ $senha = 'noreply!@2014';


/*abaixo as veriaveis principais, que devem conter em seu formulario*/
$nomeRemetente = $_POST['nomeRemetente'];
$assunto = $_POST['assunto'];
$_POST['mensagem'] = nl2br('E-mail: '. $_POST['email'] ."

". $_POST['mensagem']);

$arquivo = basename($_FILES['arquivo']['name']);
$arquivo_tmp = $_FILES['arquivo']['tmp_name'];

$dir = 'C:/xampp/htdocs/agendaGestao/PHPMailer/upload/';

if(move_uploaded_file($arquivo_tmp, $dir.$arquivo)){
    /*********************************** A PARTIR DAQUI NAO ALTERAR ************************************/
    //include_once("class.phpmailer.php");
    include_once("PHPMailerAutoload.php");
    $To = $destinatarios;
    $Subject = $assunto;
    $Message = $_POST['mensagem'];

    //$Host = 'smtp.'.substr(strstr($usuario, '@'), 1);
    $Host = 'smtp.office365.com';
    $Username = $usuario;
    $Password = $senha;
    $Port = "587";

    $mail = new PHPMailer();
    $body = $Message;
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host = $Host; // SMTP server
    $mail->SMTPDebug = 0; // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth = true; // enable SMTP authentication
    $mail->Port = $Port; // set the SMTP port for the service server
    $mail->Username = $Username; // account username
    $mail->Password = $Password; // account password
    $mail->SMTPSecure = "tls";

    $mail->SetFrom($usuario, $nomeDestinatario);
    $mail->Subject = $Subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($To, "");
    $mail->addAttachment($dir.$arquivo); 


    if(!$mail->Send()) {
    $mensagemRetorno = 'Erro ao enviar e-mail: '. print($mail->ErrorInfo);
    } else {
    $mensagemRetorno = 'E-mail enviado com sucesso!';
    }

    unlink($dir.$arquivo);
} else {
    echo "N&atilde;o foi poss&iacute;vel anexar o arquivo!";
}
}
?>