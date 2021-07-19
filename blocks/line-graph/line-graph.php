<?php 
  /**
   * @param $content
   * @param $number_label
   * @param $entries[][label]
   * @param $entries[][point_1]
   * @param $entries[][point_2]
   */

  //first find which number should go at the top of the chart, then we can related everything else to that
  $top_graph_number = 0;

  foreach($entries as $entry){
    if($entry['point_1'] > $top_graph_number){
      $top_graph_number = $entry['point_1'];
    }

    if($entry['point_2'] > $top_graph_number){
      $top_graph_number = $entry['point_2'];
    }
  }
  $top_graph_number += 10;

  //find percentages based on top graph number
  $entries = array_map(function($entry) use ($top_graph_number) {
    $entry['point_1_percentage'] = $entry['point_1'] / $top_graph_number * 100;
    $entry['point_2_percentage'] = $entry['point_2'] / $top_graph_number * 100;
    return $entry;
  }, $entries);

?>
<section class="line-graph">
  <?php if(!empty($content)): ?>
    <div class="wrapper m-b-40">
      <div class="editor-content"><?= $content ?></div>
    </div>
  <?php endif; ?>
  <div class="wrapper line-graph--wrapper">
    <div class="line-graph--scroller">
      <div class="line-graph--container">
        <div class="line-graph--graph flex space-around">
          <?php foreach($entries as $entry): ?>
            <div class="line-graph--entry">
              <div class="line-graph--point line-graph--p1" style="bottom:<?= $entry['point_1_percentage']; ?>%">
                <div class="line-graph--point--popup"><?= $entry['point_1'] ?> <?= $number_label ?></div>
              </div>        
              <div class="line-graph--point line-graph--p2" style="bottom:<?= $entry['point_2_percentage']; ?>%">
                <div class="line-graph--point--popup"><?= $entry['point_2'] ?> <?= $number_label ?></div>
              </div>        
            </div>
          <?php endforeach; ?>
        </div>
        <div class="line-graph--x-axis flex space-around">
          <?php foreach($entries as $entry): ?>
            <div class="line-graph--x-label uppercase"><?= $entry['label'] ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>