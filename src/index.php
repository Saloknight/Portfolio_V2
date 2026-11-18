<?php
  session_start();
  require 'functions.php';
	

    $baseFolder = getConfig('base_folder');
	switch (explode('?',$_SERVER['REQUEST_URI'])[0]) {
		case $baseFolder.'/':
			getPage('acceuil.php');
			break;

		
		default:
			header("HTTP/1.0 404 Not Found");
			getComposant('404.php');
			break;
	}