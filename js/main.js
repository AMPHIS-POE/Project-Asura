// Asura SPA Main Script

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
    function loadContent(section) {
        updateActiveTab(section);

        const container = $('#asura-content-container');
        if (!container.length) return;

        $('#loading-overlay').fadeIn(200);

        $.ajax({
            url: `/wp-json/asura/v1/get_section/${section}`,
            method: 'GET',
            dataType: 'html',
            success: function (response) {
                container.html(response);
                $('.section.active').removeClass('active');
                container.find('.section').first().addClass('active');

                changeLang(currentLang);

                setTimeout(function () {
                    switch (section) {
                        case 'map':
                            if (typeof window.mapCalculatorInit === 'function') window.mapCalculatorInit();
                            break;
                        case 'regex':
                            if (typeof window.regexGeneratorInit === 'function') window.regexGeneratorInit();
                            break;
                    }
                    $('#loading-overlay').fadeOut(200);
                }, 0);
            },
            error: function (jqXHR) {
                container.html(`<p class="loading-text" style="display: block; color: #FFB3B3;">${jqXHR.responseText}</p>`);
                $('#loading-overlay').fadeOut(200);
            }
        });
    }

    /* 4. SPA Routing */
    $(document).on('click', '.header-navigation-elements button[data-section], .dropdown-content a[data-section]', function (event) {
        event.preventDefault();
        const section = $(this).data('section');
        const url = `/${section}`;
        if (window.location.pathname === url) return;

        history.pushState({ section: section }, '', url);
        loadContent(section);
    });

    $(window).on('popstate', function (event) {
        if (event.originalEvent.state && event.originalEvent.state.section) {
            loadContent(event.originalEvent.state.section);
        } else {
            handleInitialPageLoad();
        }
    });

    /* 5. Initial Page Load Handler */
    function handleInitialPageLoad() {
        const path = window.location.pathname;
        const parts = path.split('/').filter(Boolean);
        let section = parts[0];
        const allowedSections = ['map', 'vorici', 'regex', 'links', 'builds'];

        if (!section || !allowedSections.includes(section)) {
            section = 'map';
            history.replaceState({ section: section }, '', `/${section}`);
        }

        loadContent(section);
    }

    /* 6. Document Ready (Entry Point) */
    $(document).ready(function () {
        const initialLang = window.asura_geoip_vars?.lang || 'en';
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