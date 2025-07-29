<?php
/**
 * asura-child 테마의 functions.php
 */

// ─────────────────────────────────────────────────────────────────────────
// 1) 부모 테마(asura)의 스타일 불러오기
// ─────────────────────────────────────────────────────────────────────────
add_action('wp_enqueue_scripts', 'asura_child_enqueue_styles');
function asura_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}

// ─────────────────────────────────────────────────────────────────────────
// 2) Asura 도구 전용 CSS + JS 로딩 (모든 페이지 공통 적용)
// ─────────────────────────────────────────────────────────────────────────
add_action('wp_enqueue_scripts', 'asura_tool_assets');
function asura_tool_assets() {
    wp_enqueue_style('google-fonts-cinzel', 'https://fonts.googleapis.com/css2?family=Cinzel&display=swap');
    wp_enqueue_style('google-fonts-noto-serif-kr', 'https://fonts.googleapis.com/css2?family=Noto+Serif+KR&display=swap');
    wp_enqueue_style('asura-tool-style', get_stylesheet_directory_uri() . '/style.css');

    $main_js_version = filemtime(get_stylesheet_directory() . '/js/main.js');
    wp_enqueue_script('asura-tool-main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), $main_js_version, true);

    $country_code = asura_get_user_country_code();
    $initial_lang = ($country_code === 'KR') ? 'ko' : 'en';

    wp_localize_script(
        'asura-tool-main',
        'asura_geoip_vars',
        array('lang' => $initial_lang)
    );

    wp_enqueue_script('asura-tool-map', get_stylesheet_directory_uri() . '/js/map.js', array('asura-tool-main'), '1.0', true);
    wp_enqueue_script('asura-tool-vorici', get_stylesheet_directory_uri() . '/js/vorici.js', array('asura-tool-main'), '1.0', true);
    wp_enqueue_script('asura-tool-regex', get_stylesheet_directory_uri() . '/js/regex.js', array('asura-tool-main'), '1.0', true);
}

// ─────────────────────────────────────────────────────────────────────────
// 3) REST API 등록: /wp-json/asura/v1/divinerate + /mapprices
// ─────────────────────────────────────────────────────────────────────────
add_action('rest_api_init', function () {
    register_rest_route('asura/v1', '/divinerate', array(
        'methods'             => 'GET',
        'callback'            => 'asura_get_divine_rate',
        'permission_callback' => '__return_true',
    ));
    register_rest_route('asura/v1', '/mapprices', array(
        'methods'             => 'GET',
        'callback'            => 'asura_get_map_prices',
        'permission_callback' => '__return_true',
    ));
});

// ─────────────────────────────────────────────────────────────────────────
// 4) Divine Orb 시세 반환
// ─────────────────────────────────────────────────────────────────────────
function asura_get_divine_rate($request) {
    $url = 'https://poe.ninja/api/data/currencyoverview?league=Mercenaries&type=Currency';
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log('★ asura_get_divine_rate: 요청 오류 → ' . $response->get_error_message());
        return new WP_Error('fetch_failed', 'poe.ninja 요청 실패', array('status' => 500));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!is_array($data) || !isset($data['lines'])) {
        error_log('★ asura_get_divine_rate: JSON 파싱 실패 → ' . substr($body, 0, 200));
        return new WP_Error('invalid_response', 'poe.ninja 응답 형식 오류', array('status' => 500));
    }

    foreach ($data['lines'] as $line) {
        if ($line['currencyTypeName'] === 'Divine Orb' && isset($line['chaosEquivalent'])) {
            return rest_ensure_response(array('chaosEquivalent' => $line['chaosEquivalent']));
        }
    }

    error_log('★ asura_get_divine_rate: Divine Orb 정보 없음');
    return new WP_Error('not_found', 'Divine Orb 정보 없음', array('status' => 404));
}

