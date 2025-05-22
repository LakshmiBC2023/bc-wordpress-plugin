<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Discount Listing Editing
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('jquery-ui-datepicker'); 
wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');
wp_enqueue_style( 'jquery-ui',VC_URL.'/css/vlcr-calendar.css');

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$_REQUEST['cid'] = $_REQUEST['id'];
$vc_obj = new vlcr_class();
$plan = $vc_obj->vlcr_getplan();
$class_data = (object)$vc_obj->vlcr_class_detail($_REQUEST['cid']);

  if(strtoupper($class_data->currency) == "GBP"){
        $currencysymbol = "£";
  }else if(strtoupper($class_data->currency) == "CAD"){
        $currencysymbol = "$";
  }else if(strtoupper($class_data->currency) == "AUD"){
        $currencysymbol = "$";
  }else if(strtoupper($class_data->currency) == "EUR"){
        $currencysymbol = "€";
  }else{
        $currencysymbol = "$";
  }
                
                


$discountid = '';
if(isset($_REQUEST['discountid'])){
	if(is_array($_REQUEST['discountid'])){
		$discountid = $_REQUEST['discountid'][0];
	} else {
		$discountid = $_REQUEST['discountid'];	
	}
 }
$cid = $_REQUEST['cid'];
$discountVal = (object)$vc_obj->vlcr_discount_detail($discountid,$cid);
?>
<script type="text/javascript">
jQuery(document).ready(function () {

    <?php if($discountVal->discount_type=='percentage'){
      ?>

      if(jQuery( "#coupon-type" ).val() == '1'){
        jQuery("#fixed_amount").css("display", "none");
        jQuery("#percentage").css("display", "inline-block");
        }
    <?php }?>
     });
</script>

