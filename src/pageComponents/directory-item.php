<div class="directory-item">
    <a href="<?php echo $office['alias_']; ?>" class="directory-item__link"></a>
    <div class="directory-item__logo">
        <img src="<?php echo $office['logo_url']; ?>" alt="<?php echo $office['name']; ?>">
    </div>
    <div class="directory-item__content">
        <div class="estate__action">
            <a href="<?php echo $office['alias_']; ?>" class="btn btn--forward">
                <span class="icon icon--icon-next">
                    <svg class="icon__svg">
                        <use xlink:href="#icon-next"></use>
                    </svg>
                </span>
                Zobrazit detail
            </a>
        </div>
        <h3 class="title title--sm directory-item__title"><?php echo $office['name']; ?></h3>
        <p class="directory-item__location"><?php echo $office['address']; ?></p>
        <div class="directory-item__data">
            <dl>
                <?php
        $mobilDes = unserialize($office['mobil']);
        $phoneDes = unserialize($office['phone']);
        ?>
                <dt>Mobil:</dt>
                <?php if (!empty($mobilDes)) { ?>
                <dd><strong><a href="tel:<?php  echo $mobilDes[0]; ?>"><?php echo $mobilDes[0]; ?></a></strong></dd>
                <?php } else { ?>
                <dd><strong><a href="">&nbsp;</a></strong></dd>
                <?php } ?>
                <dt>Telefon:</dt>
                <?php if (!empty($phoneDes)) { ?>
                <dd><strong><a href="tel:<?php  echo $phoneDes[0]; ?>"><?php echo $phoneDes[0]; ?></a></strong></dd>
                <?php } else { ?>
                <dd><strong><a href=""></a>&nbsp;</strong></dd>
                <?php } ?>
                <dt>Web:</dt>
                <dd><strong><a href="<?php echo $office['www']; ?>"
                            target="_blank"><?php echo $office['www']; ?></a></strong></dd>
            </dl>
        </div>
        <p class="directory-item__last">Nabídek: <strong><?php echo $office['property_cnt']; ?></strong></p>
    </div>
</div>