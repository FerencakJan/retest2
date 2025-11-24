
<?php if($portal->renderRichSnippets()){ ?>
<div class="rating">
  <div class="rating__stars" 
       data-advert-id="<?php echo $property['advert_id']; ?>"
       data-stars-avg="<?php echo $property['score_avg']; ?>"
       data-stars-cnt="<?php echo $property['score_cnt']; ?>" 
       data-score-type="detail"
       data-score-value="<?php echo $data['score']['value']; ?>">
    <?php
      $ratings = ['blank','blank','blank','blank','blank'];

      for($i = 0; $i < $starValue - 1; $i++){
        $ratings[$i] = 'full';
      }
      if($property['score_avg'] - floor($starValue) > 0.5) {
        $ratings[floor($starValue)] = 'half';
      }

      for ($i = 0; $i < 5; $i++) {
        echo "<span class='icon icon--icon-star-{$ratings[$i]}' data-value='{$i}'>
                <svg class='icon__svg'><use xlink:href='#icon-star-{$ratings[$i]}'></use></svg>
              </span>";
      }
    ?>
  </div>
  <p>
    <?php echo round($property['score_avg'], 1); ?> z 5.0
    (<span itemprop="reviewCount"><?php echo $data['score']['cnt']; ?></span> hodnocení)</strong>
  </p>
</div>
<?php } ?>