<head>
  <?php
    $google_analytics_id = get_field('google_analytics_id', 'options');
    $google_tag_manager_id = get_field('google_tag_manager_id', 'options')
  ?>

  <?php if(!empty($google_analytics_id)): ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_analytics_id; ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?php echo $google_analytics_id; ?>');
      <?php if(!empty($google_tag_manager_id)): ?>
        gtag('config', '<?php echo $google_tag_manager_id; ?>');
      <?php endif ?>
    </script>
  <?php endif; ?>

  <?php if(!empty($google_tag_manager_id)): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $google_tag_manager_id; ?>');</script>
    <!-- End Google Tag Manager -->
  <?php endif; ?>

  <?php wp_head(); ?>

  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="format-detection" content="telephone=no"><!-- Removes auto phone number detection on iOS -->

  <title><?php wp_title( '|', true, 'right' ); ?></title>
  
  <!-- typekit stuff -->
  <link rel="stylesheet" href="https://use.typekit.net/ivn5cmg.css">
</head>