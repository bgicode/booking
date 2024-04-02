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
    if (isset($arData[2])) {
        return "Новое бронирование на сайте: ". $_SERVER['HTTP_HOST'] . "\r\n\r\n" . $arData[0] . "\r\nзабронировал от\r\n" . $arData[1] . ' до ' . $arData[2];
    } else {
        return "Новое бронирование на сайте: ". $_SERVER['HTTP_HOST'] . "\r\n\r\n" . $arData[0] . "\r\nзабронировал на " . $arData[1];
    }
}
