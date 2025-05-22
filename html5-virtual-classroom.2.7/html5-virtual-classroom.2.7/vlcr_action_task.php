<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Action task
 * @package  virtual-classroom
 * @since    2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb,$key,$base_url;
$task = sanitize_text_field($_REQUEST['task']);
if($task){
	vlcr_action_task($task);	
}

function vlcr_action_task($task)
{
	switch($task){
		
		case 'createprice':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/PriceList&cid='.sanitize_text_field($_REQUEST['cid']);
			if($_REQUEST['type']=="pricelist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=pricelist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=pricelist';
			    }
			}
			vlcr_createprice($return);
			break;
		case 'creatediscount':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/DiscountList&cid='.sanitize_text_field($_REQUEST['cid']);
			if($_REQUEST['type']=="discountlist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=discountlist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=discountlist';
			    }
			}
			vlcr_creatediscount($return);
			break;

		case 'launchurl':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/DiscountList&cid='.sanitize_text_field($_REQUEST['cid']);
			vlcr_launchurl($return);
			break;
		
			
		case 'saveClass':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/ClassList';
			vlcr_saveClass($return,0);
			break;

		case 'saveClassfront':
		    global $wpdb,$key,$base_url;
			$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
			$return = get_post_permalink($row->class_detail_page);
			vlcr_saveClass($return,1);
			break;
			
		case 'unpublishClass':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/ClassList';
			vlcr_unpublishClass($return);
			break;
		case 'publishClass':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/ClassList';	
			vlcr_publishClass($return);
			break;
		case 'unpublishuser':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/TeacherList';	
			vlcr_unpublishuser($return);
			break;	
		case 'publishuser':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/TeacherList';	
			vlcr_publishuser($return);
			break;	
		case 'deleteClass':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/ClassList';	
			if($_REQUEST['type']=="pricelist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=pricelist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=pricelist';
			    }
			}
			
			vlcr_deleteClass($return);
			break;
		case 'deletePrice':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/PriceList&cid='.sanitize_text_field($_REQUEST['cid']);
			vlcr_deletePrice($return);
			break;
		case 'removediscount':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/DiscountList&cid='.sanitize_text_field($_REQUEST['cid']);
			if($_REQUEST['type']=="discountlist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=discountlist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=discountlist';
			    }
			}
			vlcr_removediscount($return);
			break;
			
		case 'deleteRecording':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid='.sanitize_text_field($_REQUEST['cid']);
			vlcr_deleteRecording($return);
			break;
		case 'downloadRecord':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid='.sanitize_text_field($_REQUEST['cid']);
			vlcr_downloadRecord($return);
			break;
		case 'change_recording_status':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid&id='.sanitize_text_field($_REQUEST['rid']);
			if($_REQUEST['type']=="recordinglist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=recordinglist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=recordinglist';
			    }
			}
			vlcr_changeRecordstatus($return);
			break;

		case 'remove_recording':
			$return = 'admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid&id='.sanitize_text_field($_REQUEST['rid']);
			if($_REQUEST['type']=="recordinglist"){
				$return = get_permalink($post->ID).'&id='.$_REQUEST['cid'].'&type=recordinglist';
				if(strpos(get_permalink($post->ID),'?')===false){
			        $return = get_permalink($post->ID).'?id='.$_REQUEST['cid'].'&type=recordinglist';
			    }
			}
			vlcr_remove_recording($return);
			break;	
	}
}
function vlcr_launchurl($return){
	return $data = $_REQUEST;
}
function vlcr_creatediscount($return){

  		global $key,$base_url;
  		$vc_obj = new vlcr_class();
		$data = $_REQUEST;
    	 
    	unset($data['page']);     
    	unset($data['action']);     
    	 
        $data['task'] = sanitize_text_field('addSpecials');
        $data['apikey'] = sanitize_key($key);
        $data['class_id'] = sanitize_text_field($data['cid']);
        if($data['id']){
        $data['discountid'] = sanitize_text_field($data['id']);
        unset($data['id']);
        }
        $result_data = (object)$vc_obj->vlcr_get_curl_info($data);
        
        if($result_data->status == 'error'){
            echo esc_attr($result_data->error);
        }
        
        if(strtolower($result_data->status) == 'ok'){
        	
            header('Location:'.$return);

			exit;
        }
}
function vlcr_unpublishuser($return){
	$data = $_REQUEST;
	global $wpdb;
	$tchr_id="";
	if(isset($data['user_id'])){
  		$tchr_id  = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE user_id = %d",array($data['user_id'])));
  	}
  	$tblname = $wpdb->prefix . 'virtualclassroom_teacher';
  	if($tchr_id){
    	$wpdb->update($tblname,array('is_teacher' => 0),array('user_id'=> $data['user_id']));
    }else{
        $qry="INSERT INTO ".$wpdb->prefix."virtualclassroom_teacher (user_id,is_teacher) VALUES ('".sanitize_text_field($data['user_id'])."',0)";

        $wpdb->insert( $tblname, 
                            array( 
                                'id' => '', 
                                'user_id' => $data['user_id'],
                                'is_teacher'=>0 
                            ) 
                        );
     }
}

