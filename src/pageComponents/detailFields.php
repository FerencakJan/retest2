<?php
/*

TZ1- internet_poskytovatel	varchar(100)
TZ1- internet_rychlost	int(11)
TZ1- internet_typ	int(11)

TZ1- pocet_fazi	int(11)
TZ1- jistice	int(11)


fastest__eurobydleni_properties
TZ1- multi_zdroj_teple_vody	varchar(100)
TZ1- multi_typ_studny	varchar(100)
TZ1- multi_ochrana	varchar(100)
TZ1- multi_topne_teleso	varchar(100)
TZ1- multi_zdroj_topeni	varchar(100)
 */
$fieldsNet = array(
  array('block' => 'Internetové připojení',
  'items' => array(
    array(
      'value' => 'internet_typ',
      'title' => 'Typ připojení',
      'type' => 'item',
    ),
    array(
      'value' => 'internet_rychlost',
      'title' => 'Rychlost v Mb/s',
      'type' => 'int',
    ),
    array(
      'value' => 'internet_poskytovatel',
      'title' => 'Poskytovatel internetu',
      'type' => 'text',
    ),
  )
  )
);

$fieldsShare = array(
  array('block' => 'Podílová nemovitost a podíly',
  'items' => array(
    array(
      'value' => 'podilova_nemovitost',
      'title' => 'Podílová nemovitost',
      'type' => 'yes',
    ),
    array(
      'value' => 'podil_cast',
      'title' => 'Podíl',
      'type' => 'int',
    ),
    array(
      'value' => 'podil_celek',
      'title' => 'Podíl z',
      'type' => 'int',
    ),
    array(
      'value' => 'podil_cast_spolecne',
      'title' => 'Podíl na společných částech',
      'type' => 'int',
    ),
    array(
      'value' => 'podil_celek_spolecne',
      'title' => 'Podíl na společných částech z',
      'type' => 'int',
    ),
  )
  )
);

