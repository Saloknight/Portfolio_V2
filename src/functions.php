<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';

function env_var(string $key): string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return is_string($value) ? trim($value) : '';
}

function sendmsg(): bool
{
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !empty($_POST['website'])) {
        return false;
    }

    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $objet = trim($_POST['objet'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (
        $nom === ''
        || $prenom === ''
        || $objet === ''
        || $message === ''
        || empty($_POST['consent'])
        || !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        return false;
    }

    $user = env_var('SMTP_USER');
    $password = env_var('SMTP_PASS');
    if ($user === '' || $password === '') {
        error_log('sendmsg: SMTP_USER ou SMTP_PASS vide');
        return false;
    }

    $escape = static fn(string $value): string => htmlspecialchars(
        $value,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.infomaniak.com';
        $mail->SMTPAuth = true;
        $mail->Username = $user;
        $mail->Password = $password;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 15;

        $mail->setFrom($user, 'Portfolio Nicolas Martins');
        $mail->addAddress('nicolas.martins1912@gmail.com');
        $mail->addReplyTo($email, $prenom . ' ' . $nom);
        $mail->isHTML(true);
        $mail->Subject = 'Portfolio : ' . $objet;
        $mail->Body =
            '<h1>Message envoyé depuis le portfolio</h1>' .
            '<p><strong>Nom :</strong> ' . $escape($nom) . '<br>' .
            '<strong>Prénom :</strong> ' . $escape($prenom) . '<br>' .
            '<strong>Email :</strong> ' . $escape($email) . '<br>' .
            '<strong>Téléphone :</strong> ' . $escape($telephone) . '<br>' .
            '<strong>Objet :</strong> ' . $escape($objet) . '</p>' .
            '<p><strong>Message :</strong><br>' . nl2br($escape($message)) . '</p>';
        $mail->AltBody =
            "Message envoyé depuis le portfolio\n\n" .
            "Nom : {$nom}\nPrénom : {$prenom}\nEmail : {$email}\n" .
            "Téléphone : {$telephone}\nObjet : {$objet}\n\n{$message}";

        $mail->send();
        return true;
    } catch (Exception $exception) {
        error_log('sendmsg: erreur PHPMailer : ' . $mail->ErrorInfo);
        return false;
    }
}

function contact_status_markup(): string
{
    $status = $_SESSION['contact_status'] ?? null;
    unset($_SESSION['contact_status']);

    if ($status === 'sent') {
        return '<p role="status">Votre message a bien été envoyé.</p>';
    }

    if ($status === 'failed') {
        return '<p role="alert">L’envoi a échoué. Vérifiez les champs ou réessayez plus tard.</p>';
    }

    return '';
}
