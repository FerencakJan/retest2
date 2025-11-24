<?php
$photoCount = count($photos);
$videoCount = count($videos);
$youtubeCount = count($youtubeVideos);
$matterportCount = count($matterport);
?>



<section class="gallery">
    <article class="container container--content" role="article">
        <div class="gallery__menu-wrap">
            <div class="gallery__menu">
                <?php if ($photoCount > 0) { ?>
                <a href="#photo" class="gallery__menu-item js-gallery-filter is-active">
                    <span class="icon icon--icon-camera">
                        <svg class="icon__svg">
                            <use xlink:href="#icon-camera"></use>
                        </svg>
                    </span>
                    Fotografie (<?php echo $photoCount; ?>)
                </a>
                <?php } ?>
                <?php if ($youtubeCount > 0) { ?>
                <a href="#video-youtube" class="gallery__menu-item js-gallery-filter">
                    <span class="icon icon--icon-youtube">
                        <svg class="icon__svg">
                            <use xlink:href="#icon-youtube"></use>
                        </svg>
                    </span>
                    Video YouTube (<?php echo $youtubeCount; ?>)
                </a>
                <?php } ?>
                <?php if ($videoCount > 0) { ?>
                <a href="#video-mp4" class="gallery__menu-item js-gallery-filter">
                    <span class="icon icon--icon-video">
                        <svg class="icon__svg">
                            <use xlink:href="#icon-video"></use>
                        </svg>
                    </span>
                    Video MP4 (<?php echo $videoCount; ?>)
                </a>
                <?php } ?>
                <?php if ($matterportCount > 0) { ?>
                <a href="#video-matterport" class="gallery__menu-item js-gallery-filter">
                    <span class="icon icon--icon-video">
                        <svg class="icon__svg">
                            <use xlink:href="#icon-video"></use>
                        </svg>
                    </span>
                    Matterport (<?php echo $matterportCount; ?>)
                </a>
                <?php } ?>
            </div>

        </div>
        <div class="gallery__content">
            <div class="gallery__grid js-gallery js-gallery__grid">
                <?php if ($photoCount > 0) { ?>
                <div class="gallery__grid-filter" id="photo">
                    <?php
            foreach ($photos as $photo) {
            ?>
                    <a href="<?php echo ($photo); ?>" class="gallery__grid-item
          <?php if ($photoCount == 1) {
                echo 'gallery__grid-item--full';
              } ?>
          <?php if ($photoCount == 2) {
                echo 'gallery__grid-item--double';
              } ?>
          <?php if ($photoCount > 2 and $photoCount <= 4) {
                echo 'gallery__grid-item--half';
              } ?>
          <?php if ($photoCount >= 5 and $photoCount <= 6) {
                echo 'gallery__grid-item--third';
              } ?>
          ">
                        <img loading="lazy" <?php if ($portal->renderRichSnippets()) { ?>itemprop="image" <?php } ?>
                            src="<?php echo $photo; ?>" alt="">
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>

                <?php if ($youtubeCount > 0) { ?>
                <div class="gallery__grid-filter" id="video-youtube">
                    <?php
            foreach ($youtubeVideos as $youtubeVideo) {
            ?>
                    <!-- <a href="#" -->
                    <!-- class="gallery__grid-item gallery__grid-item--full gallery__grid-item--video js-video-start" -->
                    <!-- data-video-id=<?php echo ($youtubeVideo); ?>> -->
                    <!-- <img loading="lazy" src="https://img.youtube.com/vi/<?php echo ($youtubeVideo); ?>" alt=""> -->
                    <iframe width="100%" height="100%"
                        src="https://www.youtube.com/embed/<?php echo ($youtubeVideo); ?>" title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    <!-- </a> -->
                    <?php } ?>
                </div>
                <?php } ?>

                <?php if ($videoCount > 0) { ?>
                <div class="gallery__grid-filter" id="video-mp4">
                    <?php
            foreach ($videos as $video) {
            ?>
                    <!-- <a href="#"
                        class="gallery__grid-item gallery__grid-item--full gallery__grid-item--video js-video-start"
                        data-video-id=<?php echo ($video); ?>> -->
                    <!-- <img loading="lazy" src="https://img.youtube.com/vi/<?php echo ($video); ?>" alt=""> -->
                    <video controls style="width: 100%; height: 100%;">
                        <source src="<?php echo ($video); ?>" type="video/mp4">
                    </video>
                    <!-- </a> -->
                    <?php } ?>
                </div>
                <?php } ?>

                <?php if ($matterportCount > 0) { ?>
                <div class="gallery__grid-filter" id="video-matterport">
                    <?php
            foreach ($matterport as $matterportVideo) {
            ?>
                    <a href="#" class="gallery__grid-item gallery__grid-item--full js-video-start"
                        data-video-url="<?php echo ($matterportVideo); ?>">
                        <!-- <img loading="lazy" src="<?php echo ($matterportVideo); ?>" alt=""> -->
                        <iframe width="100%" height="100%" src="<?php echo ($matterportVideo); ?>" frameborder="0"
                            allowfullscreen></iframe>
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>


    </article>
</section>