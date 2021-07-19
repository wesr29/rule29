<?php
/**
 * Builds Card Callouts Module 
 * @param $cards[][link] - ACF link
 * @param $cards[][icon] - icon slug to render_svg
 */

if(empty($cards)) {
  return false;
}

?>
<section class="card-callouts bg-light-blue p-t-80 p-b-60">
  <div class="wrapper">
    <div class="row">
      <?php foreach($cards as $card): ?>
        <div class="col col-md-3 col-sm-4 col-xs-6">
          <a class="card-callouts--card morph-icon--hover will-animate" data-animation="fade-from-bottom" href="<?= $card['link']['url'] ?>" target="<?= $card['link']['target'] ?>">
            <div><?php block('morph-icon', [ 'icon' => $card['icon'] ]); ?></div>
            <div class="card-callouts--title h3"><?= $card['link']['title'] ?></div>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>