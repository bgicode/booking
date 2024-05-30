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

            <div class="input" style="text-align: right;">
            <span>Изменить имя/Забронировать</span>
            </div> 
            <div class="switchWrap">
                <label class="switch" for="checkboxName" >
                        <input id="checkboxName" type="checkbox" class="switchInput" name="checkbox">
                        <div class="switchSliderOn"></div>
                        <div class="switchSliderOff"></div>
                </label>
            </div>

            <label class="booking">Имя</label>
            <input class="input booking bookingInput" type="text" name="name" />

            <div class="changeName">
                <label>Какое имя изменить</label>
                <select name="oldName">
                    <?php
                    if ($arDataCust = Read($dataPathCustomers)) {
                        foreach ($arDataCust as $arCustomers) {
                            echo '<option value="' . $arCustomers[0] . '">' . $arCustomers[1] . '</option>';
                        }
                    }
                    ?>
                </select>
                <label>Новое Имя</label>
                <input class="input newName" type="text" name="newName"/>
            </div>

            <div class="booking">
                <div class="dateWrap">
                    <div class="labelDate">Дата бронирования</div>
                    <div>
                        <span style="margin-left: 10px;">от</span>
                        <input class="input bookingInput" type="date" name="firstDate" />
                        <span>до</span>
                        <input class="input" id="secondDate" type="date" name="secondDate" />
                    </div>
                </div>
            </div>
            <div class="input checkboxDate booking" style="text-align: right;">
                <span>забронировать период</span>
                <input id="checkbox" type="checkbox" name="checkbox" />
            </div>
            <input class="input booking" type="submit" name="booking" value="Забронировать">
            <input class="input changeName" type="submit" name="changeName" value="Изменить имя">
            <?php
            if ($error) {
                echo '<p class="message">' . $message . '</p>';
            }
            ?>
        </form>
        <div>
            <div class="listTitle">Список бронирования</div>
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
    </div>
</body>
</html>
