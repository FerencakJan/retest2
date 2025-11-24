<?php
$filter = 0;
$order = '';
if (isSet($_POST['filter'])) { $filter = (int)$_POST['filter']; }
if (isSet($_POST['order'])) { $order = $_POST['order']; }
?>
<form method="POST" action="/blog/">
    <div class="search-filter">

        <div class="search-filter__search">
            <input name="search" type="text" placeholder="Zadejte název článku" value="<?php echo $search?>">
        </div>
        <!--    <div class="search-filter__select">-->
        <!--      <label for="filter1">Kategorie:</label>-->
        <!--      <div class="custom-select custom-select--filter">-->
        <!--        <select name="filter" id="filter1" onchange="this.form.submit()">-->
        <!--          <option value="">Vše</option>-->
        <!--          <option value="">Vše</option>-->
        <!--          <option value="">Vše</option>-->
        <!--          <option value="">Vše</option>-->
        <!--          <option value="">Vše</option>-->
        <!--        </select>-->
        <!--      </div>-->
        <!--    </div>-->
        <!-- <div class="search-filter__select">
      <label for="filter2">Řazení:</label>
      <div class="custom-select custom-select--filter">
        <select name="order" id="filter2" onchange="this.form.submit()">
          <option value="date_desc" <?php if ($order == 'date_desc'){ echo 'selected'; } ?>>Nejnovější</option>
          <option value="date_asc" <?php if ($order == 'date_asc'){ echo 'selected'; } ?>>Nejstarší</option>
        </select>
      </div>
    </div> -->
        <div class="search-filter__select">
            <button class="btn">Zobrazit</button>
        </div>
    </div>
</form>