$fields = array(
  array ('block' => 'Základní údaje',
    'items' => array(
      array(
        'value' => 'advert_code',
        'title' => 'Evidenční číslo',
        'type' => 'text',
      ),
      array(
        'value' => 'advert_type_eu',
        'title' => 'Typ nemovitosti',
        'type' => 'item',
      ),
      array(
        'value' => 'advert_subtype_eu',
        'title' => 'Upřesnění',
        'type' => 'item',
      ),
      array(
        'value' => 'advert_function_eu',
        'title' => 'Typ prodeje',
        'type' => 'item',
      ),
      array(
        'value' => 'flat_type',
        'title' => 'Typ bytu',
        'type' => 'item',
      ),
      array(
        'value' => 'flat_size',
        'title' => 'Dispozice',
        'type' => 'item',
      ),
      array(
        'value' => 'ownership_eu',
        'title' => 'Vlastnictví',
        'type' => 'item',
      ),
      array(
        'value' => 'apartment_number',
        'title' => 'Číslo bytu, apartmánu',
        'type' => 'int',
      ),
      array(
        'value' => 'typ_bytu',
        'title' => 'Typ bytu',
        'type' => 'item',
      ),
      array(
        'value' => 'typ_pronajmu',
        'title' => 'Typ pronájmu',
        'type' => 'item',
      ),
      array(
        'value' => 'ready_date',
        'title' => 'K nastěhování od',
        'type' => 'text',
      ),
      array(
        'value' => 'advert_status_eu',
        'title' => 'Stav inzerátu',
        'type' => 'item',
      ),
      array(
        'value' => 'energy_efficiency_rating_eu',
        'title' => 'En. náročnost',
        'type' => 'item',
      ),
      array(
        'value' => 'energy_performance_certificate_eu',
        'title' => 'Podle vyhlášky',
        'type' => 'item',
      ),
      array(
        'value' => 'energy_performance_summary',
        'title' => 'En. náročnost',
        'type' => 'text',
      ),
      array(
        'value' => 'energy_performance_replacement',
        'title' => 'Náhrada PENB vyúčtováním',
        'type' => 'yes',
      ),
    )
  ),
  array ('block' => 'Cena a údaje k ceně',
    'items' => array(
      array(
        'value' => 'advert_price',
        'title' => 'Cena',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_price_unit_eu',
        'title' => 'Měna',
        'type' => 'item',
      ),
      array(
        'value' => 'prevod_do_ov',
        'title' => 'Možný převod do OV',
        'type' => 'yes',
      ),
      array(
        'value' => 'pronajem_bez_provize',
        'title' => 'Pronájem bez platby provize',
        'type' => 'yes',
      ),
      array(
        'value' => 'bremeno',
        'title' => 'Pronájem bez platby provize',
        'type' => 'yes',
      ),
      array(
        'value' => 'poplatky_cena',
        'title' => 'Výše poplatků',
        'type' => 'int',
      ),
      array(
        'value' => 'anuita',
        'title' => 'Nesplacená anuita',
        'type' => 'int',
      ),
      array(
        'value' => 'provize_pronajem',
        'title' => 'Provize z pronájmu',
        'type' => 'int',
      ),
      array(
        'value' => 'vratna_kauce',
        'title' => 'Vratná kauce',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_price_negotiation',
        'title' => 'Cena k jednání',
        'type' => 'yes',
      ),
      array(
        'value' => 'advert_price_text_note',
        'title' => 'Poznámka k ceně',
        'type' => 'text',
      ),
      array(
        'value' => 'poplatky_cena',
        'title' => 'Výše poplatků',
        'type' => 'int',
      ),
      array(
        'value' => 'cost_of_living',
        'title' => 'Náklady na bydlení',
        'type' => 'int',
      ),
      array(
        'value' => 'poplatky',
        'title' => 'Poplatky popis',
        'type' => 'text',
      ),
      array(
        'value' => 'cena_konstrukce',
        'title' => 'Složení ceny',
        'type' => 'multi-item',
        'multi-item-id' => 394
      ),
      array(
        'value' => 'auction_kind_eu',
        'title' => 'Druh aukce, dražby',
        'type' => 'item',
      ),
      array(
        'value' => 'auction_date',
        'title' => 'Datum aukce',
        'type' => 'datetime',
      ),
      array(
        'value' => 'auction_tour',
        'title' => 'Datum 1. prohlídky',
        'type' => 'datetime',
      ),
      array(
        'value' => 'auction_tour2',
        'title' => 'Datum 2. prohlídky',
        'type' => 'datetime',
      ),
      array(
        'value' => 'auction_place',
        'title' => 'Místo konání aukce',
        'type' => 'text',
      ),
      array(
        'value' => 'price_minimum_bid',
        'title' => 'Minimální příhoz',
        'type' => 'int',
      ),
      array(
        'value' => 'price_expert_report',
        'title' => 'Znalecký posudek',
        'type' => 'int',
      ),
      array(
        'value' => 'price_auction_principal',
        'title' => 'Dražební jistota',
        'type' => 'int',
      ),
    )
  ),
  array ('block' => 'Adresa, lokalita, GPS',
    'items' => array(
      array(
        'value' => 'advert_locality',
        'title' => 'Přesná adresa',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_city',
        'title' => 'Město, obec',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_citypart',
        'title' => 'Část obce',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_street',
        'title' => 'Ulice',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_psc',
        'title' => 'PSČ',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_latitude',
        'title' => 'GPS latitude',
        'type' => 'text',
      ),
      array(
        'value' => 'locality_longitude',
        'title' => 'GPS longitude',
        'type' => 'text',
      ),
    )
  ),

  array ('block' => 'Klíčové vlastnosti',
    'items' => array(
      array(
        'value' => 'balcony',
        'title' => 'Bazén',
        'type' => 'yes',
      ),
      array(
        'value' => 'basin',
        'title' => 'Balkón',
        'type' => 'yes',
      ),
      array(
        'value' => 'cellar',
        'title' => 'Sklep',
        'type' => 'yes',
      ),
      array(
        'value' => 'garage',
        'title' => 'Garáž',
        'type' => 'yes',
      ),
      array(
        'value' => 'parking_lots',
        'title' => 'Parkovací stání',
        'type' => 'yes',
      ),
      array(
        'value' => 'loggia',
        'title' => 'Lodžie',
        'type' => 'yes',
      ),
      array(
        'value' => 'terrace',
        'title' => 'Terasa',
        'type' => 'yes',
      ),
      array(
        'value' => 'elevator',
        'title' => 'Výtah',
        'type' => 'yes',
      ),
      array(
        'value' => 'advert_low_energy',
        'title' => 'Nízkoenergetický',
        'type' => 'yes',
      ),
      array(
        'value' => 'easy_access',
        'title' => 'Bezbariérový',
        'type' => 'yes',
      ),
    )
  ),
  array ('block' => 'Plochy',
    'items' => array(
      array(
        'value' => 'usable_area',
        'title' => 'Obytná',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'usable_area_correct',
        'title' => 'Užitná',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'building_area',
        'title' => 'Zastavěná',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'floor_area',
        'title' => 'Podlahová',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'plocha_zastavena',
        'title' => 'Zastavěná',
        'type' => 'int',
      ),
      array(
        'value' => 'plocha_pozemek',
        'title' => 'Pozemek',
        'type' => 'int',
      ),
      array(
        'value' => 'estate_area',
        'title' => 'Pozemek',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'garden_area',
        'title' => 'Zahrada',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'terrace_area',
        'title' => 'Terasa',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'balcony_area',
        'title' => 'Balkon',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'loggia_area',
        'title' => 'Lodžie',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'cellar_area',
        'title' => 'Sklep',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'plocha_garaz',
        'title' => 'Garáž',
        'type' => 'int',
      ),
      array(
        'value' => 'basin_area',
        'title' => 'Bazén',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'nolive_total_area',
        'title' => 'Nebytové prostory',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'shop_area',
        'title' => 'Obchodní',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'production_area',
        'title' => 'Výroba',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'store_area',
        'title' => 'Sklady',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'offices_area',
        'title' => 'Kancelář',
        'type' => 'int',
        'add-after' => ' m<sup>2</sup>'
      ),
      array(
        'value' => 'plocha_ostatni',
        'title' => 'Ostatní',
        'type' => 'int',
      ),
    )
  ),
  array ('block' => 'Další vlastnosti',
    'items' => array(
      array(
        'value' => 'furnished_eu',
        'title' => 'Vybavení nábytkem',
        'type' => 'item',
      ),
      array(
        'value' => 'vybaveni',
        'title' => 'Vybavení',
        'type' => 'multi-item',
        'multi-item-id' => 98
      ),
      array(
        'value' => 'topeni',
        'title' => 'Topení',
        'type' => 'item',
      ),
      array(
        'value' => 'building_condition',
        'title' => 'Stav objektu',
        'type' => 'item',
      ),
      array(
        'value' => 'object_location_eu',
        'title' => 'Umístění v obci',
        'type' => 'item',
      ),
      array(
        'value' => 'building_type_eu',
        'title' => 'Konstrukce budovy',
        'type' => 'item',
      ),
      array(
        'value' => 'object_kind_eu',
        'title' => 'Typ budovy',
        'type' => 'item',
      ),
      array(
        'value' => 'ucel_nemovitosti',
        'title' => 'Účel nemovitosti',
        'type' => 'item',
      ),
      array(
        'value' => 'vyuziti',
        'title' => 'Využití',
        'type' => 'item',
      ),
    )
  ),
  array ('block' => 'Inženýrské sítě',
    'items' => array(
      array(
        'value' => 'site',
        'title' => 'Inž. sítě',
        'type' => 'multi-item',
        'multi-item-id' => 80
      ),
      array(
        'value' => 'multi_topeni',
        'title' => 'Možnosti topení',
        'type' => 'multi-item',
        'multi-item-id' => 554
      ),
      array(
        'value' => 'multi_voda',
        'title' => 'Voda',
        'type' => 'multi-item',
        'multi-item-id' => 481
      ),
      array(
        'value' => 'multi_plyn',
        'title' => 'Plyn',
        'type' => 'multi-item',
        'multi-item-id' => 518
      ),
      array(
        'value' => 'multi_odpad',
        'title' => 'Odpad',
        'type' => 'multi-item',
        'multi-item-id' => 505
      ),
      array(
        'value' => 'multi_telekomunikace',
        'title' => 'Telekomunikace',
        'type' => 'multi-item',
        'multi-item-id' => 484
      ),
      array(
        'value' => 'multi_elektro',
        'title' => 'Elektro',
        'type' => 'multi-item',
        'multi-item-id' => 577
      ),
    )
  ),

  array ('block' => 'Podlaží, počty místností',
    'items' => array(
      array(
        'value' => 'floor_number_eu',
        'title' => 'Podlaží objektu',
        'type' => 'item',
      ),
      array(
        'value' => 'floors',
        'title' => 'Počet NP',
        'type' => 'int',
      ),
      array(
        'value' => 'undeground_floors',
        'title' => 'Počet PP',
        'type' => 'show',
      ),
      array(
        'value' => 'advert_rooms_count',
        'title' => 'Počet místností',
        'type' => 'int',
      ),
      array(
        'value' => 'floor_definition',
        'title' => 'Druh podlaží',
        'type' => 'item',
      ),
      array(
        'value' => 'advert_room_count',
        'title' => 'Počet pokojů',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_bath_count',
        'title' => 'Počet koupelen',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_garage_count',
        'title' => 'Počet garáží',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_parking_count',
        'title' => 'Počet park. stání',
        'type' => 'int',
      ),
      array(
        'value' => 'parkovani_pocet_mist',
        'title' => 'Počet park. míst',
        'type' => 'int',
      ),
      array(
        'value' => 'advert_nolive_count',
        'title' => 'Počet nebyt. prostor',
        'type' => 'int',
      ),
      array(
        'value' => 'pocet_obchodu',
        'title' => 'Počet obchodů',
        'type' => 'int',
      ),
    )
  ),
  array ('block' => 'Okolí',
    'items' => array(
      array(
        'value' => 'vybavenost',
        'title' => 'Vybavenost okolí',
        'type' => 'multi-item',
        'multi-item-id' => 173
      ),
      array(
        'value' => 'multi_komunikace',
        'title' => 'Komunikace',
        'type' => 'multi-item',
        'multi-item-id' => 500
      ),
      array(
        'value' => 'doprava',
        'title' => 'Dopravní spojení',
        'type' => 'multi-item',
        'multi-item-id' => 166
      ),
    )
  ),
  array ('block' => 'Ostatní',
    'items' => array(
      array(
        'value' => 'charakteristika',
        'title' => 'Charakteristika',
        'type' => 'multi-item',
        'multi-item-id' => 73
      ),
      array(
        'value' => 'druh_zarizeni',
        'title' => 'Druh zařízení',
        'type' => 'multi-item',
        'multi-item-id' => 0
      ),
      array(
        'value' => 'multi_plyn',
        'title' => 'Plyn',
        'type' => 'multi-item',
        'multi-item-id' => 518
      ),
      array(
        'value' => 'multi_komunikace',
        'title' => 'Komunikace',
        'type' => 'multi-item',
        'multi-item-id' => 500
      ),
    )
  ),

  /*
  array ('block' => 'Údaje k dražbě',
      'items' => array(
          array(
              'value' => 'drazba_datum_konani',
              'title' => 'Datum konání dražby',
              'type' => 'date',
          ),
          array(
              'value' => 'drazba_misto_konani',
              'title' => 'Místo konání dražby',
              'type' => 'text',
          ),
          array(
              'value' => 'drazba_datum_termin_1',
              'title' => 'Datum prohlídky č.1',
              'type' => 'datetime',
          ),
          array(
              'value' => 'drazba_datum_termin_2',
              'title' => 'Datum prohlídky č.2',
              'type' => 'datetime',
          ),
          array(
              'value' => 'drazba_aukcni_jistina',
              'title' => 'DRažební jistina',
              'type' => 'int',
          ),
          array(
              'value' => 'drazba_znalecky_posudek',
              'title' => 'Znalecký posudek',
              'type' => 'int',
          ),
          array(
              'value' => 'drazba_minimalni_prihoz',
              'title' => 'Minimální příhoz',
              'type' => 'int',
          ),
          array(
              'value' => 'drazba_posudek_file',
              'title' => 'Posudek k dražbě, soubor',
              'type' => 'text',
          ),
          array(
              'value' => 'drazba_posudek_url',
              'title' => 'Posudek k dražbě, URL',
              'type' => 'text',
          ),
          array(
              'value' => 'drazba_vyhlaska_file',
              'title' => 'Dražební vyhláška, soubor',
              'type' => 'text',
          ),
          array(
              'value' => 'drazba_vyhlaska_url',
              'title' => 'Dražební vyhláška, URL',
              'type' => 'text',
          ),
      ),
  )
  */
);

