<?php
/**
 * @package BestWebsite
 */
/**
Plugin Name: Best Website Support
Plugin URI: https://bestwebsite.com
Description: Submit a support ticket directly to bestwebsite.com from your WordPress dashboard!
Version:1.0
Author: bestwebsite.com
Author URI: https://bestwebsite.com
License: GPL V2 or later
Text Domain: bestwebsite
*/
defined('ABSPATH') or die("Are you sure you want to do this?");
define('BESTWEBSITE_PLUGIN_DIR', plugin_dir_path(__FILE__));

// code...

function bw_support_form_menu()
{
    // code...
    add_menu_page(__('Support', 'bestwebsite-support'), 'Support', 'manage_options', 'bw_support_form', 'bw_support_form', 'dashicons-sos', 999);
}

function bw_support_form()
{
    // code...
    // ob_start();
?>
		<div class="bestwebsite_support_form">
      <div class="bw-logo"><img src="<?php echo plugins_url('assets/images/bestwebsite-horizontal-logo.png', __FILE__);?>" alt="Best Website Support" /><p class="bw-info">Text: (855) 932-3685</p></div>
      <h3 id="successmsg" style="display:none; font-size:1rem; padding:5% 10px;"></h3>
      <div id="form-content">
      <p>Submit your details below and one of our support agents will review ASAP!</p>
		<input type="text" name="bestwebsite_support_subject" placeholder="One-sentence summary" id="bw_support_subject" />
		<input type="hidden" name="bestwebsite_support_clientemail" value="<?php echo get_bloginfo('admin_email'); ?>" id="bw_support_clientemail" />
		<textarea cols="10" rows="10" name="bestwebsite_support_msg" id="bw_support_msg" placeholder="Describe your issue or request using as much detail as possible."></textarea>

		<button type="button" class="bw-button" id="submit_bw_ticket">Submit Ticket</button>
      </div>
		</div>
		<?php
    // ob_get_clean();
}
add_action('admin_menu', 'bw_support_form_menu');

//enqueue admin styles and scripts

function bw_adminscripts($hook)
{
    if ($hook != "toplevel_page_bw_support_form") {
       return;
     // echo $hook;
    }
    
    wp_enqueue_style('bw_support_styles', plugins_url('assets/css/bwsupport.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'bw_adminscripts');

//ajax call - form handling and sending a mail to support

add_action('wp_ajax_bw_support_mail', 'bw_support_mail');

function bw_support_mail(){
  //get subject, email, and message
  $email = $_POST['bw_support_clientemail']?$_POST['bw_support_clientemail']:get_bloginfo('admin_email');
  $subject = $_POST['bw_support_subject'];
  $msg = $_POST['bw_support_msg'] . " \r\n sent from " . get_bloginfo('url');
  //set headers
  $tomail = "support@bestwebsite.com";
  $headers[] = "From: ". get_bloginfo('name') . " <".$email.">";
  wp_mail($tomail, $subject, $msg, $headers);
  echo "Your request has been received! We will send an update to this request as soon as possible to ".$email;
  wp_die();
}

add_action('admin_footer', 'bw_support_mail_javascript');

function bw_support_mail_javascript(){
  echo '<script>
jQuery(document).ready(function() {
    jQuery("#submit_bw_ticket").click(function() {
        var bws_msg = jQuery("#bw_support_msg").val();
        var bws_subject = jQuery("#bw_support_subject").val();
        var bws_mail = jQuery("#bw_support_clientemail").val();
        var bws_data = {
            "action": "bw_support_mail",
            "bw_support_msg": bws_msg,
            "bw_support_subject": bws_subject,
            "bw_support_clientemail": bws_mail
        };
        jQuery.post(ajaxurl, bws_data, function(response) {
        jQuery("#form-content").hide();
        jQuery("#successmsg").show();
        jQuery("#successmsg").html(response);
            console.log(response);
        });
    });
});
</script>';
}