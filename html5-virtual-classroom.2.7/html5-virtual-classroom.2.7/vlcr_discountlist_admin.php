<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Discount List
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');

echo '<h3>Discount List</h3>';

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$vc_obj =new vlcr_class();
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}
$filter = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : '';
if($search){
    $search = wp_strip_all_tags($search);
}
$targetpage = "admin.php?page=".VC_FOLDER."/vlcr_setup.php/PriceList"; 	//your file name  (the name of this file)
$limit = 10; 								//how many items to show per page
$cid = isset( $_REQUEST['cid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cid'] ) ) : '';
$result=$vc_obj->vlcr_listdiscount($search,$limit,$cid);
?>
<form id="searchForm" name="searchForm" method="post" action="">    	

<table class="table">
    <thead><tr>
      <td width="100%">
            Filter:
            <input type="text" name="search" id="search" value="<?php echo esc_attr($search);?>" class="text_area" title="Filter by Title">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Go"  />
            <input type="button" name="reset" id="reset" onclick="resetbtn();" class="button button-primary" value="Reset"  />
      </td>
    </tr>
  </thead></table>
 </form> 
 
<form id="adminForm" name="adminForm" method="post">    	
<table class="wp-list-table widefat striped">
<thead>
    <tr>
    	<td colspan="12">
        	<a class="button button-primary button-large" href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/DiscountList&action=add&cid='.$cid.''))?>">Add</a>
            <a class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','edit')}">Edit</a>
            <a class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','delete')}">Delete</a>
        </td>
    </tr>
    <tr>
    	<th><input type="checkbox" onclick="checkAll(this)" value="" name="checkall-toggle"></th>
    	<th>Discount id</th>
        <th>Discount</th>
        <th>Discount code</th>
        <th>Discount type</th>
        <th>Start date</th>
        <th>End date</th>
    </tr>
</thead>
<tfoot>   
    <tr>
        <td colspan="12">
		</td>
    </tr>
</tfoot>
<tbody>    
       <?php
       if($result && @$result['Discount'] != 'No Discount in this Class'){
		   foreach($result  as $i => $item)
		   { 
            ?>
             <tr class="row<?php echo esc_attr($i) % 2; ?>">
                <td class="center">
                	<input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo esc_html($item['id']); ?>" name="discountid[]" id="cb<?php echo esc_attr($i)?>">
                </td>
                 <td class="center">
                    <?php echo esc_html($item['id']); ?>
                </td>
                 <td class="center">
                     <?php echo esc_html($item['special_price']); ?>
                </td>
                 
                <td class="center">
                   <?php echo esc_html($item['discount_code']) ; ?>
                </td>
                <td class="center">
                     <?php if($item['discount_type'] == "fixed_amount"){
                            $discount_type =  "Fixed Amount";
                        }
                        if($item['discount_type'] == "percentage"){
                         $discount_type =  "Percentage";   
                        }
                        ?>
                        <?php echo esc_attr($discount_type); ?>
                </td>
                 
                <td class="center">
                    <?php echo esc_attr(gmdate("F j, Y", strtotime($item['start_date'])));?>
                </td>
                 <td class="center">
                    <?php if($item['end_date'] == '' || $item['end_date']=='0000-00-00 00:00:00'){echo esc_attr('Unlimited');}else{echo esc_attr(gmdate("F j, Y", strtotime($item['end_date'])));} ?>
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
        window.location.href = 'admin.php?page=<?php echo esc_attr(VC_FOLDER);?>/vlcr_setup.php/DiscountList&cid=<?php echo esc_html($cid);?>';
    }
</script>