<?php
session_start();
include_once('readWriteSQL.php');
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
                // echo '<pre>';
                // print_r($_SESSION['ult']);
                // echo '</pre>';
            } elseif ($_SESSION['result'] == 'change') {
                echo "Имя измененно";
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
            $query = "SELECT name, date_entry, date_exit
                    FROM guests JOIN booking_list
                    ON guests.id = booking_list.name_id;";
            // $arNameDate = Read('guests', $pdo, $query);
            if (!empty($arNameDate = Read('guests', $pdo, $query))) {
                getTable($arNameDate);
            }
            // if (($arData = Read('booking_list', $pdo))
            //     && ($arDataCust = Read('guests', $pdo))
            // ) {
            //     joinTable($arData, $arDataCust);
            // }
            ?>
        </table>
    </div>
</body>
</html>