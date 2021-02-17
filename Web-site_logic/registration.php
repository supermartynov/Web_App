<?php

    class Reg{
        public $login;
        public $name;
        public $password;
        public $connect;
        public $phone_number;
        public $error;


        public function __construct(){
            $this->login = filter_var(trim($_POST['login']), FILTER_SANITIZE_STRING);
            $this->name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
            $this->password = filter_var(trim($_POST['password']), FILTER_SANITIZE_STRING);
            $this->phone_number = $_POST["phone"];
            $this->connect = new mysqli("localhost","root","root","main_db");
            require_once "session.php";
        }
        public function __destruct(){
            $this->error = 0;
            $this->connect->close();
        }

        public function check(){
            $check_1 = $this->connect->query("SELECT * FROM `clients` WHERE `login` = '$this->login' ");
            $check_1_array = $check_1->fetch_assoc();
            $check_2 = $this->connect->query("SELECT * FROM `clients` WHERE `phone` = '$this->phone_number' ");
            $check_2_array = $check_2->fetch_assoc();

            if (count($check_1_array) != ''){
                setcookie('login_error', 1 , time() + 1);
               $this->error = 1;
            }
            if (strlen(($this->login)) < 5 || (strlen($this->login)) > 30){
                setcookie('length_login_error', 1 , time() + 1);
                echo "тут косяк";
                $this->error = 1;
            }
            if (count($check_2_array) != ''){
                setcookie('phone_error', 1 , time() + 1);
                $this->error = 1;
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
    }

    $first_one = new Reg();
    $first_one->check();
    if ($first_one->error == 1){
        header('Location: http://localhost:8888/Web-site_logic/auto_reg.php');
    }
    else{
        $parametrs_for_clients = ['login' => $first_one->login, 'name' => $first_one->name,'phone' => $first_one->phone_number,'password' => $first_one->password];
        try{
            $res = $first_one->insertQuery(clients, $parametrs_for_clients);
        }
        catch (Exception $exception_1){
            echo $exception_1->getMessage();
        }
        setcookie("register", 1, time() +1);
        header('Location: http://localhost:8888/Web-site_logic/ ');
    }

?>
