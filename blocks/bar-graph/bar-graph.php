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
  $top_graph_number = ceil($top_graph_number);
  $graph_steps = floor($top_graph_number / 10);
  $top_graph_number += ($graph_steps * 3);

  //find percentages based on top graph number
  $entries = array_map(function($entry) use ($top_graph_number) {
    $entry['point_1_percentage'] = $entry['point_1'] / $top_graph_number * 100;
    $entry['point_2_percentage'] = $entry['point_2'] / $top_graph_number * 100;
    return $entry;
  }, $entries);

?>
<section class="bar-graph">
  <div class="wrapper">
    <div class="editor-content"><?= $content ?></div>
    <div class="bar-graph--scroller">
      <div class="bar-graph--container">
        <div class="bar-graph--graph flex space-around">
          <div class="bar-graph--y-axis">
            <?php for($i = $top_graph_number; $i >= 0; $i -= $graph_steps): ?>
              <div class="bar-graph--y-label"><span><?= $i ?></span></div>
            <?php endfor; ?>
            <div class="bar-graph--y-label"><span>0</span></div>
          </div>

          <?php foreach($entries as $key => $entry): ?>
            <div class="bar-graph--entry bar-graph--entry-<?php echo $key; ?>">
              <div class="bar-graph--point bar-graph--p1" style="height:<?= $entry['point_1_percentage']; ?>%">
                <div class="bar-graph--point--popup"><?= $entry['point_1'] ?> <?= $number_label ?></div>
              </div>        
              <div class="bar-graph--point bar-graph--p2" style="height:<?= $entry['point_2_percentage']; ?>%">
                <div class="bar-graph--point--popup"><?= $entry['point_2'] ?> <?= $number_label ?></div>
              </div>        
            </div>
          <?php endforeach; ?>
        </div>
        <div class="bar-graph--x-axis flex space-around">
          <?php foreach($entries as $entry): ?>
            <div class="bar-graph--x-label uppercase"><?= $entry['label'] ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>