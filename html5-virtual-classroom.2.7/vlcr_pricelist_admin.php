<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Price Listing
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');

echo '<h3>Price List</h3>';

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$vc_obj = new vlcr_class();

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
$cid = isset( $_REQUEST['cid'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['cid'] ) ) : '';
$result=$vc_obj->vlcr_listprice($search,$limit,$cid);
?>
<form id="adminForm" name="adminForm" method="post" action="">    	 
<table class="wp-list-table widefat striped">
<thead>
    <tr>
    	<td colspan="12">
        	<a class="button button-primary button-large" href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/PriceList&action=add&cid='.$cid.''))?>">Add</a>
            <a class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','edit')}">Edit</a>
            <a class="button button-primary button-large" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{ submitForm('adminForm','delete')}">Delete</a>
        </td>
    </tr>
    <tr>
    	<th><input type="checkbox" onclick="checkAll(this)" value="" name="checkall-toggle"></th>
    	<th>Price id</th>
        <th>Price</th>
        <th>Scheme days</th>
        <th>Lifetime</th>
        <th>Times</th>
        <th>Numbertimes</th>
    </tr>
</thead>
<tbody>    
       <?php
       if($result &&  @$result['Price'] != 'No Price in this Class'){
		   foreach($result  as $i => $item)
		   { 
		 
            ?>
             <tr class="row<?php echo esc_attr($i) % 2; ?>">
                <td class="center">
                	<input type="checkbox" onclick="isChecked(this.checked);" value="<?php echo esc_html($item['id']); ?>" name="priceid[]" id="cb<?php echo esc_attr($i)?>">
                </td>
                 <td class="center">
                    <?php echo esc_html($item['id']); ?>
                </td>
                 <td class="center">
                     <?php echo esc_html($item['scheme_price']); ?>
                </td>
                 
                <td class="center">
                   <?php echo esc_html($item['scheme_days']) ; ?>
                </td>
                <td class="center">
                    <?php
                                    if ($item['lifetime'] == "1") {
                                        echo "Unlimited";
                                    } elseif ($item['lifetime'] == "0") {
                                        echo "Fix Day";
                                    }
                                    ?>
                </td>
                
                <td class="center">
                     <?php if($item['times'] == 0){
                           $times = "unlimited";
                        }
                        if($item['times'] == 1){
                           $times = "limited";
                        }
                        ?>
                        <?php echo esc_attr($times); ?>
                </td>
                <td class="center">
                    <?php echo esc_html($item['numbertimes']) ; ?>
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