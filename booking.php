<?php
session_start();

include_once('readWriteCSV.php');
include_once('sources.php');
include_once('helpers.php');
include_once('sendMail.php');

/**
 * @param string $firstDate дата бронирования от
 * @param string $secondDate дата бронирования до
 * @param string $message сообщение об ошибке
 * @param array $arBookingDate новая дата или период бронирования в формате дд.мм.ггг-дд.мм.ггг
 * @param array $arBookedPeriods все забронированные даты и промежутки
 * @param array $arBookedDates все забронированные даты
 * @param array $arNewEntry новая запись
 * @param int $bookingId ключ записи брони
 * @param array $arOldCustomers клиента которые бронированили
 * @param int $customerId ключ записи клиента
 * @param string $newName новое имя клиента
 * @param string $oldNameId ключ клиента
 */

$error = false;

//______________начало записи о бронировании_________________________
if ($_POST['booking']) {
    // ___________получение новой даты в нужном формате_____________

    // пребразование полученной даты из амер-го формата в европейский
    $firstDate = DateConverter($_POST['firstDate']);
    if ($_POST['secondDate']
        and $_POST['secondDate'] != $_POST['firstDate']
    ) {
        $secondDate = DateConverter($_POST['secondDate']);

        //__________проверка корректности ввода периода_____________
        if (strtotime($firstDate) > strtotime($secondDate)) {
            $error = true;
            $message = "вторая дата должна быть позже первой";
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
        // получение всех дат введённого нового периода
        if (count($arBookingDate) == 2) {
            $arNewPeriodDetail = DateSpliter($arBookingDate);

            foreach ($arNewPeriodDetail as $periodDate) {
                $arBookingPeriod[] = $periodDate;
            }
        } else {
            $arBookingPeriod[] = $arBookingDate[0];
        }

         // нахождение пересечений новых дат со старыми
        if (empty(array_intersect($arBookedDates, $arBookingPeriod))) {
            $bookingId = count((array)$arBookedPeriods) + 1;
            

            // бронирующий
            $arOldCustomers = Read($dataPathCustomers);
            $customerId = count((array)$arOldCustomers) + 1;

            //_____________поиск клиента в базе______________
            foreach ($arOldCustomers as $arOldCust) {
                if ($arOldCust[1] == $_POST['name']) {
                    $customerId = $arOldCust[0];
                    $isCustomerFind = true;
                    break;
                } else {
                    $isCustomerFind = false;
                }
            }

            // новая строка в файл с записями о клиентах
            if (!$isCustomerFind) {
                $arNewEntryCust[] = $customerId;
                $arNewEntryCust[] = $_POST['name'];
            }

            // новая строка в файл с записями о бронировании
            $arNewEntry[] = $bookingId;
            $arNewEntry[] = $customerId;
            $arNewEntry[] = $arBookingDate[0];

            if ($arBookingDate[1]) {
                // новая строка в файл, если это период
                $arNewEntry[] = $arBookingDate[1];
            }

            if (!empty($arNewEntry)) {
                
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                    try {
                        $writeBooking = Write($arNewEntry, $dataPath);
                        if (!$isCustomerFind) {
                            $writeCust = Write($arNewEntryCust, $dataPathCustomers);
                        } else {
                            $writeCust = true;
                        }

                        if ($writeBooking
                           && $writeCust
                        ) {

                            $message = Message($arNewEntry, $arNewEntryCust);
                            
                            $_SESSION['result'] = 'booking';

                            Notification($message);
                            header('Location: result.php');
                            exit;

                        } else {
                            $error = true;
                            $message = "Извините запись не произошла, попробуйте позже 2";
                        }
                    } catch (Throwable $e){
                        $error = true;
                        $message = $writeBooking . "Извините запись не произошла, попробуйте позже 3" . $writeCust;
                    }
                }
            }
        } else {
            $error = true;
            $message = "дата или период заняты";
        }
    }
    //_____________конец записи новых дат в базу__________________
}
//______________конец записи о бронировании_________________________

//______________начало перезаписи имени клиента_________________________
if ($_POST['changeName']) {
    if ($_POST['newName']
        && $_POST['oldName']
    ) {
        $newName = $_POST['newName'];
        $oldNameId = (int)$_POST['oldName'];

        //___________начало созадие массива и замена записи о клиенте в нём__________
        if ($arCustomers = Read($dataPathCustomers)) {
            foreach ($arCustomers as $key => $arCust) {
                if ($arCust[0] == $oldNameId) {
                    $arCustomers[$key][1] = $newName;
                    break;
                }
            }
        //___________конец созадие массива и замена записи о клиенте в нём__________

            if (!empty($arCustomers)) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    try {
                        if (reWrite($arCustomers, $dataPathCustomers)) {
                            $_SESSION['result'] = 'change';
                            header('Location: result.php');
                            exit;
                        } else {
                            $error = true;
                            $message = "Извините имя не изменилось, попробуйте позже";
                        }
                    } catch (Throwable $e){
                        $error = true;
                        $message = "Извините имя не изменилось, попробуйте позже";
                    }
                }
            }
        }
    }
}
//______________конец перезаписи имени клиента_________________________
