<?php
/**
 * Functions
 * Core functions file for the theme.
 * php version 8.0
 *
 * @category Core
 *
 * @package Aliolam
 *
 * @author César Montilla <cmontilla@fabsoftw.com>
 *
 * @license MIT https://opensource.org/licenses/MIT
 *
 * @link https://editorialpodergrafico.com
 **/
require_once __DIR__ . '/vendor/autoload.php';

/**
 * ThemeInit
 * WPPackio Init enqueue files.
 * php version 8.0
 *
 * @category E
 *
 * @package Enqueue
 *
 * @author César Montilla <cmontilla@fabsoftw.com>
 *
 * @license MIT https://opensource.org/licenses/MIT
 *
 * @link https://editorialpodergrafico.com
 **/
class EpgraficoThemeInit
{
    /**
     * Define public var.
     *
     * @var \WPackio\Enqueue
     */
    public $enqueue;

    /**
     * Define public function.
     *
     * @return array
     */
    public function __construct()
    {
        $this->enqueue = new \WPackio\Enqueue(
            'epgrafico',
            'dist',
            '1.0.0',
            'theme',
            false,
            "child"
        );
        add_action('wp_enqueue_scripts', [ $this, 'themeEnqueue' ], 999);
    }

    /**
     * Define public themeEnqueue.
     *
     * @return array
     */
    public function themeEnqueue()
    {
        $this->enqueue->enqueue('desktop', 'main', []);
    }
}

// Init
new EpgraficoThemeInit();

/**
 * Load translation files from your child theme
 *
 * @return string
 */
function Epgrafico_Theme_locale()
{
    load_child_theme_textdomain(
        'epgrafico', get_stylesheet_directory() . '/languages'
    );
}
add_action('after_setup_theme', 'Epgrafico_Theme_locale');

/**
 * Add post loop function
 *
 * @return string
 */
function Epgrafico_Post_loop() {
    echo '<div class="entry-content" itemprop="articleBody">';
    if( has_post_thumbnail() ) {
        the_post_thumbnail( 'large', [ 'itemprop' => 'image' ] );
    }
    the_excerpt();
    echo '</div>';
}

/**
 * Overwrite existing storefront actions
 *
 * @return void
 */
function Epgrafico_Customise_storefront()
{
    // Remove header actions.
    remove_action('storefront_header', 'storefront_secondary_navigation', 30);
    remove_action('storefront_header', 'storefront_product_search', 40);
    remove_action('storefront_header', 'storefront_header_cart', 60);

    // Remove homepage title.
    remove_action('storefront_homepage', 'storefront_homepage_header', 10);
    remove_action('homepage', 'storefront_product_categories', 20);
    remove_action('homepage', 'storefront_popular_products', 50);
    remove_action('homepage', 'storefront_on_sale_products', 60);
    remove_action('homepage', 'storefront_best_selling_products', 70);

    // Reorder header actions.
    add_action('storefront_header', 'storefront_product_search', 10);
    add_action('storefront_header', 'storefront_secondary_navigation', 30);

    remove_action('storefront_loop_post', 'storefront_post_content', 30);
    add_action('storefront_loop_post', 'Epgrafico_Post_loop', 30);
}
add_action('init', 'Epgrafico_Customise_storefront');

/**
 * Add priority inline css
 *
 * @return string
 */
