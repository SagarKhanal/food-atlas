<?php
/**
 * template for options page
 * @uses HTCC_Admin::settings_page
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div class="wrap">

    <?php settings_errors(); ?>
    
    <form action="options.php" method="post" class="">
        <?php settings_fields( 'htcc_settings_group' ); ?>
        <?php do_settings_sections( 'htcc_options_settings' ) ?>
        <?php submit_button() ?>
    </form>
        
</div>