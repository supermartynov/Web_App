<?php require "session.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<?php
    if($_COOKIE['register'] != ''){
        echo "<script> alert ('Вы успешно зарегестрировались')</script>";
    }
?>
<body class="class_2">
<font  color="white"> Контактный номер : 8-800-555-35-55</font>
<?php if(($_COOKIE['user'] == '') && ($_COOKIE['manager'] == '') && ($_COOKIE['runout'] == '')): ?>
<table class="logo">
    <h1 class="class_h1_1">Добро пожаловать!</h1>
    <tr>
        <td align="center">
            <a href="describe.html">О нас</a>
        </td>
        <td align="center">
            <font size="5"></font>
            <a href="auto_reg.php">Зарегестрироваться / Авторизоваться</a>
        </td>
    </tr>
</table>
<br>
<br>
<div class="block_paragraph">

</div>
<?php elseif ($_COOKIE['user'] != '') :?>
<table class="logo">
    <h1 class="class_h1_1" >Добро пожаловать!</h1>
    <tr>
        <td align="center">
            <a href="describe.html">О нас</a>
        </td>
        <td align="center">
            <a href="Personal_place.php">Личный кабинет</a>
        </td>
        <td align="center">
            <a href="Event.php">Организовать мероприятие</a>
        </td>
        <td align="center">
            <form method="post" action="authorization.php" class="yellow">
                <input type="submit" name="button_3" value="Выйти из личного кабинета">
        </td>
    </tr>
</table>
<?php elseif ($_COOKIE['manager'] != '') :?>
    <table class="logo">
        <h1 class="class_h1_1" >Добро пожаловать, администратор!</h1>
        <tr>
            <td align="center">
                <a href="Event.php">Взять заявку на рассмотерние</a>
            </td>
            <td align="center">
                <form method="post" action="authorization.php" class="yellow">
                    <input type="submit" name="button_3" value="Выйти из личного кабинета">
            </td>
        </tr>
    </table>
<?php elseif ($_COOKIE['runout'] != '') :?>
<script>
    alert('Авторизируйтесь, время сеанса истекло');
</script>
    <table class="logo">
        <?php
        // print_r($_COOKIE['user']) ;
        ?>
        <h1 class="class_h1_1">Добро пожаловать!</h1>
        <tr>
            <td align="center">
                <a href="describe.html">О нас</a>
            </td>
            <td align="center">
                <font size="5"></font>
                <a href="auto_reg.php">Зарегестрироваться / Авторизоваться</a>
            </td>
        </tr>
    </table>
<br>
<br>
    <div class="block_paragraph">

    </div>
<?php ?>
<?php endif ?>