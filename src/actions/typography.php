<?php
$data = $portal->getPageData()['getData'];
$text = $data['html']['text'];
$getTextService = new GetTextService($portal);

function renderPhotos($data)
{
  $fancyboxId = rand(100,1000);
  $gallery = "<section class=\"gallery-simple__wrap\">

      <div class=\"container\">
        <div class=\"gallery-simple gallery-simple--typo js-gallery\">";

  $iMax = count($data['sub_items']) > 4 ? 4 : count($data['sub_items']);
  for ($i = 0; $i < $iMax; $i++) {
    $subItem = $data['sub_items'][$i];
    $gallery .= "<a href='{$subItem['img_url']}' class=\"gallery-simple__item js-fancybox\" class='gallery-simple__item' data-fancybox-group='fancybox-{$fancyboxId}'>";
    $gallery .= "<img loading='lazy' src='{$subItem['img_url']}' alt=\"\" >";

    if ($i == 3 && count($data['sub_items']) > 4) {
      $count = count($data['sub_items']) - 3;
      $gallery .=
        "<span class=\"gallery-simple__more\">

              <span>+ {$count} fotky</span>

              <button class=\"btn\" data-index=\"4\">Zobrazit více</button>

          </span>";
    }
    $gallery .= "</a>";
  }

  if(count($data['sub_items']) > 4){
    for($i = 4; $i < count($data['sub_items']); $i++){
      $subItem = $data['sub_items'][$i];
      $gallery .= "<a style='display:none' href='{$subItem['img_url']}' class=\"gallery-simple__item js-fancybox\" data-fancybox-group='fancybox-{$fancyboxId}'>";
      $gallery .= "<img src='{$subItem['img_url']}' alt=\"\" >";
      $gallery .= "</a>";
    }
  }

  // ukoncit galerku
  $gallery .= "
          </div>
          </div>
      </section>";

  return $gallery;
}

preg_match_all('/##photos-[0-9]*##/', $text, $matches, PREG_UNMATCHED_AS_NULL);
foreach ($matches[0] as $match) {
  $id = abs((int)filter_var($match, FILTER_SANITIZE_NUMBER_INT));
  $dataText = $getTextService->getText($id);
  $text = str_replace($match, renderPhotos($dataText), $text);
}
?>



<main class="main">

  <section class="typo-page">
    <article class="container container--xlg">
      <div class="blog-detail__hero-image">
        <img src="/build/img/blog-hero-image.jpg" alt="">
      </div>
    </article>

    <article class="container container--content" role="article">
        <?php echo $text; ?>
    </article>
  </section>

</main>

<?php @include __DIR__."/../pageComponents/footer.php" ?>
