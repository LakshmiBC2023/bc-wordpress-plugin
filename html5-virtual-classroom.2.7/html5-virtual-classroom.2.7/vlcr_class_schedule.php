<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Edit listing
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_enqueue_script("vlcr.jquery-ui.min",VC_URL.'/js/vlcr.jquery-ui.min.js');
 wp_enqueue_script('jquery-ui-datepicker'); 
wp_enqueue_script('jquery.timepicker',VC_URL.'/js/jquery.timepicker.js');
wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');
wp_enqueue_script('tag-it',VC_URL.'/js/tag-it.js');
wp_enqueue_style( 'jquery-ui',VC_URL.'/css/vlcr-calendar.css');
wp_enqueue_style( 'jquery.timepicker', VC_URL.'/css/jquery.timepicker.css');
wp_enqueue_style( 'jquery.tagit', VC_URL.'/css/jquery.tagit.css');

if(isset($_REQUEST['task'])){
    include_once('vlcr_action_task.php');   
}
$vc_obj = new vlcr_class();
$current_user = wp_get_current_user();
$plan = (object)$vc_obj->vlcr_getplan();
$getservers = $vc_obj->vlcr_getservers();
$instructor_list = get_users();
$timezoneList = (object)$vc_obj->vlcr_timezoneList();

$cid = '';
if(isset($_REQUEST['cid'])){
    if(is_array($_REQUEST['cid'])){
        $cid = $_REQUEST['cid'][0];
    } else {
        $cid = $_REQUEST['cid'];    
    }
}   
$classVal = (object)$vc_obj->vlcr_class_detail($cid);
 if(isset($classVal->created_by)){
    $current_user =get_userdata( $classVal->created_by );
}
$exist_avatar_fun=0;
if(function_exists("get_avatar_url")){
$exist_avatar_fun=1;
}
$default_path = "http://0.gravatar.com/avatar/ad516503a11cd5ca435acc9bb6523536";
global $wpdb;
$settings = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
$isteacher  = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE user_id = %d",array(get_current_user_id())));

$is_super_admin = is_super_admin(get_current_user_id());
if($is_super_admin==''){
    if($settings->is_schedule_class==0){
        echo "<div class='vc-alert-danger'>You are not authorise to view this page</div>";
        return;
    }
    if($isteacher==''){
        echo "<div class='vc-alert-danger'>You are not authorise to view this page</div>";
        return;    
    }
}
 ?>

<style>
input.button.button-primary.button-large
{
width: 50%;
border-radius: 5px;
margin-left: 10px !important;
margin-top: 20px !important;
margin-bottom: 10px !important;
}
.schedule_title
{
font-size: 30px;
font-weight: 700;
font-family: serif;
display: flex;
justify-content:center;
color: black;
margin-bottom: 30px;
margin-top: 20px;
}

