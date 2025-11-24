<div id="broker-wrapper" class="team">
    <?php
    foreach ($team as $member){
        echo $portal->render("team-member.php",  array(
            'member' => $member
        ));
    }
    ?>
</div>