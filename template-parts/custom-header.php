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