<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category VLCR ADMIN
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class vlcr_class{

   function vlcr_get_curl_info($data) {
    global $wpdb; 

    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}virtualclassroom_settings");

    // Basic sanity check
    if ( empty($row) || empty($row->braincert_base_url) || empty($row->braincert_api_key) ) {
        error_log('Missing BrainCert API key or base URL in database.');
        return array('error' => 'Plugin misconfigured. Please check BrainCert settings.');
    }

    $key = $row->braincert_api_key;
    $base_url = trim($row->braincert_base_url); // trim just in case

    // Validate URL format
    if ( ! filter_var($base_url, FILTER_VALIDATE_URL) ) {
        error_log("Invalid BrainCert base URL: $base_url");
        return array('error' => 'Invalid BrainCert API URL.');
    }

    $data['apikey'] = $key;

    try {
        $response = Requests::post($base_url, array(), $data);
        $result = $response->body;
    } catch (Exception $e) {
        error_log('API request failed: ' . $e->getMessage());
        return array('error' => 'API request failed.');
    }

    if (isset($data['task']) && $data['task'] === "validatecoupon") {
        ob_clean();
        ob_start();
        return $result;
    }

    return json_decode($result, true);
}

    function vlcr_setting_check(){
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
        if(!$row){
            return 1;
        }else{
            return 0;
        }
    }
    function vlcr_get_usergroups(){
      global $wpdb;
      $groups = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'groups_group',''));
      return $groups;
    }

    function vlcr_get_class_groups($class_id){
      if($class_id>0){
        global $wpdb;
        $groups = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."virtualclassroom_user_assign_group WHERE `class_id` = %d",array($class_id)));
        return $groups;
      }
      return;
    }

    function vlcr_get_loginusergroup(){
      global $wpdb;
      include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
      if (is_plugin_active('groups/groups.php' ) ) {
        $groups = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."groups_user_group WHERE `user_id` = %d",array(get_current_user_id())));
        $classlist_arr= array();
        foreach ($groups as $group) {
          if($group->group_id>0){
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
        }
      }else{
        return $classlist_arr='';
      }   
  }

  public function vlcr_get_paymentInfo(){
      $data['task'] = 'getPaymentInfo';
      $result = $this->vlcr_get_curl_info($data);
      return $result;
  }
   
  function vlcr_get_class_checkout(){
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));

    $key = $row->braincert_api_key;
    $base_url = $row->braincert_base_url;
    $p_data = $_POST;

      $data['task'] = sanitize_text_field('apiclasspayment');
      $data['apikey'] = sanitize_key($key);
      $data['class_id'] = sanitize_text_field($p_data['class_id']);
      $data['price_id'] = sanitize_text_field($p_data['price_id']);
      $data['cancelUrl'] = base64_encode($p_data['cancelUrl']);
      $data['returnUrl'] = base64_encode($p_data['returnUrl']);
      $data['card_holder_name'] = sanitize_text_field($p_data['card_holder_name']);
      $data['card_number'] = sanitize_text_field($p_data['card_number']);
      $data['card_cvc'] = sanitize_text_field($p_data['card_cvc']);
      $data['card_exp_month'] = sanitize_text_field($p_data['card_expiry_month']);
      $data['card_exp_year'] = sanitize_text_field($p_data['card_expiry_year']);
      $data['student_email'] = sanitize_text_field($p_data['student_email']);
      
      $response = Requests::post($base_url, array(), $data );
      $result = $response->body; 
      ob_clean();
      ob_start();
      echo esc_attr($result);
      exit;
    }
    public function vlcr_get_priceList($class_id){
      $data['class_id'] = sanitize_text_field($class_id);
      $data['task'] = sanitize_text_field('listSchemes');
      $result = $this->vlcr_get_curl_info($data);
      return $result;
   }

    function vlcr_get_class_search_teacher(){
      ob_clean();
      global $wpdb;
      $p_data = $_POST;
      $user_list = $this->vlcr_teacherlist($p_data['search_txt'],1000000,$p_data['search_type']);
      ?>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
        </tr>
      </thead>
      <tfoot>   
        <tr>
          <td colspan="12"></td>
        </tr>
      </tfoot>
      <tbody>    
        <?php  $i=0;
        foreach ( $user_list as $user ) { $i++ ?>
          <tr class="row<?php echo esc_attr($i) % 2; ?>">
            <td><input name="chooseselector" name='user_id' type='radio' value='<?php echo esc_html( $user->ID ) ?>'> </td> 
            <td class='name' id='name_<?php echo esc_html( $user->ID ) ?>' ><?php echo esc_html( $user->user_nicename ) ?></td>
            <td class='email' id='email_<?php echo esc_attr($i);?>' ><?php echo esc_html( $user->user_email ) ?><input type="hidden" id="thumb_<?php echo esc_html( $user->ID ) ?>" value="<?php echo $exist_avatar_fun==1 ? esc_url(get_avatar_url($user->ID)) : esc_url($default_path);?>" /></td> 
            <td><?php echo $user->is_teacher==1 ? "Teacher" : "Student"; ?></td>
          </tr>
        <?php } ?> 
      </tbody> 
      <?php        
      exit;
   } 
    function vlcr_get_groupsdata($data){
      $gid = implode(',', $data['gid']);
      global $wpdb;

      $class_id = $data['id'];
      $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."virtualclassroom_user_assign_group WHERE `class_id` = %d",array($class_id)));
      foreach ($data['gid'] as $key => $value) {
          if($value>0 && $class_id>0){
            $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."virtualclassroom_user_assign_group (class_id,  group_id) VALUES (%d,%d)",array($class_id,$value)));
          }
      }
      $groups = $wpdb->get_results($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."groups_user_group WHERE `group_id` = %s",array($gid)));
      $email=array();
      foreach ($groups as $user) {
        $userdetail = $wpdb->get_results($wpdb->prepare("SELECT user_email FROM ".$wpdb->prefix."users WHERE `id` = %d",array($user->user_id)));

        foreach ($userdetail as $udetail) {
          $email['to'].=$udetail->user_email.",";
        }
      }
      $data1 = array();
      $data1['id'] = sanitize_text_field($data['id']);
      $data1['to'] = sanitize_text_field(rtrim($email['to'],','));
      $this->vlcr_invite_by_email($data1);
    }

    function vlcr_listclass($search,$limit){
      $data['task'] = sanitize_text_field('listclass');
      $data['apikey'] = sanitize_text_field($key);

      if(isset($search)){
        $data['search'] = sanitize_text_field($search);    
      }
      $page1 = isset( $_GET['page1'] ) ? sanitize_text_field( wp_unslash( $_GET['page1'] ) ) : '';

      @$page = $page1;
      if($page) 
        $start = ($page - 1) * $limit;          //first item to display on this page
      else
        $start = 0; 
                    
      $data['limitstart'] = sanitize_text_field($start);
      $data['limit'] = sanitize_text_field($limit);
      $result = $this->vlcr_get_curl_info($data);
      return $result;
    }

    function vlcr_learnerPreview($id){

        $data['task'] = sanitize_text_field('getclass');
        $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = $id;
        $result = $this->vlcr_get_curl_info($data);

        $task = isset( $_REQUEST['task'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['task'] ) ) : '';
        if($task == "returnpayment"){
            $class_id = isset( $_REQUEST['class_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['class_id'] ) ) : '';
            $amount = isset( $_REQUEST['amount'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['amount'] ) ) : '';
            $payment_mode = isset( $_REQUEST['payment_mode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['payment_mode'] ) ) : '';
            $pcid = isset( $_REQUEST['pcid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pcid'] ) ) : '';
            $page_id = isset( $_REQUEST['page_id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page_id'] ) ) : '';
            $wpdb->query($wpdb->prepare("INSERT INTO ".$wpdb->prefix."virtualclassroom_purchase (class_id,  mc_gross, payer_id,payment_mode,date_puchased) VALUES (%d,%s,%d,%s,%s)",array($class_id,$amount,get_current_user_id(),$payment_mode,now())));
            $return =  get_permalink($page_id).'?pcid='.$pcid;
            header('Location:'.$return);
           }
        return $result; 
    }
    function vlcr_class_launch_btn($item){
      global $wpdb;
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
      if(!$row){
        echo "Please setup API key and URL";
        return;
      }
      $key = $row->braincert_api_key;
      $base_url = $row->braincert_base_url;
      $isteacher  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %d",array(get_current_user_id())));

      $enrolled  = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM ".$wpdb->prefix."virtualclassroom_purchase WHERE `class_id` = %d AND `payer_id`= %d",array($item['id'],get_current_user_id())));
      if($item['ispaid'] && $item['status']!="Past" && !$enrolled && $isteacher == 0){?>
        <button class="btn btn-danger btn-sm" onclick="buyingbtn(<?php echo esc_attr($item['id']) ?>); return false;" id=""><h4  style="margin: 0px;" class=" "><i class="icon-shopping-cart icon-white"></i>Buy</h4></button>
      <?php
      }

      if(($item['status'] == "Live" && $enrolled) || $item['ispaid']==0 || $isteacher == 1){
        $uuname=$item['uuname'];
        if($uuname == ''){
          $uuname =$current_user->display_name;
        }
                    
        $current_user = wp_get_current_user();
        $data1['userId'] = sanitize_text_field($current_user->ID);
        $data1['userName'] = sanitize_text_field($uuname);
        $titles = sanitize_text_field($item['title']);
        $data1['lessonName'] = $titles;
        $data1['courseName'] = $titles;
           
        $is_tchr  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %d",array($current_user->ID)));
        if ($is_tchr == 1)  { $data1['isTeacher'] = 1; }
        else {  $data1['isTeacher'] = 0;  }
        $data1['task'] = sanitize_text_field('getclasslaunch');
        $data1['apikey'] = sanitize_text_field($key);
        $data1['class_id'] = sanitize_text_field($item['id']);
        $launchurl = (object)$this->vlcr_get_curl_info($data1);
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
  
    function vlcr_get_user_info($id) {
      global $wpdb;
      $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."users WHERE `ID` = %d",array($id)));
      return $row;
    }
    function vlcr_instructorPreview($id){

        $data['task'] = sanitize_text_field('getclass');
        $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = $id;
        $result = $this->vlcr_get_curl_info($data);
        return $result; 
    }

    function vlcr_addclass_acl($data){
      $class_id = implode(',', $data['class_id']);
      $group_id =$data['usergroup'];
      global $wpdb;

      $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."virtualclassroom_acl WHERE `group_id` = %d",array($group_id)));

      $wpdb->insert($wpdb->prefix."virtualclassroom_acl", 
                            array( 
                                'id' => '', 
                                'group_id' => sanitize_text_field($group_id),
                                'class_id' => sanitize_text_field($class_id)
                                
                            ), 
                            array('%d','%d','%s') 
                        );
      if($class_id == ''){
        echo '<div class="error">
        <p><strong>ERROR</strong>: Please Select Class.</p></div>';
      }else{
        echo '<div id="message" class="updated notice is-dismissible"><p>Added successfully</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
      }
    }
    function vlcr_email_temp_setting_save($data){
      $class_id = $data['class_id'];
      global $wpdb;
      $tblname = $wpdb->prefix . 'virtualclassroom_email_template_settings';
      $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."virtualclassroom_email_template_settings WHERE `class_id` = %d",array($class_id)));
      if($row->id){
        $wpdb->update($tblname,array('email_template_subject' => $data['email_template_subject'],'email_template_body' => $data['email_template_body']),array('id'=> $row->id));
      }else{
        $wpdb->insert( $tblname, 
                            array( 
                                'id' => '', 
                                'email_template_subject' => sanitize_text_field($data['email_template_subject']),
                                'email_template_body' =>sanitize_text_field($data['email_template_body']),
                                'class_id'=>sanitize_text_field($class_id) 
                            ) 
                        );
      }
      return $class_id;
    }
    function vlcr_invite_by_email($data){

        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
        $template_settings = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".$wpdb->prefix."virtualclassroom_email_template_settings WHERE `class_id` = %d",array($data['id'])));
        $key = $row->braincert_api_key;
        $base_url = $row->braincert_base_url;
        $pageid = $row->inv_email_page;

        $data['task'] = sanitize_text_field('getclass');
        $data['apikey'] = sanitize_text_field($key);

        $data['class_id'] = sanitize_text_field($data['id']);

        if($template_settings->id){
          $data['subject'] = $template_settings->email_template_subject;
        }else if(!$data['subject']){
          $data['subject'] ='Live Class Invitation';
        }
       
        $subject =$data['subject'];

        $classroom = $this->vlcr_get_curl_info($data);

        if($data['email']){
         $data['to'] = implode(',', $data['email']);
        }
      
        $to = preg_split("/\\r\\n|\\r|\\n/", $data['to']);
        $class_id = sanitize_text_field($data['id']);

        for($i=0;$i<count($to);$i++){      
            $receiver = trim($to[$i]);
            if( $receiver == '') continue;
            $uid = uniqid(md5(wp_rand()), true);

            $joinclassurl = get_permalink($row->class_detail_page).'?pcid='.$class_id;
            $current_user = wp_get_current_user();

            if($receiver){ 
              $wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix."virtualclassroom_shared_users WHERE `class_id` = %d AND `email` = %s",array($class_id,$receiver))); 
            }

            $objdate = gmdate('Y-m-d H:i:s');
            $q =   $wpdb->insert( 
                      $wpdb->prefix."virtualclassroom_shared_users", 
                        array( 
                            'class_id' => $class_id, 
                            'name' => '',
                            'email'=> $receiver,
                            'uid' =>$uid,
                            'date'=>$objdate
                        ), 
                        array('%d','%s','%s','%s','%s') 
                    );

                $content="";        
                if($data['message']){
                  $content= $data['message'];
                 }
                if($template_settings->email_template_body){
                $content.= $template_settings->email_template_body;
               }else{
                $content.= '<p>{owner_name} has invited you to join the Live Class at BrainCert.</p>
                            <p>Class Name: {class_name}</p>
                            <p>Date/Time: {class_date_time}</p>
                            <p>Time Zone: {class_time_zone}</p>
                            <p>Duration: {class_duration}</p>
                            <p>Click on the link below to join the class:</p>
                            <p>{class_join_url}</p>
                            <p> Thank you.</p>';  
               }
               $class_join_url = '<a href="'.$joinclassurl.'">'.$joinclassurl.'</a>';
               $content = str_replace("{owner_name}",$current_user->display_name,$content);
               $content = str_replace("{class_name}",$classroom[0]['title'],$content);
               $content = str_replace("{class_date_time}",gmdate('l F j, Y',strtotime($classroom[0]['date'])). $classroom[0]['start_time'] .$classroom[0]['end_time'],$content);
               $content = str_replace("{class_time_zone}",$classroom[0]['timezone_label'],$content);
               $content = str_replace("{class_duration}",$classroom[0]['duration']/60,$content);
               $content = str_replace("{class_join_url}",$class_join_url,$content);
                 
                $email_body =  '<div>'.nl2br($content).'</div>';
                
               $headers = array('Content-Type: text/html; charset=UTF-8');
             wp_mail($receiver, $subject, $email_body, $headers );

            echo '<div id="" class="updated notice is-dismissible">
                    <p><strong>Invitation send successfully.</strong></p>
                  </div>';
        }
    }

    function vlcr_attendanceReport($id){
        $data['task'] = sanitize_text_field('getclassreport');
        $data['apikey'] = sanitize_text_field($key);
        $data['classId'] = $id;
        $result = $this->vlcr_get_curl_info($data);
        return $result; 
    }

    function vlcr_listdiscount($search,$limit,$cid){
        $data['task'] = sanitize_text_field('listdiscount');
        $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = sanitize_text_field($cid);
        if(isset($search)){
            $data['search'] = sanitize_text_field($search);    
        }
        $result = $this->vlcr_get_curl_info($data);
        return $result; 
    }
     function vlcr_listprice($search,$limit,$cid){
        $data['task'] = sanitize_text_field('listSchemes');
        $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = sanitize_text_field($cid);
        if(isset($search)){
            $data['search'] = sanitize_text_field($search);    
        }
        $result = $this->vlcr_get_curl_info($data);
        return $result; 
    }
    function vlcr_listrecording($search,$limit,$cid){
        $data['task'] = sanitize_text_field('getclassrecording');
        $data['apikey'] = sanitize_text_field($key);
        $data['class_id'] = sanitize_text_field($cid);
        if(isset($search)){
            $data['search'] = sanitize_text_field($search);    
        }
        $result = $this->vlcr_get_curl_info($data);
        return $result; 
    }

    function vlcr_view_class_recording(){
      $filename = isset( $_POST['filename'] ) ? sanitize_text_field( wp_unslash( $_POST['filename'] ) ) : '';
      $videourl = $filename;
      ?>
      <div class="video-area">
      <video
          id="my-video"
          class="video-js vjs-default-skin"
          controls
          width="800" height="350"
          >
      </video>
      </div>
 
<script type="text/javascript">
 (function ($) {
        $(document).ready(function () {
            var player = videojs('my-video', {
                controls: true,
                sources: [{src: '<?php echo esc_url($videourl);?>', type: 'video/mp4'}],
                techOrder: ['youtube', 'html5']
            });
       });
    })(jQuery);
</script>
<?php
exit;
    }
    function vlcr_teacherlist($filter,$limit,$search_type=''){
      global $wpdb;
      $list_users  = $wpdb->get_results($wpdb->prepare("SELECT usrs.`ID`,usrs.`user_nicename`,usrs.`user_login`,usrs.`user_email`,tchr.`is_teacher` FROM ".$wpdb->prefix."users as usrs LEFT JOIN ".$wpdb->prefix."virtualclassroom_teacher as tchr ON tchr.user_id = usrs.id WHERE usrs.ID>0")); 
      return $list_users;
    }


    function vlcr_total_teacherlist($filter){
        global $wpdb;
        $list_users  = count($wpdb->get_results($wpdb->prepare("SELECT users.`ID` FROM ".$wpdb->prefix."users as users LEFT JOIN ".$wpdb->prefix."virtualclassroom_teacher as tchr ON tchr.user_id = users.id WHERE ( user_login like %s  OR user_email like %s OR user_nicename like %s ) GROUP BY users.id",array("%".$filter."%","%".$filter."%","%".$filter."%"))));
        return $list_users;
    }
    function vlcr_getplan(){
      $data['task'] = sanitize_text_field('getplan');
      $result = $this->vlcr_get_curl_info($data);
      return $result;
    }
    function vlcr_getservers(){
      $data1['task'] = sanitize_text_field('getservers');
      $result = $this->vlcr_get_curl_info($data1);  
      return $result;
     }
     function vlcr_timezoneList(){
      $data1['task'] = sanitize_text_field('getTimezoneList');
      $result = $this->vlcr_get_curl_info($data1);  
      return $result;
     }
     function vlcr_class_validatecoupon(){
        $p_data = $_POST;
        $data['task'] = sanitize_text_field('validatecoupon');
        $data['class_id'] = sanitize_text_field($p_data['class_id']);
        $data['coupon_code'] = sanitize_text_field($p_data['coupon_code']);
        $result = $this->vlcr_get_curl_info($data);
        echo esc_attr($result);
        exit;
      } 
     
    function vlcr_class_detail($cid){
      if(isset($cid)){
        $data['class_id'] = sanitize_text_field($cid);
        $data['task'] = sanitize_text_field('getclass');
        $result = $this->vlcr_get_curl_info($data);  
        if($result){
          if(is_array($result)){
            $classVal = $result[0]; 
          } else {
            $classVal = $result;
          }
                 return $classVal; 
        }
        
      }
      return false;
    }
    function vlcr_price_detail($priceid,$cid){
      if(isset($priceid)){
        $data1['class_id'] = sanitize_text_field($cid);
        $data1['price_id'] = sanitize_text_field($priceid);
        $data1['task'] = sanitize_text_field('classprice');
        $result = $this->vlcr_get_curl_info($data1);  
      if($result){
        if(is_array($result)){
          $priceVal = $result[0]; 
        } else {
          $priceVal = $result;
        }
      }
      return $priceVal;
    }
    return false;
    }
    function vlcr_discount_detail($discountid,$cid){
      if(isset($discountid)){
        $data1['class_id'] = sanitize_text_field($cid);
        $data1['discount_id'] = sanitize_text_field($discountid);
        $data1['task'] = sanitize_text_field('classdiscount');
        $result = $this->vlcr_get_curl_info($data1);
      if($result){
        if(is_array($result)){
          $discountVal = $result[0];  
        } else {
          $discountVal = $result;
        }
      }
      return $discountVal;
    }
    return false;
    }
    
    function vlcr_purchaselist($filter,$limit){
      global $wpdb;
      if(trim($filter)!=''){
        $list_purchase  = $wpdb->get_results($wpdb->prepare("SELECT p.*, u.`user_login` as uname FROM ".$wpdb->prefix."virtualclassroom_purchase p LEFT JOIN ".$wpdb->prefix."users u ON u.id = p.payer_id WHERE p.`payer_id`>0 AND u.`user_login` like %s ",array("%".$filter."%")));
      }else{
        $list_purchase  = $wpdb->get_results($wpdb->prepare("SELECT p.*, u.`user_login` as uname FROM ".$wpdb->prefix."virtualclassroom_purchase p LEFT JOIN ".$wpdb->prefix."users u ON u.id = p.payer_id WHERE p.payer_id>0"));  
      }
      return $list_purchase;
    }
    function vlcr_total_purchaselist($filter){
        global $wpdb;
        $total_purchase  = count($wpdb->get_results($wpdb->prepare("SELECT p.`id` FROM ".$wpdb->prefix."virtualclassroom_purchase p LEFT JOIN ".$wpdb->prefix."users u ON u.id = p.payer_id WHERE u.user_login like %d ",array("%".$filter."%"))));
        return $total_purchase;
    }
    function vlcr_pagination_teacherlist($targetpage,$total_count,$limit){
        $lastpage = ceil($total_count/$limit);        //lastpage is = total pages / items per page, rounded up.
        $page1 = isset( $_GET['page1'] ) ? sanitize_text_field( wp_unslash( $_GET['page1'] ) ) : '';
        @$page = $page1;
        if ($page == 0) $page = 1;                  //if no page var is given, default to 1.
        $prev = $page - 1;                          //previous page is page - 1
        $next = $page + 1;                          //next page is page + 1
        $lpm1 = $lastpage - 1;                      //last page minus 1
        $pagination = "";
        $adjacents = "";
        if($lastpage > 1)
        {   
            $pagination .= "<div class=\"pagination pagination-toolbar\"><ul class=\"pagination-list\">";
            //previous button
            if ($page > 1) 
                $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$prev.''))."'>previous</a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">previous</span></li>";  
            
            //pages 
            if ((int)$lastpage < 7 + ((int)$adjacents * 2))   //not enough pages to bother breaking it up
            {   
                for ($counter = 1; $counter <= $lastpage; $counter++)
                {
                    if ($counter == $page)
                        $pagination.= "<li><span class=\"current\">$counter</span></li>";
                    else
                        $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$counter.''))."'>$counter</a></li>";                  
                }
            }
            elseif($lastpage > 5 + ((int)$adjacents * 2))    //enough pages to hide some
            {
                //close to beginning; only hide later pages
                if($page < 1 + ((int)$adjacents * 2))        
                {
                    for ($counter = 1; $counter < 4 + ((int)$adjacents * 2); $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$counter.''))."'>$counter</a></li>";                  
                    }
                    $pagination.= "...";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$lpm1.''))."'>$lpm1</a></li>";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$lastpage.''))."'>$lastpage</a><li>";     
                }
                //in middle; hide some front(int)me back
                elseif($lastpage - ((int)$adjacents * 2) > $page && $page > ((int)$adjacents * 2))
                {
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1=1'))."'>1</a></li>";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1=2'))."'>2</a></li>";
                    $pagination.= "...";
                    for ($counter = $page - (int)$adjacents; $counter <= $page + (int)$adjacents; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$counter.''))."'>$counter</a></li>";                  
                    }
                    $pagination.= "...";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$lpm1.''))."'>$lpm1</a></li>";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$lastpage.''))."'>$lastpage</a></li>";        
                }
                //close to end; only hide early pages
                else
                {
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1=1'))."'>1</a></li>";
                    $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1=2'))."'>2</a></li>";
                    $pagination.= "...";
                    for ($counter = $lastpage - (2 + ((int)$adjacents * 2)); $counter <= $lastpage; $counter++)
                    {
                        if ($counter == $page)
                            $pagination.= "<li><span class=\"current\">$counter</span></li>";
                        else
                            $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$counter.''))."'>$counter</a></li>";                  
                    }
                }
            }
            
            //next button
            if ($page < $counter - 1) 
                $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$next.''))."'>next </a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">next</span></li>";
            $pagination.= "</ul></div>\n";      
        }
        return $pagination;
    }
    function vlcr_admin_pagination($targetpage,$result,$limit){

        $total_records = $result['total'];
        $page1 = isset( $_GET['page1'] ) ? sanitize_text_field( wp_unslash( $_GET['page1'] ) ) : '';
        $page = intval( $page1 );

        if ($page == 0 || $page=="") $page = 1;                  //if no page var is given, default to 1.
        $prev = $page - 1;                          //previous page is page - 1
        $next = $page + 1;                          //next page is page + 1
        $lastpage = ceil($total_records/$limit);        //lastpage is = total pages / items per page, rounded up.

        $lpm1 = $lastpage - 1;                      //last page minus 1
        $pagination = "";
        $adjacents = "";

        if($lastpage > 1)
        {   
            $pagination .= "<div class=\"pagination pagination-toolbar\"><ul class=\"pagination-list\">";

            $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1=1'))."' style=\"border-left: 1px solid #dddddd;\">First</a></li>";
            //previous button
            if ($page > 1) 
                $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$prev.''))."'>Previous</a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">previous</span></li>";  

             $temp=0; 
            for($k=$page;$k<=$lastpage;$k++){
              $temp++;

              if($temp<10){
                if ($k != $page){
                  $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$k.''))."'>$k</a></li>";  
                } else {
                  $pagination.= "<li><span class=\"current\">$k</span></li>";
                }
              }else{
                break;
              }
            }
            //next button
            if ($page < $lastpage) 
                $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$next.''))."'>Next </a></li>";
            else
                $pagination.= "<li><span class=\"disabled\">next</span></li>";
              $pagination.= "<li><a href='".wp_nonce_url(admin_url(''.$targetpage.'&page1='.$lastpage))."'>Last</a></li>";
            $pagination.= "</ul></div>\n";      
        }
        return $pagination;
    }
}