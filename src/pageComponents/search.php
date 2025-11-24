<div class="search">
  <h1 class="search__title">Nejsnažší cesta do nového domova</h1>
  <form class="form" id="listing-form" method="POST" action="/search-form/">
    <!-- <div class="form__row">
      <div class="form__item form__item--grow search__location">
        <label class="search__label" for="location">Lokalita</label>
        <input class="search__input" type="text" id="location" placeholder="Např.: Mladá Boleslav">
      </div>
    </div> -->
    <div class="form__row form__row--simple">
      <div class="form__item form__item--full">
        <div class="filter filter--location">
          <?php include "filter-dropdown-city.php" ?>
        </div>
      </div>
    </div>
    <!-- <div class="form__row">
      <div class="form__item"><label for="">Typ nemovitosti</label></div>
      <div class="form__item">
        <div class="custom-select custom-select--clear custom-select--icons">
          <select name="" id="">
            <option value="" id="appartment">Byt</option>
            <option value="" id="house">Domy</option>
            <option value="" id="recreation">Rekreační</option>
            <option value="" id="commerce">Komerční</option>
            <option value="" id="land">Pozemek</option>
            <option value="" id="others">Ostatní</option>
          </select>
        </div>
      </div>
    </div> -->
    <div class="form__row form__row--simple">
      <div class="form__item form__item--full">
        <div class="filter filter--simple" id="filter-simple-select">
          <?php include "filter-dropdown.php" ?>
        </div>
      </div>
    </div>
    <!-- <div class="form__row">
      <div class="form__item"><label for="">Typ inzerátu</label></div>
      <div class="form__item">
        <div class="form-radio__wrap">
          <div class="form-radio form-radio--tag">
            <input type="radio" name="radio-choice" id="radio-choice-1" value="choice-1" checked="checked">
            <label class="form-label" for="radio-choice-1">Vše</label>
          </div>
          <div class="form-radio form-radio--tag">
            <input type="radio" name="radio-choice" id="radio-choice-2" value="choice-2">
            <label class="form-label" for="radio-choice-2">Prodej</label>
          </div>
          <div class="form-radio form-radio--tag">
            <input type="radio" name="radio-choice" id="radio-choice-3" value="choice-3">
            <label class="form-label" for="radio-choice-3">Pronájem</label>
          </div>
          <div class="form-radio form-radio--tag">
            <input type="radio" name="radio-choice" id="radio-choice-4" value="choice-4">
            <label class="form-label" for="radio-choice-4">Dražby</label>
          </div>
        </div>
      </div>
    </div> -->
    <div class="form__row form__row--bottom">
      <div class="form__item">
        <a href="#search-advanced" class="btn btn--text-icon js-modal-open"><span class="icon icon--icon-settings">
          <svg class="icon__svg"><use xlink:href="#icon-settings"></use></svg>
        </span>Pokročilé hledání</a>
      </div>
      <div class="form__item"><button class="btn">Zobrazit výsledky</button></div>
    </div>
  </form>
</div>
