<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
ob_start();
   /*
    Plugin Name: Virtual Classroom
    Plugin URI:
    Description: Plugin for Virtual Classroom
    Author: BrainCert
    Version: 2.7
    * License:     GPL-2.0+
 	* License URI: https://www.gnu.org/licenses/gpl-2.0.html
    Author URI: https://www.braincert.com/developer/virtualclassroom-api
    */
define('VC_FOLDER', dirname(plugin_basename(__FILE__)));
define('VC_URL', plugin_dir_url(__FILE__));
function vlcr_admin() {
	include('vlcr_admin.php');
}
function vlcr_admin_class(){
	include('vlcr_admin_class_function.php');
}
function vlcr_admin_menu()
{
	add_menu_page(
		"",
		"Virtual Classroom",
		'manage_options',
		__FILE__,
		"vlcr_admin"
	);
	add_submenu_page(__FILE__, 'Configuration', 'Configuration', 'manage_options', __FILE__.'/Configuration', 'vlcr_configuration');
	add_submenu_page(__FILE__, 'Classes', 'Classes', 'manage_options', __FILE__.'/ClassList', 'vlcr_classlist_admin_fun');
	add_submenu_page(__FILE__, 'Teachers', 'Teachers', 'manage_options', __FILE__.'/TeacherList', 'vlcr_teacherlist_admin_fun');
	add_submenu_page('options.php', 'PriceList', 'PriceList', 'manage_options', __FILE__.'/PriceList', 'vlcr_pricelist_admin_fun');
	add_submenu_page('options.php', 'DiscountList', 'DiscountList', 'manage_options', __FILE__.'/DiscountList', 'vlcr_discountlist_admin_fun');
	add_submenu_page('options.php', 'ViewRecording', 'ViewRecording', 'manage_options', __FILE__.'/ViewRecording', 'vlcr_viewrecording_admin_fun');
	
	add_submenu_page('options.php', 'RecordingList', 'RecordingList', 'manage_options', __FILE__.'/RecordingList', 'vlcr_recordinglist_admin_fun');
	add_submenu_page(__FILE__, 'Payments', 'Payments', 'manage_options', __FILE__.'/Payments', 'vlcr_paymentlist_admin_fun');
	add_submenu_page(__FILE__, 'Permissions', 'Permissions', 'manage_options', __FILE__.'/Permissions', 'vlcr_permissions_admin_fun');
	add_submenu_page(null, 'Learner Preview', 'Learner Preview', 'manage_options', __FILE__.'/learnerpreview', 'vlcr_learner_preview_admin_fun');
	add_submenu_page(null, 'Attendance report', 'Attendance report', 'manage_options', __FILE__.'/attendancereport', 'vlcr_attendance_report_admin_fun');
	add_submenu_page(null, 'Instructor Preview', 'Instructor Preview', 'manage_options', __FILE__.'/inspreview', 'vlcr_instructor_preview_admin_fun');
	add_submenu_page(null, 'Invite by E-mail', 'Invite by E-mail', 'manage_options', __FILE__.'/inviteemail', 'vlcr_inviteemail_admin_fun');
	add_submenu_page(null, 'E-mail Template', 'E-mail Template', 'manage_options', __FILE__.'/Emailtemplate', 'vlcr_email_template_fun');
	add_submenu_page(null, 'Invite Users', 'Invite Users', 'manage_options', __FILE__.'/inviteusers', 'vlcr_inviteuser_admin_fun');
	add_submenu_page(null, 'Invite User group ', 'Invite User group', 'manage_options', __FILE__.'/inviteusergroup', 'vlcr_inviteuser_group_admin_fun');
}
function vlcr_install()
{

    global $wpdb;

	$table_name = $wpdb->prefix . 'virtualclassroom_purchase';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		  	id INT(11) NOT NULL AUTO_INCREMENT,
			class_id INT( 11 ) NOT NULL,
     		mc_gross FLOAT(10,2)  NOT NULL ,
  			payer_id INT(11)  NOT NULL ,
			payment_mode VARCHAR(255)  NOT NULL ,
			date_puchased DATETIME NOT NULL,
			UNIQUE KEY `id` (`id`));";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	$table_name = $wpdb->prefix . 'virtualclassroom_acl';
	$sql="CREATE TABLE IF NOT EXISTS ".$table_name." (
		 `id` int(11) NOT NULL AUTO_INCREMENT,
  		 `group_id` int(11) NOT NULL,
		  `class_id` text NOT NULL,
		  PRIMARY KEY (`id`)
		)";
	 require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
	
	$table_name = $wpdb->prefix . 'virtualclassroom_teacher';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		  	id INT(11) NOT NULL AUTO_INCREMENT,
			user_id INT( 11 ) NOT NULL,
     		is_teacher TINYINT(4) NOT NULL,
			UNIQUE KEY `id` (`id`));";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$table_name = $wpdb->prefix . 'virtualclassroom_shorturl';
	$sql ="CREATE TABLE IF NOT EXISTS ".$table_name." (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`shorturl` varchar(255) NOT NULL,
		`fullurl` varchar(255) NOT NULL,
		`rand_number` varchar(255) NOT NULL,
		PRIMARY KEY (`id`))";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$table_name = $wpdb->prefix . 'virtualclassroom_shared_users';
	$sql ="CREATE TABLE IF NOT EXISTS ".$table_name." (
		  `class_id` int(11) NOT NULL,
		  `name` varchar(255) NOT NULL,
		  `email` varchar(255) NOT NULL,
		  `uid` varchar(100) NOT NULL,
		  `date` datetime NOT NULL,
		  PRIMARY KEY (`class_id`,`email`)
		)";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$table_name = $wpdb->prefix . 'virtualclassroom_email_template_settings';
	$sql ="CREATE TABLE IF NOT EXISTS ".$table_name." (
	      `id` int(11) NOT NULL AUTO_INCREMENT,
		  `class_id` int(11) NOT NULL,
		  `email_template_subject` varchar(255) NOT NULL,
		  `email_template_body` text NOT NULL,
		  PRIMARY KEY (`id`)
		)";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);

	$table_name = $wpdb->prefix . 'virtualclassroom_settings';
	$sql = "CREATE TABLE IF NOT EXISTS ".$table_name." (
		  	id INT(11) NOT NULL AUTO_INCREMENT,
     		braincert_api_key VARCHAR(255) NOT NULL ,
			braincert_base_url VARCHAR(255) NOT NULL,
			inv_email_page INT(10),
			class_detail_page INT(10),
			is_schedule_class INT(10),
			schedule_class_page INT(10),
			sharing_code VARCHAR(255) NOT NULL,
			UNIQUE KEY `id` (`id`));";
	dbDelta($sql);
	return;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	if(!$row)
	{
		$table_name = $wpdb->prefix . 'virtualclassroom_settings';
      	$rows_affected = $wpdb->insert( $table_name, array( 'id' => "null", 'braincert_base_url' => 'https://api.braincert.com/v2' ));
     	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
     	dbDelta( $rows_affected );
 	}
	return;
}
function vlcr_install_del()
{
    global $wpdb;

	$table_name = esc_sql($wpdb->prefix . 'virtualclassroom_settings');
	$wpdb->query( "DROP TABLE IF EXISTS `$table_name`" ); // ✅ Secure: Proper escaping

	$table_name1 = esc_sql($wpdb->prefix . 'virtualclassroom_teacher');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name1`");

	$table_name3 = esc_sql($wpdb->prefix . 'virtualclassroom_purchase');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name3`");

	$table_name4 = esc_sql($wpdb->prefix . 'virtualclassroom_email_template_settings');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name4`");
    
	$table_name5 = esc_sql($wpdb->prefix . 'virtualclassroom_shorturl');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name5`");

	$table_name6 = esc_sql($wpdb->prefix . 'virtualclassroom_shared_users');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name6`");
	
	$table_name7 = esc_sql($wpdb->prefix . 'virtualclassroom_acl');
	$wpdb->query("DROP TABLE IF EXISTS `$table_name7`");
	return;
}
function fb_opengraph() {
	global $post;
	$vc_obj = new vlcr_class();
	$pcid = isset( $_REQUEST['pcid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pcid'] ) ) : '';
	$ogurl = get_permalink($post->ID).'&pcid='.$pcid;
    if(strpos(get_permalink($post->ID),'?')===false){
        $ogurl = get_permalink($post->ID).'?pcid='.$pcid;
    }
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	$data['task'] = sanitize_text_field('getclass');
	$data['apikey'] = sanitize_text_field($key);
	$data['class_id'] = sanitize_text_field($pcid);
	$result = $vc_obj->vlcr_get_curl_info($data);
	$title =$result[0]['title'];
	$description=gmdate("M j, Y",strtotime($result[0]['date'])).' '.$result[0]['start_time'];
	?>
 	<title><?php echo esc_attr($title); ?></title>
    <meta property="og:title" content="<?php echo esc_attr($title); ?>"/>
    <meta property="og:description" content="<?php echo esc_attr($description); ?>"/>
    <meta property="og:url" content="<?php echo esc_url($ogurl); ?>"/>
	<?php
} 
add_action('wp_head', 'fb_opengraph', 5);
function vlcr_class_detail($atts){
	$vc_obj = new vlcr_class();
	$allowClass_list = vlcr_get_usergroup();
	if(!empty($allowClass_list)){
		if (!in_array($atts['id'], $allowClass_list)){
			echo '<div class="error">
	                <p><strong>ERROR</strong>: You can not access this class.</p>
	              </div>';
			return;
		}
	}
	wp_enqueue_style( 'font-awesome.min', VC_URL.'/css/font-awesome.min.css');
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	if(!$row)
	{
		echo "Please setup API key and URL";
		return;
	}
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	$data['task'] = sanitize_text_field('getclass');
    $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = sanitize_text_field($atts['id']);
        $result = $vc_obj->vlcr_get_curl_info($data);

		if($result[0]['status'] == "Upcoming"){
		    $class = "vc-alert vc-alert-warning";
		}
		if($result[0]['status'] == "Past"){
		     $class = "vc-alert vc-alert-danger";
		}
		if($result[0]['status'] == "Live"){
		    $class = "vc-alert vc-alert-success";
		}


      ?>
	<div style="float:right;" class="<?php echo esc_attr($class);?> span12 class-status"><?php echo esc_attr($result[0]['status']); ?></div>      
 		<div class="class-details-title"><?php echo esc_attr($result[0]['title'])?></div>   
			<div style="margin-top:10px;">
				<p class="datecalrow">  
				<?php if($result[0]['status'] =='Upcoming' && !empty($result[0]['class_next_date'])) { ?>
				<i class="icon icon-calendar"></i> <?php echo esc_attr(gmdate('l F j, Y', $result[0]['class_next_date']));
				}else {?>	
				<i class="icon icon-calendar"></i>&nbsp;<?php echo esc_attr(gmdate("l F j, Y",strtotime($result[0]['date']))); 
				}?> 
				<br><i class="icon icon-time"></i> <?php echo esc_html($result[0]['start_time']); ?> - <?php echo esc_html($result[0]['end_time']) .' ('.(esc_html($result[0]['duration'])/60) .' Minutes)'; ?> 
				<span class="vctitlepink"><br>Time Zone:</span> <?php echo esc_html($result[0]['timezone_label']); ?>
				</p></div>
	      		<?php 
      			$item=$result[0];
				$current_user = wp_get_current_user();
				$item['uuname']=esc_html($current_user->display_name);
				$url = vlcr_class_launch_btn($item);
      			if($url){
      			?>
		<br /><a target="_blank" class="btn btn-primary btn-large" style="font-weight: bold; margin-bottom: 10px;" id="launch-btn" onclick="popup('<?php echo esc_url($url) ?>'); return false;">Launch</a>
			<?php } ?>
		<script type="text/javascript">function popup(url)
		{
		 params  = 'width='+screen.width;
		 params += ', height='+screen.height;
		 params += ', top=0, left=0'
		 params += ', fullscreen=yes,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,addressbar=no';

		 newwin=window.open(url,'windowname4', params);
		 if (window.focus) {newwin.focus()}
		 return false;
		}
		</script>		
      <?php 
}
add_shortcode('class_details', 'vlcr_class_detail');
function vlcr_class_launch_btn($item){
	global $wpdb;
	$vc_obj = new vlcr_class();
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	if(!$row)
	{
		echo "Please setup API key and URL";
		return;
	}
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;

	$isteacher  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE user_id = %d",array(get_current_user_id())));
   	$enrolled  = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM ".$wpdb->prefix."virtualclassroom_purchase WHERE class_id = %d AND payer_id=%d",array($item['id'],get_current_user_id())));
    if($item['ispaid'] && strtolower($item['status'])!="past" && !$enrolled && $isteacher == 0){
    	$buy_url = get_permalink($row->class_detail_page).'&pcid='.$item['id'];
	    if(strpos(get_permalink($row->class_detail_page),'?')===false){
	        $buy_url = get_permalink($row->class_detail_page).'?pcid='.$item['id'];
	    }
    	?>
        <a href="<?php echo esc_url($buy_url);?>" class="btn btn-danger btn-sm"><h4  style="margin: 0px;" class=" "><i class="icon-shopping-cart icon-white"></i> Buy</h4></a>
        <?php
    }
    if((strtolower($item['status']) == "live" && $enrolled) || $item['ispaid']==0 || $isteacher == 1){
		$uuname=$item['uuname'];
    	if($uuname == ''){
	  		$uuname =$current_user->display_name;
    	}
 		$current_user = wp_get_current_user();
 		$data1['userId'] = sanitize_text_field($current_user->ID);
	    $data1['userName'] = sanitize_text_field($uuname);
	    $data1['lessonName'] = sanitize_text_field($item['title']);
	    $data1['courseName'] = sanitize_text_field($item['title']);

		$is_tchr  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %d",array($current_user->ID)));
		if ($is_tchr == 1)  { $data1['isTeacher'] = 1; }
	    else {  $data1['isTeacher'] = 0;  }
        $data1['task'] = sanitize_text_field('getclasslaunch');
	    $data1['apikey'] = sanitize_text_field($key);
	    $data1['class_id'] = sanitize_text_field($item['id']);
	    $launchurl = (object)$vc_obj->vlcr_get_curl_info($data1);
        $url='';
        if(isset($launchurl->encryptedlaunchurl) && strtolower($item['status']) == "live"){
        	$url = str_replace("'\'","",$launchurl->encryptedlaunchurl);
         }
        if($url){ ?>
	        <br>
        <?php return $url;
       } 
  	}
}
function vlcr_get_usergroup(){
	global $wpdb;
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
   	if (is_plugin_active('groups/groups.php' ) ) {
 		$groups = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."groups_user_group WHERE `user_id` = %d",array(get_current_user_id())));

		$classlist_arr= array();
		foreach ($groups as $group) {
			$classid_list=$wpdb->get_col($wpdb->prepare("SELECT class_id FROM ".$wpdb->prefix."virtualclassroom_acl WHERE `group_id` = %d",array($group->group_id)));

			if(!empty($classid_list[0])){
				$classlist_arr[].=$classid_list[0];
			}
			
		}
		$cidlist = implode(',', $classlist_arr);
		if($cidlist != ''){
			return $classlist_arr=explode(',', $cidlist);
		}else{
			return $classlist_arr='';
		}
	}else{
			return $classlist_arr='';
	}		
}
function vlcr_check_user_access_class(){
	global $wpdb;
	$vc_obj = new vlcr_class();
	$is_super_admin = is_super_admin(get_current_user_id());
	$id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';

	$isteacher  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %d",array(get_current_user_id())));
	if(get_current_user_id()=="" || ($isteacher==0 && $is_super_admin==0)){
		wp_redirect(get_permalink($post->ID));
	}
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	$data['task'] = sanitize_text_field('getclass');
	$data['apikey'] = sanitize_text_field($key);
	$data['class_id'] = sanitize_text_field($id);
	$result = $vc_obj->vlcr_get_curl_info($data);
	$m=0;
	if(get_current_user_id()==$result[0]['created_by'] || get_current_user_id()==$result[0]['instructor_id'] || $is_super_admin==1){
		$m=1;	
	}
	if($m==0){
		wp_redirect(get_permalink($post->ID));
	}
}
function vlcr_classlist_site_fun() {
	global $post;
	$vc_obj = new vlcr_class();
	wp_enqueue_style( 'font-awesome.min', VC_URL.'/css/font-awesome.min.css');
	wp_enqueue_script('vlcr.jquery.min', VC_URL. '/js/vlcr.jquery.min.js');
	$type = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
	$task = isset( $_REQUEST['task'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['task'] ) ) : '';
	$isCancel = isset( $_REQUEST['isCancel'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['isCancel'] ) ) : '';
	$id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';

	if($task=="cancelclassfront"){ 
		$data2['isCancel'] = sanitize_text_field($isCancel);
		$data2['class_id'] = sanitize_text_field($id);
        $data2['task'] = sanitize_text_field('cancelclass');
        $response = (object)$vc_obj->vlcr_get_curl_info($data2);
        wp_redirect(get_permalink($post->ID));
	}
	if($task=="activeclassfront"){ 
		$data2['isCancel'] = 0;
		$data2['class_id'] = sanitize_text_field($id);
        $data2['task'] = sanitize_text_field('cancelclass');
        $response = (object)$vc_obj->vlcr_get_curl_info($data2);
        wp_redirect(get_permalink($post->ID));
	}
	if($type){
		vlcr_check_user_access_class();

		if(strtolower($type)=="attendancereport"){
			include 'vlcr_attendance_report.php';	
			return;
		}
		if(strtolower($type)=="inviteemail"){
			include 'vlcr_invite_by_email.php';
			return;
		}
		if(strtolower($type)=="inviteusers"){
			include 'vlcr_invite_user.php';
			return;
		}
		if(strtolower($type)=="inviteusergroup"){
			include 'vlcr_invite_user_group.php';
			return;
		}
		if(strtolower($type)=="viewrecording"){
			wp_enqueue_style( 'vlcr_video-js', VC_URL.'/css/vlcr_video-js.css');
			include 'vlcr_view_recording_admin.php';
			return;
		}
		if(strtolower($type)=="emailtemplate"){
			include 'vlcr_email_template.php';
			return;
		}
		
		if(strtolower($type)=="recordinglist"){
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
			switch($action){
				case 'add':
					include 'vlcr_recording_edit_front.php';
					break;

				case 'edit':
					include 'vlcr_recording_edit_front.php';
					break;

				case 'delete':
					$_REQUEST['task'] = 'deleteRecording';
					include 'vlcr_recordinglist_front.php';
					break;

				default:
					include 'vlcr_recordinglist_front.php';
				break;
			}
			return;
		}
		
		if(strtolower($type)=="pricelist"){
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
			switch($action){
				case 'add':
					include 'vlcr_price_edit_front.php';
					break;

				case 'edit':
					include 'vlcr_price_edit_front.php';
					break;

				case 'delete':
					$_REQUEST['task'] = 'deletePrice';
					include 'vlcr_pricelist_front.php';
					break;

				default:
					include 'vlcr_pricelist_front.php';
					break;
			}
			return;
		}
		if(strtolower($type)=="discountlist"){
			$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
			switch($action){
				case 'add':
					include 'vlcr_discount_edit_front.php';
					break;

				case 'edit':
					include 'vlcr_discount_edit_front.php';
					break;

				case 'delete':
					$_REQUEST['task'] = 'removediscount';
					include 'vlcr_discountlist_front.php';
					break;

				default:
					include 'vlcr_discountlist_front.php';
					break;

			}
			return;
		}
	}
	if($_REQUEST['pcid'] != ''){
		include 'vlcr_site_class_detail.php';
		return;
	}
	global $wpdb;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	if(!$row)
	{
	echo "Please setup API key and URL";
	return;
	}
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	$allowClass_list = vlcr_get_usergroup();
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery(".close").click(function(event) {
        	jQuery(".modal").hide();
    	});
	});

	function loginpopup(surl){
	    window.location.href ="<?php echo esc_url(site_url());?>/wp-login.php?redirect_to="+surl;
	}

	function cancelclass(class_id,class_title){
		jQuery("#cancelclassid").val(class_id);
		jQuery(".class_title").html(class_title);
		jQuery('#modal-content-cancelclass').show();
	}
  	jQuery("a .icon.icon-cog").click(function(e){jQuery(this).parent().trigger('click');e.stopImmediatePropagation();});
	function dropdownmenu(id) {
	   if(jQuery("#slide-gear-"+id).hasClass('show')){
	        jQuery("#slide-gear-"+id).removeClass('show');
	    }else{
	        jQuery(".dropdown-content").removeClass('show')
	        jQuery("#slide-gear-"+id).addClass('show');
	    }
	    window.onclick = function(e) {
	    	if (!e.target.matches('.dropbtn') && !e.target.matches('i.icon.icon-cog') && !e.target.matches('b.caret')) {
	            jQuery(".dropdown-content").removeClass('show');
	        }
	    }
	}
    function popup(url)
		{
		 params  = 'width='+screen.width;
		 params += ', height='+screen.height;
		 params += ', top=0, left=0'
		 params += ', fullscreen=yes,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,addressbar=no';

		 newwin=window.open(url,'windowname4', params);
		 if (window.focus) {newwin.focus()}
		 return false;
		}
	</script>
	<?php
	$task1 = isset($_REQUEST['task1']) ? sanitize_text_field($_REQUEST['task1']) : '';
	$task = isset($_REQUEST['task']) ? sanitize_text_field($_REQUEST['task']) : '';
	
	if($task == "returnpayment"){

		$wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."virtualclassroom_purchase (class_id,  mc_gross, payer_id,payment_mode,date_puchased) VALUES (%d,%s,%d,%s,%s)",array($_REQUEST['class_id'],$_REQUEST['amount'],get_current_user_id(),$_REQUEST['payment_mode'].now())));
	    $return = '?page_id='.sanitize_text_field($_REQUEST['page_id']);
	    header('Location:'.$return);
       }
	if($task1 == 'launchurl'){
		$data2['cid'] = sanitize_text_field($_REQUEST['cid']);
        $data2['task'] = 'classdetail';
        $classtitle = (object)$vc_obj->vlcr_get_curl_info($data2);
		$current_user = wp_get_current_user();
		$data1['userId'] = sanitize_text_field($current_user->ID);
    	$data1['userName'] = sanitize_text_field($current_user->display_name);
    	$data1['lessonName'] = sanitize_text_field($classtitle->title);
    	$data1['courseName'] = sanitize_text_field($classtitle->title);

	    global $wpdb;

		$is_tchr  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %s",array($current_user->ID)));

	    if ($is_tchr == 1)
	    { $data1['isTeacher'] = 1; }
	    else {  $data1['isTeacher'] = 0;  }

	    $data1['task'] = sanitize_text_field('getclasslaunch');
	    $data1['apikey'] = sanitize_key($key);
	    $data1['class_id'] = sanitize_text_field($_REQUEST['cid']);
	    $launchurl = (object)$vc_obj->vlcr_get_curl_info($data1);
	    $url = str_replace("'\'","",$launchurl->encryptedlaunchurl);

  		ob_clean();
   		?>
	    <iframe onload="this.width=screen.width;this.height=screen.height;" style="background-color:transparent;" name=inline src="<?php echo esc_url($url);?>" frameBorder=0 scrolling=Yes allowtransparency="true">
	    </iframe>
	    <?php
	    exit();
	    return;
	}
		$data['task'] = sanitize_text_field('listclass');
		$data['apikey'] = sanitize_text_field($key);
		$data['published'] = sanitize_text_field("1");
		$targetpage = get_permalink($post->ID); 	//your file name  (the name of this file)


		$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; 
		if(isset($search)){
            $data['search'] = sanitize_text_field($search);    
        }

		$limit = 10; 
	 				//how many items to show per page
		@$page = $_GET['page1'];
		if($page)
			$start = ($page - 1) * $limit; 			//first item to display on this page
		else
		$start = 0;
		$data['limitstart'] = $start;
		$data['limit'] = $limit;
		$data = $vc_obj->vlcr_get_curl_info($data);

		if(is_plugin_active('groups/groups.php') && !current_user_can( 'manage_options' ) && !$isteacher){
			if(!empty($allowClass_list)){
				$data['total'] = count($allowClass_list);
			}
		}
		//echo "<pre>";print_r($data);echo "</pre>";
		$total_records = $data['total'];
		@$page = $_GET['page1'];
        if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
        $prev = $page - 1;                          //previous page is page - 1
        $next = $page + 1;                          //next page is page + 1
        $lastpage = ceil($total_records/$limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                      //last page minus 1
        $pagination = "";
        $adjacents = "";

        $pagination_separator = "?";
        if(strpos($_SERVER['REQUEST_URI'], "?") !== false && strpos($_SERVER['REQUEST_URI'], "?page1") === false)
        	$pagination_separator = "&";

         if($lastpage > 1)
        {   
            $pagination .= "<div class=\"site pagination pagination-toolbar\"><ul class=\"pagination-list\">";

            $pagination.= "<li><a href='".$targetpage.''.$pagination_separator.'page1=1'."' style=\"border-left: 1px solid #dddddd;\">First</a></li>";

            //previous button
            if ($page > 1){ 
                $pagination.= "<li><a href='".$targetpage.''.$pagination_separator.'page1='.$prev."'>previous</a></li>";
				}else{
                $pagination.= "<li><span class=\"disabled\">previous</span></li>";  
            	}

             $temp=0; 
            for($k=$page;$k<=$lastpage;$k++){
              $temp++;

              if($temp<10){
                if ($k != $page){
                  $pagination.= "<li><a href='".$targetpage.''.$pagination_separator.'page1='.$k.''."'>$k</a></li>";  
                } else {
                  $pagination.= "<li><span class=\"current\">$k</span></li>";
                }
              }else{
                break;
              }
            }	
            
            
            //next button
            if ($page < $lastpage) 
                $pagination.= "<li><a href='".$targetpage.''.$pagination_separator.'page1='.$next."'>next </a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">next</span></li>";
                $pagination.= "<li><a href='".$targetpage.''.$pagination_separator.'page1='.$lastpage."'>Last</a></li>";
            $pagination.= "</ul></div>\n";      
        } ?>

		<?php 
		$menu_base_url = get_permalink($post->ID).'&task=cancelclassfront';
	    if(strpos(get_permalink($post->ID),'?')===false){
	        $menu_base_url = get_permalink($post->ID).'?task=cancelclassfront';
	    }
	    ?>
		<style type="text/css">
		.entry-content{ padding: 50px; width: 100% !important;box-shadow: 0px 1px 5px 1px;border-radius: 40px;}
		.wrap {max-width: 900px !important;}
		.components-notice.is-error.is-dismissible { display: none;}
		@media(max-width: 768px){
			#search{width: 100% !important;margin-bottom: 10px;}
		}
		@media(max-width: 425px){
			#reset{	margin-top:10px;}
		}
	</style>
<div id="modal-content-cancelclass" class="modal">
	<div class="modal-content" style="overflow: hidden;padding: 0;">
		<header style="background: #23282d;padding: 16px 16px">
			<h2 style="color: #FFF;margin: 0;">Cancel recurring class
			<span class="close">&times;</span>
			</h2>
		</header>	
		<form action="<?php echo esc_url($menu_base_url)?>" class="form-horizontal form-validate" id="adminForm" action="" method="post" enctype="multipart/form-data">
			<div style="padding: 25px;">
				<div>Are you sure you want to cancel this recurring class <span class="class_title"></span> ?</div>
				<div>
					<input type="radio" name="isCancel" value="1" checked="checked" style="margin-top: 7px;">
					<span>Cancel only current class in the recurring schedule</span>
				</div>
				<div>
					<input type="radio" name="isCancel" value="2" style="margin-top: 7px;">
					<span>Cancel all classes in this recurring schedule</span>
				</div>
			</div>	
				<input type="hidden" id="cancelclassid" name="id" value="">
				<footer style="border-top: 1px solid #23282d;padding: 16px 16px;">
					<button type="submit" class="btn btn-primary">Save</button>
				</footer>	
		</form>
	</div>
</div>
		<?php	
		global $wpdb;
  		$isteacher  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %s",array(get_current_user_id())));
  		$is_super_admin = is_super_admin(get_current_user_id());
  		$current_user = wp_get_current_user();

  		if( $isteacher==1 || $is_super_admin==1 ){ ?>
			<button class="button button-primary button-large" style="margin-bottom: 15px;">
				<a href="<?php echo esc_url(get_post_permalink($row->schedule_class_page))?>" style="box-shadow: none;color: #ffffff;text-transform: none;">Schedule</a>
			</button>
		<?php } ?>
		<form id="searchForm" name="searchForm" method="post" action="">  
			<table class="table">
			    <thead><tr style="border: none !important">
			      <td width="100%">
			            <input type="text" name="search" id="search" value="<?php echo esc_attr($search);?>" class="text_area" title="Filter by Title" style="width: 64%;float: left;margin-right: 10px;">
			            <input type="submit" name="submit" id="submit" class="button button-primary" value="Go" style="line-height: 27px;" />
						<input type="reset" name="reset" id="reset" class="button button-primary" value="Reset" style="line-height: 41px;color: white;font-weight:900;background: #222;cursor: pointer;width: 100px;" />
			      </td>
			    </tr>
			  </thead></table>
			</form> 
		<?php
        if(isset($data['classes'])){
          foreach ($data['classes'] as $i => $item) {
          			$m=0;
          			if($current_user->ID==$item['created_by'] || $current_user->ID==$item['instructor_id'] || $is_super_admin==1){
          				$m=1;	
          			}
          				
      				if(is_plugin_active('groups/groups.php' ) && !current_user_can( 'manage_options' ) && !$isteacher && $m==0){
	          			if ( (is_array($allowClass_list) && !in_array($item['id'], $allowClass_list)) || empty($allowClass_list)){continue;}
        			}	
          			
          			if($isteacher && $is_super_admin==0){
          				if($current_user->ID!=$item['instructor_id']){
          					continue;	
          				}
          			} 

					$is_tchr  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %s",array($current_user->ID)));

          			$mins = $item['class_starts_in'] / 60;
		            $before_time=0;
		            if(strtolower($item['status'])=="upcoming" && $mins>0 && $mins<=30 && $is_tchr==1 ){
		                $item['status'] = "Live";
		                $before_time=1;
		            } 

                    if(strtolower($item['status']) == "upcoming"){
                        $class = "vc-alert vc-alert-warning";
                    }
                    if(strtolower($item['status']) == "past"){
                         $class = "vc-alert vc-alert-danger";
                    }
                    if(strtolower($item['status']) == "live"){
                        $class = "vc-alert vc-alert-success";
                    }
                    ?>
               <tr>
                    <td>
                        <div class="class_div cl_list">
                        <div style="width: 70%;float: left;">
                            <!-- Shaista   replaced strong with span and added class - class-heading-->
	
							<?php 

				$title_url = get_permalink($post->ID).'&pcid='.$item['id'];
				$submenu_base_url = get_permalink($post->ID).'&id='.$item['id'];
			    if(strpos(get_permalink($post->ID),'?')===false){
			        $title_url = get_permalink($post->ID).'?pcid='.$item['id'];
			        $submenu_base_url = get_permalink($post->ID).'?id='.$item['id'];
			    }
			    ?>
                            <i class="icon-bullhorn"></i><strong class="class-heading">
                                <a style="text-decoration: none !important;font-weight: 600;color: blue;" href="<?php echo esc_attr($title_url);?>"><?php echo esc_html($item['title']) ?></a></strong> &nbsp;
								<?php if($item['isCancel']==1 || $item['isCancel']==2){ ?>
									<span class="vc-alert vc-alert-danger">Canceled</span>	
								<?php }else{?>
									<span class="<?php echo esc_attr($class);?>"><?php echo esc_attr($item['status']) ?></span>
								<?php }?>
                                

                            <div class="course_info">
                                <p>
                            <?php if(strtolower($item['status']) =='upcoming' && !empty($item['class_next_date'])) { ?>
                            	 <i class="icon icon-calendar"></i> <?php echo esc_attr(gmdate('l, F d, Y', $item['class_next_date']));
                          	}else {?>	
		 						<i class="icon icon-calendar"></i>&nbsp;<?php echo esc_attr(gmdate("l, F d, Y",strtotime($item['date']))); 
							} ?>  


                                    
                                </p>

                                <?php $duration = (int)($item['duration'] / 60); ?>
                                <p><i class="icon icon-time"></i>
                                    <?php echo esc_html($item['start_time']) . " - " . esc_html($item['end_time']); ?> (<?php
                                    echo esc_attr($duration) . " Minutes";
                                    ?>)</p>
                                <p> <i class="icon icon-globe"></i> Time Zone: <?php echo esc_html($item['label']); ?></p>
                                <p> Keywords: <?php echo esc_attr($item['keyword']); ?></p>
                            </div>
                            <?php
  							$enrolled  = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM ".$wpdb->prefix."virtualclassroom_purchase WHERE `class_id` = %s AND payer_id=%s",array($item['id'],get_current_user_id())));

  							if($item['instructor_id']==$current_user->ID){
                                $enrolled = 1;
                            }

                            if($item['ispaid'] && strtolower($item['status'])!="past" && !$enrolled && $isteacher == 0 && get_current_user_id() !=0 && $item['isCancel']==0){

                            	$buy_url = get_permalink($post->ID).'&pcid='.$item['id'];
							    if(strpos(get_permalink($post->ID),'?')===false){
							        $buy_url = get_permalink($post->ID).'?pcid='.$item['id'];
							    }

                            	?>
                                <br>
                                <a class="btn btn-danger btn-sm" href="<?php echo esc_url($buy_url);?>"><h4  style="margin: 0px;" class=""><i class="icon-shopping-cart icon-white"></i>Buy</h4></a>
                                <br>
                                <?php
                            }else if($item['ispaid'] && strtolower($item['status'])!="past" && !$enrolled && $isteacher == 0 && get_current_user_id() ==0  && $item['isCancel']==0){ 
                            	global $post;

                            	?>
                            	<br>
                                <button class="btn btn-danger btn-sm"  onclick="loginpopup('<?php echo esc_url(get_permalink($post->ID)); ?>'); return false;" id="buybtn"><h4  style="margin: 0px;" class=""><i class="icon-shopping-cart icon-white"></i>Buy</h4></button>
                                <br>

                          <?php  }
                            if((strtolower($item['status']) == "live" && $enrolled) || $item['ispaid']==0 || $isteacher == 1 && $item['isCancel']==0){

					 		$current_user = wp_get_current_user();
					 		$data1['userId'] = sanitize_text_field($current_user->ID);
						    $data1['userName'] = sanitize_text_field($current_user->display_name);
						    $data1['lessonName'] = sanitize_text_field($item['title']);
						    $data1['courseName'] = sanitize_text_field($item['title']);
						    

  							$data1['isTeacher'] = 0;
							if($item['instructor_id']>0 && $item['instructor_id']==$current_user->ID){
                                    $data1['isTeacher'] = 1;       
                            }else if($item['created_by']>0 && $item['created_by']==$current_user->ID){
                            	$data1['isTeacher'] = 1;
                            }else if($is_tchr == 1){
                            	$data1['isTeacher'] = 1; 
                            }

                            $data1['task'] = sanitize_text_field('getclasslaunch');
						    $data1['apikey'] = sanitize_text_field($key);
						    $data1['class_id'] = sanitize_text_field($item['id']);
						   // echo "<prE>";print_r($data1);echo "</pre>";
						    $launchurl = (object)$vc_obj->vlcr_get_curl_info($data1);

                            $url='';
                            if(isset($launchurl->encryptedlaunchurl) && strtolower($item['status']) == "live"){
                                	$url = str_replace("'\'","",$launchurl->encryptedlaunchurl);
                             }
                            if($url){ ?>
                            <?php
                               global $post;
                                 ?>
                                <br>
                                <?php if($before_time==1){ ?>
                                	<a target="_blank" class="btn btn-primary" style="font-weight: bold; margin-bottom: 10px;" id="launch-btn" onclick="popup('<?php echo esc_url($url) ?>'); return false;">Enter to prepare class</a>    
                                <?php }else{ ?>
                                    <a target="_blank" class="btn btn-primary" style="font-weight: bold; margin-bottom: 10px;" id="launch-btn" onclick="popup('<?php echo esc_url($url) ?>'); return false;">Launch</a>
                                <?php }?>
                                <br>
                            <?php } ?>
                                <?php
                              }
                             ?>
                             </div>
                             <div>
                             <?php

                              if( ($m==1 && $isteacher==1 ) || $is_super_admin==1 ){?>
                             	<div class="dropdown" style="float: right;">
                    
                    <a class="dropbtn" id="dropbtn" href="javascript:void(0);" onclick="dropdownmenu('<?php echo esc_attr($item["id"])?>')" style="padding: 0 16px;box-shadow: none;"> <i class="icon icon-cog"></i> <b class="caret"></b> </a>
                
                <div class="dropdown-content" id="slide-gear-<?php echo esc_attr($item['id'])?>">
                <li>    
                <?php 
                 $learner_url = get_permalink($row->class_detail_page).'&islearner=1&pcid='.$item['id'];
                 if(strpos(get_permalink($row->class_detail_page),'?')===false){
                    $learner_url = get_permalink($row->class_detail_page).'?islearner=1&pcid='.$item['id'];
                }
                 ?>
                    <a target="_blank" alt="Click to see test detail" href="<?php echo esc_url($learner_url);?>"><i class="icon icon-eye-open"></i> Preview as Learner</a>
                    
                </li>
                <li>
                <?php 
                $instructor_url = get_permalink($row->class_detail_page).'&isinstructor=1&pcid='.$item['id'];
                 if(strpos(get_permalink($row->class_detail_page),'?')===false){
                    $instructor_url = get_permalink($row->class_detail_page).'?isinstructor=1&pcid='.$item['id'];
                }
                ?>
                    <a target="_blank" alt="Click to see test detail" href="<?php echo esc_attr($instructor_url);?>"><i class="icon icon-eye-open"></i> Preview as Instructor</a>
                </li>
				
				<?php if(strtolower($item['status'])=="upcoming"){?>		
                <li>
                <?php 
                $schedule_class_page_url = get_permalink($row->schedule_class_page).'&cid='.$item['id'];
                 if(strpos(get_permalink($row->schedule_class_page),'?')===false){
                    $schedule_class_page_url = get_permalink($row->schedule_class_page).'?cid='.$item['id'];
                }
                ?>
                    <a target="_blank" alt="Click to see test detail" href="<?php echo esc_attr($schedule_class_page_url);?>"><i class="icon icon-eye-open"></i> Edit</a>
                </li>
                <?php } ?>

                <li>

                    <a href="<?php echo esc_url($submenu_base_url)."&type=attendancereport"?>"><i class="icon icon-users"></i> Attendance report</a>
                </li>
               
               <?php if($item['isCancel']==1 || $item['isCancel']==2){ ?>
					<li>
                    <a href="<?php echo esc_url($submenu_base_url)."&task=activeclassfront"?>" onclick="return confirm('Are you sure you want to active this class?')"><i class="icon icon-plus"></i> Active class</a>
                	</li>
               	<?php } else{ ?>
               		<?php if($item['repeat']==0){ ?>
						<li>
                    <a href="<?php echo esc_url($submenu_base_url)."&task=cancelclassfront&isCancel=1"?>" onclick="return confirm('Are you sure you want to cancel this class?')"><i class="icon icon-minus-circle"></i> Cancel class</a>
                	</li>	
               		<?php }else{ ?>
						<li>
                    <a href="#" onclick="cancelclass(<?php echo esc_attr($item['id']);?>,'<?php echo esc_html($item['title']) ?>')"><i class="icon icon-minus-circle"></i> Cancel class</a>
                	</li>
               		<?php }?>
					
               	<?php }?>

                
                <li class="divider"></li>
                <li> 
                    <a href="<?php echo esc_url($submenu_base_url)."&type=inviteemail"?>"> <i class="icon icon-envelope"></i> Invite by E-mail </a> 
                </li>
                <li> 
                    <a href="<?php echo esc_url($submenu_base_url)."&type=inviteusers"?>"> <i class="icon icon-envelope"></i> Invite Users </a> 
                </li>
                
                <li> 
                    <a href="<?php echo esc_url($submenu_base_url)."&type=inviteusergroup"?>"> <i class="icon icon-envelope"></i> Invite User Group </a> 
                </li>
                    <li class="divider"></li>
                    <?php if($item['ispaid']==1){ ?> 
                    <li>
                    <a href="<?php echo esc_url($submenu_base_url)."&type=pricelist"?>" >
                    <i class="icon icon-shopping-cart"></i> Shopping Cart
                    </a>
                    </li>                    
                    <li>
                    <a href="<?php echo esc_url($submenu_base_url)."&type=discountlist"?>" >
                    <i class="icon icon-ticket"></i> Discounts
                    </a>
                    </li>
                    <?php } ?>
                    <?php 
                    $result="";
                     if($item['record']>0){
                     	$data['task'] = 'getclassrecording';
        				$data['class_id'] = $item['id'];
        				$result = $vc_obj->vlcr_get_curl_info($data);
        			}
        			if($item['record']>0 && isset($result['0']['id'])){ 
                     	
        			?> 
                    <li>
                        <a href="<?php echo esc_url($submenu_base_url)."&type=viewrecording"?>" >
                        <i class="icon icon-play-circle"></i>
                        View class Recording
                        </a>
                    </li>

                    <li>
                        <a href="<?php echo esc_url($submenu_base_url)."&type=recordinglist"?>" >
                        <i class="icon icon-play-circle"></i>
                        Manage Recording
                        </a>
                    </li>
                    
                    <?php } ?>
                    <li>
                        <a href="<?php echo esc_url($submenu_base_url)."&type=emailtemplate"?>" >
                        <i class="icon icon-envelope"></i>
                        Manage Email template
                        </a>
                    </li>
                </div></div>
                <?php } ?>
                             </div>
                        </div>
                    <hr style="clear: both;">
                     
            <?php  } } ?>
            <?php
            echo  wp_kses_post($pagination);
}
add_shortcode('class_list_front', 'vlcr_classlist_site_fun');
add_shortcode('class_schedule_teacher', 'vlcr_class_schedule');
function vlcr_class_schedule(){
	include 'vlcr_class_schedule.php';
}
function vlcr_classlist_admin_fun()
{
	$vc_obj = new vlcr_class();
	$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
	global $wpdb,$key,$base_url;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	$task = isset($_REQUEST['task']) ? sanitize_text_field($_REQUEST['task']) : '' ;
    $id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';
    $page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
	if($task){
		if($task=="cancelclass"){ 
			$data2['isCancel'] = $_REQUEST['isCancel'];
			$data2['class_id'] = sanitize_text_field($id);
	        $data2['task'] = 'cancelclass';
	        $response = (object)$vc_obj->vlcr_get_curl_info($data2);
	        wp_redirect(admin_url().'admin.php?page='.$page);
		}
		if($task=="activeclass"){ 
			$data2['isCancel'] = 0;
			$data2['class_id'] = sanitize_text_field($id);
	        $data2['task'] = 'cancelclass';
	        $response = (object)$vc_obj->vlcr_get_curl_info($data2);
	        wp_redirect(admin_url().'admin.php?page='.$page);
		}
	}
	switch($action){
		case 'add':
			include 'vlcr_class_listing_edit.php';
			break;

		case 'edit':
			include 'vlcr_class_listing_edit.php';
			break;

		case 'delete':
			$_REQUEST['task'] = 'deleteClass';
			include 'vlcr_classlist_admin.php';
			break;
		case 'search_teacher':
			$vc_obj->vlcr_get_class_search_teacher();
			break;
		default:
			include 'vlcr_classlist_admin.php';
			break;
	}
}
function vlcr_configuration()
{
global $wpdb;
if(isset($_POST['save-settings'])){
 	$wpdb->query($wpdb->prepare("UPDATE ".$wpdb->prefix . "virtualclassroom_settings SET `braincert_api_key` = %s , `braincert_base_url` = %s , `inv_email_page` = %s, `sharing_code` = %s, `is_schedule_class` = %s, `schedule_class_page` = %s, `class_detail_page` = %s",array($_POST['braincert_api_key'],$_POST['braincert_base_url'],$_POST['inv_email_page'],$_POST['sharing_code'],$_POST['is_schedule_class'],$_POST['schedule_class_page'],$_POST['class_detail_page'])));
	echo "<p>Settings Saved!</p>";
}
$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
if(!$row)
{
    $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."virtualclassroom_settings (`id`,`braincert_api_key`,`braincert_base_url`,`inv_email_page`,`class_detail_page`,`is_schedule_class`,`sharing_code`) VALUES (%s,%s,%s,%s,%s,%s,%s)",array(null,'','https://api.braincert.com/v2','','','','')));
}
$setting = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix . "virtualclassroom_settings");
?>
<h1 style="float: left;">Virtual Class Room Settings</h1>
<div class="vc_tooltip" style="margin: 18px 8px 0">
<i class="icon icon-question-sign" style="color: #0085ba;font-size: 20px;"></i>
<span class="vc_tooltiptext" style="    width: auto;top: 100%;left: 50%;margin-left: -60px;bottom: auto;padding: 5px;">Generate API key at <a href="https://www.braincert.com/app/virtualclassroom">https://www.braincert.com/app/virtualclassroom</a></span>
</div>


<form name="frmsettings" action="" method="post" style="clear: both;">
<table class="table" border="0">
	<tr>
    	<td>BrainCert API Key: </td>
        <td><input type="text" name="braincert_api_key" value="<?php echo ($setting->braincert_api_key) ? esc_attr($setting->braincert_api_key) : ''?>" style="width: 300px;"/></td>
    </tr>
    <tr>
    	<td>BrainCert Base URL: </td>
        <td><input type="text" name="braincert_base_url" value="<?php echo ($setting->braincert_base_url) ? esc_attr($setting->braincert_base_url) : ''?>" style="width: 300px;"/>			</td>
    </tr>
    <tr style="line-height: 30px;">
    	<td>Allow instructor to schedule classes from frontend: </td>
        <td>
        	<input type="radio" name="is_schedule_class" value="1" <?php if($setting->is_schedule_class > 0) echo 'checked="checked"'?>>Yes
            <input type="radio" name="is_schedule_class" value="0" <?php if($setting->is_schedule_class == 0 || !isset($setting)) echo 'checked="checked"'?>>No
        </td>
    </tr>
    <tr>
    	<td colspan="2"><h2 style="float: left;">Invite by E-mail Settings</h2>
    	
		</td>
    </tr>
    <tr>
    	<td>Page id for Invite by E-mail
    	<div class="vc_tooltip">
		<i class="icon icon-question-sign" style="color: #0085ba;"></i>
		<span class="vc_tooltiptext" style="font-size: 13px;line-height: 1.4em;">The page ID where you have added the short code student_invite</span>
		</div>
		</td>
    	<td>
    		<input type="text" name="inv_email_page" value="<?php echo ($setting->inv_email_page) ? esc_attr($setting->inv_email_page) : ''?>" style="width: 300px;"/>
    	</td>
    </tr>
    <tr>
    	<td>Page id for Class Details
    	<div class="vc_tooltip">
		<i class="icon icon-question-sign" style="color: #0085ba;"></i>
		<span class="vc_tooltiptext" style="font-size: 13px;line-height: 1.4em;">Page id for Class Details</span>
		</div>
		</td>
    	<td>
    		<input type="text" name="class_detail_page" value="<?php echo ($setting->class_detail_page) ? esc_attr($setting->class_detail_page) : ''?>" style="width: 300px;"/>
    	</td>
    </tr>
    <tr>
    	<td>Page id for Schedule Class
    	<div class="vc_tooltip">
		<i class="icon icon-question-sign" style="color: #0085ba;"></i>
		<span class="vc_tooltiptext" style="font-size: 13px;line-height: 1.4em;">Page id for Schedule Class</span>
		</div>
		</td>
    	<td>
    		<input type="text" name="schedule_class_page" value="<?php echo ($setting->schedule_class_page) ? esc_attr($setting->schedule_class_page) : ''?>" style="width: 300px;"/>
    	</td>
    </tr>
    <tr>
    	<td>
    	Addthis social sharing publisher ID
    	<div class="vc_tooltip">
    	 <i class="icon icon-question-sign" style="color: #0085ba"></i>
		<span class="vc_tooltiptext" style="font-size: 13px;line-height: 1.4em;">Show this social sharing icons in class details page in the frontend</span>
        </div>	
    	</td>
    	<td>
    		<input type="text" name="sharing_code" value="<?php echo ($setting->sharing_code) ? esc_attr($setting->sharing_code) : ''?>" style="width: 300px;"/>
    	</td>
    </tr>
    
    <tr>
    	<td colspan="2"><input type="submit" class="button button-primary button-large" value="Save Settings" name="save-settings" /></td>
    </tr>
</table>
</form>
	<?php

}

function vlcr_recordinglist_admin_fun()
{
	$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
	global $wpdb,$key,$base_url;

	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	switch($action){
		case 'add':
			include 'vlcr_recording_listing_edit.php';
			break;

		case 'edit':
			include 'vlcr_recording_listing_edit.php';
			break;

		case 'delete':
			$_REQUEST['task'] = 'deleteRecording';
			include 'vlcr_recordinglist_admin.php';
			break;

		default:
			include 'vlcr_recordinglist_admin.php';
		break;
	}
}
function vlcr_viewrecording_admin_fun(){
	wp_enqueue_style( 'vlcr_video-js', VC_URL.'/css/vlcr_video-js.css');
	include 'vlcr_view_recording_admin.php';
}

function vlcr_discountlist_admin_fun()
{
	global $wpdb,$key,$base_url;
	$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	switch($action){
		case 'add':
			include 'vlcr_discount_listing_edit.php';
			break;

		case 'edit':
			include 'vlcr_discount_listing_edit.php';
			break;

		case 'delete':
			$_REQUEST['task'] = 'removediscount';
			include 'vlcr_discountlist_admin.php';
			break;

		default:
			include 'vlcr_discountlist_admin.php';
			break;

	}
}
function vlcr_pricelist_admin_fun()
{
	$action = isset( $_REQUEST['action'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ) : '';
	global $wpdb,$key,$base_url;
	$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
	$key = $row->braincert_api_key;
	$base_url = $row->braincert_base_url;
	switch($action){
		case 'add':
			include 'vlcr_price_listing_edit.php';
			break;

		case 'edit':
			include 'vlcr_price_listing_edit.php';
			break;

		case 'delete':
			$_REQUEST['task'] = 'deletePrice';
			include 'vlcr_pricelist_admin.php';
			break;

		default:
			include 'vlcr_pricelist_admin.php';
			break;
	}
}

function vlcr_teacherlist_admin_fun()
{
	include 'vlcr_teacherlist_admin.php';
}
function vlcr_paymentlist_admin_fun()
{
	include 'vlcr_paymentlist_admin.php';
}
function vlcr_permissions_admin_fun()
{
	include 'vlcr_user_group_capabilities.php';
}

function vlcr_learner_preview_admin_fun(){
	include 'vlcr_learner_preview.php';   
}

function vlcr_instructor_preview_admin_fun(){
	include 'vlcr_instructor_preview.php';   
}
function vlcr_inviteemail_admin_fun(){
	include 'vlcr_invite_by_email.php';   
}
function vlcr_email_template_fun(){
	include 'vlcr_email_template.php';   
}

function vlcr_inviteuser_admin_fun(){
	include 'vlcr_invite_user.php';   
}
function vlcr_inviteuser_group_admin_fun(){
	include 'vlcr_invite_user_group.php';   
}
function vlcr_attendance_report_admin_fun(){
	include 'vlcr_attendance_report.php';   
}

function vlcr_front_view_func()
{
	include 'classes.php';
}
function vlcr_get_selected_class(){
	ob_clean();
	$gid = $_REQUEST['gid'];
	global $wpdb;
	$row = $wpdb->get_col($wpdb->prepare("SELECT class_id FROM ".$wpdb->prefix."virtualclassroom_acl WHERE `group_id` = %s",array($gid)));
	echo esc_attr($row[0]);
	exit;
}
add_action('wp_ajax_vlcr_get_selected_class','vlcr_get_selected_class');
function vlcr_stylesheetcss_scripts() {
	wp_enqueue_style( 'font-awesome.min', VC_URL.'css/font-awesome.min.css');
	wp_enqueue_style( 'vlcr_style', VC_URL.'css/vlcr_style.css' );
}
function vlcr_footer_scripts(){}
add_action( 'init', 'vlcr_stylesheetcss_scripts');
add_action( 'wp_footer', 'vlcr_footer_scripts');
add_action('admin_menu','vlcr_admin_menu');
register_activation_hook(__FILE__,'vlcr_install');
register_deactivation_hook(__FILE__,'vlcr_install_del');
add_shortcode('VC_CLASS_LIST', 'vlcr_front_view_func');
add_action( 'init', 'vlcr_admin_class');
?>