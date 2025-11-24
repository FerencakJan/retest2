<?php
// search sem príde z estate-agency.php ako parameter 'search',
// ale pre istotu fallbackneme na $_GET
if (!isset($search)) {
  $search = isset($_GET['search'])
    ? filter_var($_GET['search'], FILTER_SANITIZE_STRING)
    : '';
}
?>

<form method="GET" action="/realitni-kancelar/">
  <div class="search-filter search-filter__agencies">

    <div class="search-filter__search">
      <input
        name="search"
        type="text"
        placeholder="Zadejte název realitní kanceláře"
        value="<?php echo htmlspecialchars($search, ENT_QUOTES); ?>"
      >
    </div>

    <button class="btn btn--md filter__end" type="submit">Zobrazit</button>
  </div>
</form>
