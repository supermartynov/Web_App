<?php
    require "Creator.php";
    require "Event_opener.php";
    session_start();
    require "manager_aims.php"
?>
<?php  if(!($_COOKIE['user'] == '')): ?>
    <?php if($_COOKIE['kind_of_chill'] == ''):?>
    <div class="block1">

        Выберите тип мероприятия <br><br>
        <form method="post" action="">
            <input type="radio" name="answer" value="bowling">Боулинг<br>
            <input type="radio" name="answer" value="ofsite">Выездное мероприятие<br>
            <input type="radio" name="answer" value="banquet">Банкет<br>
            <input type="radio" name="answer" value="table">Столик<br><br>
            <input class="green" type="submit" name="button_4" value="отправить">
            <br>
        </form>
        <?php
        if ($_POST['answer'] == 'bowling'){
            setcookie('kind_of_chill', 'bowling', time() + 1000);
            header("Location:http://localhost:8888/Web-site_logic/Event.php");
        }
        elseif ($_POST['answer'] == 'ofsite'){
            setcookie('kind_of_chill', 'ofsite', time() + 1000);
            header("Location:http://localhost:8888/Web-site_logic/Event.php");
        }
        elseif ($_POST['answer'] == 'banquet'){
            setcookie('kind_of_chill', 'banquet', time() + 1000);
            header("Location:http://localhost:8888/Web-site_logic/Event.php");
        }
        elseif ($_POST['answer'] == 'table'){
            setcookie('kind_of_chill', 'table', time() + 1000);
            header("Location:http://localhost:8888/Web-site_logic/Event.php");
        }
        ?>

    </div>
    <?php elseif ($_COOKIE['kind_of_chill'] == 'bowling'):?>
        <?php  if(!($_COOKIE['user'] == '')): ?>
        <div class="block2">
            <h2>Боулинг</h2>
            <h5>Цена - 1000 рублей с человека</h5>
            <h3>Заполните поля</h3>
            <form method="post" action="">
                <input type="text" name="amount" value="<?php $_SESSION['amount']?>"  required placeholder="количество человек"><br><br>
                <input required type="date"  value="<?php $_SESSION['data']?>" name="date">
                <input class="green" type="submit" name="button_5" value="Отправить">
            </form>

            <br>
            <form method="post">
                <input name="disreg" type="submit" value="Поменять тип">
            </form>
            <?php if(isset($_POST['disreg'])){
                setcookie('kind_of_chill', 'bowling', time() - 1000);
                header("Location:http://localhost:8888/Web-site_logic/Event.php");
            }
            ?>
        </div>
            <?php
            if(isset($_POST['button_5'])){
                $book_1 = new booking(1);
                $book_1->Table();
            }
            ?>

        <?php else:?>
            <?php require "check_cookie.php"?>
        <?php endif;?>
    <?php elseif ($_COOKIE['kind_of_chill'] == 'ofsite'):?>
        <?php  if(!($_COOKIE['user'] == '')): ?>
        <div class="block2">
            <h2> Выездное мероприятие</h2>
            <h5>Цена - 1100 рублей с человека</h5>
            <h3>Заполните поля</h3>
            <form method="post" action="">
                <input type="text" name="amount" required placeholder="количество человек"><br><br>
                <input  required type="date" name="date">
                <input class="green" type="submit" name="button_5" value="Отправить">
            </form>
            <br>
            <form method="post">
                <input name="disreg" type="submit" value="Поменять тип">
            </form>
            <?php if(isset($_POST['disreg'])){
                setcookie('kind_of_chill', ' ofsite', time() - 1000);
                header("Location:http://localhost:8888/Web-site_logic/Event.php");
            }
            ?>
        </div>
            <?php
            if(isset($_POST['button_5'])){
                $book_1 = new booking(2);
                $book_1->Table();
            }
            ?>

        <?php else:?>
            <?php require "check_cookie.php"?>
        <?php endif;?>
    <?php elseif ($_COOKIE['kind_of_chill'] == 'table'):?>
        <?php  if(!($_COOKIE['user'] == '')): ?>
        <div class="block2">
            <h2> Бронирование столика</h2>
            <h5>Цена - 500 рублей с человека(депозит)</h5>
            <h3>Заполните поля</h3>
            <form method="post" action="">
                <input type="text" name="amount" required placeholder="количество человек"><br><br>
                <input required type="date" name="date">
                <input class="green" type="submit" name="button_5" value="Отправить">
            </form>
            <br>
            <form method="post">
                <input name="disreg" type="submit" value="Поменять тип">
            </form>
            <?php if(isset($_POST['disreg'])){
                setcookie('kind_of_chill', ' table', time() - 1000);
                header("Location:http://localhost:8888/Web-site_logic/Event.php");
            }
            ?>
        </div>
            <?php
            if(isset($_POST['button_5'])){
                $book_1 = new booking(3);
                $book_1->Table();
            }
            ?>

        <?php else:?>
            <?php require "check_cookie.php"?>
        <?php endif;?>
    <?php elseif ($_COOKIE['kind_of_chill'] == 'banquet'):?>
        <?php  if(!($_COOKIE['user'] == '')): ?>
        <div class="block2">
            <h2> Бронирование банкета</h2>
            <h5>Цена - 3000 рублей с человека</h5>
            <h3>Заполните поля</h3>
            <form method="post" action="">
                <input type="text" name="amount" required placeholder="количество человек"><br><br>
                <input required type="date" name="date"><br><br>
                <input type="radio" name="menu" required value="1"> Банкетное меню<font size="1"> (+1500р. c человека)</font><br>
                <input type="radio" name="menu" value="2"> Детское меню<font size="1"> (+1000р с человека)</font><br><br>
                <input class="green" type="submit" name="button_5" value="Отправить">
            </form>
            <br>
            <form method="post">
                <input name="disreg" type="submit" value="Поменять тип">
            </form>
            <?php if(isset($_POST['disreg'])){
                setcookie('kind_of_chill', ' banquet', time() - 1000);
                header("Location:http://localhost:8888/Web-site_logic/Event.php");
            }
            ?>
        </div>
            <?php
            if(isset($_POST['button_5'])){
                $book_1 = new booking(4);
                $book_1->Table();
            }
            ?>

        </div>

        <?php else:?>
            <?php require "check_cookie.php"?>
        <?php endif;?>
    <?php endif;?>

<?php elseif(!($_COOKIE['manager'] == '')):?>
<?php  if(!($_COOKIE['manager'] == '')): ?>
<div  class="block_admin">
    <h3>Свободные заявки : </h3><br><br>
    <?php
        $lol = new aims();
        $lol->free_events_func();
    ?>
</div>
    <?php else:?>
        <?php require "check_cookie.php"?>
    <?php endif;?>

<?php else:?>
    <?php require "check_cookie.php" ?>
<?php endif;?>
