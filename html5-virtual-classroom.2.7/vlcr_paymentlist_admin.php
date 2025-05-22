<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Payment Listing
 * @package  virtual-classroom
 * @since    2.7
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');

echo '<h3>Payment List</h3>';
 
if(isset($_REQUEST['task'])){
        include_once('vlcr_action_task.php');   
}
$vc_obj = new vlcr_class();
$limit = 10;
$filter = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : '';

if($filter){
    $filter = wp_strip_all_tags($filter);
}
$list_purchase=$vc_obj->vlcr_purchaselist($filter,$limit);
$list_purchase_total=$vc_obj->vlcr_total_purchaselist($filter);
$targetpage = "admin.php?page=".VC_FOLDER."/vlcr_setup.php/Payments";    //your file name  (the name of this file)
$pagination = $vc_obj->vlcr_pagination_teacherlist($targetpage,$list_purchase_total,$limit);
?>
<form id="searchForm" name="searchForm" method="post" action="">  
<table class="table">
    <thead>
      <tr>
        <td width="100%">
            Filter:
            <input type="text" name="search" id="search" value="<?php echo esc_attr($filter);?>" class="text_area" title="Filter by Title">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Go"  />
            <input type="button" name="reset" id="reset" onclick="resetbtn();" class="button button-primary" value="Reset"  />
        </td>
      </tr>
    </thead>
  </table>
</form>
<form id="adminForm" name="adminForm" method="post">
  <table class="wp-list-table widefat striped">
    <thead>
      <tr>
        <th><input type="checkbox" onclick="checkAll(this)" value="" name="checkall-toggle"></th>
        <th>Payment id</th>
        <th>Class id</th>
        <th>Amount</th>
        <th>Payer Name</th>
        <th>Payment mode</th>
        <th>Payment Date</th>
      </tr>
    </thead>
    <tfoot>   
      <tr>
        <td colspan="12">
          <?php echo esc_attr($pagination);?>
        </td>
      </tr>
    </tfoot>
  <tbody>
  <?php if(count($list_purchase)>0){
    foreach($list_purchase as $i=>$purchase){ 
   ?>
      <tr class="row<?php echo esc_attr($i) % 2; ?>">
        <td class="center">
          <input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo esc_html($purchase->id); ?>" name="userid[]" id="cb<?php echo esc_attr($i)?>">
        </td>
        <td class="center">
          <?php echo esc_html($purchase->id); ?>
        </td>
        <td class="center">
          <?php echo esc_html($purchase->class_id); ?>
        </td>
        <td class="center">
          <?php echo esc_html($purchase->mc_gross) ; ?>
        </td>
        <td class="center">
          <?php echo esc_html($purchase->uname) ; ?>
        </td>
        <td class="center">
          <?php echo esc_html($purchase->payment_mode) ; ?>
        </td>
        <td class="center">
          <?php echo esc_html($purchase->date_puchased) ; ?>
        </td>
      </tr>
    <?php  
    } // foeach
  }
  ?> 
  </tbody>      
</table>
<input type="hidden" value="0" name="boxchecked">
<input type="hidden" name="task" value="" />
<input type="hidden" name="action" value="" />
</form>
<script type="text/javascript">
  function resetbtn(){
        document.getElementById('search').value=' '; 
        window.location.href = 'admin.php?page=<?php echo esc_html(VC_FOLDER);?>/vlcr_setup.php/Payments';
    }
</script>