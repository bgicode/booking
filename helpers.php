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

function SmoothArr(array $arMulti, callable $calbackFunc): array
{
    $arAllMono = [];

    // создание списка всех уже забронированых дат
    foreach ($arMulti as $arMomo) {
        array_shift($arMomo);

        // если период то получаем все даты из этого периода
        if ($arMomo[1]) {
            $arAllMono = array_merge($arAllMono, $calbackFunc($arMomo));
        } else {
            $arAllMono[] = $arMomo[0];
        }
    }

    return $arAllMono;
}

function Message(array $arData): string
{
    // $uri = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']);
    $url = $_SERVER['HTTP_HOST'];

    if (isset($arData[2])) {
        return "<strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" . $arData[0] . "<br>забронировал от " . $arData[1] . ' до ' . $arData[2];
    } else {
        return "<strong>Новое бронирование на сайте: " . $url . "</strong><br><br>" . $arData[0] . "<br>забронировал на " . $arData[1];
    }
}
