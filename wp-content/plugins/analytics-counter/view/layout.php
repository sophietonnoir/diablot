<?php add_thickbox(); ?>

<?php $url = plugins_url(WPADM_GA__PLUGIN_NAME . '/view/scripts'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url(WPADM_GA__PLUGIN_NAME . '/view/scripts'); ?>/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $url; ?>/glyphicons.css" />


<div id="wpadm-ga-support_container" style="display:none;">
    <div id="wpadm-ga_support_text_container">
        <h2>Suggestion</h2>
        <textarea style="width: 100%; height: 300px" id="wpadm-ga_support_text"></textarea>
    </div>

    <div id="wpadm-ga_support_thank_container" style="display: none;">
        <h2>Thanks for your suggestion!</h2>
        Within next plugin updates we will try to satisfy your request.
    </div>

    <div id="wpadm-ga_support_error_container" style="display: none;">
        <br><b>At your website the mail functionality is not available.</b><br /><br />
        Your request was not sent.
    </div>


    <div style="text-align: right; margin-top: 20px;">
        <button type="button" class="btn btn-default" onclick="jQuery('.tb-close-icon').click()">close</button>
        <button type="button" class="btn btn-primary" id="wpadm-ga-support_send_button" onclick="wpadm_ga_sendSupportText()">Send suggestion</button>

    </div>
</div>


<?php
/**
 * @var $wpadm_ga_view WPAdm_GA_View
 */
$show_notice_5stars = false;
if (!isset($_GET['modal'])) {
    if (!get_option('wpadm-ga-stopNotice5Stars')) {
        $first_time = get_option('wpadm-ga-first_time');
        $show_notice_5stars = ($first_time && $first_time < time() - 24 * 60 * 60);

    }
}
?>

<div id="wpadm-ga-layout">
    <div id="wpadm-ga-header">
        <h1 style="display: inline;"><img src="<?php echo plugins_url('/img/big_icon.png',__FILE__); ?>" style="height: 48px; width: 48px;"> <?php echo WPAdm_GA_View::$title; ?> <small><?php echo WPAdm_GA_View::$subtitle;?></small></h1>
    </div>

    <?php if ($show_notice_5stars): ?>
        <div class="wpadm-ga-notice-5stars-content">
            <div class="wpadm-ga-notice-5stars-right">
                <a id="wpadm-ga-notice-5stars-remover" href="javascript:void(0)" onclick="wpadm_ga_stopNotice5Stars()">[ Hide this message ]</a>
            </div>
            <div  class="wpadm-ga-notice-5stars-left"  onclick="window.open('https://wordpress.org/support/view/plugin-reviews/analytics-counter?filter=5#postform')">
                Leave us 5 stars
                <button id="wpadm-ga-button-5stars" type="button" class="btn btn-default btn-sm">
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                    <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                </button>
                <small>It will help us to develop this plugin for you</small>
            </div>
        </div>

        <div class="clear"></div>
    <?php endif; ?>


    <div id="wpadm-ga-content">
        <?php
            if(WPAdm_GA_View::$content_file) {
                require WPAdm_GA_View::$content_file;
            }
        ?>
    </div>
    
    <div id="wpadm-ga-footer"></div>
</div>

<script>
    var wpadm_ga_url_GA_AUTH = "<?php echo WPAdm_GA::URL_GA_AUTH; ?>";
    var wpadm_ga_url_GA_SETTINGS = "<?php echo admin_url('options-general.php?page=wpadm-ga-menu-settings') ?>";

</script>