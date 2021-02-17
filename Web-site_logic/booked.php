<?php require "header_accept.php" ?>
<?php
    class Reserved{
        public $time;
        public $connect;
        public $place;
        public $date;
        public $id;
        public $amount;
        public $parametrs_for_calendar;
        public $parametrs_for_event;
        public $id_of_hall;
        public $check_this_event;
        public $dbh;
        public $login;
        public $price;
        public $info;
        public $menu_exist;
        public $max_id;
        public $banket_menu;
        public $banket_menu_price;
        public $total_price ;
        public $skiplock;



        public function __construct(){

            $this->time = base64_decode($_GET['TI']);
            $this->amount = base64_decode($_GET['AM']);
            $this->price = base64_decode($_GET['PR']);
            $this->id = base64_decode($_GET['I']);
            $this->skiplock = $_GET['skiplock'];
            $this->place = $_GET['PL'];
            $this->connect = new mysqli("localhost", "root", "root", "main_db");
            if (base64_decode($_GET['ME']) != ''){
                $this->banket_menu = base64_decode($_GET['ME']);
                $this->banket_menu_price = $this->connect->query("SELECT `price` FROM `Menu` WHERE `id` = $this->banket_menu");
                $this->banket_menu_price = $this->banket_menu_price->fetch_row()[0];
                $this->menu_exist = 1;
            }
            else{
                $this->menu_exist = 0;
            }
            $this->date = base64_decode($_GET['DA']);
            $this->login = $_COOKIE['user'];
            $this->check_this_event = $this->connect->query("SELECT");
            $this->max_id = $this->connect->query("SELECT MAX(id) FROM `eventsCalendar`")->fetch_row()[0] + 1;
            $this->id_of_hall = $this->connect->query("SELECT `id` FROM `sprHalls` WHERE `name` = '$this->place' 
                AND `type` = '$this->id' AND `numberOfSeats` >= '$this->amount'");
            $this->id_of_hall = $this->id_of_hall->fetch_row()[0];
            $this->parametrs_for_calendar = array('eventsDate' => $this->date, 'hall' => $this->id_of_hall, 'startDate' => $this->time);

        }

    /*SELECT * FROM `eventsCalendar` WHERE `eventsDate` = '$this->date'
    AND `startDate` = '$time' AND `hall` = '$this->id_of_hall'*/
        public function isReserved($time){
            $result = $this->connect->query("SELECT * FROM `eventsCalendar` JOIN  `events` ON events.calendarId = eventsCalendar.id WHERE `eventsDate` = '$this->date' 
                AND `startDate` = '$time' AND `hall` = '$this->id_of_hall'  ");
            $result = $result->fetch_assoc();
            if (count($result) == 0){
                return 0;
            }
            else{
                return 1;
            }
        }

        function insertQuery($tableName = "", $params = []){
            if(empty($tableName)){
                throw new Exception("Не передано название таблицы");
            }

            if(empty($params)){
                throw new Exception("Не переданы аргументы");
            }

            $prepareParams = [];

            foreach ($params as $column => $value){
                if(is_numeric($value)){
                    $prepareParams[] = "`{$column}` = {$value}";
                }
                else{
                    $prepareParams[] = "`{$column}` = '{$value}'";
                }
            }

            $query = "INSERT INTO {$tableName} SET " . implode(',', $prepareParams);

            return $this->connect->query($query);
        }

        function insertQuery_2($tableName = "", $params = []){
            if(empty($tableName)){
                throw new Exception("Не передано название таблицы");
            }

            if(empty($params)){
                throw new Exception("Не переданы аргументы");
            }

            $prepareParams = [];

            foreach ($params as $column => $value){
                if(is_numeric($value)){
                    $prepareParams[] = "`{$column}` = {$value}";
                }
                else{
                    $prepareParams[] = "`{$column}` = '{$value}'";
                }
            }

            $query = "INSERT INTO {$tableName} SET " . implode(',', $prepareParams);

            return $query;
        }

        public function back(){
            $this->acception->query("DELETE FROM `acception` WHERE id = 1");
        }
        public function ready(){
            if($this->info == 1){
                return 1;
            }
            else{
                return 0;
            }
        }

        public function info(){
            if ($this->menu_exist == 1){
                $total_price = $this->amount * ($this->price + $this->banket_menu_price);
            }
            else{
                $total_price = $this->amount * $this->price;
            }
            echo "{$this->amount}  человек <br><br>" ;
            echo "{$this->place} - место проведения <br><br>";
            echo "{$this->date} - дата проведения <br><br>";
            echo "{$this->time} - время проведения <br><br>";
            echo "Цена за {$this->amount} человек  - $total_price рублей <br><br>";
            $this->total_price = $total_price;
        }

        public function reservation_1(){
            if ($this->skiplock == 0){
                try {
                    $this->dbh = new PDO('mysql:host = localhost:8888;dbname=main_db', 'root', 'root');
                } catch (Exception $e){
                    die("Не удалось подключиться: " . $e->getMessage());
                }
                try {
                    if(isset($_POST['yes'])){
                        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //режим сообщения об ошибках
                        $this->dbh->exec("LOCK TABLES `eventsCalendar` WRITE, `events` WRITE");
                        $this->connect->query("SELECT SLEEP(5)");
                        $this->dbh->beginTransaction();
                        if($this->isReserved($this->time) == 1){
                            throw new Exception("Кто-то забронировал данное место,<br> попробуйте снова  ");
                        }
                        if ($this->banket_menu != '') {
                            $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                                'clientLogin' => $this->login, 'type' => $this->id, 'menu' => $this->banket_menu,'price' => $this->total_price, 'status' => "на_рассмотрении");
                        }
                        else{
                            $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                                'clientLogin' => $this->login, 'type' => $this->id, 'price' => $this->total_price, 'status' => "на_рассмотрении");
                        }

                         $this->dbh->exec("LOCK TABLES `eventsCalendar` WRITE, `events` WRITE ;".
                            $this->insertQuery_2('eventsCalendar', $this->parametrs_for_calendar).";".
                            $this->insertQuery_2('events', $this->parametrs_for_event));
                        //$this->dbh->exec($this->insertQuery_2('events', $this->parametrs_for_event));


                        setcookie('kind_of_chill', 'bowling', time() - 1000);
                        setcookie('kind_of_chill', 'ofsite', time() - 1000);
                        setcookie('kind_of_chill', 'table', time() - 1000);
                        setcookie('kind_of_chill', 'banquet', time() - 1000);


                        echo "Время забронировано <br>";
                        echo "<div><a href='opener.php' class='c_2'>На главную</a> </div>";

                        $this->dbh->commit();
                        $this->dbh->exec("UNLOCK TABLES");
                    }
                    elseif (isset($_POST['no'])){
                        header ('Location: http://localhost:8888/Web-site_logic/Event.php');
                    }

                } catch (Exception $e) {
                    $this->dbh->rollBack();
                    echo "Ошибка: " . $e->getMessage();
                    echo "<br>";
                    echo "<a href='Event.php' class='c_2'>Выбрать другое время</a>";
                }
                }
            }

            /*if (isset($_POST['yes'])){
                if ($this->isReserved($this->time) == 0){

                    $this->insertQuery('eventsCalendar', $this->parametrs_for_calendar);
                    if ($this->banket_menu != ''){
                        $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                            'clientLogin' => $this->login, 'type' => $this->id, 'menu' => $this->banket_menu,'price' => $this->total_price, 'status' => "на_рассмотрении");
                    }
                    else{
                        $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                            'clientLogin' => $this->login, 'type' => $this->id, 'price' => $this->total_price, 'status' => "на_рассмотрении");
                    }

                    $lol = $this->insertQuery('events', $this->parametrs_for_event);
                    if (!$lol){
                        echo $this->connect->error;
                    }

                    setcookie('kind_of_chill', 'bowling', time() - 1000);
                    setcookie('kind_of_chill', 'ofsite', time() - 1000);


                    echo "Время забронировано <br>";
                    echo "<a href='opener.php' class='c_2'>На главную </a>";
                }
                elseif ($this->isReserved($this->time) == 1){
                    echo "кто-то забронировал время, извините <br>";
                    echo "<a href='opener.php' class='c_2'>На главную </a>";
                }
            }
            elseif($_POST['no']){
                header ('Location: http://localhost:8888/Web-site_logic/Event.php');
            }*/


        public function reservation(){
            if (isset($_POST['yes'])){
                if ($this->isReserved($this->time) == 0){

                    $this->insertQuery('eventsCalendar', $this->parametrs_for_calendar);
                    $this->connect->query("SELECT SLEEP(5)");
                    if ($this->banket_menu != ''){
                        $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                            'clientLogin' => $this->login, 'type' => $this->id, 'menu' => $this->banket_menu,'price' => $this->total_price, 'status' => "на_рассмотрении");
                    }
                    else{
                        $this->parametrs_for_event = array('calendarId' => $this->max_id, 'numberOfGuests' => $this->amount,
                            'clientLogin' => $this->login, 'type' => $this->id, 'price' => $this->total_price, 'status' => "на_рассмотрении");
                    }

                    $lol = $this->insertQuery('events', $this->parametrs_for_event);
                    if (!$lol){
                        echo $this->connect->error;
                        exit();
                    }

                    setcookie('kind_of_chill', 'bowling', time() - 1000);
                    setcookie('kind_of_chill', 'ofsite', time() - 1000);
                    setcookie('kind_of_chill', 'table', time() - 1000);
                    setcookie('kind_of_chill', 'banquet', time() - 1000);


                    echo "Время забронировано <br>";
                    echo "<a href='opener.php' class='c_2'>На главную </a>";
                }
                elseif ($this->isReserved($this->time) == 1){
                    echo "кто-то забронировал время, извините <br>";
                    echo "<a href='opener.php' class='c_2'>На главную </a>";
                }
            }
            elseif($_POST['no']){
                header ('Location: http://localhost:8888/Web-site_logic/Event.php');
            }
        }

}