// ─────────────────────────────────────────────────────────────────────────
// 5) 맵 가격 반환
// ─────────────────────────────────────────────────────────────────────────
function asura_get_map_prices($request) {
    $league = 'Mercenaries';
    $url = "https://poe.ninja/api/data/itemoverview?league={$league}&type=Map";

    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
        error_log('★ asura_get_map_prices: 요청 오류 → ' . $response->get_error_message());
        return new WP_Error('fetch_failed', 'poe.ninja 요청 실패', array('status' => 500));
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!is_array($data) || !isset($data['lines'])) {
        error_log('★ asura_get_map_prices: JSON 파싱 실패 → ' . substr($body, 0, 200));
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

// ─────────────────────────────────────────────────────────────────────────
// 6) Open Graph / Twitter Card 메타 태그 삽입
// ─────────────────────────────────────────────────────────────────────────
add_action('wp_head', 'asura_add_meta_og_tags');
function asura_add_meta_og_tags() {
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
function asura_remove_oembed_author($data, $post) {
    unset($data['author_name']);
    unset($data['author_url']);
    return $data;
}

// ─────────────────────────────────────────────────────────────────────────
// 7) SPA 섹션 로딩을 위한 REST API 엔드포인트 추가
// ─────────────────────────────────────────────────────────────────────────
add_action('rest_api_init', function () {
    register_rest_route('asura/v1', '/get_section/(?P<section_name>[a-zA-Z0-9-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'asura_get_section_html',
        'permission_callback' => '__return_true',
    ));
});

// ─────────────────────────────────────────────────────────────────────────
// 7) SPA 섹션 로딩을 위한 REST API 엔드포인트 추가
// ─────────────────────────────────────────────────────────────────────────
add_action('rest_api_init', function () {
    register_rest_route('asura/v1', '/get_section/(?P<section_name>[a-zA-Z0-9-]+)', array(
        'methods'             => 'GET',
        'callback'            => 'asura_get_section_html',
        'permission_callback' => '__return_true',
    ));
});

function asura_get_section_html($data) {
    $section_name = $data['section_name'];
    $allowed_sections = ['map', 'vorici', 'regex', 'links', 'builds'];

    if (!in_array($section_name, $allowed_sections)) {
        status_header(404);
        die('잘못된 섹션 요청입니다.');
    }

    $template_map = [
        'map'    => 'map-17t-calculator',
        'vorici' => 'vorici-calculator',
        'regex'  => 'regex',
        'links'  => 'fansite-links',
        'builds' => 'builds'
    ];
    
    $template_name = $template_map[$section_name] ?? '';

    if (empty($template_name) || !file_exists(get_stylesheet_directory() . '/template-parts/' . $template_name . '.php')) {
        status_header(404);
        echo "서버 오류: '{$template_name}.php' 파일을 찾을 수 없습니다.";
        die();
    }
    
    ob_start();

    if ($section_name === 'builds') {
        $builds_page = get_page_by_path('builds', OBJECT, 'page');

        if ($builds_page) {
            $args = ['post' => $builds_page];
            get_template_part('template-parts/' . $template_name, null, $args);
        } else {
            echo "<p style='color:red;'>오류: 'builds'라는 이름의 워드프레스 페이지를 찾을 수 없습니다. 페이지가 생성되어 있고, 공개 상태인지 확인하세요.</p>";
        }
    } else {
        get_template_part('template-parts/' . $template_name);
    }
    
    $html = ob_get_clean();
    
    header('Content-Type: text/html; charset=utf-8');
    echo $html;

    die();
}

// ─────────────────────────────────────────────────────────────────────────
// 🛂 8) IP 주소로 접속 국가 코드 확인 (GeoIP Detection 플러그인 사용)
// ─────────────────────────────────────────────────────────────────────────
function asura_get_user_country_code() {
    if (function_exists('geoip_detect2_get_info_from_current_ip')) {
        $geoip_info = geoip_detect2_get_info_from_current_ip();
        return $geoip_info->country->isoCode ?? 'US';
    }
    return 'US';
}

// ─────────────────────────────────────────────────────────────────────────
// 🔄 9) SPA 새로고침 시 404 오류 방지
// ─────────────────────────────────────────────────────────────────────────
add_action('init', 'asura_custom_rewrite_rules');
function asura_custom_rewrite_rules() {
    $spa_routes = 'map|vorici|regex|links|builds';

    add_rewrite_rule(
        "($spa_routes)/?$",
        'index.php?pagename=asura',
        'top'
    );
}

add_action( 'wp_enqueue_scripts', 'asura_child_remove_parent_scroll_scripts', 11 );
function asura_child_remove_parent_scroll_scripts() {
    wp_dequeue_script( 'asura-back-to-top' );
    wp_deregister_script( 'asura-back-to-top' );
}