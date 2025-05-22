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

if ( ! is_plugin_active( 'groups/groups.php' ) ) {
  echo "<div class='error'>
        <h2>If you want set permissions for class to visible specific user groups on site then you must need to install groups plugin.</h2>
        <h3>you can download plugin using below link.</h3>
        <p><a href='https://wordpress.org/plugins/groups/'>https://wordpress.org/plugins/groups/</a></p>
        </div>";
  return;

}



$vc_obj = new vlcr_class();
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}
$id=$_REQUEST['id'];
$users= get_users();
if (isset($_POST['addclass_acl'])){
  
  $data =$_POST;
  $result=$vc_obj->vlcr_addclass_acl($data);
  if($result){
    echo "Added successfully";
  }
}
$groups=$vc_obj->vlcr_get_usergroups();
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : ''; 
if($search){
    $search = wp_strip_all_tags($search);
}
$classlist=$vc_obj->vlcr_listclass($search,''); 

echo "<h1>Set permissions for class visiblility.</h1>";

?>
<form method='post' action='' name="vlcr_acl" >
<div>
 <ul>
     <li>
        <b>Select User group </b>
        <select name="usergroup" id="usergroup">
        <?php foreach($groups as $group){  ?>
             <option value='<?php echo esc_attr($group->group_id);?>'><?php echo esc_attr($group->name);?></option>
         <?php } ?>
        </select>
    <input id="save" type="submit" class="button button-primary" value="Save Changes" name="addclass_acl"></li>
 </ul>
    
</div>
   <div class="class_list_group">
<?php  foreach($classlist['classes'] as $class){ ?>
      <div>
        <input type='checkbox' name='class_id[]' class="classchk classid-<?php echo esc_attr($class['id']);?>" value="<?php echo esc_attr($class['id']);?>" />
        <?php echo esc_attr($class['title']);?>
      </div>
  <?php  } ?>
</div>
</form>




<style type="text/css">
  .class_list_group > div{width: 33.33%;float: left; padding: 5px 0;}
  .class_list_group > div:nth-child(3n+1){clear: both;}
  .class_list_group {float: left;padding: 0 0 0 20px;padding: 30px 0 0 20px;background: #fff none repeat scroll 0 0;width: 100%;}
</style>
<script type="text/javascript">
jQuery( document ).ready(function() {
  var gid =jQuery("#usergroup").val();
  data = { action: 'vlcr_get_selected_class', gid: gid };
  jQuery.post(ajaxurl, data, function(response){

    var cidArray = response.split(',');
    jQuery('.classchk').removeAttr('checked', 'checked');

    jQuery.each(cidArray, function(index, value) { 
      jQuery('.classid-'+value).prop( "checked", true );
    });

  });
      
jQuery("#usergroup").change(function(){
   var gid =jQuery("#usergroup").val();
      data = { action: 'vlcr_get_selected_class', gid: gid };
      jQuery.post(ajaxurl, data, function(response){
      var cidArray = response.split(',');
       jQuery('.classchk').removeAttr('checked', 'checked');
            jQuery.each(cidArray, function(index, value) { 
                jQuery('.classid-'+value).prop( "checked", true );
            });
          });
      
    });
});
</script>