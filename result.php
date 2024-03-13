<?php
include_once('readWriteCSV.php');
include_once('sources.php');
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
        <p class="result">Дата забронирована</p>
        <button class="result resBtn">
            <a href="index.php" class="button beer-button-blue">бронировать ещё</a>
        </button>
        <?php
        if ($data = Read($dataPath)) {
            echo '<table>';
            foreach ($data as $line) {
                echo '<tr>';
                foreach ($line as $cel) {
                    echo '<td>' . $cel . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
    </div>
</body>
</html>