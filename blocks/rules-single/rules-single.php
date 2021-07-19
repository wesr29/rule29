<?php
  /**
   * @param $title - string
   * @param $image - WP Image ID
   * @param $mobile_image - WP Image ID
   * @param $description - string
   * @param $number - string
   */


  /*$title = get_field('title');
  $image = get_field('image');
  $mobile_image = get_field('mobile_image');
  $description = get_field('description');
  $number = get_field('rule_number');*/

  global $post;
  $rules = get_posts([
    'numberposts' => 28,
    'post_type' => 'rules',
    'orderby' => 'menu_order',
    'order' => 'ASC'
  ]);


  $rules = array_map(function($rule){
    $rule->title = get_field('title', $rule);
    $rule->permalink = get_the_permalink($rule);
    $rule->image = get_field('image', $rule);
    $rule->mobile_image = get_field('mobile_image', $rule);
    $rule->description = get_field('description', $rule);
    $rule->number = get_field('rule_number', $rule);
    
    //Get the current URL  
    $URI = get_home_url().$_SERVER['REQUEST_URI'];
    //If the URL matches the permalink of the rule, it should be the starting slide
    if($URI == $rule->permalink){
      $rule->start = 'starting-slide';
    }
    else{
      $rule->start = '';
    }
    return $rule;
  }, $rules);

?>

<section class="rule-single bg-light-blue">
  <div class="wrapper">
    <div class="rule-single--slides">
      <?php foreach($rules as $key => $rule): ?>
      <div class="rule-single--slide relative <?php echo $rule->start; ?>" data-slider-page="<?php echo $key; ?>">
        <div class="rule-single--header">
          <div class="rule-single--number-container--mobile">
            <h1 class="dont-animate" style="opacity: 1">
              <span class="rule-single--rule-title uppercase">Rule</span>
              <span class="rule-single--number"><?php echo $rule->number; ?></span>
            </h1>
          </div>
          <a href="<?php home_url(); ?>/rules" class="rule-single--back-to-rules">
            <span class="rule-single--back-to-rules--desktop-svg"><?php echo render_svg('grid'); ?></span>
            <span>Back to Rules</span>
            <span class="rule-single--back-to-rules--mobile-svg"><?php echo render_svg('grid'); ?></span>
          </a>
        </div>
        <div class="rule-single--image desktop">
          <?php echo wp_image($rule->image, 'full', [ 'class' => 'not-lazy' ]); ?>
        </div>
        <div class="rule-single--image mobile">
          <?php echo wp_image($rule->mobile_image, 'full', [ 'class' => 'not-lazy' ]); ?>
        </div>
        <div class="rule-single--footer bg-dark-blue">
          <div class="rule-single--number-container desktop relative">
            <i class="rule-single--prev"></i>
            <h1 class="dont-animate" style="opacity: 1">
              <span class="rule-single--rule-title uppercase">Rule</span>
              <span class="rule-single--number"><?php echo $rule->number; ?></span>
            </h1>
            <i class="rule-single--next"></i>
          </div>
          <div class="rule-single--content">
            <i class="rule-single--prev mobile"></i>
            <p class="rule-single--title"><?php echo $rule->title; ?></p>
            <hr class="rule-single--hr">
            <?php echo !empty($rule->description) ? '<p class="rule-single--description">'.$rule->description.'</p>' : ''; ?>
            <i class="rule-single--next mobile"></i>
          </div>
          <div class="rule-single--share">
            <h3 class="rule-single--share--title">Share This Rule</h3>
            <hr class="rule-single--share--hr">
            <?php block('share', [
              'title' => $rule->title,
              'link' => $rule->permalink,
              'description' => $rule->title,
              'image' => wp_get_attachment_url($rule->image)
            ]); ?>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>    
  </div>
</section>