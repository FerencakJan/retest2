<!-- header -->
<?php

use Portal\Helpers\Resources\MenuResource;

$pageData = $portal->getPageData(); ?>

<header class="header <?php if ($pageData['action'] === 'hp') { ?>header--front<?php } ?>">
  <script>
    var formAttr = [{name:'company_id', value:"<?php echo $portal->getPortalId(); ?>"},{name:'company_name', value:"<?php echo $portal->getPortalName(); ?>"},{name:'company_country_id', value:"<?php echo $portal->getPortalCountryId(); ?>"},{name:'company_id_array', value:"<?php echo $portal->getPortalIdArray(); ?>"},{name:'company_lang_id', value:"<?php echo $portal->getPortalLangId(); ?>"},{name:'company_type', value:"<?php echo $portal->getPortalCompanyType(); ?>"},{name:'client_ip', value:"<?php echo $portal->getClientIP(); ?>"}];
  </script>
  <div class="container container--xlg">
    <div class="header__block">
      <a href="/" class="header__logo">
        <img loading="lazy" src="/build/img/logo@2x.png" width="166" height="" alt="">
      </a>
      <nav class="header__nav" role="navigation">
        <ul>
          <li><a href="/">Domů</a></li>
          <li><a href="/listing/">Vyhledat</a></li>
          <li><a href="/realitni-kancelar/">Adresář RK</a></li>
          <li><a href="/kontakt/">Kontakty</a></li>
          <li><a href="/oblibene/"><span>Oblíbené (<span class="favorites-count">0</span>)</span></a></li>
        </ul>
      </nav>
    </div>
    <div class="header__block">
      <nav class="header__nav" role="navigation">
        <ul>
          <li>
            <a href="#register" class="js-modal-open">
              <span class="icon icon--icon-user">
                <svg class="icon__svg"><use xlink:href="#icon-user"></use></svg>
              </span>
              Registrace
            </a>
          </li>
          <li>
            <a href="#login" class="js-modal-open">
              <span class="icon icon--icon-lock">
                <svg class="icon__svg"><use xlink:href="#icon-lock"></use></svg>
              </span>
              Přihlášení
            </a>
          </li>
        </ul>
      </nav>
      <div class="nav-switcher" tabindex="0" aria-label="Menu" role="button" aria-controls="navigation">
        <div class="nav-switcher--line"></div>
      </div>
    </div>
  </div>
</header>

<?php
  $menuResource = new MenuResource($portal->getServerName(), $portal);
  $menuItems = $menuResource->getData();
?>
<nav class="nav" role="navigation">
  <ul>
    <?php foreach($menuItems as $menuItem) {
      $title = $menuItem['title'] !== '' ? $menuItem['title'] : $menuItem['name'];

      if(count($menuItem['sub_menu']) > 0) {
        echo "<li>";
        echo "<a title=\"{$title}\" href=\"{$menuItem['alias_']}\">{$menuItem['name']}</a>";
        echo "<ul>";

        foreach($menuItem['sub_menu'] as $subMenuItem) {
          $title = $subMenuItem['title'] !== '' ? $subMenuItem['title'] : $subMenuItem['name'];
          echo "<li><a title=\"{$title}\" href=\"{$subMenuItem['alias_']}\">{$subMenuItem['name']}</a></li>";
        }

        echo "</ul>";
        echo "</li>";
      }else{
        echo "<li><a title=\"{$title}\" href=\"{$menuItem['alias_']}\">{$menuItem['name']}</a></li>";
      }
    } ?>
  </ul>
</nav>
