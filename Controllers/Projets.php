<?php

$Projets = json_decode(file_get_contents('src/Projets-bdd.json'), true);




    
    

    ob_start();
    for ($i=0; $i < count($Projets) ; $i++) { 
       getComposant('card-1.php', [
            'title'=>$Projets[$i]['title'],
            'content'=>$Projets[$i]['body'],
            'img'=>$Projets[$i]['img'],
            'btn'=>$Projets[$i]['btn'],
            'url'=>$Projets[$i]['url']
        ]);
    }

    $cards = ob_get_clean();



return[
    'title' => 'Projets',
    'Projets' =>$cards
];