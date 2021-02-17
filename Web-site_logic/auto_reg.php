<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reg/autor</title>
    <link rel="stylesheet" href="Styles.css">
</head>
<?php
    if ($_COOKIE['login_error'] != ''){
        echo "<script> alert('Такой логин занят') </script>";
    }
    elseif ($_COOKIE['phone_error'] != ''){
        echo "<script> alert('Такой номер занят') </script>";
    }
    elseif ($_COOKIE['length_login_error'] != ''){
        echo "<script> alert(' Длина логина должна быть от 5 до 30 символов') </script>";
    }
    elseif ($_COOKIE['autho_error'] != ''){
        echo "<script> alert('Проверьте логин или пароль') </script>";
    }


?>
<?php if($_COOKIE['user'] == '' ): ?>
<body class="class_2">
    <div align="right" >
        <font size="3" color="red" face="Arial"><a href="opener.php" class="c_2">На главную</a></font>
    </div>
    <table class="back_1" cellspacing="30">
        <tr>
            <td >
            <h1> Регистрация</h1>
            <form action="registration.php" method="post" autocomplete="on">
                <p> Введите имя</p>
                <input class="wrapper"  type = "text" name = "name" value="<?php echo $_SESSION['name']?>" required placeholder="Введите имя">
                <p>Введите Логин  <font size="1" color="red">(от 5 до 30 символов)</font> </p>
                <input type = "text" value="<?php echo $_SESSION['login']?>" name = "login" required placeholder="Введите логин">
                <p>Введите Пароль</p>
                <input type = "password" name = "password" required placeholder="Введите пароль">
                <p>Введите номер телефона  <font size="1" color="red">(Только российские номера)</font> </p>
                <input type="tel" name="phone" value="<?php echo $_SESSION['phone']?>" id="phone" required placeholder="+7 (926) 862-90-04"  maxlength="12" ">
                <input class="green" name="button" type="submit" value="Готово">
            </form>
            </td>

            <td valign="top" >
                <h1>Авторизация</h1>
                <form action="authorization.php" method="post">
                        <p>Введите логин</p>
                        <input  type="text" value="<?php echo $_SESSION['login_aut']?>" name="login_aut" required placeholder="Введите логин">
                        <p>Введите пароль</p>
                        <input  type="password" name="password_aut"  value="<?php echo $_SESSION['password_aut']?>" required placeholder="Введите пароль"><br><br>
                        <input class="green" type="submit" name="button_2" value="Готово">
                </form>
            </td>

            <td valign="top" width="">
                <h1>Авторизация для менеджера</h1>
                <form action="authorization.php" method="post">
                    <p>Введите логин</p>
                    <input  type="text" name="login_manager"   value="<?php echo $_SESSION['login_manager']?>" required placeholder="Введите логин">
                    <p>Введите пароль</p>
                    <input  type="password" name="password_manager"  value="<?php echo $_SESSION['password_manager']?>" required placeholder="Введите пароль"><br><br>
                    <input class="green" type="submit" name="manager_button" value="Готово">
                </form>
            </td>
        </tr>
    </table>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
    <script src="jquery.maskedinput.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#phone").mask("+7 (999) 999 9999");
        });
    </script>
    <?php else: ?>
    <?php endif; ?>


</body>
</html>