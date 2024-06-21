<?php
include_once('SendMailSmtpClass.php');

function Notification(string $newData, $mail): void
{
    // $host = 'smtp.mailsnag.com';
    // $port = 2525;
    // $username = 'TtKDlDkv3dE2';
    // $password = 'PwwLiM8iVzZp';
    // $smtpFrom = 'elvis@example.com';
    // $mail = new SendMailSmtpClass($username, $password, $host, $smtpFrom, $port);

    $uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
    $url = $_SERVER['HTTP_HOST'] . $uri;

    $mailTo = 'chack@example.com';
    $subject = 'бронирование на ';
    $subject .= $url;
    $headers = "From: elvis@example.com\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    $mail->send($mailTo, $subject, $newData, $headers);
}

