<?php
require_once('booking.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="./script.js" type="text/javascript"></script>
</head>
<body>
    <div class="wraper">
        <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="post">
            <label>Имя</label>
            <input class="input" type="text" name="name" required />
            <label>Дата бронирования
                <span style="margin-left: 10px;">от</span>
                <input class="input" type="date" name="firstDate" required />
                <span>до</span>
                <input class="input" id="secondDate" type="date" name="secondDate" />
            </label>
            <div class="input" style="text-align: right;">
                <span>забронировать период</span>
                <input id="checkbox" type="checkbox" name="checkbox" />
            </div>
            <input class="input" type="submit" name="booking" value="Забронировать">
            <?php
            if ($error) {
                echo '<p class="massage">' . $massage . '</p>';
            }
            ?>
        </form>
        <div>
            <div class="listTitle">Список бронирования</div>
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
    </div>
</body>
</html>
