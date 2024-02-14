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
            <a href="/index.php" class="button beer-button-blue">бронировать ещё</a>
        </button>
        <?php
        if (($file = fopen('date.csv', 'r')) !== false) {
            echo '<table>';
            while (($data = fgetcsv($file, 1000, ';')) !== false) {
                echo '<tr>';
                foreach ($data as $cel) {
                    echo '<td>' . $cel . '</td>'; 
                }
                echo '</tr>'; 
            }
            echo '</table>';
            fclose($file);
        }
        ?>
    </div>
</body>
</html>