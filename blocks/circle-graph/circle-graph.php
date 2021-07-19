<?php
  /**
   * @param $content
   * @param $data[][year]
   * @param $data[][data]
   */

  $clean_data = [];

  foreach($data as $key => $point){
    $clean_data[] = [
      'year' => $point['year'],
      'data' => $point['data'],
      'size' => $key == 0 ? 100 : $point['data'] / $data[$key - 1]['data'] * 100
    ];
  }

?>
<section class="circle-graph">
  <div class="wrapper bg-light-blue">
    <div class="row">
      <div class="col col-md-4 col-xs-12 editor-content">
        <?= $content ?>
      </div>
      <div class="col col-md-8 col-xs-12">

        <div class="circle-graph--outer">
          <?php foreach($clean_data as $key => $d): ?>
            <?php if($d['size'] !== 100): ?>
              <div class="circle-graph--inner" style="width:<?= $d['size'] ?>%;height:<?= $d['size'] * 2 ?>%">
            <?php else: ?>
              <div class="circle-graph--inner">
            <?php endif; ?>
              <div class="circle-graph--year"><?= $d['year'] ?></div>
              <div class="circle-graph--data-point"><?= $d['data'] ?></div>
            </div>
          <?php endforeach ?>
        </div>
             
      </div>
    </div>
  </div>
</section>