function vlcr_publishuser($return){
	$data = $_REQUEST;
	
	global $wpdb;
  	$tchr_id="";
	if(isset($data['user_id'])){
  		$tchr_id  = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE user_id = %d",array($data['user_id'])));
  	}
  	$tblname = $wpdb->prefix . 'virtualclassroom_teacher';
  	if($tchr_id){
    	$wpdb->update($tblname,array('is_teacher' => 1),array('user_id'=> $data['user_id']));
    }else{
        $wpdb->insert( $tblname, 
                            array( 
                                'id' => '', 
                                'user_id' => $data['user_id'],
                                'is_teacher'=>1 
                            ) 
                        );
    }
    return;
}

function vlcr_createprice($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
    	 
   	unset($data['page']);     
   	unset($data['action']);     
    	 
    $data['task'] = sanitize_text_field('addSchemes');
    $data['apikey'] = sanitize_key($key);
    $data['class_id'] = sanitize_text_field($data['cid']);
    $result_data = (object)$vc_obj->vlcr_get_curl_info($data);
    if($result_data->status == 'error'){
        echo esc_attr($result_data->error);
    }
    if(strtolower($result_data->status) == 'ok'){
        wp_redirect($return);
		exit;
    }
}

function vlcr_saveClass($return,$temp){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	 
	$data['task'] = sanitize_text_field('schedule');
	$data['apikey'] = sanitize_text_field($key);

	if(is_array($data['weekdays'])){
		$data['weekdays'] = implode(',', $data['weekdays']);
	}
	
	if($data['record'] == '1' && $data['start_recording_auto'] == '2'){
		$data['record'] = '2';
	}

	if(($data['record'] == '1' || $data['record']==2) && $data['isControlRecording'] == '0'){
		$data['record'] = '3';
	}

	if($data['allow_change_interface_language']==0){
		$data['isLang']=sanitize_text_field($data['language']);
 	}else{
 		$data['isLang']=11;
 	}

 	if($data['location_id']){
 		$data['isRegion'] = sanitize_text_field($data['location_id']) ;
 	}

	if($data['location']){
		$data['isRegion'] = sanitize_text_field($data['location']) ;
	}	

	$data['isBoard']=sanitize_text_field($data['classroom_type']);
 	$data['description']=base64_encode($data['description']);
 	unset($data['location_id']);
 	unset($data['location']);
	unset($data['start_recording_auto']);
	unset($data['allow_change_interface_language']);
	unset($data['classroom_type']);
	unset($data['language']);
	unset($data['isControlRecording']);
	$result_data = (object)$vc_obj->vlcr_get_curl_info($data);

	if($result_data->status == 'error'){
		$msg = $result_data->error;
		echo '<div class="error">
		<p><strong>ERROR</strong>: '.esc_attr($msg).'</p>	</div>';
	}
	if(strtolower($result_data->status) == 'ok'){	
		$return = add_query_arg( 'success', 'ok', $return );	
		wp_redirect($return);
		exit;
	}
}

function vlcr_unpublishClass($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$msg="";
	$data1['task'] = sanitize_text_field('unpublishclass');
	$data1['apikey'] = sanitize_key($key);
	$data1['class_id'] = sanitize_text_field($data['cid']);

	$result_data = (object)$vc_obj->vlcr_get_curl_info($data1);
	 
	if(strtolower($result_data->status) == 'ok'){
		$msg = esc_html("Class unpublish successfully");
	}
	return $msg;
}

function vlcr_publishClass($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$msg="";
	$data1['task'] = sanitize_text_field('publishclass');
	$data1['apikey'] = sanitize_key($key);
	$data1['class_id'] = sanitize_text_field($data['cid']);
	$result_data = (object)$vc_obj->vlcr_get_curl_info($data1);
	 
	if(strtolower($result_data->status) == 'ok'){
		$msg = esc_attr("Class publish successfully");
	}
	return $msg;
}
function vlcr_remove_recording($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	
	$data1['task'] = sanitize_text_field('removeclassrecording');
	$data1['apikey'] = sanitize_key($key);
	$data1['id'] = sanitize_text_field($data['rid']);

	$result_data = (object)$vc_obj->vlcr_get_curl_info($data1);
  
   $returnurl =	admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid='.$data["cid"].'');
   if($_REQUEST['type']=="recordinglist"){ $returnurl=$return; }

	if(strtolower($result_data->status) == 'ok'){
		wp_redirect( $returnurl );
		exit;
	}

}

