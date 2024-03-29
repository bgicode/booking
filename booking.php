<?php
declare(strict_types = 1);

include_once('readWriteCSV.php');
include_once('sources.php');
include_once('helpers.php');
include_once('sendMail.php');

/**
 * @param string $firstDate дата бронирования от
 * @param string $secondDate дата бронирования до
 * @param string $massage сообщение об ошибке
 * @param array $arBookingDate новая дата или период бронирования в формате дд.мм.ггг-дд.мм.ггг
 * @param array $arBookedPeriods все забронированные даты и промежутки
 * @param array $arBookedDates все забронированные даты
 * @param array $arNewEntry новая запись
 */ 

$error = false;

if ($_POST['booking']) {

    // ___________получение новой даты в нужном формате_____________

    // пребразование полученной даты из амер-го формата в европейский
    $firstDate = DateConverter($_POST['firstDate']);
    if ($_POST['secondDate']
        and $_POST['secondDate'] != $_POST['firstDate']
    ) {
        $secondDate = DateConverter($_POST['secondDate']);

        // проверка корректности ввода периода
        if (strtotime($firstDate) > strtotime($secondDate)) {
            $error = true;
            $massage = "вторая дата должна быть позже первой";
        } elseif (isset($secondDate)) {
            $arBookingDate[] = $firstDate;
            $arBookingDate[] = $secondDate;
        }
    } elseif (isset($firstDate)) {
        $arBookingDate[] = $firstDate;
    }

    // ___________конец получение новой даты в нужном формате_____________

    // ___________получение списка заббронированных дат из базы__________

    if ($error == false) {
        $arBookedPeriods = Read($dataPath);
        $arBookedDates = SmoothArr($arBookedPeriods, 'DateSpliter');
    }

    // ___________конец получение списка забронированных дат из базы__________

    //_____________запись новых дат в базу__________________

    if (isset($arBookingDate)
        and !$error
    ) {
        // полученте всех дат введённого нового периода
        if (count($arBookingDate) == 2) {
            $arNewPeriodDetail = DateSpliter($arBookingDate);

            foreach ($arNewPeriodDetail as $periodDate) {
                $arBookingPeriod[] = $periodDate;
            }
        } else {
            $arBookingPeriod[] = $arBookingDate[0];
        }

         // надождение пересечений новых дат со старыми
        if (empty(array_intersect($arBookedDates, $arBookingPeriod))) {
            // бронирующий
            $arNewEntry[] = $_POST['name'];
            // новая строка в файл
            $arNewEntry[] = $arBookingDate[0];

            if ($arBookingDate[1]) {
                // новая строка в файл, если это период
                $arNewEntry[] = $arBookingDate[1];
            }

            if (!empty( $arNewEntry)) {
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    Write($dataPath, $arNewEntry);

                    $massage = Massage($arNewEntry);

                    Notification($massage);

                    header('Location: result.php');

                    exit;
                }
            }
        } else {
            $error = true;
            $massage = "дата или период заняты";
        }
    }

    //_____________конец записи новых дат в базу__________________

}
