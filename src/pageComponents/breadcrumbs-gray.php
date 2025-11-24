<section class="breadcrumbs breadcrumbs--gray" role="navigation" aria-label="Drobečková navigace">
    <div class="container container--content">
        <div class="breadcrumbs__wrap">
            <a href="<?php if(isSet($ajax)) { echo '#'; } else { echo '/'; } ?>" class="breadcrumbs__back"
                id="breadcrumbsBack">Zpět</a>
            <?php echo $data['navigace']; ?>
        </div>
    </div>
</section>