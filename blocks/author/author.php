<?php
  global $post;

  $first_name     = explode(' ', $post->post_title)[0];
  $author_title   = get_field('title');
  $bio            = get_field('bio');
  $profile_photo  = get_field('profile_photo');
  $author_badge   = !empty($profile_photo) ? $profile_photo : get_field('default_author_photo', 'option');
  $links          = get_field('links');
  $twitter_quote  = get_field('twitter_quote');
  $blurb          = get_field('blurb');
  $email          = get_field('email');
  $facebook       = get_field('facebook');
  $linkedin       = get_field('linkedin');
  $twitter        = get_field('twitter');
  $instagram      = get_field('instagram');
  $posts          = get_posts([
    'posts_per_page' => 2,
    'meta_key' => 'author_credit',
    'meta_value' => $post->ID
  ]);

  // dd(htmlentities($bio));
?>
<div class="author">
  <div class="wrapper author--wrapper">
    <div class="author--main">
      <div class="author--photo mobile"><?php echo wp_image($author_badge); ?></div>
      <div class="author--meta">
        <h1 class="author--name text-dark-blue"><?php echo $post->post_title; ?></h1>
        <?php echo !empty($author_title) ? '<h2 class="author--title text-dark-blue">'.$author_title.'</h2>' : ''; ?>
        <?php echo !empty($blurb) ? '<p class="author--blurb text-dark-blue">'.$blurb.'</p>' : ''; ?>
        <?php echo !empty($bio) ? '<div class="author--bio editor-content">'.$bio.'</div>' : ''; ?>
      </div>      
    </div>
    <div class="author--sidebar">
      <div class="author--photo desktop"><?php echo wp_image($author_badge); ?></div>

      <div class="author--sidebar--contact author--sidebar--block">
        <h3 class="author--sidebar--title">Get in Contact with <?= $first_name ?></h3>
        <div class="author--sidebar--contact--items flex align-center">
        <?php if(!empty($facebook)): ?>
          <a href="<?php echo $facebook; ?>" target="_blank" class="author--sidebar--contact--link flex align-center"><?php echo render_svg('facebook'); ?></a>
        <?php endif; ?>
        <?php if(!empty($linkedin)): ?>
          <a href="<?php echo $linkedin; ?>" target="_blank" class="author--sidebar--contact--link flex align-center"><?php echo render_svg('linkedin'); ?></a>
        <?php endif; ?>
        <?php if(!empty($twitter)): ?>
          <a href="<?php echo $twitter; ?>" target="_blank" class="author--sidebar--contact--link flex align-center"><?php echo render_svg('twitter'); ?></a>
        <?php endif; ?>
        <?php if(!empty($instagram)): ?>
          <a href="<?php echo $instagram; ?>" target="_blank" class="author--sidebar--contact--link flex align-center"><?php echo render_svg('instagram'); ?></a>
        <?php endif; ?>
        <?php if(!empty($email)): ?>
          <a href="mailto:<?php echo $email; ?>" class="author--sidebar--contact--link flex align-center"><?php echo render_svg('email'); ?></a>
        <?php endif; ?>
        </div>
      </div>

      <?php if(!empty($links)): ?>
        <div class="author--sidebar--related author--sidebar--block">
          <h3 class="author--sidebar--title">Related Links</h3>
          <ul>
          <?php foreach($links as $link): ?>
            <?php if(!empty($link['link'])): ?>
            <li><?php echo acf_link($link['link']); ?></li>
            <?php endif; ?>
          <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if(!empty($twitter_quote)): ?>
        <?php echo do_shortcode('[tweet quote="'.$twitter_quote.'"]'); ?>
      <?php endif; ?>
    </div>
  </div>
  <?php
    if(!empty($posts)){
      block('featured-posts', [ 
        'posts' => $posts,
        'title' => 'Stories by '.$first_name
      ]);
    }  
  ?>
</div>