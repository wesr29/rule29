<?php
  /**
   * @param $content
   * @param $y_axis_label
   * @param $entries[][label]
   * @param $entries[][value]
   */

  //first find which number should go at the top of the chart, then we can related everything else to that
  $top_graph_number = 0;

  foreach($entries as $entry){
    if($entry['value'] > $top_graph_number){
      $top_graph_number = $entry['value'];
    }
  }

  $top_graph_number = ceil($top_graph_number);
  $graph_steps = floor($top_graph_number / 10);
  $top_graph_number += ($graph_steps * 1);

  //find percentages based on top graph number
  $entries = array_map(function($entry) use ($top_graph_number) {
    $entry['value_percentage'] = $entry['value'] / $top_graph_number * 100;
    return $entry;
  }, $entries);
?>

<section class="curve-graph">
  <div class="wrapper bg-light-blue">
    <div class="row">
      <div class="col col-md-4 col-xs-12 editor-content"><?= $content ?></div>
      <div class="col col-md-8 col-xs-12">
        <div class="curve-graph--scroller">
          <div class="curve-graph--container">
            <div class="curve-graph--graph flex space-around">
              <div class="curve-graph--y-main-label"><?= $y_axis_label ?></div>

              <div class="curve-graph--y-axis">
                <?php for($i = $top_graph_number; $i >= 0; $i -= $graph_steps): ?>
                  <div class="curve-graph--y-label"><span><?= $i ?></span></div>
                <?php endfor; ?>
              </div>

              <?php foreach($entries as $entry): ?>
                <div class="curve-graph--entry">
                  <div class="curve-graph--point curve-graph--p1" style="bottom:<?= $entry['value_percentage']; ?>%"></div>          
                </div>
              <?php endforeach; ?>
            </div>
            <div class="curve-graph--x-axis flex space-around">
              <?php foreach($entries as $entry): ?>
                <div class="curve-graph--x-label"><?= $entry['label'] ?></div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>