<?php
    class aims{

        public $connect;
        public $free_events;
        public $name_of_free_events;
        public $free_events_amount;
        public $free_events_login;
        public $free_events_place;
        public $free_events_id;
        public $status_of_id;
        public $free_events_date;
        public $free_events_time;
        public $amount_of_managers_events;
        public $login;


        public function __construct(){
            $this->login = $_COOKIE['manager'];
            $this->connect =  $this->connect = new mysqli("localhost", "root", "root", "main_db");
            $this->free_events = $this->connect->query("SELECT * FROM `events` WHERE `managersLogin` IS NULL OR `managersLogin` = '$this->login'  AND events.active = 1 ");
            $this->free_events_login = $this->connect->query("SELECT `clientLogin` FROM `events` WHERE `managersLogin` IS NULL OR `managersLogin` = '$this->login'  AND events.active = 1");
            $this->free_events_place = $this->connect->query("SELECT sprHalls.name FROM `events` JOIN eventsCalendar ON events.calendarId = eventsCalendar.id 
                                                                        JOIN sprHalls ON sprHalls.id = eventsCalendar.hall WHERE `managersLogin` IS NULL  
                                                                            OR `managersLogin` = '$this->login'  AND events.active = 1");
            $this->free_events_id = $this->connect->query("SELECT `id` FROM `events` WHERE `managersLogin` IS NULL OR `managersLogin` = '$this->login'  AND events.active = 1");
            $this->status_of_id= $this->connect->query("SELECT `status` FROM `events` WHERE `managersLogin` IS NULL OR `managersLogin` = '$this->login'  AND events.active = 1");
            $this->free_events_date= $this->connect->query("SELECT eventsCalendar.eventsDate FROM `events` JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id 
                                                        WHERE events.managersLogin IS NULL OR events.managersLogin = '$this->login'  AND events.active = 1");
            $this->free_events_time = $this->connect->query("SELECT eventsCalendar.startDate FROM `events` JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id 
                                                        WHERE events.managersLogin IS NULL OR events.managersLogin = '$this->login'  AND events.active = 1");

            $this->free_events_amount = $this->connect->query("SELECT COUNT(*) FROM `events` WHERE `managersLogin` IS NULL OR `managersLogin` = '$this->login'  AND events.active = 1");
            $this->free_events_amount = $this->free_events_amount->fetch_row()[0];
            $this->amount_of_managers_events= $this->connect->query("SELECT `amountOfEvents` FROM `managers`  WHERE `login` = '$this->login'");
            $this->amount_of_managers_events = $this->amount_of_managers_events->fetch_row()[0];

        }

        public function Button_Maker($id,$status){
            echo "<a class='c_3' href='status_change.php?id={$id}&status={$status}'> Взять на рассмотрение </a>";
        }

        public function DataArray(){
            $data_array_of_amount = [];

            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_amount[$i] = $this->free_events->fetch_row()[2];
            }
            //print_r($data_array_of_amount);
            return $data_array_of_amount;
        }

        public function DataArray_place(){
            $data_array_of_place = [];

            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_place[$i] = $this->free_events_place->fetch_row()[0];
            }
            //print_r($data_array_of_amount);
            return $data_array_of_place;
        }
        public function DataArray_date(){
            $data_array_of_date = [];

            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_date[$i] = $this->free_events_date->fetch_row()[0];
            }
            //print_r($data_array_of_amount);
            return $data_array_of_date;
        }
        public function DataArray_time(){
            $data_array_of_time = [];

            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_time[$i] = $this->free_events_time->fetch_row()[0];
            }
            //print_r($data_array_of_amount);
            return $data_array_of_time;
        }
        public function DataArray_status(){
            $data_array_of_status = [];

            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_status[$i] = $this->status_of_id->fetch_row()[0];
            }
            //print_r($data_array_of_status);
            return $data_array_of_status;
        }
        public function DataArrayLogin(){
            $data_array_of_login = [];
            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_login[$i] = $this->free_events_login->fetch_row()[0];
            }
            //print_r($data_array_of_login);
            return $data_array_of_login;
        }
        public function DataArrayId(){
            $data_array_of_id = [];
            for ($i = 0; $i < $this->free_events_amount; $i++){
                $data_array_of_id[$i] = $this->free_events_id->fetch_row()[0];
            }
            //print_r($data_array_of_id);
            return $data_array_of_id;
        }


        public function free_events_func(){
            echo "Количество заявок, которые вы взяли -  $this->amount_of_managers_events ";
            echo "<table cellspacing='15px' border='4px'>";
            echo "<th> Номер </th>";
            echo "<th> Место проведения </th>";
            echo "<th> Дата проведения </th>";
            echo "<th> Время проведения </th>";
            echo "<th> Количество гостей </th>";
            echo "<th> Логин клиента </th>";
            echo "<th> Статус заявки </th>";
            echo "<th> Взять на рассмотрение</th>";
            $amount_of_rows = $this->free_events_amount;
            if ($amount_of_rows == 0){
                echo ". Нет свободных заявок";
                echo ", перейти на главную - <a href='opener.php' class='c'>Главная</a>";
                exit();
            }
            $logins = $this->DataArrayLogin();
            $date = $this->DataArray_date();
            $time = $this->DataArray_time();
            $place = $this->DataArray_place();
            $status= $this->DataArray_status();
            $id_of_events = $this->DataArrayId();
            $amount_of_guests = $this->DataArray();



            for ($i = 0; $i < $amount_of_rows; $i++){

                echo "<tr>";

                    echo "<td>";
                        echo $i + 1 ;
                    echo "</td>";
                    echo "<td>";
                        echo $place[$i];
                    echo "</td>";

                    echo "<td>";
                         echo $date[$i];
                    echo "</td>";
                    echo "<td>";
                        echo $time[$i];
                    echo "</td>";

                    echo "<td>";
                        echo $amount_of_guests[$i];
                    echo "</td>";

                    echo "<td>";
                        echo "$logins[$i]";
                    echo "</td>";

                    echo "<td>";
                        echo "$status[$i]";
                     echo "</td>";

                    echo "<td>";
                        $this->Button_Maker($id_of_events[$i],$status[$i]);
                    echo "</td>";

                echo "</tr>";
            }
            echo "</table>";
        }
    }
?>
