<?php
define('SULLI_VERSION', '1.0.0');

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function sulli_theme_support()
{

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    // Set post thumbnail size.
    set_post_thumbnail_size(1200, 9999);

    // Add custom image size used in Cover Template.
    add_image_size('twentytwenty-fullscreen', 1980, 9999);

    /*
     * Let WordPress manage the document title.
     * By adding theme support, we declare that this theme does not use a
     * hard-coded <title> tag in the document head, and expect WordPress to
     * provide it for us.
     */
    add_theme_support('title-tag');

    /*
     * Switch default core markup for search form, comment form, and comments
     * to output valid HTML5.
     */
    add_theme_support(
        'html5',
        array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'script',
            'style',
        )
    );

    // Add support for full and wide align images.
    add_theme_support('align-wide');

    /*
     * Adds `async` and `defer` support for scripts registered or enqueued
     * by the theme.
     */
    //$loader = new TwentyTwenty_Script_Loader();
    //add_filter( 'script_loader_tag', array( $loader, 'filter_script_loader_tag' ), 10, 2 );

}

add_action('after_setup_theme', 'sulli_theme_support');

/**
 * Register and Enqueue Styles.
 */
function sulli_register_styles()
{

    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style('sulli-style', get_template_directory_uri() . '/build/css/app.css', array(), $theme_version);

}

add_action('wp_enqueue_scripts', 'sulli_register_styles');

/**
 * Register and Enqueue Scripts.
 */
function sulli_register_scripts()
{

    $theme_version = wp_get_theme()->get('Version');

    if ((!is_admin()) && is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    wp_enqueue_script('sulli-js', get_template_directory_uri() . '/build/js/app.js', array('jquery'), $theme_version, false);
    wp_script_add_data('sulli-js', 'async', true);

}

add_action('wp_enqueue_scripts', 'sulli_register_scripts');

/**
 * Register navigation menus uses wp_nav_menu in five places.
 */
function sulli_menus()
{

    $locations = array(
        'primary' => '主题菜单',
    );

    register_nav_menus($locations);
}

add_action('init', 'sulli_menus');

/**
 * Comments
 */
/**
 * Check if the specified comment is written by the author of the post commented on.
 *
 * @param object $comment Comment data.
 *
 * @return bool
 */
function sulli_is_comment_by_post_author($comment = null)
{

    if (is_object($comment) && $comment->user_id > 0) {

        $user = get_userdata($comment->user_id);
        $post = get_post($comment->comment_post_ID);

        if (!empty($user) && !empty($post)) {

            return $comment->user_id === $post->post_author;

        }
    }
    return false;

}

/**
 * Filter comment reply link to not JS scroll.
 * Filter the comment reply link to add a class indicating it should not use JS slow-scroll, as it
 * makes it scroll to the wrong position on the page.
 *
 * @param string $link Link to the top of the page.
 *
 * @return string $link Link to the top of the page.
 */
function sulli_filter_comment_reply_link($link)
{

    $link = str_replace('class=\'', 'class=\'do-not-scroll ', $link);
    return $link;

}

//add_filter( 'comment_reply_link', 'twentytwenty_filter_comment_reply_link' );

/**
 * Classes
 */
/**
 * Add No-JS Class.
 * If we're missing JavaScript support, the HTML element will have a no-js class.
 */
function sulli_no_js_class()
{

    ?>
	<script>document.documentElement.className = document.documentElement.className.replace( 'no-js', 'js' );</script>
	<?php

}

add_action('wp_head', 'sulli_no_js_class');

function aladdin_get_background_image($post_id, $width = null, $height = null) {
    if (has_post_thumbnail($post_id)) {
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
        $output       = $timthumb_src[0];
    } elseif (get_post_meta($post_id,'_banner',true)) {
        $output = get_post_meta($post_id,'_banner',true);
    }else {
        $content         = get_post_field('post_content', $post_id);
        $defaltthubmnail = '//static.fatesinger.com/2018/05/q3wyes7va2ehq59y.JPG';
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        $n = count($strResult[1]);
        if ($n > 0) {
            $output = $strResult[1][0];
        } else {
            $output = $defaltthubmnail;
        }
    }
    $result = $output;

    return $result;
}


function sulli_comment($comment, $args, $depth){
    $GLOBALS['comment'] = $comment;
    switch ($comment->comment_type):
        case 'pingback':
        case 'trackback':
            ?>
            <li <?php comment_class();?> id="comment-<?php comment_ID();?>">
            <div class="pingback-content"><?php comment_author_link();?></div>
            <?php
            break;
        default:
            global $post;
            ?>
        <li class="comment sulliComment" itemtype="http://schema.org/Comment" data-id="<?php comment_ID()?>" itemscope="" itemprop="comment">
            <div id="comment-<?php comment_ID()?>" class="sulliComment--block">
                <div class="sulliComment--info">
                        <img height=48 width=48 alt="<?php echo $comment->comment_author;?>的头像" aria-label="<?php echo $comment->comment_author;?>的头像" src="<?php echo get_avatar_url($comment, array('size'=>48));?>" class="avatar" />
                        <span class="sulliComment--author" itemprop="author"><?php echo get_comment_author_link();?></span>
                    </div>
                <div class="sulliComment--content" itemprop="description">
                    <?php comment_text();?>
                </div>
                <div class="sulliComment--footer">
<?php echo '<span class="comment-reply-link u-cursorPointer" onclick="return addComment.moveForm(\'comment-' . $comment->comment_ID . '\', \'' . $comment->comment_ID . '\', \'respond\', \'' . $post->ID . '\')">reply</span>';?> · <span class="comment--time sulli comment-time" itemprop="datePublished" datetime="<?php echo get_comment_date('c');?>"><?php echo get_comment_date('M d,Y');?></span>
                </div>
            </div>
            <?php
            break;
    endswitch;
}