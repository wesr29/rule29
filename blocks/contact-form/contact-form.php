<?php
  $background_image = get_field('contact_form_background_image', 'options');
?>
<div id="contact-form">
  <?= !empty($background_image) ? wp_image($background_image, 'large', [ 'class' => 'contact-form--background-image' ] ) : '' ?>
  <r29-contact-form />
</div>