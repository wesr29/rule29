<?php
  $logo = get_field('footer_logo', 'options');
  $contact_info = get_field('footer_contact_information', 'options');
  $logos_title = get_field('footer_logos_title', 'options');
  $logos = get_field('footer_logos', 'options');
  $copyright_information = get_field('footer_copyright_information', 'options');
  $hide_form = get_field('hide_contact_form');
?>
    </main>
    <footer class="main-footer">
      <?php if(!is_page_template('page-contact.php') && (empty($hide_form) || $hide_form == false)): ?>
      <?php block('contact-form'); ?>
      <?php endif; ?>
      <div class="main-footer--top bg-medium-blue">
        <div class="wrapper">
          <div class="row">
            <div class="col col-sm-3 col-xs-12">
              
              <?php echo ''; //!empty($logo) ? wp_image($logo, 'medium', [ 'class' => 'm-b-30' ]): null; ?>
              <div class="editor-content m-b-30">
                <?php echo $contact_info; ?>
              </div>
              <div><?= render_svg('logo-tagline'); ?></div>
              <div class="mobile-only main-footer--mobile-socials">
                <?php block('social-media-list'); ?>
              </div>
            </div>
            <div class="col col-sm-4 col-xs-12 desktop-only">
              <nav class="main-footer--menu">
                <div class="main-footer--menu--title"><strong>Our Capabilities</strong></div>
                <?php  
                  wp_nav_menu([
                    'theme_location'  => 'footer-menu',
                    'container'       => false,
                  ]); 
                ?>
              </nav>
            </div>
            <div class="col col-sm-5 col-xs-12 desktop-only">
              <?php if(!empty($logos_title)): ?>
                <div class="main-footer--logos--title m-b-20"><strong><?php echo $logos_title; ?></strong></div>
              <?php endif; ?>
              <?php if(!empty($logos)): ?>
                <div class="main-footer--logos row">
                  <?php foreach($logos as $item): ?>
                    <div class="col col-xs-3">
                      <?php if(!empty($item['link'])): ?>
                        <a href="<?php echo $item['link']; ?>" target="_blank"> 
                          <?php echo wp_image($item['logo'], 'medium'); ?>
                        </a>
                      <?php else: ?>
                        <?php echo wp_image($item['logo'], 'medium'); ?>
                      <?php endif; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <hr class="m-b-30">
              <?php block('social-media-list'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="bg-dark-blue text-white text-center p-t-25 p-b-25 main-footer--copyright">
        <div class="wrapper">
          <?php echo $copyright_information; ?>
        </div>
      </div>
      <?php wp_footer(); ?>
    </footer>
    <script type="text/javascript">
    var _ss = _ss || [];
    _ss.push(['_setDomain', 'https://koi-3QNBYBDKVE.marketingautomation.services/net']);
    _ss.push(['_setAccount', 'KOI-3V1M40TOAW']);
    _ss.push(['_trackPageView']);
    window._pa = window._pa || {};
    // _pa.orderId = "myOrderId"; // OPTIONAL: attach unique conversion identifier to conversions
    // _pa.revenue = "19.99"; // OPTIONAL: attach dynamic purchase values to conversions
    // _pa.productId = "myProductId"; // OPTIONAL: Include product ID for use with dynamic ads
(function() {
    var ss = document.createElement('script');
    ss.type = 'text/javascript'; ss.async = true;
    ss.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'koi-3QNBYBDKVE.marketingautomation.services/client/ss.js?ver=2.4.0';
    var scr = document.getElementsByTagName('script')[0];
    scr.parentNode.insertBefore(ss, scr);
})();
</script>
  </body>
</html>