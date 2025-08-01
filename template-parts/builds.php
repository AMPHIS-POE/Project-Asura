<section id="builds" class="section" data-section="builds">
    <div class="container">
        <h1>
            <span data-lang="ko">빌드 소개</span>
            <span data-lang="en">Builds</span>
        </h1>

        <?php
        if (isset($args['post'])) {
            echo apply_filters('the_content', $args['post']->post_content);
        }
        ?>
    </div>
</section>