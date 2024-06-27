<?php
function redirect(string $extra): void
{
    $host = $_SERVER['HTTP_HOST'];
    $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    
    header("Location: http://$host$uri/$extra");
}

function DateConverter(string $date): string
{
    return implode('.', array_reverse(explode('-', $date)));
}

function DateSpliter(array $period): array
{
    $start = strtotime($period[0]);
    $end = strtotime($period[1]);
    $arPeriodDates = [];

    while ($start <= $end) {
        $arPeriodDates[] = date('d.m.Y', $start);
        $start = strtotime('+1 day', $start);
    }

    return $arPeriodDates;
}

function SmoothArr(mixed $arMulti, callable $calbackFunc): mixed
{
    $arAllMono = [];

    // создание списка всех уже забронированых дат
    foreach ($arMulti as $arMomo) {
        $arMomo =  array_slice($arMomo, 2);

        // если период то получаем все даты из этого периода
        if ($arMomo[1]) {
            $arAllMono = array_merge($arAllMono, $calbackFunc($arMomo));
        } else {
            $arAllMono[] = $arMomo[0];
        }
    }
    return $arAllMono;
}

function Message(array $arData, $customer): string
{
    // $uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
    $url = $_SERVER['HTTP_HOST'];

    if (isset($arData[1])) {
        return "<html><body><strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" . $customer . "<br>забронировал<br> от " . $arData[0] . ' до ' . $arData[1] . "</body></html>";
    } else {
        return "<html><body><strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" .$customer . "<br>забронировал<br>на " . $arData[0] . "</body></html>";
    }
}

function getTable(array $arTable): void
{
    foreach ($arTable as $line) {
        echo '<tr>';
            echo '<td>' . $line[0] . '</td>';
            echo '<td>' . $line[1] . '</td>';
            echo '<td>' . $line[2] . '</td>';
        echo '</tr>';
    }
}