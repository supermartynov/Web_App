<?php require "Event_opener.php" ?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script>
    function send() {
        $.ajax({
            type: "POST",
            url: "Personal_place_logic.php",
            data: "test=1",
            success: function(html) {
                $("#content").html(html);
            }

        });
        return false;
    };
</script>
<br>
<button onclick="send()">Вывести предстоящие заявки</button>
<div  id="content" >

</div>
<?php require "closer.php" ?>

