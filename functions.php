<?php

// 1) 부모 테마(asura)의 스타일 불러오기
add_action('wp_enqueue_scripts', 'asura_child_enqueue_styles');
function asura_child_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}

// 2) Asura 도구 전용 CSS + JS 로딩
add_action('wp_enqueue_scripts', 'asura_tool_assets');
function asura_tool_assets()
{
    if (!is_page_template('page-asura.php')) {
        return;
    }

    wp_enqueue_style('google-fonts-cinzel', 'https://fonts.googleapis.com/css2?family=Cinzel&display=swap');
    wp_enqueue_style('google-fonts-noto-serif-kr', 'https://fonts.googleapis.com/css2?family=Noto+Serif+KR&display=swap');
    wp_enqueue_style('asura-tool-style', get_stylesheet_directory_uri() . '/style.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css', array(), '6.5.2');

    $main_js_version = filemtime(get_stylesheet_directory() . '/js/main.js');
    wp_enqueue_script('asura-tool-main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), $main_js_version, true);

    $country_code = asura_get_user_country_code();
    $initial_lang = ($country_code === 'KR') ? 'ko' : 'en';
    $base_url = get_permalink();

    wp_localize_script(
        'asura-tool-main',
        'asura_page_data',
        array(
            'lang' => $initial_lang,
            'base_url' => trailingslashit($base_url)
        )
    );

    wp_enqueue_script('asura-tool-map', get_stylesheet_directory_uri() . '/js/map.js', array('asura-tool-main'), '1.0', true);
    wp_enqueue_script('asura-tool-vorici', get_stylesheet_directory_uri() . '/js/vorici.js', array('asura-tool-main'), '1.0', true);
    wp_enqueue_script('asura-tool-regex', get_stylesheet_directory_uri() . '/js/regex.js', array('asura-tool-main'), '1.0', true);
}

// 3) REST API 엔드포인트 등록 (SPA 및 시세 정보)
add_action('rest_api_init', function () {
    register_rest_route('asura/v1', '/get_section/(?P<game>[a-zA-Z0-9-]+)/(?P<section>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'asura_get_section_html',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('asura/v1', '/get_section/(?P<section_name>[a-zA-Z0-9-]+)', array(
        'methods' => 'GET',
        'callback' => 'asura_get_section_html',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('asura/v1', '/divinerate', array(
        'methods' => 'GET',
        'callback' => 'asura_get_divine_rate',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('asura/v1', '/mapprices', array(
        'methods' => 'GET',
        'callback' => 'asura_get_map_prices',
        'permission_callback' => '__return_true',
    ));
});

// 4) Divine Orb 시세 반환
function asura_get_divine_rate($request)
{
    $url = 'https://poe.ninja/api/data/currencyoverview?league=Mercenaries&type=Currency';
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return new WP_Error('fetch_failed', 'poe.ninja 요청 실패', array('status' => 500));
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!is_array($data) || !isset($data['lines'])) {
        return new WP_Error('invalid_response', 'poe.ninja 응답 형식 오류', array('status' => 500));
    }
    foreach ($data['lines'] as $line) {
        if ($line['currencyTypeName'] === 'Divine Orb' && isset($line['chaosEquivalent'])) {
            return rest_ensure_response(array('chaosEquivalent' => $line['chaosEquivalent']));
        }
    }
    return new WP_Error('not_found', 'Divine Orb 정보 없음', array('status' => 404));
}

// 5) 맵 가격 반환
function asura_get_map_prices($request)
{
    $league = 'Mercenaries';
    $url = "https://poe.ninja/api/data/itemoverview?league={$league}&type=Map";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        return new WP_Error('fetch_failed', 'poe.ninja 요청 실패', array('status' => 500));
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!is_array($data) || !isset($data['lines'])) {
        return new WP_Error('invalid_response', 'poe.ninja 응답 형식 오류', array('status' => 500));
    }
    $wantedMaps = ['Sanctuary Map', 'Fortress Map', 'Citadel Map', 'Ziggurat Map', 'Abomination Map'];
    $result = [];
    foreach ($data['lines'] as $item) {
        if (isset($item['name'], $item['chaosValue']) && in_array($item['name'], $wantedMaps)) {
            $shortName = str_replace(' Map', '', $item['name']);
            $result[$shortName] = $item['chaosValue'];
        }
    }
    return rest_ensure_response($result);
}

// 6) Open Graph / Twitter Card 메타 태그 삽입
add_action('wp_head', 'asura_add_meta_og_tags');
function asura_add_meta_og_tags()
{
    ?>
    <meta property="og:title" content="ASURA" />
    <meta property="og:description" content="For Wraeclast & Path of Exile" />
    <meta property="og:url" content="https://asura.design/" />
    <meta property="og:image" content="https://asura.design/wp-content/themes/asura-child/img/og-asura-banner.png" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="ASURA" />
    <meta name="twitter:description" content="For Wraeclast & Path of Exile" />
    <meta name="twitter:image" content="https://asura.design/wp-content/themes/asura-child/img/og-asura-banner.png" />
    <?php
}

add_filter('oembed_response_data', 'asura_remove_oembed_author', 10, 2);
function asura_remove_oembed_author($data, $post)
{
    unset($data['author_name']);
    unset($data['author_url']);
    return $data;
}

// 7) SPA 섹션 로딩 함수
function asura_get_section_html($data)
{
    $game_slug = $data['game'] ?? null;
    $section_slug = $data['section'] ?? $data['section_name'];

    $template_name = '';

    if ($game_slug === 'poe1') {
        $allowed_sections = ['landing', 'map', 'vorici', 'regex', 'links', 'builds'];
        if (!in_array($section_slug, $allowed_sections)) {
            status_header(404);
            die('잘못된 POE1 섹션 요청입니다.');
        }
        $template_map = [
            'landing' => 'poe1-landing',
            'map' => 'map-17t-calculator',
            'vorici' => 'vorici-calculator',
            'regex' => 'regex',
            'links' => 'fansite-links',
            'builds' => 'builds',
        ];
        $template_name = $template_map[$section_slug] ?? '';
    } elseif ($game_slug === 'poe2') {
        $allowed_sections = ['landing', 'news', 'classes', 'skills'];
        if (!in_array($section_slug, $allowed_sections)) {
            status_header(404);
            die('잘못된 POE2 섹션 요청입니다.');
        }
        $template_map = [
            'landing' => 'poe2-landing',
        ];
        $template_name = $template_map[$section_slug] ?? '';
    } else {
        $allowed_sections = ['main'];
        if (!in_array($section_slug, $allowed_sections)) {
            status_header(404);
            die('잘못된 섹션 요청입니다.');
        }
        $template_map = ['main' => 'main-landing'];
        $template_name = $template_map[$section_slug] ?? '';
    }

    if (empty($template_name) || !file_exists(get_stylesheet_directory() . '/template-parts/' . $template_name . '.php')) {
        if ($game_slug === 'poe2' && $section_slug !== 'landing') {
            echo "<div class='section' style='text-align:center; padding: 50px;'><h2 data-lang='ko'>콘텐츠 준비 중입니다.</h2><h2 data-lang='en'>Content Coming Soon.</h2></div>";
            die();
        }
        status_header(404);
        echo "서버 오류: '{$template_name}.php' 파일을 찾을 수 없습니다.";
        die();
    }

    ob_start();
    if ($section_slug === 'builds' && $game_slug === 'poe1') {
        $builds_page = get_page_by_path('builds', OBJECT, 'page');
        if ($builds_page) {
            get_template_part('template-parts/' . $template_name, null, ['post' => $builds_page]);
        } else {
            echo "<p style='color:red;'>오류: 'builds' 페이지를 찾을 수 없습니다.</p>";
        }
    } else {
        get_template_part('template-parts/' . $template_name);
    }
    $html = ob_get_clean();

    header('Content-Type: text/html; charset=utf-8');
    echo $html;
    die();
}

// 8) IP 주소로 접속 국가 코드 확인
function asura_get_user_country_code()
{
    if (function_exists('geoip_detect2_get_info_from_current_ip')) {
        $geoip_info = geoip_detect2_get_info_from_current_ip();
        return $geoip_info->country->isoCode ?? 'US';
    }
    return 'US';
}

// 9) 부모 테마 스크립트 비활성화
add_action('wp_enqueue_scripts', 'asura_child_remove_parent_scroll_scripts', 11);
function asura_child_remove_parent_scroll_scripts()
{
    wp_dequeue_script('asura-back-to-top');
    wp_deregister_script('asura-back-to-top');
}

// ─────────────────────────────────────────────────────────────────────────
// ✅ 9) SPA 라우팅 처리 (최종 버전)
// ─────────────────────────────────────────────────────────────────────────

/**
 * 테마가 활성화될 때 URL 규칙을 한번만 초기화합니다.
 */
add_action('after_switch_theme', 'asura_flush_rewrite_rules_once');
function asura_flush_rewrite_rules_once()
{
    asura_custom_rewrite_rules();
    flush_rewrite_rules();
}

/**
 * 9-1. 'is_spa_route' 변수 등록
 */
add_filter('query_vars', 'asura_add_query_vars');
function asura_add_query_vars($vars)
{
    $vars[] = 'is_spa_route';
    return $vars;
}

/**
 * 9-2. 재작성 규칙 설정
 */
add_action('init', 'asura_custom_rewrite_rules');
function asura_custom_rewrite_rules()
{
    add_rewrite_rule('^(poe1|poe2)(/.*)?$', 'index.php?is_spa_route=1', 'top');
}

/**
 * 9-3. 메인 쿼리 강제 수정
 */
add_action('pre_get_posts', 'asura_spa_query_fix');
function asura_spa_query_fix($query)
{
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    if (get_query_var('is_spa_route')) {
        $front_page_id = get_option('page_on_front');
        if ($front_page_id) {
            $query->set('page_id', $front_page_id);
            $query->is_page = true;
            $query->is_singular = true;
            $query->is_home = false;
            $query->is_front_page = true;
        }
    }
}

/**
 * 9-4. 리디렉션 방지
 */
add_filter('redirect_canonical', 'asura_disable_canonical_redirect_for_spa', 10, 2);
function asura_disable_canonical_redirect_for_spa($redirect_url, $requested_url)
{
    $path = wp_parse_url($requested_url, PHP_URL_PATH);
    if (preg_match('/^\/(poe1|poe2)/', $path)) {
        return false;
    }
    return $redirect_url;
}

// ─────────────────────────────────────────────────────────────────────────
// ✅ 10) 커스텀 헤더 및 푸터 적용
// ─────────────────────────────────────────────────────────────────────────
add_action('wp', 'asura_control_astra_elements');
function asura_control_astra_elements()
{
    // 우리가 만든 SPA 페이지(정적 홈페이지)에서만 작동하도록 조건을 설정합니다.
    if (is_front_page()) {

        // Astra 테마의 기본 헤더와 저작권 푸터 영역을 비활성화합니다.
        add_filter('astra_main_header_display', '__return_false');
        add_filter('astra_footer_bar_display', '__return_false');

        // 우리가 만든 커스텀 헤더와 푸터를 지정된 위치에 출력합니다.
        add_action('astra_body_top', 'asura_add_custom_header');
        add_action('wp_footer', 'asura_add_custom_footer');
    }
}

// 커스텀 헤더를 출력하는 함수
function asura_add_custom_header()
{
    get_template_part('template-parts/custom-header');
}

// 커스텀 푸터를 출력하는 함수
function asura_add_custom_footer()
{
    get_template_part('template-parts/custom-footer');
}