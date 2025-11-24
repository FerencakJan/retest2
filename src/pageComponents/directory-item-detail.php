<div class="directory-item directory-item--detail">
    <div class="directory-item__logo">
        <img src="<?php echo $office['logo_url']; ?>" alt="<?php echo $office['name']; ?>">
    </div>
    <div class="directory-item__content">
        <h1 class="title title--lg directory-item__title"><?php echo $office['name']; ?></h1>
        <p class="directory-item__location"><?php echo $office['address']; ?></p>
        <div class="directory-item__data">
            <dl>
                <dt>Mobil:</dt>
                <dd><strong><a
                            href="tel:<?php $mobilDes = unserialize($office['mobil']); echo $mobilDes[0]; ?>"><?php echo $mobilDes[0]; ?></a></strong>
                </dd>
                <dt>Telefon:</dt>
                <dd><strong><a
                            href="tel:<?php $phoneDes = unserialize($office['phone']); echo $phoneDes[0]; ?>"><?php echo $phoneDes[0]; ?></a></strong>
                </dd>
                <dt>Web:</dt>
                <dd><strong><a href="<?php echo $office['www']; ?>" y\
                            target="_blank"><?php echo $office['www']; ?></a></strong></dd>
            </dl>
        </div>
    </div>
    <div class="directory-item__content directory-item__content--right">
        <div class="directory-item__data">
            <div>
                Moje nabídka:
            </div>
            <div>
                <dl>
                    <dt>Domy:</dt>
                    <dd><strong>120</strong></dd>

                    <dt>Byty:</dt>
                    <dd><strong>65</strong>

                    <dt>Rekreace:</dt>
                    <dd><strong>65</strong>

                    <dt>Komerční:</dt>
                    <dd><strong>65</strong>
                </dl>
                <dl>
                    <dt>Prodej:</dt>
                    <dd><strong>120</strong>

                    <dt>Pronájem:</dt>
                    <dd><strong>65</strong>

                    <dt>Dražba:</dt>
                    <dd><strong>65</strong>
                </dl>
            </div>
        </div>
    </div>
</div>