<?php
$propertySubTypesCount = count($propertySubTypes);
if ($propertySubTypesCount > 9)
{
    $columns = 4;
    $columnsClass = "columns__item--25 ";
}
else
{
    $columns = 3;

    $columnsClass = "columns__item--33-5";
}
$rowCount = (integer)($propertySubTypesCount/$columns);
$lastIndex = 0;
for ($i = 0; $i < $columns; $i++)
{
    //echo "<div class=\"columns__item {$columnsClass} columns__item--mob-50\">";
    $propertySubTypeKeys = array_keys($propertySubTypes);
    for ($y = $lastIndex; $y <= $lastIndex + $rowCount && $y < $propertySubTypesCount; $y++)
    {
        ?>
        <div class="form-checkbox">
            <input type="checkbox" id="f-propertySubTypeCheckbox-<?php echo $masterId; ?>-<?php echo $y; ?>" name="sql[advert_subtype_eu][]" value="<?php echo $propertySubTypeKeys[$y]; ?>"
            data-master-f-id="<?php echo $masterId ?>" data-name="<?php echo $propertySubTypes[$propertySubTypeKeys[$y]]['name']; ?>">
            <span class="form-checkbox__box"></span>
            <label class="form-label" for="f-propertySubTypeCheckbox-<?php echo $masterId; ?>-<?php echo $y; ?>"><?php echo $propertySubTypes[$propertySubTypeKeys[$y]]['name']; ?></label>
        </div>
        <?php
    }
    $lastIndex = $y;
   // echo "</div>";
}
?>
