<?php

class Auto{
    public $login_auto;
    public $password_auto;
    public $connect_auto;
    public $user_final;
    public $login_manager;
    public $password_manager;
    public $manager_or_user;
    public $error;

    public function __construct(){
        if (isset($_POST['button_2'])){
            $this->login_auto = filter_var(trim($_POST['login_aut']), FILTER_SANITIZE_STRING);
            $this->password_auto = filter_var(trim($_POST['password_aut']), FILTER_SANITIZE_STRING);
            $this->manager_or_user = 1;
        }
        elseif (isset($_POST['manager_button'])){
            $this->login_manager = $_POST['login_manager'];
            $this->password_manager = $_POST['password_manager'];
            $this->manager_or_user = 2;
        }
        $this->connect_auto = new mysqli("localhost", "root", "root", "main_db");
        require_once "session.php";
    }
    public function authorization(){
        if ($this->manager_or_user == 1){
            $user = $this->connect_auto->query("SELECT * FROM `clients` WHERE `login` = '$this->login_auto' AND `password` = '$this->password_auto'");
            $user_final = $user->fetch_assoc();
            print_r($user_final);
            if (count($user_final) == 0) {
                setcookie("autho_error", 1, time() +1);
                header('Location: http://localhost:8888/Web-site_logic/auto_reg.php ');
            } else {
                setcookie('user', $user_final['login'], time() + 1200);
                print_r($_COOKIE['user']);
                setcookie('manager', $user_final['login'], time() - 3600);
                echo $this->login_auto;
                $this->user_final = $user_final;
                header('Location: http://localhost:8888/Web-site_logic/');
            }
        }
        else{
            $user = $this->connect_auto->query("SELECT * FROM `managers` WHERE `login` = '$this->login_manager' AND `password` = '$this->password_manager'");
            $user_final = $user->fetch_assoc();
            if (count($user_final) == 0) {
                setcookie("autho_error", 1, time() + 1);
                header('Location: http://localhost:8888/Web-site_logic/auto_reg.php ');;
            } else {
                setcookie('manager', $user_final['login'], time() + 3600);
                setcookie('user', $user_final['login'], time() - 3600);
                $this->user_final = $user_final;
                header('Location: http://localhost:8888/Web-site_logic/');
            }
        }

    }


    public function disreg(){
        if(isset($_POST['button_3'])){
            setcookie('user', $this->user_final['name'], time() - 10000);
            setcookie('manager', $this->user_final['name'], time() - 10000);
            header('Location: http://localhost:8888/Web-site_logic/');
        }
    }

}

$Authorization = new Auto();
$Authorization->authorization();
$Authorization->disreg();

?>