/*public function reservation(){
    if (isset($_POST['yes'])){
        if ($this->isReserved($this->time) == 0){
            $this->insertQuery('eventsCalendar', $this->parametrs_for_calendar);
            $this->id_of_eventsCalendar= $this->connect->query("SELECT  `id` FROM `eventsCalendar` WHERE `hall` = '$this->id_of_hall'
                        AND `startDate` = '$this->time' AND `eventsDate` = '$this->date' ");
            $this->id_of_eventsCalendar = $this->id_of_eventsCalendar->fetch_row()[0];
            if ($this->banket_menu != ''){
                $this->parametrs_for_event = array('calendarId' => $this->id_of_eventsCalendar, 'numberOfGuests' => $this->amount,
                    'clientLogin' => $this->login, 'type' => $this->id, 'menu' => $this->banket_menu, 'status' => "на_рассмотрении");
            }
            else{
                $this->parametrs_for_event = array('calendarId' => $this->id_of_eventsCalendar, 'numberOfGuests' => $this->amount,
                    'clientLogin' => $this->login, 'type' => $this->id, 'status' => "на_рассмотрении");
            }

            $this->insertQuery('events', $this->parametrs_for_event);

            setcookie('kind_of_chill', 'bowling', time() - 1000);
            setcookie('kind_of_chill', 'ofsite', time() - 1000);

            echo "Время забронировано <br>";
            echo "<a href='opener.php' class='c_2'>На главную </a>";
        }
        elseif ($this->isReserved($this->time) == 1){
            echo "кто-то забронировал время, извините <br>";
            echo "<a href='opener.php' class='c_2'>На главную </a>";
        }
    }
    elseif($_POST['no']){
        header ('Location: http://localhost:8888/Web-site_logic/Event.php');
    }
}
}*/

?>
<?php require "closer.php"?>



