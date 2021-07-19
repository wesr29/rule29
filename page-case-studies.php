<?php
  /**
   * Template Name: Case Studies
   */
  block('header');
  block('hero-inner');
  block('content', [ 'content' => apply_filters('the_content', $post->post_content) ]);  ?>
<section class="case-studies--featured">
  <div class="wrapper">
  <?php
    block('case-study', [ 
      'case_study' => get_field('case_study'),
      'title' => get_field('case_study_title'),
      'copy' => get_field('case_study_copy'),
      'image' => get_field('case_study_image'),
      'image_side' => 'right',
      'classes' => 'm-t-70 m-b-70'
    ]);
  ?>
  </div>
</section>
<?php
  block('logos-grid', [ 
    'title' => get_field('logos_title'),
    'logos' => get_field('logos'),
    'logos_animate_individual' => true,
    'classes' => 'case-studies--logos bg-light-blue'
  ]);
  block('downloads-studies-gallery', [
    'featured_posts' => get_field('featured_posts')
  ]);
  block('footer');
?>
