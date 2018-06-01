<?php
if($_POST) {

    $to_Email = "info@cubesquad.org"; // Write your email here

    // Use PHP To Detect An Ajax Request
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {

        // Exit script for the JSON data
        $output = json_encode(
        array(
            'type'=> 'error',
            'text' => 'Request must come from Ajax'
        ));

        die($output);
    }

    // Checking if the $_POST vars well provided, Exit if there is one missing
    if(!isset($_POST["userName"]) || !isset($_POST["userEmail"]) || !isset($_POST["userSubject"]) || !isset($_POST["userMessage"])) {

        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Los campos están vacíos!'));
        die($output);
    }

    // PHP validation for the fields required
    if(empty($_POST["userName"])) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Lo sentimos, pero tu nombre es muy corto o no fue especificado.'));
        die($output);
    }

    if(!filter_var($_POST["userEmail"], FILTER_VALIDATE_EMAIL)) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Por favor, ingresa un e-mail válido.'));
        die($output);
    }

    // To avoid the spammy bots, you can change the value of the minimum characters required. Here it's <20
    if(strlen($_POST["userMessage"])<20) {
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Eso es muy corto! Tómate tu tiempo y escríbenos un poco más.'));
        die($output);
    }

    // Proceed with PHP email
    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
    $headers .= 'From: My website' . "\r\n";
    $headers .= 'Reply-To: '.$_POST["userEmail"]."\r\n";

    'X-Mailer: PHP/' . phpversion();

    // Body of the Email received in your Mailbox
    $emailcontent = 'Hey! has recibido un nuevo mensaje de  <strong>'.$_POST["userName"].'</strong><br/><br/>'. "\r\n" .
                'El mensaje es: <br/> <em>'.$_POST["userMessage"].'</em><br/><br/>'. "\r\n" .
                '<strong> Recuerda contactárlo '.$_POST["userName"].' via email al : '.$_POST["userEmail"].'</strong>' . "\r\n" ;

    $Mailsending = @mail($to_Email, $_POST["userSubject"], $emailcontent, $headers);

    if(!$Mailsending) {

        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => '<i class="icon ion-close-round"></i> Oops! algo salió mal, revisa tu configuración.'));
        die($output);

    } else {
        $output = json_encode(array('type'=>'message', 'text' => '<i class="icon ion-checkmark-round"></i> Hello '.$_POST["userName"] .', Tu mensaje ha sido enviado, nos comunicaremos contigo asap!'));
        die($output);
    }
}
?>
