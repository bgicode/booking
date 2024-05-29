<?php
session_start();
include_once('readWriteCSV.php');
include_once('sources.php');
include_once('helpers.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
    <div class="resWraper">
        <p class="result">
            <?php
            if ($_SESSION['result'] == 'booking') {
                echo 'Дата забронирована';
            } elseif ($_SESSION['result'] == 'change') {
                echo 'Имя измененно';
            }
            ?>
        </p>
        <button class="result resBtn">
            <a href="index.php" class="button beer-button-blue">НАЗАД</a>
        </button>
        <table>
            <tr>
                <td><strong>Имя</strong></td>
                <td><strong>Дата заселения</strong></td>
                <td><strong>Дата выезда</strong></td>
            </tr>
            <?php
            if (($arData = Read($dataPath))
                && ($arDataCust = Read($dataPathCustomers))
            ) {
                joinTable($arData, $arDataCust);
            }
            ?>
        </table>
    </div>
</body>
</html>