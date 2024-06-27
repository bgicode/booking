<?php
include_once('SendMailSmtpClass.php');

function Notification(string $newData, $mail): void
{
    $uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
    $url = $_SERVER['HTTP_HOST'] . $uri;

    $mailTo = 'chack@example.com';
    $subject = 'бронирование на ';
    $subject .= $url;
    $headers = "From: elvis@example.com\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    $mail->send($mailTo, $subject, $newData, $headers);
}

