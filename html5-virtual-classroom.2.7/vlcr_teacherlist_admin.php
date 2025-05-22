<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Teacher List
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');

echo '<h3>Teacher List</h3>';

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$vc_obj = new vlcr_class();
$limit = 10;
$filter = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
if($filter){
    $filter = wp_strip_all_tags($filter);
}
$list_users=$vc_obj->vlcr_teacherlist($filter,$limit);
$list_users_total=$vc_obj->vlcr_total_teacherlist($filter);
$targetpage = "admin.php?page=".VC_FOLDER."/vlcr_setup.php/TeacherList";    //your file name  (the name of this file)
$pagination = $vc_obj->vlcr_pagination_teacherlist($targetpage,$list_users_total,$limit);
?>
<form id="adminForm" name="adminForm" method="post" action="">   
<table class="table">
    <thead><tr>
      <td width="100%">
            Filter:
            <input type="text" name="search" id="search" value="<?php echo esc_attr($filter);?>" class="text_area" title="Filter by Title">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Go"  />
            <input type="button" name="reset" id="reset" onclick="resetbtn();" class="button button-primary" value="Reset"  />
      </td>
    </tr>
  </thead></table>

<table class="wp-list-table widefat striped">
<thead>
    <tr>
    	<th><input type="checkbox" onclick="checkAll(this)" value="" name="checkall-toggle"></th>
    	  <th>Name</th>
          <th>User name</th>
          <th>Email</th>
          <th>Is teacher</th>
    </tr>
</thead>
<tfoot>   
    <tr>
        <td colspan="12">
        	<?php echo esc_attr($pagination);	?>
    </tr>
</tfoot>
<tbody>    
  <?php
  if($list_users){
  foreach($list_users  as $i=>$list_user)
  { ?>
    <tr class="row<?php echo esc_attr($i % 2); ?>">
      <td class="center">
      	<input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo esc_html($list_user->ID); ?>" name="userid[]" id="cb<?php echo esc_attr($i)?>">
      </td>
      <td class="center">
        <?php echo esc_html($list_user->user_nicename); ?>
      </td>
      <td class="center">
        <?php echo esc_html($list_user->user_login); ?>
      </td>
      <td class="center">
        <?php echo esc_html($list_user->user_email); ?>
      </td>
      <td>
        <?php if($list_user->is_teacher == 1) {?>
          <span class="hasTip" title="Remove User">
            <a href="<?php echo esc_url(admin_url('admin.php?page='.esc_attr(VC_FOLDER).'/vlcr_setup.php/TeacherList&task=unpublishuser&user_id='.esc_attr($list_user->ID).''))?>" class=""><img src="<?php echo esc_url(VC_URL)?>/images/tick.png" alt="Tooltip"></a>
          </span>
        <?php } else{ ?>
          <span class="hasTip" title="Make User">
            <a href="<?php echo esc_url(admin_url('admin.php?page='.esc_attr(VC_FOLDER).'/vlcr_setup.php/TeacherList&task=publishuser&user_id='.$list_user->ID.''))?>" class=""><img src="<?php echo esc_url(VC_URL)?>/images/publish_x.png" alt="Tooltip"></a>
          </span>
        <?php } ?>
      </td> 
    </tr>
	<?php  
	} // foeach
 }?> 
</tbody>      
</table>
<input type="hidden" value="0" name="boxchecked">
<input type="hidden" name="task" value="" />
<input type="hidden" name="action" value="" />
</form>
<script type="text/javascript">
  function resetbtn(){
        document.getElementById('search').value=' '; 
        window.location.href = 'admin.php?page=<?php echo esc_attr(VC_FOLDER);?>/vlcr_setup.php/TeacherList';
    }
</script>