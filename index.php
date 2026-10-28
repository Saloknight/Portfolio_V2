<?php
  session_start();
  require 'Models/crud.php';
  require 'functions.php';
	

    $baseFolder = getConfig('base_folder');
	switch (explode('?',$_SERVER['REQUEST_URI'])[0]) {
		case $baseFolder.'/':
			getPage('acceuil.php');
			break;

			case $baseFolder.'/Projets':
			getPage('Projets.php');
			break;

			case $baseFolder.'/Profil':
			getPage('Profil.php');
			break;

			case $baseFolder.'/Competences':
			getPage('Competences.php');
			break;

			case $baseFolder.'/Contact':
			getPage('Contact.php');
			break;

			case $baseFolder.'/Politique':
			getPage('politique.php');
			break;

            case $baseFolder.'/cv':
			getComposant('cv.pdf');
			break;

		
		default:
			header("HTTP/1.0 404 Not Found");
			getComposant('404.php');
			break;
	}