function Epgrafico_Inline_styles()
{
    echo ("
        <style>
            .storefront-secondary-navigation.woocommerce-active .site-header img {
                max-width: 200px;
            }
        </style>
    ");
}
add_action('wp_head', 'Epgrafico_Inline_styles', 10);

/**
 * Replace Storefront Google font.
 *
 * @return mixed
 **/
function Epgrafico_Replace_Google_fonts()
{
    // Remove Storefront fonts.
    wp_dequeue_style('storefront-fonts');
}

add_action('wp_enqueue_scripts', 'Epgrafico_Replace_Google_fonts', 100);

/**
 * Code snippet to speed up Google Fonts rendering
 *
 * @return string
 **/
function Epgrafico_Load_Google_fonts()
{
    ?>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="anonymous">
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Quattrocento&display=swap" as="fetch" crossorigin="anonymous">
    <script type="text/javascript">
    !function(e,n,t){"use strict";var o="https://fonts.googleapis.com/css2?family=Quattrocento&display=swap",r="__epgrafico_googleFontsStylesheet";function c(e){(n.head||n.body).appendChild(e)}function a(){var e=n.createElement("link");e.href=o,e.rel="stylesheet",c(e)}function f(e){if(!n.getElementById(r)){var t=n.createElement("style");t.id=r,c(t)}n.getElementById(r).innerHTML=e}e.FontFace&&e.FontFace.prototype.hasOwnProperty("display")?(t[r]&&f(t[r]),fetch(o).then(function(e){return e.text()}).then(function(e){return e.replace(/@font-face {/g,"@font-face{font-display:swap;")}).then(function(e){return t[r]=e}).then(f).catch(a)):a()}(window,document,localStorage);
    </script>
    <?php
}
add_action('wp_head', 'Epgrafico_Load_Google_fonts');

/**
 * Display the theme credit
 *
 * @since  1.0.0
 * @return void
 */
// phpcs:ignore
function storefront_credit()
{
    $links_output = '';

    if (apply_filters('storefront_privacy_policy_link', true)
        && function_exists('the_privacy_policy_link')
    ) {
        $separator = '<span role="separator" aria-hidden="true"></span>';
        $links_output = get_the_privacy_policy_link(
            '', (! empty($links_output) ? $separator : '')
        ) . $links_output;
    }

    $links_output = apply_filters('storefront_credit_links_output', $links_output);
    ?>
    <div class="site-info">
      <?php
        echo esc_html(
            apply_filters(
                'storefront_copyright_text',
                $content = '&copy; ' . get_bloginfo('name') . ' ' . gmdate('Y')
            )
        );
        ?>

      <?php if (! empty($links_output)) { ?>
        <br />
            <?php echo wp_kses_post($links_output); ?>
      <?php } ?>
    </div><!-- .site-info -->
    <?php
}

/**
 * Add extra class to post loop
 *
 * @param array $classes Post item classes
 *
 * @return array
 */
function Epgrafico_Post_List_class($classes)
{
    global $wp_query;
    if (is_archive() or is_home()) {
        $classes[] = 'post-loop-item';
    }
    return $classes;
}
add_filter('post_class', 'Epgrafico_Post_List_class');


/**
 * Remove Woocommerce subcategory count
 *
 * @return void
 */
function Epgrafico_Hide_Subcategory_count() {
  /* empty - no count */
}
add_filter('woocommerce_subcategory_count_html', 'Epgrafico_Hide_Subcategory_count');

/**
 * Adds sharing buttons
 *
 * @return string
 */
function Epgrafico_Share_buttons()
{
    global $post;

    // Get current page URL
    $contentUrl = urlencode(get_permalink());

    // Get current page title
    $contentTitle = htmlspecialchars(
        urlencode(
            html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')
        ), ENT_COMPAT, 'UTF-8'
    );

    // Get Post Thumbnail for pinterest
    $contentThumbnail = wp_get_attachment_image_src(
        get_post_thumbnail_id($post->ID), 'full'
    );

    // Construct sharing URL without using any script
    $facebookURL = (
        'https://www.facebook.com/sharer/sharer.php?u=' .$contentUrl .''
    );

    $twitterURL = (
        'https://twitter.com/intent/tweet?text='
         .$contentTitle. '&amp;url=' .$contentUrl. '&amp;'
    );

    $googleURL = (
        'https://plus.google.com/share?url=' .$contentUrl
    );

    $linkedInURL = (
        'https://www.linkedin.com/shareArticle?mini=true&url='
         .$contentUrl. '&amp;title=' .$contentTitle
    );

    $pinterestURL = (
        'https://pinterest.com/pin/create/button/?url='
         .$contentUrl. '&amp;media=' .$contentThumbnail[0]
        . '&amp;description=' .$contentTitle
    );

    $tumblrURL = (
        'https://www.tumblr.com/widgets/share/tool?posttype=link&amp;title=' .$contentTitle. '&amp;caption=' .$contentTitle. '&amp;content=' .$contentUrl. '&amp;canonicalUrl=' .$contentUrl. '&amp;shareSource=tumblr_share_button'
    );

    $emailURL = (
        'mailto:?subject=' .$contentTitle. '&amp;body=' .$contentUrl. ''
    );

    $whatsappURL = (
        'whatsapp://send?text=' .$contentTitle. '%20' .$contentUrl. ''
    );

    $telegramURL = (
        'https://telegram.me/share/url?text=' .$contentTitle. '&amp;url=' .$contentUrl. ''
    );

    $content = (
        '<a class="resp-sharing-button__link" href="'. $facebookURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Facebook', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--facebook resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
            </div>
        </div>
        </a>'
    );

    $content .= (
        '<a class="resp-sharing-button__link" href="'. $twitterURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Twitter', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--twitter resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
            </div>
            </div>
        </a>'
    );


    $content .= (
        '<a class="resp-sharing-button__link" href="'. $tumblrURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Tumblr', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--tumblr resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13.5.5v5h5v4h-5V15c0 5 3.5 4.4 6 2.8v4.4c-6.7 3.2-12 0-12-4.2V9.5h-3V6.7c1-.3 2.2-.7 3-1.3.5-.5 1-1.2 1.4-2 .3-.7.6-1.7.7-3h3.8z"/></svg>
            </div>
        </div>
        </a>'
    );

    $content .= (
        '<a class="resp-sharing-button__link" href="'. $emailURL .'" target="_self" rel="noopener" aria-label="'. __('Share by Email', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--email resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22 4H2C.9 4 0 4.9 0 6v12c0 1.1.9 2 2 2h20c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM7.25 14.43l-3.5 2c-.08.05-.17.07-.25.07-.17 0-.34-.1-.43-.25-.14-.24-.06-.55.18-.68l3.5-2c.24-.14.55-.06.68.18.14.24.06.55-.18.68zm4.75.07c-.1 0-.2-.03-.27-.08l-8.5-5.5c-.23-.15-.3-.46-.15-.7.15-.22.46-.3.7-.14L12 13.4l8.23-5.32c.23-.15.54-.08.7.15.14.23.07.54-.16.7l-8.5 5.5c-.08.04-.17.07-.27.07zm8.93 1.75c-.1.16-.26.25-.43.25-.08 0-.17-.02-.25-.07l-3.5-2c-.24-.13-.32-.44-.18-.68s.44-.32.68-.18l3.5 2c.24.13.32.44.18.68z"/></svg></div>
        </div>
        </a>'
    );

    $content .= (
        '<a class="resp-sharing-button__link" href="'. $pinterestURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Pinterest', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--pinterest resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12.14.5C5.86.5 2.7 5 2.7 8.75c0 2.27.86 4.3 2.7 5.05.3.12.57 0 .66-.33l.27-1.06c.1-.32.06-.44-.2-.73-.52-.62-.86-1.44-.86-2.6 0-3.33 2.5-6.32 6.5-6.32 3.55 0 5.5 2.17 5.5 5.07 0 3.8-1.7 7.02-4.2 7.02-1.37 0-2.4-1.14-2.07-2.54.4-1.68 1.16-3.48 1.16-4.7 0-1.07-.58-1.98-1.78-1.98-1.4 0-2.55 1.47-2.55 3.42 0 1.25.43 2.1.43 2.1l-1.7 7.2c-.5 2.13-.08 4.75-.04 5 .02.17.22.2.3.1.14-.18 1.82-2.26 2.4-4.33.16-.58.93-3.63.93-3.63.45.88 1.8 1.65 3.22 1.65 4.25 0 7.13-3.87 7.13-9.05C20.5 4.15 17.18.5 12.14.5z"/></svg>
            </div>
        </div>
        </a>'
    );

    $content .= (
        '<a class="resp-sharing-button__link" href="'. $linkedInURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Linkedin', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--linkedin resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6.5 21.5h-5v-13h5v13zM4 6.5C2.5 6.5 1.5 5.3 1.5 4s1-2.4 2.5-2.4c1.6 0 2.5 1 2.6 2.5 0 1.4-1 2.5-2.6 2.5zm11.5 6c-1 0-2 1-2 2v7h-5v-13h5V10s1.6-1.5 4-1.5c3 0 5 2.2 5 6.3v6.7h-5v-7c0-1-1-2-2-2z"/></svg>
            </div>
        </div>
        </a>'
    );

    $content .= (
        '<a class="resp-sharing-button__link" href="'. $whatsappURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Whatsapp', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--whatsapp resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20.1 3.9C17.9 1.7 15 .5 12 .5 5.8.5.7 5.6.7 11.9c0 2 .5 3.9 1.5 5.6L.6 23.4l6-1.6c1.6.9 3.5 1.3 5.4 1.3 6.3 0 11.4-5.1 11.4-11.4-.1-2.8-1.2-5.7-3.3-7.8zM12 21.4c-1.7 0-3.3-.5-4.8-1.3l-.4-.2-3.5 1 1-3.4L4 17c-1-1.5-1.4-3.2-1.4-5.1 0-5.2 4.2-9.4 9.4-9.4 2.5 0 4.9 1 6.7 2.8 1.8 1.8 2.8 4.2 2.8 6.7-.1 5.2-4.3 9.4-9.5 9.4zm5.1-7.1c-.3-.1-1.7-.9-1.9-1-.3-.1-.5-.1-.7.1-.2.3-.8 1-.9 1.1-.2.2-.3.2-.6.1s-1.2-.5-2.3-1.4c-.9-.8-1.4-1.7-1.6-2-.2-.3 0-.5.1-.6s.3-.3.4-.5c.2-.1.3-.3.4-.5.1-.2 0-.4 0-.5C10 9 9.3 7.6 9 7c-.1-.4-.4-.3-.5-.3h-.6s-.4.1-.7.3c-.3.3-1 1-1 2.4s1 2.8 1.1 3c.1.2 2 3.1 4.9 4.3.7.3 1.2.5 1.6.6.7.2 1.3.2 1.8.1.6-.1 1.7-.7 1.9-1.3.2-.7.2-1.2.2-1.3-.1-.3-.3-.4-.6-.5z"/></svg>
            </div>
        </div>
        </a>'
    );


    $content .= (
        '<a class="resp-sharing-button__link" href="'. $telegramURL .'" target="_blank" rel="noopener" aria-label="'. __('Share on Telegram', 'epgrafico') .'">
          <div class="resp-sharing-button resp-sharing-button--telegram resp-sharing-button--large"><div aria-hidden="true" class="resp-sharing-button__icon resp-sharing-button__icon--solid">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M.707 8.475C.275 8.64 0 9.508 0 9.508s.284.867.718 1.03l5.09 1.897 1.986 6.38a1.102 1.102 0 0 0 1.75.527l2.96-2.41a.405.405 0 0 1 .494-.013l5.34 3.87a1.1 1.1 0 0 0 1.046.135 1.1 1.1 0 0 0 .682-.803l3.91-18.795A1.102 1.102 0 0 0 22.5.075L.706 8.475z"/></svg>
            </div>
        </div>
        </a>'
    );

    echo $content;
}
add_action('storefront_single_post_bottom', 'Epgrafico_Share_buttons');
add_action('woocommerce_product_meta_end', 'Epgrafico_Share_buttons');
/**
 * Note: Do Not alter or remove the code above this text and
 * only add your custom functions below here.
 */