function vlcr_changeRecordstatus($return){
	global $key,$base_url;
	$data = $_REQUEST;
	$vc_obj = new vlcr_class();
	$data1['task'] = sanitize_text_field('changestatusrecording');
	$data1['apikey'] = sanitize_key($key);
	$data1['id'] = sanitize_text_field($data['rid']);
	
	$result_data = (object)$vc_obj->vlcr_get_curl_info($data1);
   		
   	$returnurl =	admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/RecordingList&cid='.$data["cid"].'');
   	if($_REQUEST['type']=="recordinglist"){ $returnurl=$return; }

	if(strtolower($result_data->status) == 'ok'){
		wp_redirect( $returnurl );
		exit;		
	}
}

function vlcr_downloadRecord($return){
	global $key,$base_url;
	$data = $_REQUEST;
	$temp = 0;

	$data1['apikey'] = sanitize_key($key);
	$data1['id'] = sanitize_text_field($value);
	$data1['name'] = sanitize_text_field($data['file']);
	$data1['task'] = sanitize_text_field('downloadRecord');

	$response = Requests::post($base_url, array(), $data1 );
    $result_data = $response->body;

 	header('Content-Transfer-Encoding: binary');  // For Gecko browsers mainly
	header('Content-Type: application/octet-stream');
	header('Content-Length:'.strlen($result_data));
	header('Content-Disposition: attachment; filename="'.$data1['name'].'"');
	echo esc_attr($result_data);
	exit;
}

function vlcr_deletePrice($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$temp = 0;
	$msg="";
	foreach ($data['priceid'] as $value) {
		$data1['apikey'] = sanitize_key($key);
		$data1['id'] = sanitize_text_field($value);
		$data1['task'] = sanitize_text_field('removeprice');
		$result = (object)$vc_obj->vlcr_get_curl_info($data1);
			 
		if(strtolower($result->status) == 'ok'){
	        $temp = 1;
	 	}
       	if($result->status == 'error'){
           	echo esc_attr($result->error);
       	}
	}
	if($temp == 1){
		$msg = esc_attr("Price remove successfully");
    }
    return $msg;
}

function vlcr_removediscount($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$temp = 0;
	$msg="";
	foreach ($data['discountid'] as $value) {
			 
		$data1['apikey'] = sanitize_key($key);
		$data1['discountid'] = sanitize_text_field($value);
		$data1['task'] = sanitize_text_field('removediscount');

		$result = (object)$vc_obj->vlcr_get_curl_info($data1);

		if(strtolower($result->status) == 'ok'){
            $temp = 1;
   		}
       	if($result->status == 'error'){
           	echo esc_attr($result->error);
       	}
	}
	if($temp == 1){
 		$msg = esc_attr("Discount remove successfully");
    }
    return $msg;
}

function vlcr_deleteRecording($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$temp = 0;
	$msg="";
	foreach ($data['discountid'] as $value) {
		$data1['apikey'] = sanitize_key($key);
		$data1['id'] = sanitize_text_field($value);
		$data1['task'] = sanitize_text_field('removeclassrecording');

		$result = (object)$vc_obj->vlcr_get_curl_info($data1);
			 
		if(strtolower($result->status) == 'ok'){
           $temp = 1;
	 	}
       	if($result->status == 'error'){
           	$msg = esc_attr($result->error);
       	}
	}
	if($temp == 1){
		$msg = esc_attr("Discount remove successfully");
	}
	return $msg;
}

function vlcr_deleteClass($return){
	global $key,$base_url;
	$vc_obj = new vlcr_class();
	$data = $_REQUEST;
	$msg="";
	if(is_array($data['cid']) && count($data['cid'])){
		foreach ($data['cid'] as $value) {			 
			$data1['apikey'] = sanitize_key($key);
			$data1['cid'] = sanitize_text_field($value);
			$data1['task'] = sanitize_text_field('removeclass');
			$result = (object)$vc_obj->vlcr_get_curl_info($data1);
			 
			if(strtolower($result->status) == 'ok'){
			   $temp = 1;
			 }
			 if($result->status == 'error'){
				$msg = esc_attr($result->error);
			 }
		}
	} else if(isset($data['cid']) && !empty($data['cid'])){
		$data1['apikey'] = sanitize_key($key);
		$data1['cid'] = sanitize_text_field($data['cid']);
		$data1['task'] = sanitize_text_field('removeclass');
		$result = (object)$vc_obj->vlcr_get_curl_info($data1);
		 
		if(strtolower($result->status) == 'ok'){
		   $temp = 1;
		 }
		 if($result->status == 'error'){
			 $msg = esc_attr($result->error);
		 }
	}
	if($temp == 1){
		$msg = esc_attr("class remove successfully");
	}
	return $msg;
}
?>