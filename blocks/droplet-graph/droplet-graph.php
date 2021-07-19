<?php
  /**
   * @param $content
   * @param $entries[][number]
   * @param $entries[][unit_type]
   * @param $entries[][label]
   */

  $tallest = 0;
  foreach($entries as $entry){
    if($entry['number'] > $tallest){
      $tallest = $entry['number'];
    }
  }

  $entries = array_map(function($entry) use ($tallest) {
    $entry['number_percentage'] = $entry['number'] / $tallest * 100;
    return $entry;
  }, $entries);
?>

<section class="droplet-graph">
  <svg class="hidden" xmlns="http://www.w3.org/2000/svg" width="94" height="180"><clipPath id="drop-shape"><path data-name="Path 204" d="M94 132.562a47 47 0 11-94 0C0 106.362 47 0 47 0s47 106.362 47 132.562z" fill="currentColor"/></clipPath></svg>
  <div class="wrapper">
    <div class="editor-content"><?= $content ?></div>
    <div class="droplet-graph--container flex space-around">
      <?php foreach($entries as $entry): ?>
        <div class="droplet-graph--droplet-container">
          <div class="droplet-graph--droplet bg-medium-light-blue flex flex-column justify-end align-center text-white">
            <div class="droplet-graph--fill bg-medium-dark-blue" style="height:<?= $entry['number_percentage'] ?>%"></div>
            <div class="droplet-graph--number"><?= $entry['number'] ?></div>
            <div class="droplet-graph--unit-type"><?= $entry['unit_type'] ?></div>
          </div>
          <div class="droplet-graph--label text-center"><?= $entry['label'] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>