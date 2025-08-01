(function ($) {
    let currentLang = 'en';

    /* 1. Language Switcher */
    function changeLang(lang) {
        const validLangs = ['ko', 'en'];
        if (!validLangs.includes(lang)) {
            lang = 'en';
        }
        currentLang = lang;
        document.documentElement.lang = lang;

        $('.lang-switch button').removeClass('lang-active');
        $(`.lang-switch button[onclick="changeLang('${lang}')"]`).addClass('lang-active');

        const activeSection = $('#asura-content-container .section.active');
        if (activeSection.length > 0) {
            activeSection.find('[data-lang]').hide();
            const selector = (lang === 'ko') ? '[data-lang="ko"], [data-lang="kr"]' : '[data-lang="en"]';
            activeSection.find(selector).show();
        }

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

    /* 2. Active Tab Updater */
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

    /* 3. Content Loader */
    function loadContent(game, section) {
        if (!section) {
            section = game;
            game = null;
        }

        updateActiveTab(section);

        let $targetContainer = $('#asura-content-container');
        let isSubContent = false;

        if (game === 'poe1' && section !== 'landing') {
            $targetContainer = $('#poe1-sub-content-container');
            isSubContent = true;
        }

        if (!$targetContainer.length) {
            console.error('Target container not found.');
            return;
        }

        if (!isSubContent) {
            $targetContainer.html('');
        }

        $('#loading-overlay').fadeIn(200);

        let apiUrl = game
            ? `/wp-json/asura/v1/get_section/${game}/${section}`
            : `/wp-json/asura/v1/get_section/${section}`;

        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'html',
            success: function (response) {
                $targetContainer.html(response);
                $targetContainer.find('.section.active').removeClass('active');
                $targetContainer.find('.section').first().addClass('active');

                changeLang(currentLang);

                setTimeout(function () {
                    if (typeof window.mapCalculatorInit === 'function') window.mapCalculatorInit();
                    if (typeof window.regexGeneratorInit === 'function') window.regexGeneratorInit();
                    $('#loading-overlay').fadeOut(200);
                }, 0);
            },
            error: function (jqXHR) {
                $targetContainer.html(`<p class="loading-text" style="display: block; color: #FFB3B3;">${jqXHR.responseText}</p>`);
                $('#loading-overlay').fadeOut(200);
            }
        });
    }

    /* 4. SPA Routing */
    $(document).on('click', '.poe1-navigation-bar button[data-section]', function (event) {
        event.preventDefault();
        const section = $(this).data('section');
        const game = 'poe1';
        const url = `/${game}/${section}`;

        if (window.location.pathname === url) return;

        history.pushState({ game: game, section: section }, '', url);
        loadContent(game, section);
    });

    $(document).on('click', '.game-link[data-game]', function (event) {
        event.preventDefault();
        const game = $(this).data('game');
        const section = $(this).data('section');

        if ($(this).hasClass('disabled')) {
            return;
        }

        const url = `/${game}`;

        if (window.location.pathname === url) return;

        history.pushState({ game: game, section: section }, '', url);
        loadContent(game, section);
    });

    $(window).on('popstate', function (event) {
        if (event.originalEvent.state) {
            loadContent(event.originalEvent.state.game, event.originalEvent.state.section);
        } else {
            handleInitialPageLoad();
        }
    });

    /* 5. Initial Page Load Handler */
    function handleInitialPageLoad() {
        const baseUrl = new URL(window.asura_page_data.base_url);
        const currentUrl = new URL(window.location.href);

        const normalizedBasePath = baseUrl.pathname.replace(/^\/|\/$/g, '');
        const normalizedCurrentPath = currentUrl.pathname.replace(/^\/|\/$/g, '');

        let spaPath = '';
        if (normalizedCurrentPath.startsWith(normalizedBasePath)) {
            spaPath = normalizedCurrentPath.substring(normalizedBasePath.length).replace(/^\/|\/$/g, '');
        } else {
            spaPath = normalizedCurrentPath.replace(/^\/|\/$/g, '');
        }
        
        const parts = spaPath.split('/').filter(Boolean);
        const game = parts[0];
        let section = parts[1];
        
        if (game && !section) {
            section = 'landing';
        }
        
        if (game) {
            loadContent(game, section);
        } else {
            loadContent(null, 'main');
        }
    }


    /* 6. Document Ready (Entry Point) */
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