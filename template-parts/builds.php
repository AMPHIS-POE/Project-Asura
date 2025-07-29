<section id="builds" class="section" data-section="builds">
    <div class="container">
        <h1>
            <span data-lang="ko">빌드 소개</span>
            <span data-lang="en">Builds</span>
        </h1>

        <?php
        // 워드프레스 페이지 내용을 동적으로 불러오는 최종 코드
        if (isset($args['post'])) {
            echo apply_filters('the_content', $args['post']->post_content);
        }
        ?>
    </div>
</section>