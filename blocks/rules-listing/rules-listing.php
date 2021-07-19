<?php
  /**
   * @param $rules - array of WP posts
   */


  $rules = get_posts([
    'numberposts' => -1,
    'post_type' => 'rules',
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ]);

  $rules = array_map(function($single){
    $single->image = get_field('image', $single);
    $single->permalink = get_the_permalink($single);
    $single->title = get_field('title', $single);
    $single->color = get_field('color', $single);
    $single->number = get_field('rule_number', $single);
    return $single;
  }, $rules);

  if(empty($classes)){
    $classes = '';
  }
?>
<section class="rules <?php echo $classes; ?>">
  <div class="wrapper">
    <div class="row">    
      <?php foreach($rules as $single): ?>
        <div class="col-md-3 col-sm-4 col-xs-6 col will-animate" data-animation="fade-from-bottom">
          <a href="<?php echo $single->permalink;?>" class="rule text-center">
            <?php echo wp_image($single->image, 'medium', ['class' => 'rule--image']); ?>
            <div class="rule--hover-bg" style="background-color: <?php echo $single->color; ?>"></div>
            <h2 class="rule--number"><?php echo $single->number; ?></h2>
            <!-- <hr class="rule--hr"> -->
            <p class="rule--title"><?php echo $single->title; ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>