if ($property['internet_typ'] > 0) {
  $fields = array_merge($fields, $fieldsNet);
}

if ($property['podilova_nemovitost'] > 0) {
  $fields = array_merge($fields, $fieldsShare);
}

$arrBlockDetail = array();
$value = 'neuvedeno';
foreach ($fields as $keyblock=>$valblock) {
  $arrBlockDetail[] = '<div class="properties__block">';
  $arrBlockDetail[] = '<h3 class="title title--next properties__title">'. $valblock['block'] .'</h3>';
  foreach ($valblock['items'] as $keyitems=>$valitems) {

    switch ($valitems['type']) {
      case 'text':
        $value = (isset($property[$valitems['value']]) && !empty($property[$valitems['value']])) ? $property[$valitems['value']] : 'hide-row';
        break;
      case 'yes':
        $value = (isset($property[$valitems['value']]) && $property[$valitems['value']] > 0) ? 'ANO' : 'hide-row';
        break;
      case 'item':
        $value = (isset($property[$valitems['value']]) && $property[$valitems['value']] > 0) ? $globalListResource->getByNameId($property[$valitems['value']]) : 'hide-row';
        break;
      case 'int':
        $value = (isset($property[$valitems['value']]) && $property[$valitems['value']] > 0) ? number_format($property[$valitems['value']], 0, '', ' ') : 'hide-row';
        if ($property[$valitems['value']] == 999999999) $value = 'informace v RK';
        break;
      case 'multi-item':
        $value = ((array_key_exists($valitems['multi-item-id'], $propertiesArray))) ? implode(', ', array_map(array($globalListResource, 'getByNameId'), $propertiesArray[$valitems['multi-item-id']])) : 'hide-row';
        break;
      default:
        $value = 'hide-row';
    }
    if ($value != 'hide-row') {
      if (isset($valitems['add-after'])) $value = $value . $valitems['add-after'];
      
      $highlightClass = ($valitems['value'] === 'advert_code') ? ' properties__highlight' : '';

      $arrBlockDetail[] = '<div class="properties__item">';
      $arrBlockDetail[] = '<p>' . $valitems['title'] . '</p>';
      $arrBlockDetail[] = '<p><strong class="' . $highlightClass . '">' . $value . '</strong></p>';
      $arrBlockDetail[] = '</div>';
    }
  }
  $arrBlockDetail[] = '</div>';
}

/*
<div class="properties__block">
<h3 class="title title--next properties__title">Plochy</h3>
  <div class="properties__item">
    <p>Plocha:</p>
    <p><strong><?php echo $property['area']; ?> m2</strong></p>
</div>
</div>
*/
?>