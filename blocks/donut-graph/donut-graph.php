<?php 
  /**
   * @param $content
   * @param $entries[][label]
   * @param $entries[][value]
   */

  $total = 0;

  foreach($entries as $entry){
    $total += $entry['value'];
  }

  $entries = array_map(function($entry) use ($total) {
    $percent_of_circle = $entry['value'] / $total;
    $entry['degrees'] = $percent_of_circle * 360;
    return $entry;
  }, $entries);

?>
<section class="donut-graph">
  <div class="wrapper bg-light-blue">
    <div class="row">
      <div class="col col-md-4 col-xs-12 editor-content"><?= $content ?></div>
      <div class="col col-md-8 col-xs-12">
        <div class="donut-graph--container flex align-end">
          <div class="donut-graph--legend text-dark-blue m-r-80">
            <div class="flex align-center m-b-15">
              <div class="m-r-20 donut-graph--entry-icon donut-graph--entry-icon-1"></div><?= $entries[0]['label'] ?>
            </div>
            <div class="flex align-center">
              <div class="m-r-20 donut-graph--entry-icon donut-graph--entry-icon-2"></div><?= $entries[1]['label'] ?>
            </div>
          </div>
          <div class="donut-graph--graph">
            <?php foreach($entries as $key => $entry): ?>
              <?php /* Only showing the first one because the second one is just assumed to take up the rest of the circle */ ?>
              <?php if($key == 0): ?>
                <div class="donut-graph--slice">
                  <?php if($key == 0): ?>
                    <div class="donut-graph--slice--inner" style="--rotation:<?= $entry['degrees'] ?>deg;"></div>
                  <?php endif; ?>
                </div>
              <?php endif; ?>
              <div class="donut-graph--value donut-graph--value-<?= $key + 1 ?>"><?= $entry['value'] ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>