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
$id = isset( $_REQUEST['id'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ) : '';
$cid = isset( $_REQUEST['cid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cid'] ) ) : '';
if(isset($_REQUEST['type'])){
  if($_REQUEST['type']=="discountlist"){
    $cid =sanitize_text_field($id);
  }
}
$vc_obj =new vlcr_class();
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}
$search = isset( $_REQUEST['search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['search'] ) ) : '';
if($search){
    $search = wp_strip_all_tags($search);
}
$targetpage = "admin.php?page=".VC_FOLDER."/vlcr_setup.php/PriceList"; 	//your file name  (the name of this file)
$limit = 10; 								//how many items to show per page
$result=$vc_obj->vlcr_listdiscount($search,$limit,$cid);
$submenu_base_url = get_permalink($post->ID).'&id='.sanitize_text_field($id).'&type=discountlist&action=add';
if(strpos(get_permalink($post->ID),'?')===false){
    $submenu_base_url = get_permalink($post->ID).'?id='.sanitize_text_field($id).'&type=discountlist&action=add';
}
?>
<form id="searchForm" name="searchForm" method="post" action="">    	
      <div width="100%" style="margin-bottom: 20px;">
            Filter:
            <input type="text" name="search" id="search" value="<?php echo esc_attr($search);?>" class="text_area" title="Filter by Title" style="width: 200px;">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Go"  />
            <input type="button" name="reset" id="reset" onclick="resetbtn();" class="button button-primary" value="Reset"  />
      </div>
 </form> 
 
<form id="adminForm" name="adminForm" method="post">    	
<button class="button button-primary button-large"><a href="<?php echo esc_url($submenu_base_url)?>" style="color:#fff;box-shadow: none;">Add</a></button>
            <button class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','edit')}">Edit</button>
            <button class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','delete')}">Delete</button>
<table class="wp-list-table widefat striped" style="margin-top: 20px;">
<thead>
    <tr>
    	  <th style="width: 5%;"><input type="checkbox" onclick="checkAll(this)" value="" name="checkall-toggle"></th>
    	  <th style="width: 15%;">Discount id</th>
        <th style="width: 15%;">Discount</th>
        <th style="width: 15%;">Discount code</th>
        <th style="width: 15%;">Discount type</th>
        <th style="width: 15%;">Start date</th>
        <th style="width: 15%;">End date</th>
    </tr>
</thead>
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
        window.location.href = 'admin.php?page=<?php echo esc_attr(VC_FOLDER);?>/vlcr_setup.php/DiscountList&cid=<?php echo esc_attr($cid);?>';
    }
</script>