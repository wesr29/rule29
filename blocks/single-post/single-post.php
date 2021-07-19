<?php 
  /**
   * @param $subtitle | optional | string html to display
   * @param $image | optional | featured image
   * @param $classes | optional | string of extra classes for this section
   */
  global $post;
  $subtitle = get_field('subtitle', $post);
  $image = !empty(get_post_thumbnail_id($post)) ? get_post_thumbnail_id($post) : get_field('default_post_image', 'option');
  $listen = get_field('podcast_links', $post);
  $related = get_field('related_links', $post);
  if(!empty($subtitle)){
    $subtitle = "<h2 class='single-post--subtitle'>$subtitle</h2>";
  }
  else{
    $subtitle = '';
  }
?>
<div class="wrapper single-post--wrapper">
  <div class="single-post--wrapper--content">
    <?php block('content', [ 
      'content' => $subtitle . apply_filters('the_content', $post->post_content),
      'columns' => 'one'
    ]);
    block('post-tags');
    ?>
  </div>
  <div class="single-post--sidebar">
    <div class="single-post--sharing single-post--sidebar--block">
      <h3 class="single-post--sidebar--title">Share This Post</h3>
      <?php block('share', [
        'title' => get_the_title($post),
        'link' => get_the_permalink($post),
        'description' => get_the_title($post),
        'image' => wp_get_attachment_url($image)
      ]); ?>
    </div>
    <?php if(!empty($listen) && !empty(array_filter($listen))): ?>
    <div class="single-post--listen single-post--sidebar--block">
      <h3 class="single-post--sidebar--title">Listen On</h3>
      <?php block('listen', [
        'spotify' => $listen['spotify'],
        'google' => $listen['google'],
        'stitcher' => $listen['stitcher'],
        'apple' => $listen['apple'],
      ]); ?>
    </div>
    <?php endif; ?> 
    <?php if(!empty($related)): ?>
    <div class="single-post--related single-post--sidebar--block">
      <h3 class="single-post--sidebar--title">Related Links</h3>
      <ul>
      <?php foreach($related as $link): ?>
        <?php if(!empty($link['link'])): ?>
        <li><?php echo acf_link($link['link']); ?></li>
        <?php endif; ?>
      <?php endforeach; ?>
      </ul>
    </div>
    <?php endif; ?> 
  </div>
</div>