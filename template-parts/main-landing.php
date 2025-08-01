<?php
/**
 * Template Part for Main Landing Page
 */
?>
<div class="section" id="asura-main-landing">

    <div class="main-landing-banner">
        <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/banner-asura.png" alt="Asura Main Banner">
    </div>

    <div class="content-grid">
        <div class="left-column">
            <div class="content-box info-box">
                <h3>
                    <span data-lang="ko">아수라(Asura)란?</span>
                    <span data-lang="en">What is Asura?</span>
                </h3>
                <p>
                    <span data-lang="ko">
                        레이클라스트를 위하여                   </span>
                    <span data-lang="en">
                        For Wraecalst and Exile

                    </span>
                </p>
            </div>

            <div class="content-box cat-box">
                <h3>
                    <span data-lang="ko">이 고양이는 뭔가요?</span>
                    <span data-lang="en">What is this cat?</span>
                </h3>
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/social/james.png" alt="A cute cat">

                <p>
                    <span data-lang="ko">
                        제 고양이니까 보세요
                    </span>
                    <span data-lang="en">
                        It's my cat, Just look at him
                    </span>
                </p>
            </div>
        </div>

        <div class="content-box selection-box">
            <h2>
                <span data-lang="ko">게임을 선택하세요</span>
                <span data-lang="en">Select Your Game</span>
            </h2>
            <div class="game-buttons">
                <a href="/poe1" class="game-link" data-game="poe1" data-section="landing">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/poe1-logo.png" alt="Path of Exile 1">
                </a>
                <a href="/poe2" class="game-link" data-game="poe2" data-section="landing">
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/poe2-logo.png" alt="Path of Exile 2">
                </a>
            </div>
        </div>

    </div>

</div>