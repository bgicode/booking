<?php
function Notification(string $newData): bool
{
    $url = $_SERVER['HTTP_HOST'];
    $mailTo = 'desimo123@yandex.ru';
    $subject = 'бронирование на ';
    $subject .= $url;
    $headers = "From: test@cr25559.tw1.ru\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";

    return mail($mailTo, $subject, $newData, $headers);
}