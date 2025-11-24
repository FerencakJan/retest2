<style type="text/css">
  /* STYLY POUZE PRO STYLEGUIDE - NEPOUŽÍVAT NA PRODUKCI */
  br + h2 {
    color: #fff !important;
    background: #FD064A;
    line-height: normal;
    font-size: 14px;
    margin: 40px 0 20px 0;
    padding: 7px 7px 5px 7px;
    text-transform: uppercase;
  }
</style>

<main role="main" class="main">

  <section class="section">

    <div class="container">

      <br>
      <h2>Pages & modals</h2>

      <div class="row">
        <div class="col col--2">
          <ul>
            <li><a href="/">Homepage</a></li>
            <li><a href="detail.php">Detail</a></li>
            <li><a href="listing.php">Výpis</a></li>
            <li><a href="estate-agency.php">Realitní kanceláře</a></li>
            <li><a href="estate-agency-detail.php">Realitní kanceláře detail</a></li>
            <li><a href="auction.php">Aukce</a></li>
            <li><a href="auction-detail.php">Aukce detail</a></li>
            <li><a href="blog.php">Blog</a></li>
            <li><a href="blog-detail.php">Blog detail</a></li>
            <li><a href="404.php">404</a></li>
            <li><a href="500.php">500</a></li>
            <li><a href="typography.php">Typografie (tato stránka)</a></li>
          </ul>
        </div>
        <div class="col col--2">
          <ul>
            <li><a href="#modal" class="js-modal-open">Simple modal</a></li>
            <li><a href="#search-advanced" class="js-modal-open">Modal vyhledávání</a></li>
          </ul>
        </div>
      </div>

      <br>
      <h2>SVG Icons</h2>

      <?php @include "../src/pageComponents/icons.php" ?>

      <br>
      <h2>Typography</h2>

      <h1>HTML Ipsum Presents</h1>

      <p><strong>Pellentesque habitant morbi tristique</strong> senectus et netus et malesuada fames ac turpis egestas. Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. Donec eu libero sit amet quam egestas semper. <em>Aenean ultricies mi vitae est.</em> Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. Vestibulum erat wisi, condimentum sed, <code>commodo vitae</code>, ornare sit amet, wisi. Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. <a href="#">Donec non enim</a> in turpis pulvinar facilisis. Ut felis.</p>

      <h2>Header Level 2</h2>

      <ol>
        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
        <li>Aliquam tincidunt mauris eu risus.</li>
      </ol>

      <blockquote>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus magna. Cras in mi at felis aliquet congue. Ut a est eget ligula molestie gravida. Curabitur massa. Donec eleifend, libero at sagittis mollis, tellus est malesuada tellus, at luctus turpis elit sit amet quam. Vivamus pretium ornare est.</p>
      </blockquote>

      <h3>Header Level 3</h3>

      <ul>
        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
        <li>
          Lorem ipsum dolor sit amet, consectetuer adipiscing elit.
          <ul>
            <li>dolor sit amet, consectetuer</li>
            <li>dolor sit amet</li>
            <li>dolor sit amet, consectetuer</li>
          </ul>
        </li>
        <li>Aliquam tincidunt mauris eu risus.</li>
      </ul>

      <ul class="list">
        <li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>
        <li>Aliquam tincidunt mauris eu risus.</li>
      </ul>

      <h4>Header level 4</h4>

      <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ratione aperiam dolore modi, deserunt vitae iusto nesciunt? Obcaecati sint fuga commodi similique culpa cumque adipisci, velit tenetur dicta laborum impedit numquam.</p>

      <br>
      <h2>Buttons</h2>

      <p>
        <input type="submit" class="btn" value="Button">
        <button class="btn">Button</button>
        <a class="btn">Button</a>
      </p>

      <p>
        <input type="submit" class="btn btn--md" value="Button md">
        <button class="btn btn--md">Button md</button>
        <a class="btn btn--md">Button md</a>
      </p>

      <p>
        <input type="submit" class="btn btn--sm" value="Button sm">
        <button class="btn btn--sm">Button sm</button>
        <a class="btn btn--sm">Button sm</a>
      </p>

      <p>
        <input type="submit" class="btn btn--secondary" value="Button secondary">
        <button class="btn btn--secondary">Button secondary</button>
        <a class="btn btn--secondary">Button secondary</a>
      </p>

      <p>
        <input type="submit" class="btn btn--gray" value="Button gray">
        <button class="btn btn--gray">Button gray</button>
        <a class="btn btn--gray">Button gray</a>
      </p>

      <p>
        <button class="btn btn--forward">
          <span class="icon icon--icon-next">
            <svg class="icon__svg"><use xlink:href="#icon-next"></use></svg>
          </span>
          Zobrazit detail
        </button>
        <a href="#" class="btn btn--forward">
          <span class="icon icon--icon-next">
            <svg class="icon__svg"><use xlink:href="#icon-next"></use></svg>
          </span>
          Zobrazit detail
        </a>
      </p>

      <p>
        <button class="btn btn--icon">
          <span class="icon icon--icon-filter">
            <svg class="icon__svg"><use xlink:href="#icon-filter"></use></svg>
          </span>
          Button icon
        </button>
        <a class="btn btn--icon">
          <span class="icon icon--icon-filter">
            <svg class="icon__svg"><use xlink:href="#icon-filter"></use></svg>
          </span>
          Button icon
        </a>
      </p>

      <p>
        <input type="submit" class="btn btn--text" value="Button text">
        <button class="btn btn--text">Button text</button>
        <a class="btn btn--text">Button text</a>
      </p>

      <p>
        <button class="btn btn--text-icon">
          <span class="icon icon--icon-settings">
            <svg class="icon__svg"><use xlink:href="#icon-settings"></use></svg>
          </span>Pokročilé hledání
        </button>
        <a class="btn btn--text-icon">
          <span class="icon icon--icon-settings">
            <svg class="icon__svg"><use xlink:href="#icon-settings"></use></svg>
          </span>
          Pokročilé hledání
        </a>
      </p>

      <div class="alert">
        <p><strong>Tohle se povedlo. Děkujeme.</strong><br>Váš dotaz byl úspěšně odeslaný.</p>
      </div>

      <div class="alert alert--success">
        <p><strong>Tohle se povedlo. Děkujeme.</strong><br>Váš dotaz byl úspěšně odeslaný.</p>
      </div>

      <div class="alert alert--danger">
        <p><strong>Je nám líto, ale něco se pokazilo.</strong><br>Zkuste to prosím za chvíli znovu.</p>
      </div>

      <br>
      <h2>Tables</h2>

      <table class="table" cellpadding="0" cellspacing="0" border="1">
        <tr>
          <th>item</th>
          <th>item</th>
          <th>item</th>
          <th>item</th>
        </tr>
        <tr>
          <td>item</td>
          <td>item</td>
          <td>item</td>
          <td>item</td>
        </tr>
        <tr>
          <td>item</td>
          <td>item</td>
          <td>item</td>
          <td>item</td>
        </tr>
      </table>

      <br>
      <h2>Forms</h2>

      <form action="#" method="post">

        <p>
          <label for="name">Name:</label>
          <input type="text" id="name" value="Value">
          <input type="text2" id="name2" value="Value" class="is-success">
          <input type="text3" id="name3" value="Value" class="is-danger">
        </p>

        <p>
          <label for="email">Email:</label>
          <input type="email" id="email" value="Value">
          <input type="email" id="email2" value="Value" class="is-success">
          <input type="email" id="email3" value="Value" class="is-danger">
        </p>

        <p>
          <label for="password">Password:</label>
          <input type="password" id="password" value="Value">
          <input type="password" id="password2" value="Value" class="is-success">
          <input type="password" id="password3" value="Value" class="is-danger">
        </p>

        <p>
          <label for="message">Message:</label>
          <textarea id="message" rows="4" cols="50">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis, reprehenderit adipisci, rem officiis ut quaerat alias illo.</textarea>
        </p>

        <p>
          <input type="submit" value="Odeslat formulář" class="btn">
        </p>

        <div class="form-checkbox">
          <input type="checkbox" name="checkbox" id="checkbox">
          <span class="form-checkbox__box"></span>
          <label class="form-label" for="checkbox">Checkbox</label>
        </div>

        <div class="form-checkbox">
          <input type="checkbox" name="checkbox2" id="checkbox2" checked="checked">
          <span class="form-checkbox__box"></span>
          <label class="form-label" for="checkbox2">Checkbox <a href="#">checked</a></label>
        </div>

        <div class="form-checkbox">
          <input type="checkbox" name="checkbox3" id="checkbox3" disabled="disabled">
          <span class="form-checkbox__box"></span>
          <label class="form-label" for="checkbox3">Checkbox disabled</label>
        </div>

        <div class="form-checkbox">
          <input type="checkbox" name="checkbox4" id="checkbox4">
          <span class="form-checkbox__box"></span>
          <label class="form-label" for="checkbox4">Multiple lines checkbox: Lorem ipsum dolor, sit amet consectetur adipisicing elit. Commodi dolorum eveniet fuga repellendus illo hic aliquid suscipit assumenda quam distinctio autem neque, dolores maiores recusandae iste sequi, blanditiis quia cumque.</label>
        </div>

        <div class="form-radio">
          <input type="radio" name="radio-choice" id="radio-choice-1" value="choice-1" checked="checked">
          <span class="form-radio__box"></span>
          <label class="form-label" for="radio-choice-1">Option 1</label>
        </div>

        <div class="form-radio">
          <input type="radio" name="radio-choice" id="radio-choice-2" value="choice-2">
          <span class="form-radio__box"></span>
          <label class="form-label" for="radio-choice-2">Option 2</label>
        </div>

      </form>

      <br>
      <h2>Plugins</h2>

      <p><a href="#" class="js-video-start" data-video-id="tX1KM8WJcjc">Promo Youtube video into modal</a></p>
      <p class="js-gallery">
        <a href="/build/img/aimgroup.jpg">SimpleLightBox gallery item</a>
      </p>

      <br>
      <h2>Page components (uvnitř containeru)</h2>
      <?php @include "../src/pageComponents/around.php" ?>
      <?php @include "../src/helpers/pageHelpers.php" ; ?>
      <br>
      <?php @include "../src/pageComponents/diary-item.php" ?>
      <br>
      <?php @include "../src/pageComponents/diary-item-inline.php" ?>
      <br>
      <?php @include "../src/pageComponents/directory-item.php" ?>
      <br>
      <?php @include "../src/pageComponents/directory-item-detail.php" ?>
      <br>
      <?php @include "../src/pageComponents/directory-item-no-logo.php" ?>
      <br>
      <?php @include "../src/pageComponents/estate.php" ?>
      <br>
      <?php @include "../src/pageComponents/estate-alert.php" ?>
      <br>
      <?php @include "../src/pageComponents/estate-auction.php" ?>
      <br>
      <?php @include "../src/pageComponents/estate-no-image.php" ?>
      <br>
      <?php @include "../src/pageComponents/estate-simple.php" ?>
      <br>
      <?php @include "../src/pageComponents/gallery-simple.php" ?>
      <br>
      <?php @include "../src/pageComponents/interested.php" ?>
      <br>
      <p><?php @include "../src/pageComponents/join.php" ?></p>
      <p><?php @include "../src/pageComponents/join-sm.php" ?></p>
      <br>
      <?php @include "../src/pageComponents/location-filter.php" ?>
      <br>
      <?php @include "../src/pageComponents/pagination.php" ?>
      <br>
      <?php @include "../src/pageComponents/search-filter-agencies.php" ?>
      <br>
      <?php @include "../src/pageComponents/search-filter-blog.php" ?>
      <br>
      <?php @include "../src/pageComponents/team.php" ?>
      <br>
      <?php @include "../src/pageComponents/team-member.php" ?>
      <br>

    </div>

    <div class="container">
      <br>
      <h2>Page components (s vlastním containerem)</h2>
    </div>

    <?php @include "../src/pageComponents/back.php" ?>
    <br>
    <?php @include "../src/pageComponents/breadcrumbs.php" ?>
    <br>
    <?php @include "../src/pageComponents/breadcrumbs-gray.php" ?>
    <br>
    <?php @include "../src/pageComponents/breadcrumbs-full.php" ?>
    <br>
    <?php @include "../src/pageComponents/diary.php" ?>
    <br>
    <?php @include "../src/pageComponents/filter.php" ?>
    <br>
    <?php @include "../src/pageComponents/next.php" ?>

    <?php @include "../src/pageComponents/recent.php" ?>

    <?php @include "../src/pageComponents/service.php" ?>
    <br>

  </section>

</main>

<?php @include "../src/pageComponents/footer.php" ?>
