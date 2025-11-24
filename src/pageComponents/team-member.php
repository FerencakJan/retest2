<div class="team-member">
    <div class="team-member__image">
        <a href="/detail-broker/<?php echo ($member['broker_id']); ?>/">
            <img src="<?php echo $member['photo']; ?>" alt="<?php echo $member['name']; ?>">
        </a>
    </div>
    <strong>
        <a href="/detail-broker/<?php echo ($member['broker_id']); ?>/">
            <?php echo $member['name']; ?>
        </a>
    </strong>
    <p>
        <a
            href="tel:<?php $mobilDes = unserialize($member['mobil']);
                        echo $mobilDes[0]; ?>"><?php echo $mobilDes[0]; ?></a>
    </p>
    <p>
        <a
            href="mailto:<?php $emailDes = unserialize($member['email']);
                            echo $emailDes[0]; ?>"><?php echo $emailDes[0]; ?></a>
    </p>

    <a href="/detail-broker/<?php echo ($member['broker_id']); ?>/" class="team-member__btn__wrap">
        <span class="team-member__btn btn">Nemovitosti makléře</span>
    </a>
</div>