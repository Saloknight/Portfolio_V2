<?php

session_start();
require_once __DIR__ . '/../src/functions.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    exit('Méthode non autorisée.');
}

$_SESSION['contact_status'] = sendmsg() ? 'sent' : 'failed';

header('Location: index.php#Contact', true, 303);
exit;
