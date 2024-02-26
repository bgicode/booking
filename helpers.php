<?php
function DateConverter(string $date): string {
    return implode('.', array_reverse(explode('-', $date)));
}

function DateSpliter(array $period): array {
    $start = strtotime($period[0]);
    $end = strtotime($period[1]);
    $arPeriodDates = [];
    while ($start <= $end) {
        $arPeriodDates[] = date('d.m.Y', $start);
        $start = strtotime('+1 day', $start);
    }
    return $arPeriodDates;
}

function SmoothArr(array $arMulti, callable $calbackFunc): array {
    $arAllMono = [];

    foreach ($arMulti as $arMomo) { // создание списка всех уже забронированых дат
        array_shift($arMomo);

        if ($arMomo[1]) { // если период то получаем все даты из этого периода
            $arAllMono = array_merge($arAllMono, $calbackFunc($arMomo));
        } else {
            $arAllMono[] = $arMomo[0];
        }
    }
    return $arAllMono;
}