<h3>Add Discount</h3>
<form class="form-horizontal form-validate discount-form" id="adminForm" action="" method="post"  enctype="multipart/form-data">
       <div class="add-on after" style="height: 40px;padding: 10px;"> 
            <input type="hidden" id="is_use_discount_code_" name="is_use_discount_code"  value="0">
              <input type="checkbox"  id="use_discount_code" value="1" name="is_use_discount_code" <?php if(@$discountVal->is_use_discount_code == 1) {?>checked<?php }else{
          if(!isset($discountVal->is_use_discount_code)){
            ?>
            checked
                      <?php
            }
          } ?>
              
              /> Use Discount Code
            </div> 
            <div style="clear: both;"></div>
            <span id="use_discount_code_div" style="margin-bottom: 20px;margin-top: 20px;"> 
   
        <div class="control-group">
            <label class="span1 hasTip" for="title"  title="Discount Limit">Discount Limit:</label>
            <div class="controls">
            <input type="text" placeholder="Discount Limit" id="discount_limit" name="discount_limit" value="<?php echo esc_attr($discountVal->discount_limit); ?>" style="width: 200px;">
            </div>
        </div> 
         
         <div class="control-group">
            <label class="span1 hasTip" for="title"  title="discount_code">Discount Code:</label>
            <div class="controls">
            <input type="text" placeholder="Discount Code" id="discount_code" name="discount_code" value="<?php echo esc_attr($discountVal->discount_code); ?>" style="width: 200px;">
            </div>
        </div>  

      </span>

            
         <div style="float: left; width: 384px;">
            <label class="span1 hasTip" for="title"  title="discount_type">Discount type:</label>
            <div class="controls">
            <select name="discount_type" class="valid"  id="coupon-type">
                <option value="0" <?php if(@$discountVal->discount_type == "fixed_amount"){?> selected="selected" <?php } ?>><?php echo esc_attr($currencysymbol);?> <?php echo esc_attr(strtoupper($class_data->currency)); ?></option>
                <option value="1" <?php if(@$discountVal->discount_type == "percentage"){?> selected="selected" <?php } ?>>% Percentage</option>
            </select>
            </div>
        </div>  
         
        <div  style="float: left; width: 384px;">
            <label class="span1 hasTip" for="title"  title="Discount Price" style=" width: 40px; margin-top: 5px; margin-left: 0px;">Take</label>
            <div class="controls" style="margin-left: 35px;">
              <span data-bind="shop | money_symbol" data-showif="discount.isFixed" class="add-on before" id="fixed_amount" style="border-radius: 5px 0px 0px 5px;display: inline-block;height: 44px;margin-right: -5px;vertical-align: middle;line-height: 30px;margin-top: -2px;"><?php echo esc_attr($currencysymbol);?></span>
              <input type="text" placeholder="discount" id="discount" name="discount" value="<?php echo esc_attr($discountVal->special_price);?>" style="width: 110px; margin-top: -2px; line-height: 23px;">
              <span data-showif="discount.isPercentage" class="add-on after" style="border-radius: 0 5px 5px 0;display: none;height: 21px;margin-left: -11px;margin-top: -2px;vertical-align: -1px;" id="percentage">%</span>
             off for all orders                             
            </div>
        </div> 
        <?php 

        
             $start_date = isset($discountVal->start_date) ? esc_attr($discountVal->start_date) : '';
             if($start_date == "0000-00-00 00:00:00"){
                $start_date = '';
             }
         
        ?>
         <div class="control-group" style="clear: both;">
            <label class="span1 hasTip" for="title"  title="Start Date">Start date:</label>
            <div class="controls">
            <input type="text" placeholder="Start Date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date);?>" style="width: 200px;">
            <b>(yyyy-mm-dd), Example: { 2014-09-04 }</b>
            </div>
        </div> 
        <?php 
        
             $end_date = isset($discountVal->end_date) ? esc_attr($discountVal->end_date) : '';
             if($end_date == "0000-00-00 00:00:00 "){
                $end_date = '';
             }

        if(@$discountVal->is_never_expire == '1'){
              $end_date = '';
        }else{
              $end_date = isset($discountVal->end_date) ? esc_attr($discountVal->end_date) : '';
          }
       ?>
          <div class="control-group">
            <label class="span1 hasTip" for="title"  title="End Date">End Date:</label>
            <div class="controls">
            <input type="text" placeholder="End Date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date);?>"  style=" float: left;height: 28px;margin-right: 2px;vertical-align: top;width: 100px;">
            <label class="pointer inline fw-normal" for="coupon-never-expires">
                <span class="add-on after"  style="margin-left: -4px;height: 28px;margin-top: -11px;line-height: 22px;">
                 <input type="hidden" id="is_never_expire_" name="is_never_expire"  value="0">
                  <input type="checkbox" id="coupon-never-expires" value="1" style="vertical-align: -1px;" name="is_never_expire" <?php if($discountVal->is_never_expire == '1'){echo 'checked';} ?>> Never expires
                </span><b> (yyyy-mm-dd), Example: { 2014-09-04 }</b>
              </label>
           </div>
        </div>
          
        
         <input type="hidden" id="task" name="task" value="creatediscount"/>
         <input type="hidden" id="cid" name="cid" value="<?php echo esc_attr($_REQUEST['cid'])?>"/>
         <input type="hidden"  name="id" value="<?php echo esc_attr($discountVal->id)?>"/>
         <input type="submit" class="button button-primary button-large" name="apply-submit" value="Save" />
            
         <input type="hidden" id="format" name="format" value=""/>
         <div class="control-group">
          <div class="controls">
          </div>
         </div>
    </form>

  <script type="text/javascript">
    jQuery(function() {
            jQuery( "#start_date" ).datepicker();
            jQuery( "#start_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            jQuery("#start_date").datepicker("setDate", '<?php echo esc_attr($start_date);?>');
 });
    jQuery(function() {
            jQuery( "#end_date" ).datepicker();
            jQuery( "#end_date" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
            jQuery("#end_date").datepicker("setDate", '<?php echo esc_attr($end_date);?>');
             });
 </script>