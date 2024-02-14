<?php
$flagMassage = false;
$firstDate; // дата бронирования от
$secondDate; // дата бронирования до
$massage; // сообщение об ошибке
$bookingDate; //новая дата или период бронирования в формате дд.мм.ггг-дд.мм.ггг
$arBookedPeriods = []; // все забронированные даты и промежутки
$arBookedDates = []; // все забронированные даты
$arNewEntry = []; //новая запись

if ($_POST['booking']) {

    // ___________получение новой даты в нужном формате_____________
    $firstDate = explode('-', $_POST['firstDate']);
    $firstDate = array_reverse($firstDate);
    $firstDate = implode('.', $firstDate); // цепочка пребразование полученной даты из амер-го формата в европейский
    
    if ($_POST['secondDate']) {

        $secondDate = explode('-', $_POST['secondDate']);
        $secondDate = array_reverse($secondDate);
        $secondDate = implode('.', $secondDate);

        if (strtotime($firstDate) > strtotime($secondDate)) { //проверка корректности ввода периода
            $flagMassage = true;
            $massage = "вторая дата должна быть позже первой";
        } elseif (isset($secondDate)) {
            $bookingDate = $firstDate . '-' . $secondDate; //формироания периода в заданном формате
        }
    } elseif (isset($firstDate)) {
        $bookingDate = $firstDate;
    }
    // ___________конец получение новой даты в нужном формате_____________

    // ___________получение списка заббронированных дат из базы__________
    if (($file = fopen('date.csv', 'r')) !== false
        and $flagMassage == false
    ) {
        while (($data = fgetcsv($file, 1000, ';')) !== false) {
            $arBookedPeriods[] = $data[1]; // чтение и запись забронированных дат в служебный массив
        }

        foreach ($arBookedPeriods as $dates) { // создание списка всех уже забронированых дат
            if (strpos($dates, '-')) { // если период то получаем все даты из этого периода
                $arOnePeriod = explode('-', $dates);
                $start = strtotime($arOnePeriod[0]);
                $end = strtotime($arOnePeriod[1]);
                $arPeriodDetail = array();
                while ($start <= $end) {
                    $arPeriodDetail[] = date('d.m.Y', $start);
                    $start = strtotime('+1 day', $start);
                }
                foreach ($arPeriodDetail as $periodDate) {
                    $arBookedDates[] = $periodDate;
                }
            } else {
                $arBookedDates[] = $dates;
            }
        }
    }
    fclose($file);
    // ___________конец получение списка забронированных дат из базы__________

    //_____________запись новых дат в базу__________________
    if (isset($bookingDate)) {
        if (strpos($bookingDate, '-')) { //полученте всех дат введённого нового периода

            $arNewBookingPeriod = explode('-', $bookingDate);
            $newStart = strtotime($arNewBookingPeriod[0]);
            $newEnd = strtotime($arNewBookingPeriod[1]);
            $arNewPeriodDetail = array();
    
            while ($newStart <= $newEnd) {
                $arNewPeriodDetail[] = date('d.m.Y', $newStart);
                $newStart = strtotime('+1 day', $newStart);
            }
            foreach ($arNewPeriodDetail as $periodDate) {
                $arBookingPeriod[] = $periodDate;
            }
        } else {
            $arBookingPeriod[] = $bookingDate;
        }
        if (empty(array_intersect($arBookedDates, $arBookingPeriod))) { //надождение пересечений новых дат со старыми
            $arNewEntry[] = $_POST['name']; //бронирующий
            $arNewEntry[] = $bookingDate; //новая строка в файл
            if (!empty( $arNewEntry)) {
                $file = fopen('date.csv', 'a');
                fputcsv($file, $arNewEntry, ';');
                fclose($file);
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    header('Location: result.php');
                    exit;
                }
            }
        } else {
            $flagMassage = true;
            $massage = "дата или период заняты";
        }
    }
    //_____________конец записи новых дат в базу__________________
}
