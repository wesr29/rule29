<?php
  /**
   * @param $pretitle | string text
   * @param $title | string text
   * @param $copy | string html
   * @param $image | wp image ID
   * @param $text_color | string 
   * @param $mobile_image | wp image id
   * @param $mobile_style | image-fill|image-standalone
   * @param $color | string color, this is really only for the case studies, they have a spcecial case
   */

  $pretitle = get_field('pretitle');
  $title = get_field('title');
  $copy = get_field('copy');
  $image = get_field('image');
  $text_color = get_field('text_color');
  $mobile_image = get_field('mobile_image');
  $mobile_style = get_field('mobile_style');
  $desktop_style = get_field('desktop_style');
  $background_color = get_field('background_color');
  $desktop_fade_color = get_field('desktop_fade_color');
  $mobile_fade_color = get_field('mobile_fade_color');

    
  $color = !empty($desktop_fade_color) ? "--color:$desktop_fade_color;" : "";
  $mobile_color = !empty($mobile_fade_color) ? "--mobile-color:$mobile_fade_color;" : "";
  $background_color = !empty($background_color) ? "background-color:$background_color;" : "";



  if(empty($mobile_style)){
    $mobile_style = 'image-fill';
  }

  //default to main image
  $mobile_image = !empty($mobile_image) ? $mobile_image : $image; 
?>
<section class="hero-inner bg-light-blue <?= !empty($text_color) ? 'text-' . $text_color : '' ?> <?= !empty($mobile_style) ? 'hero-inner--mobile--' . $mobile_style : '' ?>" <?= "style='$color $mobile_color $background_color'"?>>
  <?php if($desktop_style == 'fill-image' || $desktop_style == 'half-fill'): ?>
    <?= !empty($image) ? wp_image($image, 'large', [ 'class' => 'desktop-only hero-inner--background-image ' . $desktop_style ]) : '' ?>
  <?php endif; ?>
  <?= !empty($mobile_image) ? wp_image($mobile_image, 'large', [ 'class' => 'mobile-only hero-inner--background-image--mobile' ]) : '' ?>

  <?php if($desktop_style == 'standalone-image' && !empty($image)): ?>
    <div class="wrapper standalone-image-wrapper">
      <?= wp_image($image, 'large', [ 'class' => 'desktop-only hero-inner--inline-image' ]) ?>
    </div>
  <?php endif; ?>

  <div class="hero-inner--content <?= $desktop_style ?> <?= !empty($background_color) ? "custom-background-color" : "" ?>">
    <div class="wrapper">
      <div class="editor-content">
        <?php if(!empty($pretitle)): ?>
          <span class="hero-inner--pretitle"><?= $pretitle ?></span>
        <?php endif; ?>
        <?php if(!empty($title)): ?>
          <h1 class="hero-inner--title"><?= $title ?></h1>
        <?php endif; ?>
        <?php if(!empty($copy)): ?>
          <?= $copy ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>