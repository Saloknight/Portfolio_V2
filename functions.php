<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';



/**
 * Va chercher la configuration du site
 * @param $key [?string] propriété à aller chercher DEFAULT null
 * 
 * @return [string|array|int] valeur
 * 
 */
function getConfig(?string $key=null): string|array|int{
    $config = json_decode(file_get_contents('config.json'), true);
    return $key ? $config[$key] : $config;
}

/**
 * Va chercher l'url de la home
 * @return string
 */
function homeUrl(): string{
    $config = getConfig();
    return $config['base_url'].$config['base_folder'].'/';
}

/**
 * Va dans le dossier "Views" chercher une page, l'affiche.
 * @param $pageName [string] Nom du fichier à récupérer
 * @return void affiche le HTML demandé
 */
function getPage(string $pageName):void{

    getController($pageName, function($p) use ($pageName){
         getComposant('header.php', ['title'=>$p['title']]);
         require 'Views/'.$pageName;
         getComposant('footer.php', ['title'=>$p['title']]);
    });
       
}



function getComposant(string $composantName, array $params = []):void{
    $path = getConfig('components_folder');
    if(file_exists($path.$composantName)){
        $fields = $params;
        require $path.$composantName;
    }
}


function getController(string $componentName, ?callable $fn = null):void{
    $path = getConfig('controllers_folder');
    if(file_exists($path.$componentName)){
        if(!$fn) require $path.$componentName;
            else{
                $d = require $path.$componentName;
                $fn($d);
            }
    }

}


function redirectTo(?string $url = null ):never{
    $home = homeUrl();
    header('location: '.($url ?  $home.$url : $home) );
    exit;
}

function ifLoggedThenElse(callable $elemToDisplayIfLogged, ?callable $elemToDisplayIfNotLogged = null):void{
    if( (new User())->is_user_logged_in() ){
        $elemToDisplayIfLogged();
    }else if( $elemToDisplayIfNotLogged){
        $elemToDisplayIfNotLogged();
    }
}


function logout(){
    unset($_SESSION['user']);
}



function sendmsg() {
    if (!isset($_POST['message'], $_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $h = fn($k) => htmlspecialchars($_POST[$k] ?? '', ENT_QUOTES, 'UTF-8');

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'mail.infomaniak.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USER');
        $mail->Password   = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('contact@nicolas-martins.fr', 'Portfolio');
        $mail->addAddress('nicolas.martins1912@gmail.com');
        $mail->addReplyTo($_POST['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Envoi depuis page Contact';
        $mail->Body = '<h1>Message envoyé depuis la page Contact du Portfolio</h1>
            <p><b>Nom : </b>' . $h('nom') . '<br>
            <b>Prénom : </b>' . $h('prenom') . '<br>
            <b>Email : </b>' . $h('email') . '<br>
            <b>Téléphone : </b>' . $h('telephone') . '<br>
            <b>Objet : </b>' . $h('objet') . '<br>
            <b>Message : </b>' . nl2br($h('message')) . '</p>';

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mail error: ' . $mail->ErrorInfo);
        return false;
    }
}
