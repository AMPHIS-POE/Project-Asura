<?php
/**
 * The template for displaying the header.
 * This is a custom header for Asura Child Theme.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body <?php body_class(); ?>>
<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
}
?>

<header class="asura-custom-main-header">
    <div class="asura-header-inner-content">
     <div class="site-branding">
       <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="asura-logo-link">

        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/asura-logo-2.png" alt="아수라 아이콘" class="asura-icon-logo">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/asura-logo.png" alt="<?php bloginfo( 'name' ); ?> Logo" class="asura-logo-image">

       </a>
    </div>

        <nav class="header-navigation-elements" aria-label="<?php esc_attr_e( 'Header Menu', 'asura-child' ); ?>">
            <div class="lang-switch">
                <button onclick="changeLang('ko')">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/KOREA.png" alt="KO">한글
                </button>
                <button onclick="changeLang('en')">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/USA.png" alt="EN">EN
                </button>
            </div>
        </nav>
    </div>
</header>

<div id="page" class="hfeed site grid-container container grid-parent"> 
    <div id="content" class="site-content"></div>