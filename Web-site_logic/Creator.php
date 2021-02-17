<?php
    class booking{
        public $amount;
        public $date;
        public $connect;
        public $times;
        public $amount_of_places;
        public $name_of_place;
        public $numberOfSeats;
        public $max_amount_of_seats;
        public $acception;
        public $id;
        public $price;
        public $banket_menu;
        public $current_date;
        public $current_time;


        public function __construct($id){
            $this->id = $id;
            $this->amount = $_POST['amount'];
            $this->date = $_POST['date'];
            $this->connect = new mysqli("localhost", "root", "root", "main_db");
            $this->times = ['','12:00:00','13:30:00', '15:00:00', '16:30:00', '18:00:00'];
            $this->banket_menu = $_POST['menu'];
            $this->amount_of_places = $this->connect->query("SELECT  COUNT(*) FROM `sprHalls` WHERE `type` = '$this->id' AND `numberOfSeats` >= '$this->amount'"); //количество мест с нужным id
            $this->amount_of_places = $this->amount_of_places->fetch_assoc();
            $this->name_of_place= $this->connect->query("SELECT  `name` FROM `sprHalls` WHERE `type` = '$this->id' AND `numberOfSeats` >= '$this->amount'");
            $this->id_of_place= $this->connect->query("SELECT  `name` FROM `sprHalls` WHERE `type` = '$this->id' AND `numberOfSeats` >= '$this->amount'");
            $this->numberOfSeats= $this->connect->query("SELECT  `numberOfSeats` FROM `sprHalls` WHERE `type` = '$this->id'  AND `numberOfSeats` >= '$this->amount'");
            $this->max_amount_of_seats= $this->connect->query("SELECT  MAX(numberOfSeats) FROM `sprHalls` WHERE `type` = '$this->id' ");
            $this->max_amount_of_seats = $this->max_amount_of_seats->fetch_row()[0];
            $this->current_date = $this->connect->query("SELECT CURRENT_DATE()")->fetch_row()[0];
            $this->current_time = $this->connect->query("SELECT CURRENT_TIME()")->fetch_row()[0];
            $this->price = $this->connect->query("SELECT `eventCost` FROM `EventsType` WHERE `id` = '$this->id' ");
            $this->price = $this->price->fetch_row()[0];
            $this->error = 0;
        }


        /*public function is_Blocked(){
            $this->blocked_tables = $this->connect->query("SHOW OPEN TABLES WHERE In_use > 0");
            $this->blocked_tables = $this->blocked_tables->fetch_assoc()['In_use'];
            if ($this->blocked_tables == 0){
                echo "не заблокировано";
                //return 0;
            }
            else{
                echo "заблокировано";
                //return 1;
            }
        }
        public function block(){
            $this->connect->query("LOCK TABLES eventsCalendar WRITE");
        }*/
        public function check_amount(){ //максимальное количество мест
            $max_size = $this->connect->query("SELECT `id` FROM `sprHalls` WHERE `numberOfSeats` >= '$this->amount' AND `type` = '$this->id' ");
            $max_size = $max_size->fetch_assoc();
            if (count($max_size) == 0){
                return 1;
            }
        }
        public function isReserved($time, $place){

            $id_of_hall = $this->connect->query("SELECT `id` FROM `sprHalls` WHERE `name` = '$place' 
                AND `type` = '$this->id' AND `numberOfSeats` >= '$this->amount'"); //возвращает id зала
            $id_of_hall = $id_of_hall->fetch_row()[0];
            $result = $this->connect->query("SELECT * FROM `eventsCalendar` WHERE `eventsDate` = '$this->date' 
                AND `startDate` = '$time' AND `hall` = '$id_of_hall'");
            $result = $result->fetch_assoc();
            if ($this->current_date > $this->date){
                return 1;
            }
            if ($this->current_time > $time && $this->current_date == $this->date){
                return 1;
            }
            if (count($result) == 0){
                $this->error = 1;
                return 0;
            }
            else{
                return 1;
            }
        }
        public function Button_Maker($time, $place){
            $zero_or_one = $this->isReserved($time, $place);
            if ($zero_or_one == 1){
                return "<div class='c'>занято</div>";
            }
            elseif($zero_or_one == 0){
                $time_code = base64_encode($time);
                $time_name = base64_encode('time');
                $place_code = $place;
                $date_code = base64_encode($this->date);
                $id_code = base64_encode($this->id);
                $amount_code = base64_encode($this->amount);
                $price_code = base64_encode($this->price);
                $menu_code = base64_encode($this->banket_menu);
                return "<a class='c' 
                    href='booked.php?TI={$time_code}&DA={$date_code}&PL={$place_code}&I={$id_code}&AM={$amount_code}&PR={$price_code}&ME={$menu_code}&skiplock=0'> 
                    Свободно </a>";
            }
        }

        public function DataArray(){
            $data_array = [];
            for ($i = 0; $i < $this->amount_of_places['COUNT(*)']; $i++){
                $data_array[$i] = $this->name_of_place->fetch_row()[0];
                echo"<br>";
            }
            return $data_array;
        }
        public function Amount_of_seats(){
            $data_array = [];
            for ($i = 0; $i < $this->amount_of_places['COUNT(*)']; $i++){
                $data_array[$i] = $this->numberOfSeats->fetch_row()[0];
                echo"<br>";
            }
            return $data_array;
        }

        public function Table(){


            $check_amount = $this->check_amount();
            if ($check_amount == 1){
                echo "<font color='#ebff5b'>Максимальное количество человек - ". $this->max_amount_of_seats."</font>";
                exit();
            }
            $data_array = $this->DataArray();
            $data_array_seats = $this->Amount_of_seats();
            echo "<table class='table_class'>";
            echo "<tr>";
            echo "<th colspan='6'> Выберите свободное место и время на {$this->date}  </th>";
            echo "</tr>";
            echo "<tr>";
            echo "<th> </th>";
            echo "<th>". $this->times[1] ."</th>";
            echo "<th>". $this->times[2] ."</th>";
            echo "<th>". $this->times[3] ."</th>";
            echo "<th>". $this->times[4] ."</th>";
            echo "<th>". $this->times[5] ."</th>";
            echo "</tr>";
            for ($i = 0; $i < $this->amount_of_places['COUNT(*)']; $i++){
                echo "<tr>";
                echo "<th>".  "$data_array[$i]"."<font size='1'><br>(". "$data_array_seats[$i]"." человек) </font> " . "</th>";
                for ($j = 1; $j < 6; $j++){
                    echo "<td>" . $this->Button_Maker($this->times[$j], $data_array[$i]). "</td>";
                }
                echo "</tr>";
            }
            echo "<tr>";
            echo "</tr>";
            echo "</table>";

        }


    }


?>