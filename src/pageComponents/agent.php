<div class="agent">
    <h3 class="title title--compact agent__title">Kontakty na makléře</h3>
    <div class="agent__wrap">
        <div class="agent__detail">
            <div class="agent__info">
                <div class="agent__image">
                    <img <?php if($portal->renderRichSnippets()){ ?>itemprop="image" <?php } ?>
                        src="<?php echo $broker['photo'] == '' ? '/mlift/images/brokers/empty.jpg' : $broker['photo']; ?>"
                        alt="">
                </div>
                <h3 <?php if($portal->renderRichSnippets()){ ?>itemprop="name" <?php } ?>
                    class="list-brokers__item__title"><?php echo $broker['name']; ?></h3>
                <?php
        $mobilDis = unserialize($broker['mobil']);
        if(count($mobilDis) > 0) {
        ?>
                <p><?php echo PhoneRenderer::renderPhone($mobilDis[0],$property['advert_id'], $portal->renderRichSnippets()); ?>
                </p>

                <?php } ?>

                <?php
        $emailDis = unserialize($broker['email']);
        if(count($emailDis) > 0) {
        ?>
                <p>
                    <a <?php if($portal->renderRichSnippets()){ ?>itemprop="email" content="<?php echo $emailDis[0]; ?>"
                        <?php } ?> href="mailto:<?php echo $emailDis[0]; ?>">
                        <strong><?php echo $emailDis[0]; ?></strong>
                    </a>
                </p>
                <?php } ?>
            </div>
            <div class="btn-wrap">
                <a href="/detail-broker/<?php echo($broker['broker_id']); ?>/" class="btn btn--clear">Nemovitosti
                    makléře</a>
                <a href="/detail-rk/<?php echo($property['company_id']); ?>/" class="btn btn--clear">Nemovitosti
                    společnosti</a>
            </div>
        </div>
        <div class="agent__agency">
            <div class="agent__agency-logo">
                <img src="<?php echo $company['logo_url']; ?>" alt="">
            </div>
            <div class="agent__agency-info">
                <h3 class="title title--text"><?php echo $company['name']; ?></h3>
                <ul>
                    <li <?php if($portal->renderRichSnippets()){ ?>itemprop="address"
                        content="<?php echo $company['address']; ?>" <?php } ?>><?php echo $company['address']; ?></li>
                    <?php
          $phoneDis = unserialize($broker['phone']);
          if(count($phoneDis) > 0) { ?>
                    <li class="track-phone-number">
                        <?php echo PhoneRenderer::renderPhone($phoneDis[0],$property['advert_id'],$portal->renderRichSnippets()); ?>
                        <?php } ?>
                    <li><a href="<?php echo $company['www']; ?>" target="_blank"><?php echo $company['www']; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>