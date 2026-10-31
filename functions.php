<?php



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


function sendmsg(){

    if (isset($_POST['message']) and filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $entete  = 'MIME-Version: 1.0' . "\r\n";
        $entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $entete .= 'From: contact@nicolas-martins.mds-caen.yt' . "\r\n";
        $entete .= 'Reply-to: ' . $_POST['email'];

        $message = '<h1>Message envoyé depuis la page Contact du Portfolio</h1>
        <p><b>Nom : </b>' . $_POST['nom'] . '<br>
        <p><b>Prenom : </b>' . $_POST['prenom'] . '<br>
        <p><b>Email : </b>' . $_POST['email'] . '<br>
        <p><b>Telephone : </b>' . $_POST['telephone'] . '<br>
        <p><b>Objet : </b>' . $_POST['objet'] . '<br>
        <b>Message : </b>' . htmlspecialchars($_POST['message']) . '</p>';

        $retour = mail('nicolas.martins1912@gmail.com', 'Envoi depuis page Contact', $message, $entete);
        
    }


}

