<?php
global $wpdb;
$days = [
  "Lundi",
  "Mardi",
  "Mercredi",
  "Jeudi",
  "Vendredi",
  "Samedi",
  "Dimanche",
];
$hours = $wpdb->get_results( "SELECT id, openH, closeH,openM, closeM, day  FROM m_hours_mdb" );
$finalHours;
foreach ($days as $d) {
  $finalHours[$d] = [];
}
foreach ($hours as $h) {
  $finalHours[$h->day][] = $h;
}
?>
<?php foreach ($days as $d):?>
    <div class="day-wrap">
        <div class="day">
          <?php echo $d; ?>:
          <input type="text" class="addHour">
        </div>
        <?php foreach($finalHours[$d] as $h):?>
                <?php $_h = str_pad($h->openH, 2, "0",STR_PAD_LEFT)."h".str_pad($h->openM, 2, "0",STR_PAD_LEFT)."-".str_pad($h->closeH, 2, "0",STR_PAD_LEFT)."h".str_pad($h->closeM, 2, "0",STR_PAD_LEFT); ?>
                <div class="hours"><div class="close-btn" data-id="<?php echo $h->id ?>"></div><?php echo $_h ?></div>
        <?php endforeach?>
    </div>
<?php endforeach; ?>

<p>* Pour entrer un nouvelle horaire ecrivez dans l'un des champs sous le format h-h puis pressez [entrée] ex : 12h-15h pour une ouverture en 12h et 15h </p>