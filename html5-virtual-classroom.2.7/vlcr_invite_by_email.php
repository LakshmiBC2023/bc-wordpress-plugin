<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Classlist
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$vc_obj = new vlcr_class();
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}
$id=$_REQUEST['id'];
$content = '';
$editor_id = 'message';
if (isset($_POST['invite'])){
	$data =$_POST;
	$result=$vc_obj->vlcr_invite_by_email($data);
}
?>
	<h1>Invite by E-mail</h1>
<div class="wrap" style="width: 200%;border: 1px solid;padding: 35px;border-radius: 10px;">
	<form name="invite" method="post" action="" id="vlrc_inviteform">
			<table class="form-table">
			<tbody>
				<tr>
					<th scope="row"><label>To :</label></th>
					<td class="to">
						<textarea rows="4" cols="50" id="to" name="to" placeholder="Enter email address"></textarea>
						<span class="error"></span>
						<span style="width: 100%;float: left;">(Enter one email address per line.)</span>
					</td>
				</tr>
				<tr>
					<th><label>Subject :</label></th>
					<td><input type="text" name="subject" value="Live Class Invitation" size="47"></td>
				</tr>
				<tr>
					<th>Message :</th>
					<td><?php wp_editor( $content, $editor_id); ?></td>
				</tr>
				<tr>
				<input type="hidden" name="id" value="<?php echo esc_attr($_REQUEST['id']);?>">
					<td colspan="2"><input id="send" type="submit" class="button button-primary" value="send" name="invite">

					</td>
				</tr>
			</tbody>
			</table>
	</form>
</div>
<script type="text/javascript">
	jQuery( document ).ready(function() {
		jQuery( "#send" ).click(function() {
			var to = jQuery('#to').val();
			if(to == ''){ 
			
				jQuery(".error").html("<br><b>Please enter correct E-mail address!</b>").css("color","red");
				//jQuery.scrollTo(jQuery('.error'), 1000);
					jQuery('html,body').animate({
						scrollTop: jQuery("#to").offset().top},
					'slow');
				return false;
			}
		});
	});
</script>