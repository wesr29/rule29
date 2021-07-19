<?php
  /**
   * @param $content | string html to display
   * @param $image | WP image ID to use as background
   * @param $slides | Array of image/content array
   * @param $classes - string extra classes
   */

  //Check for blog page
  $currentPostID = get_the_id();
  if(is_home()){
    $currentPostID = get_option('page_for_posts');
  }

  $hero_type = get_field('hero_type', $currentPostID);

  if(empty($hero_type) || $hero_type == 'no-hero'){
    return false;
  }

  $image = $hero_type == 'image' ? get_field('hero_image', $currentPostID) : null;
  $mobile_image = $hero_type == 'image' ? get_field('hero_mobile_image', $currentPostID) : null;
  $video = $hero_type == 'video' ? get_field('hero_video', $currentPostID) : null;
  $video_aspect_ratio = $hero_type == 'video' ? get_field('video_aspect_ratio', $currentPostID) : null;
  $content = $hero_type == 'image' || $hero_type == 'video' ? get_field('hero_content', $currentPostID) : null;
  if(is_search()){
    $content = '<h1 class="text-center">Search Results for: ' . get_search_query() . '</h1>';
  }
  $slides = $hero_type == 'carousel' ? get_field('hero_slides', $currentPostID) : null;

  if(empty($content) && empty($image) && empty($slides)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }
?>
<div class="hero hero--<?php echo $hero_type; ?> <?php echo $classes; ?>">

  <?php echo !empty($image) ? wp_image($image, 'full', [ 'class' => 'hero--background parallax' ]) : '' ?>
  <?php echo !empty($mobile_image) ? wp_image($mobile_image, 'full', [ 'class' => 'hero--mobile-image wrapper' ]) : ''; ?>

  <?php echo !empty($video) ? '<iframe data-aspect-ratio="'.$video_aspect_ratio.'" class="hero--video--frame" src="'.convert_video_link_for_iframe($video, [ 'autoplay' => 1, 'background' => 1, 'mute' => 1, 'controls' => 0, 'modestbranding' => 1, 'kbcontrols' => 0, 'loop' => 1 ]).'"></iframe>' : ''; ?>

  <?php if(!empty($content)): ?>
    <div class="wrapper">
      <div class="editor-content">
        <?php echo $content; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if(!empty($slides)): ?>
    <div class="hero--slides">
      <?php foreach($slides as $slide): ?>
        <div class="hero--slide">
          <?php echo !empty($slide['image']) ? wp_image($slide['image'], 'full', [ 'class' => 'hero--slide--background' ]) : ''; ?>
          <?php if(!empty($slide['content'])): ?>
            <div class="wrapper">
              <div class="editor-content">
                <?php echo $slide['content']; ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</div>