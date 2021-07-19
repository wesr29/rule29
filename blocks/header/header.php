<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <?php block ('head'); ?>
  <body <?php body_class(); ?>>
    <?php /* this is set in blocks/head */ ?>
    <?php if(!empty($google_tag_manager_id)): ?>
      <!-- Google Tag Manager (noscript) -->
      <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $google_tag_manager_id; ?>"
      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>
    <?php wp_body_open(); ?>
    <header class="main-header">

      <a class="skip-to-content" href="#content" onclick="document.querySelector('#content').focus()">skip to main content</a>

      <?php block('notification', [ 'content' => get_field('site_notification', 'options') ]) ?>

      <div class="main-header--search bg-dark-blue text-white relative desktop-only">
        <?php block('search-form'); ?>
      </div>

      <div class="wrapper flex flex-wrap align-center space-between">
        <a class="site-logo <?php echo is_front_page() ? 'extended' : ''; ?>" href="<?php echo home_url(); ?>" title="<?php echo bloginfo('name'); ?> home">
          <?= render_svg('rule29-logo'); ?>
        </a>
        <a class="mobile-menu-button" href="javascript:;"><span></span></a>
        <nav class="main-menu">
          <?php
            wp_nav_menu([
              'theme_location'  => 'main-menu',
              'container'       => false,
              'walker'          => new MegaMenuWalker()
           ]);
          ?>
        </nav>
      </div>
    </header>
    <div class="main-header--spacer"></div>

    <main>
      <?php if(get_field('add_hidden_title_h1')): ?>
        <h1 class="hidden"><?php the_title(); ?></h1>
      <?php endif; ?>
      <a href="javascript:;" id="content"></a>