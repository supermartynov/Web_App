<?php

class info
{
    public $connect;
    public $date;
    public $amount;
    public $amount_of_rows;
    public $place;
    public $price;
    public $login_user;
    public $start_time;
    public $login_manager;

    public function __construct()
    {
        $this->login_manager = $_COOKIE['manager'];
        $this->login_user = $_COOKIE['user'];
        $this->connect = $this->connect = new mysqli("localhost", "root", "root", "main_db");
        if ($this->login_manager != '') {
            /*$this->amount_of_rows = $this->connect->query("SELECT COUNT(*)  FROM `events`
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.managersLogin = '$this->login_manager'");
            $this->amount_of_rows = $this->amount_of_rows->fetch_row()[0];

            $this->date = $this->connect->query("SELECT eventsCalendar.eventsDate   FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.managersLogin = '$this->login_manager'");
            $this->amount = $this->connect->query("SELECT events.numberOfGuests  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.managersLogin = '$this->login_manager'");
            $this->place = $this->connect->query("SELECT eventsCalendar.hall, sprHalls.name  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id JOIN `sprHalls` ON eventsCalendar.hall = sprHalls.id  WHERE events.managersLogin = '$this->login_manager'");*/
        } else {
            $this->amount_of_rows = $this->connect->query("SELECT COUNT(*)  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.clientLogin = '$this->login_user' AND events.active = 1");
            $this->amount_of_rows = $this->amount_of_rows->fetch_row()[0];
            $this->date = $this->connect->query("SELECT eventsCalendar.eventsDate   FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.clientLogin = '$this->login_user'  AND events.active = 1");
            $this->amount = $this->connect->query("SELECT events.numberOfGuests  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.clientLogin = '$this->login_user'  AND events.active = 1");
            $this->place = $this->connect->query("SELECT eventsCalendar.hall, sprHalls.name  FROM `events` 
                                                            JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id JOIN 
                                                                `sprHalls` ON eventsCalendar.hall = sprHalls.id  WHERE events.clientLogin = '$this->login_user' AND events.active = 1");
            $this->price = $this->connect->query("SELECT events.price  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.clientLogin = '$this->login_user'  AND events.active = 1");
            $this->start_time = $this->connect->query("SELECT eventsCalendar.startDate  FROM `events` 
                    JOIN `eventsCalendar` ON events.calendarId = eventsCalendar.id WHERE events.clientLogin = '$this->login_user'  AND events.active = 1");

        }


    }

    public function Data_amount()
    {
        $data_array_of_amount = [];

        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            $data_array_of_amount[$i] = $this->amount->fetch_row()[0];
        }
        return $data_array_of_amount;
    }

    public function Date()
    {
        $data_array = [];
        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            $data_array[$i] = $this->date->fetch_row()[0];
        }
        return $data_array;
    }

    public function Data_place()
    {
        $data_array_of_place = [];

        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            $data_array_of_place[$i] = $this->place->fetch_row()[1];
        }
        return $data_array_of_place;
    }
    public function Data_price()
    {
        $data_array_of_price = [];

        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            $data_array_of_price[$i] = $this->price->fetch_row()[0];
        }
        return $data_array_of_price;
    }
    public function Data_time()
    {
        $data_array_of_time = [];

        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            $data_array_of_time[$i] = $this->start_time->fetch_row()[0];
        }
        return $data_array_of_time;
    }

    public function infotable()
    {

        echo "<table cellspacing='10px' border='4px'>";
        echo "<th>Дата предстоящего мероприятия</th><th>место</th><th>количество людей</th><th>Цена за мероприятие</th><th>Время начала</th>";
        $date1 = $this->Date();
        $amount = $this->Data_amount();
        $price = $this->Data_price();
        $start_time = $this->Data_time();
        $place = $this->Data_place();

        for ($i = 0; $i < $this->amount_of_rows; $i++) {
            echo "<tr>";
            echo "<td>";
            echo $date1[$i];
            echo "</td>";

            echo "<td>";
            echo $place[$i];
            echo "</td>";
            echo "<td>";
            echo $amount[$i];
            echo "</td>";
            echo "<td>";
            echo $price[$i];
            echo "</td>";
            echo "<td>";
            echo $start_time[$i];
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}
if(!($_COOKIE['user'] == '') ){
    if ($_POST['test'] == 1){
        echo "<div class='block_for_personal_place'>";
        echo "<h3>Предстоящие заявки</h3>";
        $kek = new info();
        $kek->infotable();
        echo "</div>";
}
    else{
        require "check_cookie.php";
    }
}

?>