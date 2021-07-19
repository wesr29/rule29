<?php 
  // their live site doesn't use ENV so don't use that, using the gforms setting instead
  $captcha_site_key = get_option('rg_gforms_captcha_public_key');//!empty($_ENV['RECAPTCHA_SITE_KEY']) ? $_ENV['RECAPTCHA_SITE_KEY'] : ''; //default is a test key
?>
<div id="contact-page-form">
  <r29-contact-page-form captcha-site-key="<?= $captcha_site_key; ?>" />
</div>