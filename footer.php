<?php
/**
 * The template for displaying the footer.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}
?>

    </div></div><?php
/**
 * asura_before_footer hook.
 *
 */
do_action( 'asura_before_footer' );
?>

<div <?php asura_footer_class(); ?>>
    <?php
    /**
     * asura_before_footer_content hook.
     *
     */
    do_action( 'asura_before_footer_content' );

    /**
     * asura_footer hook.
     *
     *
     * @hooked asura_construct_footer_widgets - 5
     * @hooked asura_construct_footer - 10
     */
    do_action( 'asura_footer' );

    /**
     * asura_after_footer_content hook.
     *
     */
    do_action( 'asura_after_footer_content' );
    ?>
</div><?php
/**
 * asura_after_footer hook.
 *
 */
do_action( 'asura_after_footer' );

?>
<a id="custom-back-to-top" title="Scroll back to top">▲</a>
<?php

wp_footer();
?>

<div id="loading-overlay">
    <div class="loading-spinner"></div>
</div>

</body>
</html>