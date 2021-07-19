<?php
  /**
   * @param $category - wp category
   * @param $post_type - wp post type
   */

  if(empty($category)){
    return false;
  }
  if(empty($post_type)){
    $post_type = 'post';
  }

?>
<div class="category-badge text-white uppercase" style="background:<?= get_field('color', $category) ?: '#002E5D' ?>">
  <?php if($post_type == 'page'){
    echo 'Page';
  }
  elseif($post_type == 'case_studies'){
    echo 'Case Study';
  }
  elseif($post_type == 'downloads'){
    echo 'Download';
  }
  else{
    echo $category->name;
  } ?>
</div>