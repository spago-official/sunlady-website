// WordPressのREST APIにニュース用のエンドポイントを追加
add_action('rest_api_init', function () {
    register_rest_route('news/v1', '/latest', array(
        'methods' => 'GET',
        'callback' => 'get_latest_news',
        'permission_callback' => '__return_true'
    ));
});

function get_latest_news() {
    $args = array(
        'post_type' => 'news',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    $posts = get_posts($args);
    $news = array();

    foreach ($posts as $post) {
        $image = get_the_post_thumbnail_url($post->ID, 'full');
        $news[] = array(
            'id' => $post->ID,
            'date' => $post->post_date,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'image' => array(
                'url' => $image ? $image : '',
                'alt' => get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true)
            ),
            'slug' => $post->post_name
        );
    }

    return $news;
} 