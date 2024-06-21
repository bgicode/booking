<?php

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=booking',
        'root',
        'root',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $e) {
    echo "Невозможно установить соединение с базой данных";
}

function Read(string $table, $pdo, $query = false): mixed
{
    try {
        if ($query == false) {
            $query = "SELECT * FROM $table";
        }
        $tab = $pdo->query($query);
        $arReadingLines = $tab->fetchAll(PDO::FETCH_NUM);
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }
    
    return $arReadingLines;
}

function Write(array $arPrepParams, $query, $pdo): mixed
{
    try {
        $write = $pdo->prepare($query);
        $write->execute($arPrepParams);
    } catch (PDOException $e) {
        echo "Ошибка выполнения запроса: " . $e->getMessage();
    }

    return $write;
}
