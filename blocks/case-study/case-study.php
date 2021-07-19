<?php
  /**
   * @param $case_study - WP case_studies object
   * @param $title - string content
   * @param $copy - strong content
   * @param $image - WP image ID
   * @param $image_side - string left|right
   * @param $classes - string extra classes
   */
  if(empty($classes)){
    $classes = '';
  }

  if(empty($case_study) && empty($title) && empty($copy)){
    return false;
  }
?>
<section class="case-study case-study--image--<?= $image_side ?> <?php echo $classes; ?> will-animate" data-animation="fade-from-bottom" style="--case-study-color: <?= get_field('color', $case_study) ?: '#002E5D' ?>">
  <?= !empty($image) ? wp_image($image, 'large', [ 'class' => 'case-study--image not-lazy' ]) : '' ?>
  <div class="case-study--content p-50">
    <?php if(!empty($case_study)): ?>
      <div class="case-study--subtitle uppercase"><?= $case_study->post_title ?></div>
    <?php endif; ?>
    <?php if(!empty($title)): ?>
      <div class="case-study--title h2"><?= $title ?></div>
    <?php endif; ?>
    <?php if(!empty($copy)): ?>
      <div class="case-study--copy"><?= $copy ?></div>
    <?php endif; ?>
    <?php if(!empty($case_study)): ?>
      <a class="button button--white button--outline" href="<?= get_the_permalink($case_study) ?>">View the Case Study</a>
    <?php endif; ?>
  </div>
</section>