<?php
session_start();

include_once('readWriteSQL.php');
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
$host = 'smtp.mailsnag.com';
$port = 2525;
$username = 'TtKDlDkv3dE2';
$password = 'PwwLiM8iVzZp';
$smtpFrom = 'elvis@example.com';

//______________начало записи о бронировании_________________________
if ($_POST['booking']) {
    $customer = $_POST['name'];
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
        $secondDate = NULL;
        $arBookingDate[] = $firstDate;
    }

    // ___________конец получение новой даты в нужном формате_____________

    // ___________получение списка заббронированных дат из базы__________

    if ($error == false) {
        $arBookedPeriods = Read('booking_list', $pdo);
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
            // бронирующий
            $query = "SELECT id FROM guests WHERE name = '$customer';";
            if (!empty($arOldNameId = Read('guests', $pdo, $query))) {
                $customerId = $arOldNameId[0][0];
                $isCustomerFind = true;
            } else {
                $isCustomerFind = false;
            }
            $arNewEntry[] = $arBookingDate[0];

            if ($arBookingDate[1]) {
                // новая строка в файл, если это период
                $arNewEntry[] = $arBookingDate[1];
            }

            if (!empty($arNewEntry)) {

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                        if (!$isCustomerFind) {
                            $arPrepParams = [
                                ':customer' => $customer,
                                ':firstDate' => $firstDate,
                                ':secondDate' => $secondDate
                            ];
                            $query = "START TRANSACTION;
                                    INSERT INTO guests VALUES (NULL, :customer);
                                    SET @last_guest_id = LAST_INSERT_ID();
                                    INSERT INTO booking_list VALUES (NULL, @last_guest_id, :firstDate, :secondDate);
                                    COMMIT;";
                        } else {
                            $arPrepParams = [
                                ':customerId' => $customerId,
                                ':firstDate' => $firstDate,
                                ':secondDate' => $secondDate
                            ];
                            $query = "INSERT INTO booking_list VALUES (NULL, :customerId, :firstDate, :secondDate);";
                        }

                        if (Write($arPrepParams, $query, $pdo)) {
                            $mail = new SendMailSmtpClass($username, $password, $host, $smtpFrom, $port);
                            $message = Message($arNewEntry, $customer);
                            
                            $_SESSION['result'] = 'booking';

                            Notification($message, $mail);
                            redirect('result.php');
                            exit;
                        } else {
                            $error = true;
                            $message = "Извините запись не произошла, попробуйте позже";
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

        //___________начало созданиие массива и замена записи о клиенте в нём__________
        $query = "SELECT name FROM guests WHERE id = $oldNameId;";
        $oldName = Read('guests', $pdo, $query);

        $query = "UPDATE guests SET name = :newName WHERE id = :oldNameId;";
        $arPrepParams = [
            ':newName' => $newName,
            ':oldNameId' => $oldNameId
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (Write($arPrepParams, $query, $pdo)) {
                $_SESSION['result'] = 'change';
                $mail = new SendMailSmtpClass($username, $password, $host, $smtpFrom, $port);

                $message = "<html><body><strong>Изменение имени на сайте: " . $_SERVER['HTTP_HOST'] . "</strong><br><br>" . $oldName[0][0] . " изменил имя на " . $newName . "</body></html>";

                Notification($message, $mail);
                redirect('result.php');
                exit;
            } else {
                $error = true;
                $message = "Извините имя не изменилось, попробуйте позже";
            }
        }
    }
}
//______________конец перезаписи имени клиента_________________________
