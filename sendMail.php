<?php
include_once('SendMailSmtpClass.php');

function Notification(string $newData): void
{
    $host = 'ssl://smtp.mail.ru';
    $port = 465;
    $username = 'desimo123@mail.ru';
    $password = 'fAqgGbdxjLpHcUWCcsVP';
    $smtpFrom = 'desimo123@mail.ru';

    $mail = new SendMailSmtpClass($username, $password, $host, $smtpFrom, $port);

    $mailTo = 'desimo123@yandex.ru';
    $subject = 'бронирование на ';
    $subject .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $headers = "From: desimo123@mail.ru\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    $mail->send($mailTo, $subject, $newData, $headers);
}

