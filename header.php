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
            <div class="tabs">

                <div class="dropdown">
                    <button class="dropbtn">
                        <span data-lang="ko">계산기</span>
                        <span data-lang="en">Calculators</span>
                    </button>
                    <div class="dropdown-content">
                        <button data-section="map">
                            <span data-lang="ko">17티어</span>
                            <span data-lang="en">17 Tier</span>
                        </button>
                        <button data-section="vorici">
                            <span data-lang="ko">색채</span>
                            <span data-lang="en">Chromatic</span>
                        </button>
                        <button data-section="regex">
                            <span data-lang="ko">정규식</span>
                            <span data-lang="en">Regex</span>
                        </button>
                    </div>
                </div>

                <button id="tab-links" data-section="links">
                    <span data-lang="ko">링크 모음</span>
                    <span data-lang="en">Links</span>
                </button>

                <button id="tab-builds" data-section="builds">
                    <span data-lang="ko">빌드 소개</span>
                    <span data-lang="en">Builds</span>
                </button>

            </div>

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