@media(max-width: 768px)
{
    #adminForm
    {
        width: 180% !important; 
        margin-left: -16px !important;
    }   
 }
 @media (max-width: 430px){
#adminForm {
    width: 110% !important;
    margin-left: -17px !important;
}
#location_id,#title,#timezone{
    width: 100% !important;
}
 }

 @media (max-width: 744px){#adminForm {width: 106% !important;}}
</style>
<?php //wp_enqueue_script('vlcr.jquery.min', VC_URL. '/js/vlcr.jquery.min.js');?>
<form class="form-horizontal form-validate vlcr_class_schedule" id="adminForm" action="" method="post"  enctype="multipart/form-data" style="box-shadow: 0px 0px 4px 1px #161010a1;border-radius: 10px;width: 80%;margin-left: 100px;">
<h2 class="schedule_title">Schedule</h2>
    <div class="control-group">
            <label class="span1 hasTip"  title="Class end time">Set Location:</label>
            <div class="controls">
            <select class="form-control valid" name="location_id" id="location_id" style="width: 60%;">

            <option value="0" <?php if($classVal->isRegion == 0){ echo "selected=true";}?> >Auto select nearest datacenter region</option>

                <?php foreach ($getservers as $key => $server) {
                $server  = (object)$server;
                 ?>
                <option value="<?php echo esc_html($server->id);?>" <?php if(@$server->free_usage == 1 && $plan->group_id==5){ echo "disabled";} if(@$classVal->isRegion == $server->id){ echo "selected=true";}?>><?php echo esc_html($server->name);?></option>
                <?php } ?>
                </option>
            </select>
            </div>
        </div>
     <div class="control-group" style="line-height: 40px;">
            <label for="title" class="span1 hasTip" title="Classroom Title">Title:</label>
            <div class="controls">
                <input type="text" placeholder="Title" id="title" style="width: 60%" name="title" value="<?php echo esc_html($classVal->title)?>">
            </div>
     </div>
     <div class="control-group" style="line-height: 40px;">
            <label for="date" class="span1 hasTip" title="Class date">Date:</label>
            <div class="controls">
            <input type="text" placeholder="(yyyy-mm-dd)" id="datepicker" name="date" value="<?php echo esc_html($classVal->date)?>" style="width: 150px;">
            </div>
     </div>
        <div class="control-group" style="line-height: 40px;">
            <label for="from" class="span1 hasTip" title="Class start time">From:</label>
            <div class="controls">
            <input type="text" data-format="hh:mm A" placeholder="From-(hh:mm)" id="class_start_time" name="start_time" value="<?php echo esc_html($classVal->start_time)?>" style="width: 150px;">
            </div>
         </div>
        <div class="control-group" style="line-height: 40px;">
            <label class="span1 hasTip"  title="Class end time">To:</label>
            <div class="controls">
            <input type="text" data-format="hh:mm A" placeholder="To-(hh:mm)" id="class_end_time" name="end_time" value="<?php echo esc_html($classVal->end_time)?>" style="width: 150px;">
            </div>
        </div>
        <div class="control-group">
                <label class="span1 hasTip"  title="timezone">Time Zone:</label>
                <div class="controls">
                <select name="timezone" id="timezone" class="valid" style="width: 60%;">
                <?php foreach ($timezoneList as $timezone) {  ?>  
                    <option title="<?php echo esc_attr($timezone['label']); ?>" value="<?php echo esc_attr($timezone['id']); ?>" <?php if(@$classVal->timezone == $timezone['id']) echo 'selected="selected"';?> ><?php echo esc_attr($timezone['title']); ?></option>

                <?php } ?>
                </select>
                </div>
        </div>
       <div class="control-group">
            <label class="span1 hasTip"  title="Recurring class">Recurring Class:</label>
            <div class="controls">
            <input type="radio" id="is_recurring_yes" name="is_recurring" value="1" <?php if(@$classVal->repeat > 0) echo 'checked="checked"'?>>Yes
            <input type="radio" id="is_recurring_no"  name="is_recurring" value="0" <?php if(@$classVal->repeat == 0 || !isset($classVal)) echo 'checked="checked"'?>>No
            </div>
        </div>
        <div class="control-group recurring_class">
            <label class="span1 hasTip"  title="Class end time">When class repeats:</label>
            <div class="controls">
            <select name="repeat" style="display: inline-block;width: 60%;" id="repeats">
            <option value="" >Select when class repeats</option>
            <option value="1" <?php if(@$classVal->repeat == 1) echo 'selected="selected"'?>>Daily (all 7 days)</option>
            <option value="2" <?php if(@$classVal->repeat == 2) echo 'selected="selected"'?>>6 Days(Mon-Sat)</option>
            <option value="3" <?php if(@$classVal->repeat == 3) echo 'selected="selected"'?>>5 Days(Mon-Fri)</option>
            <option value="4" <?php if(@$classVal->repeat == 4) echo 'selected="selected"'?>>Weekly</option>
            <option value="5" <?php if(@$classVal->repeat == 5) echo 'selected="selected"'?>>Once every month</option>
            <option value="6" <?php if(@$classVal->repeat == 6) echo 'selected="selected"'?>>On selected days</option>
            </select>
            </div>
        </div>
        <?php 
        if(@$classVal->repeat=='6'){
            $su_active= "";
            $su_checked= "";
            $mo_active= "";
            $mo_checked= "";
            $tue_active = "";
            $tue_checked = "";
            $wed_active = "";
            $wed_checked = "";
            $thu_active = "";
            $thu_checked = "";
            $fri_active = "";
            $fri_checked = "";
            $sat_active = "";
            $sat_checked = "";

            $classVal->weekdays = explode(',', $classVal->weekdays);
            if(in_array("1", $classVal->weekdays))
            {
                $su_active = "class='active'";
                $su_checked = "checked='checked'";
            }
            if(in_array("2", $classVal->weekdays))
            {
                $mo_active = "class='active'";
                $mo_checked = "checked='checked'";
            }
            if(in_array("3", $classVal->weekdays))
            {
                $tue_active = "class='active'";
                $tue_checked = "checked='checked'";
            }
            if(in_array("4", $classVal->weekdays))
            {
                $wed_active = "class='active'";
                $wed_checked = "checked='checked'";
            }
            if(in_array("5",$classVal->weekdays))
            {
                $thu_active = "class='active'";
                $thu_checked = "checked='checked'";
            }
            if(in_array("6", $classVal->weekdays))
            {
                $fri_active = "class='active'";
                $fri_checked = "checked='checked'";
            }
            if(in_array("7", $classVal->weekdays))
            {
                $sat_active = "class='active'";
                $sat_checked = "checked='checked'";
            }
    ?>
    <style type="text/css">
    .weeklytotaldays{
        display: block;
    }
    </style>
<?php
}
?>
<div class="control-group weeklytotaldays">
                <label class="control-label"></label>
                <div class="weekdays_label">
                <label for="su" <?php echo esc_attr($su_active);?> >
                    <input id="su" onclick="setweekday(this);" name="weekdays[]" type="checkbox" value="1" style="display:none;" <?php echo esc_attr($su_checked);?> > Sun
                </label>

                <label for="mo" <?php echo esc_attr($mo_active); ?> >
                    <input id="mo"  onclick="setweekday(this);" name="weekdays[]" type="checkbox" value="2" style="display:none;" <?php echo esc_attr($mo_checked)?> > Mon
                </label>

                <label for="tue" <?php echo esc_attr($tue_active); ?> >
                    <input id="tue" onclick="setweekday(this);" name="weekdays[]"  type="checkbox" value="3" style="display:none;" <?php echo esc_attr($tue_checked); ?> > Tue
                </label>

                <label for="wed" <?php echo esc_attr($wed_active); ?> >
                    <input id="wed" onclick="setweekday(this);" name="weekdays[]" type="checkbox" value="4" style="display:none;" <?php echo esc_attr($wed_checked); ?> > Wed
                </label>

                <label for="thu" <?php echo esc_attr($thu_active); ?> >
                    <input id="thu"  onclick="setweekday(this);" name="weekdays[]" type="checkbox" value="5" style="display:none;" <?php echo esc_attr($thu_checked); ?> > Thu
                </label>

                <label for="fri" <?php echo esc_attr($fri_active); ?>>
                    <input id="fri"  onclick="setweekday(this);" name="weekdays[]"  type="checkbox" value="6" style="display:none;" <?php echo esc_attr($fri_checked); ?> > Fri
                </label>

                <label for="sat" <?php echo esc_attr($sat_active); ?>>
                    <input id="sat"  onclick="setweekday(this);" name="weekdays[]"  type="checkbox" value="7" style="display:none;" <?php echo esc_attr($sat_checked); ?> > Sat
                </label>
                </div>
             </div> 
        
        <div class="control-group recurring_class">
        <label class="control-label" style="float: left; text-align: left; margin-left: 20px; width: 129px;">Ends:</label>
            <div class="controls" style="margin-left: 200px;">

                    <span style="padding-bottom: 8px; cursor: pointer;float:left" class="radio1 inline">
                    <input type="radio" class="validate-recurring required error" name="afterclasses" id="optionsRadios1" value="0" <?php if($classVal->end_classes_count) echo 'checked="checked"'?>>
                    After&nbsp;
                    </span>
                <div class="input-append">
                    <input type="text" class="span3" value="<?php echo (@$classVal->end_classes_count) ? esc_attr($classVal->end_classes_count) : ''?>" name="end_classes_count" id="recurring_endclasses" style="width: 80px;margin: 0;height:42px;">
                    <span class="add-on">Classes</span> (or)
                 </div>
                <br>
                <div>
                    <label style="padding-bottom: 8px; cursor: pointer; float: left;" class="radio1 inline">
                        <input type="radio" class="validate-recurring required error" name="afterclasses" id="optionsRadios2" value="1" <?php if(@$classVal->end_date && !isset($classVal->end_classes_count)) echo 'checked="checked"';?>>
                        Ends on
                    </label>&nbsp;
                <span>
                     <input type="text" class="span4"   name="end_date" id="recurring_enddate" value="<?php echo esc_attr($classVal->end_date)?>" style="width: 244px;">
                </span>
            </div>

            </div>
        </div>

        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Allow attendees to change interface language:</label>
            <div class="controls">
            <input type="radio" id="allow_change_language_1" name="allow_change_interface_language" value="1" <?php if(!isset($classVal->language) ||  $classVal->language==11 )echo 'checked="checked"'?>>Yes
            <input type="radio" id="allow_change_language_2" name="allow_change_interface_language" value="0" <?php if($classVal->language)echo 'checked="checked"'?>>No
            </div>
        </div>
        <?php           
               $langarray = array(1=> 'arabic',2=> 'bosnian',3=> 'bulgarian',4=> 'catalan',5=> 'chinese-simplified',6=> 'chinese-traditional',7=> 'croatian',8=> 'czech',9=> 'danish',10=> 'dutch',11=> 'english',12=> 'estonian',13=> 'finnish',14=> 'french',15=> 'german',16=> 'greek',17=> 'haitian-creole',18=> 'hebrew',19=> 'hindi',20=> 'hmong-daw',21=> 'hungarian',22=> 'indonesian',23=> 'italian',24=> 'japanese',25=> 'kiswahili',26=> 'klingon',27=> 'korean',28=> 'lithuanian',29=> 'malayalam',30=> 'malay',31=> 'maltese',32=> 'norwegian-bokma',33=> 'persian',34=> 'polish',35=> 'portuguese',36=> 'romanian',37=> 'russian',38=> 'serbian',39=> 'slovak',40=> 'slovenian',41=> 'spanish',42=> 'swedish',43=> 'tamil',44=> 'telugu',45=> 'thai',46=> 'turkish',47=> 'ukrainian',48=> 'urdu',49=> 'vietnamese',50=> 'welsh');
               ?>


        <div class="control-group" style="clear:both;<?php echo $classVal->language ? 'display:block;' : 'display:none'; ?>" id="force_language">
                    <label class="span1 hasTip"  title="Set currency for shopping cart">Force Interface Language:</label>
                    <div class="controls">
                        <select class="in-selection form-control" id="language" name="language">
                    <?php
                     foreach($langarray as $key=>$val){
                         
                         ?>
                         <option value="<?php echo esc_attr($key);?>" <?php if($key == @$classVal->language || (!$classVal->language && $key==11 )){echo "selected";} ?> ><?php echo esc_html($val);?></option>
                         <?php
                     
                     } ?>
                     
                </select>
                <br />
                <br />
                   </div>
        </div>

        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Record this class:</label>
            <div class="controls">
            <input type="radio" class="record_1" name="record" value="1" <?php if(@$classVal->record == 1 || $classVal->record == 2 || $classVal->record == 3)echo 'checked="checked"'?>>Yes
            <input type="radio" class="record_2" name="record" value="0" <?php if(@$classVal->record == 0 || !isset($classVal->record))echo 'checked="checked"'?>>No
            </div>
        </div>
        
        <div class="control-group record_auto" style="<?php if(@$classVal->record == 0 || !isset($classVal->record)) echo 'display: none;'?>">
            <label>Recorded videos layout :</label>
            <select class="in-selection required form-control" id="isRecordingLayout" name="isRecordingLayout">
                <option value="0" <?php if($classVal->isRecordingLayout==0 || (!$classVal->isRecordingLayout)){echo "selected";} ?> >Standard view (Whiteboard, Videos and Chat view with no icons)</option>
                <option value="1" <?php if($classVal->isRecordingLayout==1){echo "selected";} ?> > Enhanced view (Entire browser tab with all the icons)</option>
            </select>
        </div>
        
        <div class="control-group record_auto" style="<?php if(@$classVal->record == 0 || !isset($classVal->record))echo 'display: none;'?>">
            <label class="span1 hasTip" title="Record Class">Start recording automatically when class starts:</label>
            <div class="controls">
            <input type="radio" name="start_recording_auto" value="2" <?php echo $classVal->record == 2 ? 'checked="checked"' : ''; ?>>
                    Yes&nbsp; &nbsp;    
                 
                    <input type="radio" name="start_recording_auto" value="1" <?php echo ((isset($classVal->record) && $classVal->record!=2) || $classVal->record == 1  || !$classVal->record) ? 'checked="checked"' : ''; ?>>
                    No
            </div>
        </div>
        
        <div class="control-group video_delivery" style="<?php if(@$classVal->record == 0 || !isset($classVal->record))echo 'display: none;'?>">
            <label class="span1 hasTip" title="Record Class">Allow instructor to control recording:</label>
            <div class="controls">
            <input type="radio" name="isControlRecording" value="1" <?php echo !isset($classVal->record) || $classVal->record != 3 || $classVal->record=="" ? 'checked="checked"' : ''; ?>>
                    Yes&nbsp; &nbsp;    
                 
                    <input type="radio" name="isControlRecording" value="0" <?php echo $classVal->record == 3 ? 'checked="checked"' : ''; ?>>
                    No
            </div>
        </div>

        <div class="control-group video_delivery" style="<?php if(@$classVal->record == 0 || !isset($classVal->record))echo 'display: none;'?>">
            <label class="span1 hasTip" title="Record Class">Video delivery:</label>
            <div class="controls">
            <input type="radio" name="isVideo" value="0" <?php echo $classVal->isVideo == 0 ? 'checked="checked"' : ''; ?>>
                    Multiple video files&nbsp; &nbsp;    
                 
                    <input type="radio" name="isVideo" value="1" <?php echo !isset($classVal->isVideo) || $classVal->isVideo == 1 ? 'checked="checked"' : ''; ?>>
                    Single video file
            </div>
        </div>

        <?php $description="";
    if($classVal->description){
        $description = $classVal->description;
    }?>
       <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Message:</label>
            <div class="controls">
                    <?php wp_editor( $description, "description"); ?>
            </div>
        </div> 


        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Classroom type:</label>
            <div class="controls" style="float: left">
            
            <input type="radio" class="required" name="classroom_type" id="classroom_typeyes" value="0" <?php echo !isset($classVal) && $classVal->isBoard == 0 ? 'checked="checked"' : ''; ?>  checked>  whiteboard + audio/video + attendee list + chat&nbsp; &nbsp;  <br>
            <input type="radio"  class="required" name="classroom_type" id="classroom_typeno" value="1" <?php echo isset($classVal) && $classVal->isBoard == 1 ? 'checked="checked"' : ''; ?> >
                     whiteboard + attendee list   <br>
             <input type="radio"  class="required" name="classroom_type" id="classroom_typeno" value="2" <?php echo isset($classVal) && $classVal->isBoard == 2 ? 'checked="checked"' : ''; ?> >
                     whiteboard + attendee list + chat         
            
            </div>
        </div>

        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Enable webcam and microphone upon entry:</label>
            <div class="controls">
                <input type="radio" name="isCorporate" value="1" <?php echo $classVal->isCorporate == 1 ? 'checked="checked"' : ''; ?>>
                Yes&nbsp; &nbsp;    
                <input type="radio" name="isCorporate" value="0" <?php echo !isset($classVal->isCorporate) || $classVal->isCorporate == 0 ? 'checked="checked"' : ''; ?>>
                No
            </div>
        </div>
        
        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Enable private chat:</label>
            <div class="controls">
                <input type="radio" name="isPrivateChat" value="0" <?php echo !isset($classVal->isPrivateChat)  || $classVal->isPrivateChat == 0 ? 'checked="checked"' : ''; ?>>
                Yes&nbsp; &nbsp;    
                <input type="radio" name="isPrivateChat" value="1" <?php echo $classVal->isPrivateChat == 1 ? 'checked="checked"' : ''; ?>>
                No
            </div>
        </div>


        <div class="control-group">
            <label class="span1 hasTip" title="Record Class">Enable screen sharing:</label>
            <div class="controls">
                <input type="radio" name="isScreenshare" value="1" <?php echo $classVal->isScreenshare == 1 ? 'checked="checked"' : ''; ?>>
                Yes&nbsp; &nbsp;    
                <input type="radio" name="isScreenshare" value="0" <?php echo !isset($classVal->isScreenshare) || $classVal->isScreenshare == 0 ? 'checked="checked"' : ''; ?>>
                No
            </div>
        </div>

   
        <div class="control-group">
            <label class="span1 hasTip"  title="Class type">Class Type:</label>
            <div class="controls">
            <input type="radio" name="ispaid" id="class_type_radio" value="0" <?php if(@$classVal->ispaid == 0 || !isset($classVal))echo 'checked="checked"'?>>Free
            <input type="radio" name="ispaid" id="class_type_radio2" value="1" <?php if(@$classVal->ispaid == 1)echo 'checked="checked"'?>>Paid
            </div>
        </div>

        <div class="control-group" style="margin: 0px;" id="currencycontainer">
                    <label class="span1 hasTip"  title="Set currency for shopping cart">Currency:</label>
                    <div class="controls">
                        <select style="width:100px;" id="currency" name="currency">
                          <option value="aud" <?php if(@$classVal->currency == 'aud') echo 'selected="selected"'?>>AUD <i class="icon-aud"></i></option>
                            <option value="cad" <?php if(@$classVal->currency == 'cad') echo 'selected="selected"'?>>CAD <i class="icon-cad"></i></option>
                            <option value="eur" <?php if(@$classVal->currency == 'eur') echo 'selected="selected"'?>>EUR <i class="icon-eur"></i></option>
                            <option value="gbp" <?php if(@$classVal->currency == 'gbp') echo 'selected="selected"'?>>GBP <i class="icon-gbp"></i></option>
                            <option value="nzd" <?php if(@$classVal->currency == 'nzd') echo 'selected="selected"'?>>NZD <i class="icon-nzd"></i></option>
                            <option value="usd" <?php if(@$classVal->currency == 'usd') echo 'selected="selected"'?>>USD <i class="icon-usd"></i></option>
                        </select>&nbsp;<span id="cursym" style="float:none; margin-top:3px;"></span>
                <br />
                <br />
                   </div>
        </div>

        <div class="control-group">
            <label class="span1 hasTip"  title="Max. attendees">Max. attendees:</label>
            <div class="controls">
            <input type="text" placeholder="Max. attendees" id="seat_attendees" name="seat_attendees" value="<?php echo isset($classVal->seat_attendees) ? esc_attr($classVal->seat_attendees) : esc_attr($plan->max_attendees); ?>" style="width: 100px;">
            <input type="hidden" id="max_seat_attendees" value="<?php echo esc_html($plan->max_attendees); ?>" >
            </div>
        </div>
        <div class="control-group">
            
            <div class="controls">
                <label class="span1 hasTip"  title="Max. attendees">Keywords :</label>
                <ul id="myTags" style="width: 60%"></ul>
            <input type="hidden" placeholder="Keywords" id="keyword" name="keyword" value="<?php echo esc_attr($classVal->keyword); ?>">
            <small class="text-info" style="margin-top: -24px;display: flex;justify-content:center;font-weight: 600;">(Maximum 3 keywords seperated by a comma)</small>
             </div>
        </div>
        <div style="display: flex;justify-content: center;">
        <input type="hidden" name="instructor_id"  id="instructor_id"  value="<?php echo esc_attr($current_user->ID);?>" />
        <input type="hidden" name="created_by"  id="created_by"  value="<?php echo esc_attr($current_user->ID);?>" />
        <input type="hidden"  id="cid" name="cid" value="<?php echo esc_attr($cid)?>"/>
        <input type="hidden" name="task" value="saveClassfront" />
        <input type="submit" style="cursor: pointer;width: 100px;padding: 10px;background-color: #0693e3;color: #FFFF;" class="button button-primary button-large" name="apply-submit" value="Save" />
        </div>


    </form>
<script type="text/javascript">

    jQuery(function() {
        jQuery( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", setDate:'<?php echo esc_attr($classVal->date);?>' });
        });
    jQuery(function() {
        jQuery( "#recurring_enddate" ).datepicker({ dateFormat: "yy-mm-dd", setDate:'<?php echo esc_attr($classVal->date);?>'});
        });

    jQuery(document).ready(function(){

        jQuery("#myTags").tagit({
            singleField: true,
            singleFieldNode: jQuery('#keyword')
        });
        
        jQuery('#btnselectuser').on("click", function() {  
            instructor_id = jQuery('input[name=chooseselector]:checked').val();
            if(instructor_id){
              jQuery("#instructor_id").val(instructor_id);
              jQuery("#instructorthumb").attr('src',jQuery("#thumb_"+instructor_id).val());
              jQuery("#instructorname").html(jQuery("#name_"+instructor_id).html());
              jQuery(".modal").hide();
            }
            
        });

        jQuery(".close").click(function(event) {
            jQuery(".modal").hide();
        });
        var repeats = jQuery("#repeats").val();

        if(repeats !=6){
           jQuery('.weeklytotaldays').hide();
        }

        jQuery('#class_start_time').timepicker({ 'scrollDefault': 'now' });
        jQuery('#class_end_time').timepicker({ 'scrollDefault': 'now' });
    });
    jQuery("#repeats").change(function() {
        var repeat = jQuery( "#repeats" ).val();
        if(repeat=='6'){
            jQuery(".weeklytotaldays").show();  
        }else{
            jQuery(".weeklytotaldays").hide();
        }
    });
    function setweekday(el) {
        if(! jQuery(el).parent('label').closest(".active").length ) {
                jQuery(el).parent('label').addClass('active');
        }else{
                jQuery(el).parent('label').removeClass('active');
        }
    }
</script>