<?php
 /**
  * @param $case_studies - array of WP case study IDs
  * @param $padding | array of ints with keys: desktop_top|desktop_bottom|mobile_top|mobile_bottom
  */
  if(empty($case_studies)){
    return; 
  }

  $custom_padding = !empty($padding) ? build_custom_padding_str($padding) : '';
  $classes = !empty($custom_padding) ? ' custom-padding' : '';  
?>
<section class="case-studies <?php echo $classes; ?>" style="<?= $custom_padding ?>">
  <div class="wrapper">
    <?php foreach($case_studies as $key => $single): ?>
      <?php 
        block('case-study', [ 
          'case_study' => $single['case_study'],
          'title' => $single['title'],
          'copy' => $single['copy'],
          'image' => $single['image'],
          'image_side' => $key % 2 == 0 ? 'right' : 'left'
        ]); 
      ?>
    <?php endforeach; ?>
  </div>
</section>