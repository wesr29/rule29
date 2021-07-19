<?php
  /**
   * Creates a list of the themes social media URLs
   */

  $socials = array_filter([
    'facebook' => get_field('facebook_url', 'options'),
    'linkedin' => get_field('linkedin_url', 'options'),
    'twitter' => get_field('twitter_url', 'options'),
    'instagram' => get_field('instagram_url', 'options'),
    'dribbble' => get_field('dribbble_url', 'options'),
    'vimeo' => get_field('vimeo_url', 'options'),
    'flickr' => get_field('flickr_url', 'options'),
    'pinterest' => get_field('pinterest_url', 'options'),
    'youtube' => get_field('youtube_url', 'options'),
    'medium' => get_field('medium_url', 'options'),
    'behance' => get_field('behance_url', 'options'),
  ]);

  if(empty($socials)){
    return false;
  }
?>
<section class="social-media-list align-center">
  <?php foreach($socials as $name => $link): ?>
    <a class="flex align-center" href="<?php echo $link; ?>" target="_blank"><?php echo render_svg($name); ?><span class="sr-only">Visit us on <?php echo $name; ?></span></a>
  <?php endforeach; ?>
</section>