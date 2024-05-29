<?php
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

function Message(array $arData, $arData2): string
{
    // $uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
    $url = $_SERVER['HTTP_HOST'];

    if (isset($arData[3])) {
        return "<html><body><strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" . $arData2[1] . "<br>забронировал<br> от " . $arData[2] . ' до ' . $arData[3] . "</body></html>";
    } else {
        return "<html><body><strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" . $arData2[1] . "<br>забронировал<br>на " . $arData[2] . "</body></html>";
    }
}

function joinTable(array $arTable1,array $arTable2): void
{
    $joinCell = function ($arTable2, $arOutput)
    {
        foreach ($arTable2 as $arEntry) {
            if ($arEntry[0] == $arOutput[1]){
                return $arEntry[1];
            }
        }
    };
    foreach ($arTable1 as $line) {
        echo '<tr>';
            echo '<td>' . $joinCell($arTable2, $line) . '</td>';
            echo '<td>' . $line[2] . '</td>';
            echo '<td>' . $line[3] . '</td>';
        echo '</tr>';
    } 
}