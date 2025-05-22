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
if (isset($_POST['email-temp'])){
	$data =$_POST;
	$result=$vc_obj->vlcr_email_temp_setting_save($data);
}
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}


$class_id = sanitize_text_field($_REQUEST['cid']);
if($_REQUEST['type']=="emailtemplate"){
	$class_id=sanitize_text_field($_REQUEST['id']);	
	$_REQUEST['cid']= $class_id;
}
global $wpdb;
$row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."virtualclassroom_email_template_settings WHERE class_id = %d",array($class_id)));

if($row->email_template_subject){
	$subject = 	$row->email_template_subject;
}else{
	$subject = 'Live Class Invitation';	
}

if($row->email_template_body){
	$content = 	$row->email_template_body;
}else{
	$content = '<p>{owner_name} has invited you to join the Live Class at BrainCert.</p>
			<p>Class Name: {class_name}</p>
			<p>Date/Time: {class_date_time}</p>
			<p>Time Zone: {class_time_zone}</p>
			<p>Duration: {class_duration}</p>
			<p>Click on the link below to join the class:</p>
			<p>{class_join_url}</p>
			<p> Thank you.</p>';	
}


$editor_id = 'email_template_body';

?>
	<h1>Change E-mail template</h1>
<div class="wrap" style="width: 180%;padding: 33px;border: 1px solid;border-radius: 20px;">
<form name="invite" method="post" action="" id="vlrc_inviteform">
			<table class="form-table">
			<tbody>
				<tr>
					<th><label>Email Subject :</label></th>
					<td><input type="text" name="email_template_subject" value="<?php echo esc_attr($subject);?>" size="47"></td>
				</tr>
				<tr>
					<th style="width: 101px">Email Body :</th>
					<td><?php wp_editor( $content, $editor_id); ?></td>
				</tr>
				<tr style="border: none">
				<input type="hidden" name="class_id" value="<?php echo esc_attr($_REQUEST['cid']);?>">
					<td colspan="2"><input id="Save" type="submit" class="button button-primary" value="Save" name="email-temp">

					</td>
				</tr>
			</tbody>
			</table>
	</form>
</div>
