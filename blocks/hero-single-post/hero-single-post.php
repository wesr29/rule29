<?php
  /**
   * @param $title | string html to display
   * @param $category | WP category for the post
   * @param $author | post author
   * @param $topic | custom taxonomy for the post
   * @param $read_time | string html
   * @param $image | WP Image ID
   * @param $classes - string extra classes
   */

  global $post;

  $title = get_the_title();
  $category = get_primary_category($post->ID);
  $topic = get_primary_category($post->ID, $term = 'topic');
  $season = get_field('podcast_season');
  $episode = get_field('podcast_episode');  
  $author_credit = get_field('author_credit');
  if(!empty($author_credit)){
    $author = get_post($author_credit);
    $profile_photo = get_field('profile_photo', $author) ?: get_field('default_author_photo', 'option');
  }
  
  $image = !empty(get_post_thumbnail_id()) ? get_post_thumbnail_id() : get_field('default_post_image', 'option');
  $read_time = number_format(str_word_count($post->post_content) / 255) . ' minutes'; ////!empty(get_field('read_time')) ? get_field('read_time') : '';
  
  if(empty($title)){
    return false;
  }
  if(empty($classes)){
    $classes = '';
  }
?>
<div class="hero-single-post relative <?php echo $classes; ?>">
  <?php echo !empty($image) ? wp_image($image, 'full', [ 'class' => 'hero-single-post--background desktop-only' ]) : '' ?>
  <?php echo !empty($image) ? wp_image($image, 'large', [ 'class' => 'hero-single-post--mobile-image mobile-only' ]) : '' ?>
  
  <div class="hero-single-post--overlay"></div>

  <div class="wrapper relative">
    <div class="hero-single-post--content">
      <?php block('category-badge', [ 'category' => $category ]); ?>
      <?php if(!empty($season) || !empty($episode)): ?>
        <div class="case-study--subtitle uppercase m-b-20">
          <?= !empty($season) ? "Season $season" : "" ?>
          <?= !empty($season) && !empty($episode) ? " | " : "" ?>
          <?= !empty($episode) ? "Episode $episode" : "" ?>          
        </div>
      <?php endif ?>
      <?php echo !empty($title) ? '<h1>'.$title.'</h1>' : ''; ?>
      <?php if(!empty($author)): ?>
        <div class="hero-single-post--author">
          <?php echo wp_image($profile_photo, 'small', ['class' => 'hero-single-post--author--image']); ?>
          <p class="hero-single-post--author--content">Crafted by 
            <a href="<?= get_the_permalink($author) ?>" class="hero-single-post--author--name"><?php echo $author->post_title; ?></a>
            <?php if(!empty($topic)): ?>
              in <a href="<?php echo get_term_link($topic->term_id); ?>" class="hero-single-post--author--category"><?php echo $topic->name; ?></a>
            <?php endif; ?>
          </p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php if(!empty($read_time)): ?>
<div class="hero-single-post--read-time uppercase">
  <div class="wrapper">
    <p><?php echo $read_time; ?></p>
  </div>
  <div id="reading-progress-bar" value="0" max="100"></div>
</div>
<?php endif; ?>