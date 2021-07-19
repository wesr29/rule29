<?php
  /**
   * @param $title - string title
   * @param $logos[][image] - WP image ID
   * @param $logos[][link] - ACF link
   * @param $classes - string extra classes
   * @param $logos_animate_individual
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  if(empty($logos)){
    return;
  }
  if(empty($classes)){
    $classes = '';
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';
?>
<section class="logos-grid text-center p-t-70 p-b-70 <?php echo $classes; ?>" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <?php if(!empty($title)): ?>
      <div class="logos-grid--title h2 text-dark-blue m-b-50 will-animate" data-animation="fade-from-bottom"><?= $title ?></div>
    <?php endif; ?>
    <div class="row justify-center">
      <?php foreach($logos as $key => $logo): ?>
        <div class="col col-md-2 col-xs-4">
          <?php if(!empty($logo['link']['url'])): ?>
            <a href="<?= $logo['link']['url'] ?>" target="<?= $logo['link']['target'] ?>">
              <?= wp_image($logo['image'], 'medium', [ 'class' => 'will-animate', 'data-animation' => (empty($logos_animate_individual) ? 'fade-from-bottom' : 'fade-from-left'), 'style' => 'transition-delay: ' . ($key * 250) . 'ms; animation-delay:' . ($key * 250) . 'ms' ]) ?>
            </a>
          <?php else: ?>
            <?= wp_image($logo['image'], 'medium', [ 'class' => 'will-animate', 'data-animation' => (empty($logos_animate_individual) ? 'fade-from-bottom' : 'fade-from-left'), 'style' => 'transition-delay: ' . ($key * 250) . 'ms; animation-delay:' . ($key * 250) . 'ms' ]) ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>