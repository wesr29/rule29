<?php
  /**
   * @param $posts - array of WP posts
   * @param $twoposts - has value if you only want 2 posts, empty for default
   * @param $title - string html
   * @param $featured_title - string html
   * @param $additional_title - string html
   * @param $links - repeater array of links
   * @param $links_button - link
   * @param $selected_featured_post - optional, WP post object
   * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
   */

  $posts = !empty($posts) ? $posts : [];

  if(count($posts) < 4 && empty($twoposts)){
    $more_posts = get_posts([
      'numberposts' => count($posts) - 4,
      'post__not_in' => wp_list_pluck($posts, 'ID')
    ]);

    $posts = array_merge($posts, $more_posts);
  }

  $posts = array_map(function($single){
    $single->image = get_post_thumbnail_id($single);
    $single->permalink = get_the_permalink($single);
    $single->category = get_primary_category($single);
    $author_credit = get_field('author_credit', $single);
    if(!empty($author_credit)){
      $single->author = get_post($author_credit);
    }
    return $single;
  }, $posts);

  if(!empty($selected_featured_post)){
    $featured_post = $selected_featured_post;
    $featured_post->image = get_post_thumbnail_id($selected_featured_post);
    $featured_post->category = get_primary_category($selected_featured_post);
    $featured_post->permalink = get_the_permalink($selected_featured_post);
    $author_credit = get_field('author_credit', $selected_featured_post);
    if(!empty($author_credit)){
      $featured_post->author = get_post($author_credit);
    }
  }
  else{
    $featured_post = array_shift($posts);
  }

  if(empty($classes)){
    $classes = '';
  }
  if(!empty($twoposts)){
    $classes .= 'has-two-featured';
  }
  if(is_singular('post')){
    $padding_top = 'p-t-60';
  }
  else{
    $padding_top = 'p-t-80';
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes .= !empty($custom_padding) ? ' custom-padding' : '';  
  $title = get_field('related_posts_title', 'option');


  if(empty($featured_post)){
    return false;
  }

?>
<section class="featured-posts bg-light-blue <?php echo $padding_top; ?> p-b-80 <?php echo $classes; ?>" style="<?= $custom_padding ?>">
  <?php if(!empty($title)): ?>
    <div class="wrapper">
      <h2 class="featured-posts--section-title text-center"><?php echo $title; ?></h2>
    </div>
  <?php endif; ?>
  <div class="wrapper featured-posts--wrapper">
    <div class="featured-posts--featured">
      <?php if(!empty($featured_title)): ?>
        <h2 class="featured-posts--featured-title text-dark-blue m-b-30"><?php echo $featured_title; ?></h2>
      <?php endif; ?>  
      
      <?php
        echo !empty($featured_post->image) ? '<a href="'.$featured_post->permalink.'">'.wp_image($featured_post->image, 'large').'</a>' : '<a class="yoyo" href="'.$featured_post->permalink.'">'.wp_image(get_field('default_post_image', 'option')).'</a>';
        block('category-badge', [ 'category' => $featured_post->category, 'post_type' => get_post_type($featured_post->ID) ]);
      ?>
      
      <a href="<?= $featured_post->permalink ?>" class="featured-posts--title h3"><?= $featured_post->post_title ?></a>  

      <?php if(!empty($featured_post->author)): ?>
        <div class="featured-posts--author">
          <em>by</em> <a href="<?= get_the_permalink($featured_post->author) ?>"><?= $featured_post->author->post_title ?></a>
        </div>
      <?php endif; ?>

      <p class="featured-posts--copy"><?= $featured_post->post_excerpt ?></p>
      <a class="featured-posts--readmore" href="<?= $featured_post->permalink ?>"><?= $featured_post->category == 'Podcast' ? 'Listen Now' : 'Read More' ?></a>

      <?php if(is_page_template('page-green.php')): 
        $green_taxonomy = get_term_by('slug', 'green', 'topic');
        $green_id = $green_taxonomy->term_id;
      ?>
        <br>        
        <a href="<?php echo home_url(); ?>/all-posts/?topic=<?php echo $green_id; ?>" class="button button--dark-blue m-t-20">Read More Green Stories</a>
      <?php endif; ?>
    </div>
    <?php if(!empty($posts) && !empty($twoposts)): 
      $featured_post = end($posts);
    ?>
      <div class="featured-posts--featured">
        <?= !empty($featured_post->image) ? '<a href="'.$featured_post->permalink.'">'.wp_image($featured_post->image, 'large').'</a>' : '<a href="'.$featured_post->permalink.'">'.wp_image(get_field('default_post_image', 'option')).'</a>' ?>
        <?php block('category-badge', [ 'category' => $featured_post->category ]); ?>
        <a href="<?= $featured_post->permalink ?>" class="featured-posts--title h3"><?= $featured_post->post_title ?></a>
        <?php if(!empty($featured_post->author)): ?>
          <div class="featured-posts--author">
            <em>by</em> <a href="<?= get_the_permalink($featured_post->author) ?>"><?= $featured_post->author->post_title ?></a>
          </div>
        <?php endif; ?>
        <p class="featured-posts--copy"><?= $featured_post->post_excerpt ?></p>
        <a class="featured-posts--readmore" href="<?= $featured_post->permalink ?>"><?= $featured_post->category == 'Podcast' ? 'Listen Now' : 'Read More' ?></a>
      </div>
    <?php else: ?>

    <?php if(!empty($links)): ?>
    <div class="featured-posts--additional with-links">
    <?php else: ?>
    <div class="featured-posts--additional">
    <?php endif; ?>
      <hr class="mobile-only">
      <?php if(!empty($additional_title)): ?>
        <h2 class="featured-posts--featured-title text-dark-blue m-b-30"><?php echo $additional_title; ?></h2>
      <?php endif; ?>

      <?php if(!empty($links)): ?>
        <?php foreach($links as $key => $link): ?>
          <?php echo acf_link($link['link'], 'featured-posts--title h3'); ?>
          <?= $key !== count($links) - 1 ? '<hr>' : '' ?>
        <?php endforeach; ?>
      <?php else: ?>
        <?php foreach($posts as $key => $single): ?>
          <?php block('category-badge', [ 'category' => $single->category ]); ?>
          <a href="<?= $single->permalink ?>" class="featured-posts--title h3"><?= $single->post_title ?></a>    
          <?php if(!empty($single->author)): ?>    
            <div class="featured-posts--author">
              <em>by</em> <a href="<?= get_the_permalink($single->author) ?>"><?= $single->author->post_title ?></a>
            </div>
          <?php endif; ?>
          <?= $key !== count($posts) - 1 ? '<hr>' : '' ?>
        <?php endforeach; ?>
      <?php endif; ?>

      <?php if(!empty($links_button)): ?>
        <br>
        <?php echo acf_link($links_button, 'button button--dark-blue m-t-20'); ?>
      <?php else: ?>
        <a href="<?= get_post_type_archive_link('post') ?>" class="button button--dark-blue m-t-20">Read More of Our Stories</a>
      <?php endif; ?>
    </div>    
    <?php endif; ?>
  </div>
</section>