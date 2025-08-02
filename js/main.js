(function ($) {
    let currentLang = 'en';

    /**
     * 1. 언어 변경 기능
     */
    function changeLang(lang) {
        const validLangs = ['ko', 'en'];
        if (!validLangs.includes(lang)) {
            lang = 'en';
        }
        currentLang = lang;
        document.documentElement.lang = lang;

        $('.lang-switch button').removeClass('lang-active');
        $(`.lang-switch button[onclick="changeLang('${lang}')"]`).addClass('lang-active');

        $('#asura-content-container').find('[data-lang]').hide();
        const selector = (lang === 'ko') ? '[data-lang="ko"], [data-lang="kr"]' : '[data-lang="en"]';
        $('#asura-content-container').find(selector).show();

        $('.header-navigation-elements [data-lang]').hide();
        const headerSelector = (lang === 'ko') ? '[data-lang="ko"]' : '[data-lang="en"]';
        $('.header-navigation-elements').find(headerSelector).show();

        const $contactPopup = $('#contact-popup');
        if ($contactPopup.length > 0) {
            $contactPopup.find('[data-lang]').hide();
            const popupSelector = (lang === 'ko') ? '[data-lang="ko"]' : '[data-lang="en"]';
            $contactPopup.find(popupSelector).show();
            const $closeButton = $contactPopup.find('.close-popup');
            const titleAttr = (lang === 'ko') ? $closeButton.attr('title-ko') : $closeButton.attr('title-en');
            $closeButton.attr('title', titleAttr);
        }

        document.dispatchEvent(new CustomEvent('langChanged', { detail: { lang: lang } }));
    }
    window.changeLang = changeLang;

    /**
     * 2. 활성 탭 UI 업데이트
     */
    function updateActiveTab(section) {
        const navContainer = document.querySelector('.header-navigation-elements');
        if (!navContainer) return;

        const allButtons = navContainer.querySelectorAll('button[data-section], .dropbtn');
        allButtons.forEach(btn => btn.classList.remove('active'));

        const targetButton = navContainer.querySelector(`button[data-section="${section}"]`);
        if (!targetButton) return;

        const dropdown = targetButton.closest('.dropdown');
        if (dropdown) {
            const dropbtn = dropdown.querySelector('.dropbtn');
            if (dropbtn) dropbtn.classList.add('active');
        } else {
            targetButton.classList.add('active');
        }
    }

    /**
         * 3. 콘텐츠 AJAX 로더 (active 클래스 추가)
         */
    function loadContent(game, section, callback) {
        if (!section) {
            section = game;
            game = null;
        }
        updateActiveTab(section);

        let $targetContainer;
        if (game === 'poe1' && section !== 'landing') {
            $targetContainer = $('#poe1-sub-content-container');
        } else {
            $targetContainer = $('#asura-content-container');
        }

        if (!$targetContainer.length) {
            console.error('콘텐츠를 삽입할 컨테이너를 찾지 못했습니다:', game, section);
            $('#loading-overlay').fadeOut(200);
            return;
        }

        $('#loading-overlay').fadeIn(200);

        const apiUrl = game
            ? `/wp-json/asura/v1/get_section/${game}/${section}`
            : `/wp-json/asura/v1/get_section/${section}`;

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'html',
            success: function (response) {
                // 이전 콘텐츠를 지우고 새로운 콘텐츠를 삽입
                $targetContainer.html(response);
                // 새로 삽입된 .section 요소에 active 클래스를 추가하여 보이게 만듦
                $targetContainer.find('.section').addClass('active');

                changeLang(currentLang);

                setTimeout(function () {
                    if (typeof window.mapCalculatorInit === 'function') window.mapCalculatorInit();
                    if (typeof window.regexGeneratorInit === 'function') window.regexGeneratorInit();

                    if (typeof callback === 'function') {
                        callback();
                    }

                    $('#loading-overlay').fadeOut(200);
                }, 0);
            },
            error: function (jqXHR) {
                $targetContainer.html(`<p class="loading-text" style="display: block; color: #FFB3B3;">${jqXHR.responseText}</p>`);
                $('#loading-overlay').fadeOut(200);
            }
        });
    }

    /**
     * 4. SPA 네비게이션 이벤트 핸들러
     */
    $(document).on('click', '.poe1-navigation-bar button[data-section]', function (event) {
        event.preventDefault();
        const section = $(this).data('section');
        const game = 'poe1';
        const url = `/${game}/${section}`;
        if (window.location.pathname === url) return;
        history.pushState({ game, section }, '', url);
        loadContent(game, section);
    });

    $(document).on('click', '.game-link[data-game]', function (event) {
        event.preventDefault();
        const game = $(this).data('game');
        const section = $(this).data('section');
        if ($(this).hasClass('disabled')) return;
        const url = `/${game}`;
        if (window.location.pathname === url) return;
        history.pushState({ game, section }, '', url);
        loadContent(game, section);
    });

    $(window).on('popstate', function () {
        handleInitialPageLoad();
    });

    /**
         * 5. 초기 페이지 로드 및 직접 접속 처리 (최종 완성)
         */
    function handleInitialPageLoad() {
        const path = window.location.pathname.replace(/^\/|\/$/g, '');
        const parts = path.split('/').filter(Boolean);
        const game = parts[0];
        const section = parts[1];

        if (parts.length === 0) {
            // 루트 페이지('/')일 경우, 'main' 콘텐츠를 AJAX로 불러옴
            loadContent(null, 'main');
        } else if (parts.length === 1) {
            // 게임 랜딩 페이지 (예: /poe1)
            loadContent(game, 'landing');
        } else {
            // 하위 페이지 (예: /poe1/map)
            loadContent(game, 'landing', function () {
                loadContent(game, section);
            });
        }
    }

    /**
     * 6. 문서 로딩 완료 후 실행
     */
    $(document).ready(function () {
        const initialLang = window.asura_page_data?.lang || 'en';
        changeLang(initialLang);
        handleInitialPageLoad();

        const backToTopButton = $('#custom-back-to-top');
        $(window).on('scroll', function () {
            if ($(this).scrollTop() > 300) {
                backToTopButton.addClass('visible');
            } else {
                backToTopButton.removeClass('visible');
            }
        });

        backToTopButton.on('click', function (event) {
            event.preventDefault();
            $('html, body').stop().animate({ scrollTop: 0 }, 400);
        });
    });

})(jQuery);