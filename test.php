<?php
    for($i=1;$i<=40;$i++){
        $date = getdate(time()+(86400*$i));
        echo '<br>';
        print_r($date);
    }
?>