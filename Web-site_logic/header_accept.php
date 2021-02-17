<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<body class="class_2">
<?php if($_COOKIE['user'] != ''):?>
<div align="right" >
    <font size="3" color="red" face="Arial"><a class="c_2" href="opener.php" shape="rect">На главную</a></font>
</div>
<H1 class="class_h1_1">
    Подтверждение бронирования
</H1>
<div class="block2">
    <?php $accept_1 = new Reserved();

        echo $accept_1->login
    ?>, подтверждаем бронирование ?<br><br>
    <?php

        $accept_1->info();
    ?>
    <form method="post">
        <input class="green" type="submit" name="yes" value="да">
        <input class="green" type="submit" name="no" value="нет">
    </form>
    <?php
    if($accept_1->skiplock == 1){
        $accept_1->reservation();
    }
    else{
        $accept_1->reservation_1();
    }
    ?>
</div>
</body>
</html>
<?php else:?>
<?php require "check_cookie.php"?>
<?php endif;?>

