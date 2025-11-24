<!-- footer -->
<footer class="footer">

  <?php
  if ($portal->getPageData()['template'] === "actions/typography.php"){
    include __DIR__."/../pageComponents/backTypography.php";
  }
  ?>

  <?php
  if (!isSet($ajax)){
    if ($portal->getPageData()['template'] != "actions/homepage.php"){
      include "recent.php";
    }
  }
  ?>

  <?php include "service.php" ?>

  <div class="footer__wrap">
    <article class="container" role="article">
      <img class="footer__logo" src="/build/img/logo@2x.png" width="186" height="" alt="">
      <ul class="footer-nav">
        <li><a href="/">Domů</a></li>
        <li><a href="/reality">Nemovitosti</a></li>
        <li><a href="/realitni-kancelar/">Adresář RK</a></li>
        <li><a href="/kontakt/">Kontakty</a></li>
        <li><a href="#">O nás</a></li>
        <li><a href="/blog/">Blog</a></li>
        <li><a href="#">Obchodní podmínky</a></li>
        <li><a href="#">Právní informace</a></li>
        <li><a href="/souhlas-se-zpracovanim-osobnich-udaju-gdpr/text/12780">GDPR</a></li>
      </ul>
    </article>
  </div>

</footer>
