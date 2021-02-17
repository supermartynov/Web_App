<?php
class change{
    public $connect;
    public $id;
    public $desicion;
    public $manager;

    public function __construct(){
        $this->connect =  $this->connect = new mysqli("localhost", "root", "root", "main_db");
        $this->desicion = $_POST['desicion'];
        $this->manager = $_COOKIE['manager'];
        $this->id = $_GET['id'];


    }

    public function status_changer(){
        if ($this->desicion == 'выполненное'){
            return $this->connect->query("UPDATE `events` SET `managersLogin` = '$this->manager' , `status` = '$this->desicion', `active` = 0 WHERE `id` = '$this->id'");

        }
        else{

            return $this->connect->query("UPDATE `events` SET `managersLogin` = '$this->manager' , `status` = '$this->desicion', `active` = 1 WHERE `id` = '$this->id'");
        }
    }
    public function changed(){
        echo "Статус заявки изменен, перейти на главную - <a href='opener.php' class='c'>Главная</a>";
    }
}

?>
<?php  if(!($_COOKIE['manager'] == '')): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body class="class_2">
<div align="right" >
    <font size="3" color="red" face="Arial"><a class="c_2" href="opener.php" shape="rect">На главную</a></font><br>
</div>
<div class="block2">
    <?php
        $status = $_GET['status'];

    ?>
    <h4>Изменить статус мероприятия с "<?=$status?>"  на :</h4>
    <form method="post" >
        <input type="radio" name="desicion" value="в_разрабокте"> В разработке <br>
        <input type="radio" name="desicion" value="готовое"> Готовое <br>
        <input type="radio" name="desicion" value="выполненное"> Выполненное <br>
        <input type="submit" class="green" name="status_button" value="Отправить"> <br>
    </form>
    <div>
        <?php

            if (isset($_POST['status_button'])){
                $change = new change();
                $change->status_changer();
                $change->changed();
            }
        ?>

    </div>
</div>
</body>
</html>
<?php else:?>
    <?php require "check_cookie.php"?>
<?php endif;?>