<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Price Listing Editing
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('jquery.timepicker',VC_URL.'/js/jquery.timepicker.js');
wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');
wp_enqueue_style( 'jquery.timepicker', VC_URL.'/css/jquery.timepicker.css');

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$vc_obj = new vlcr_class();
$plan = $vc_obj->vlcr_getplan();

$priceid = '';
if(isset($_REQUEST['priceid'])){
	if(is_array($_REQUEST['priceid'])){
		$priceid = $_REQUEST['priceid'][0];
	} else {
		$priceid = $_REQUEST['priceid'];	
	}
}

$cid = $_REQUEST['id'];
$priceVal = (object)$vc_obj->vlcr_price_detail($priceid,$cid);

?>
<h3>Add Pricing Scheme</h3>
<form class="form-horizontal form-validate" id="adminForm" action="" method="post"  enctype="multipart/form-data">
        <div class="control-group">
            <label class="span1 hasTip" for="title"  title="Price" style="width: 225px;">Price:</label>
            <div class="controls">
            <input type="text" placeholder="price" id="price" name="price" value="<?php echo esc_attr($priceVal->scheme_price)?>" style="width: 100px;" >
            </div>
        </div> 
         <div class="control-group">
            <label  class="span1 hasTip" for="title"  title="Days (To Give Access for)" style="width: 225px;">Days (To Give Access for):</label>
            <div class="controls">
            <input type="text" id="scheme_days" name="scheme_days" value="<?php echo esc_attr($priceVal->scheme_days)?>" style="padding: 4px; vertical-align: top; width: 70px; height: 40px; margin: 0px;" >
            <div class="add-on after" style="height: 40px;line-height: 30px;margin-left: -4px;">
              <input type="hidden" id="lifetime" name="lifetime"  value="<?php echo esc_attr($priceVal->lifetime);?>">
             <input type="checkbox"  style="vertical-align: -3px;" <?php if(isset($priceVal->lifetime) && $priceVal->lifetime == '1'){
                            echo "checked='checked' ";
                        } ?> id="lifetimechk" />Lifetime
            </div>
            </div>
        </div>    
        <div class="control-group">
            <label class="span1 hasTip" for="title"  title="Access type" style="width: 225px;"> Access type:</label>
            <div class="controls">
            <select  id="times" name="times">
                <option value="0" id="times_no" <?php if(@$priceVal->times == 0){ ?> selected="selected" <?php } ?>>unlimited</option>
                <option value="1" id="times_yes" <?php if(@$priceVal->times == 1){ ?> selected="selected" <?php } ?>>limited</option>
            </select>
            </div>
        </div>   
         
         <div class="control-group" id="numtimes_div" style="display:none;">
            <label class="span1 hasTip" for="title"  title="Number of Times" style="width: 225px;">Number of Times:</label>
            <div class="controls">
            <input type="text" placeholder="numbertimes" id="numbertimes" name="numbertimes" value="<?php echo esc_attr($priceVal->numbertimes);?>" style="width: 150px;">
            </div>
        </div>  
        
        <input type="hidden" id="task" name="task" value="createprice"/>
        <input type="hidden" id="cid" name="cid" value="<?php echo esc_attr($cid)?>"/>
        <input type="hidden"  name="id" value="<?php echo esc_attr($priceVal->id)?>"/>
        <input type="hidden" id="format" name="format" value=""/>
        <input type="submit" class="button button-primary button-large" name="apply-submit" value="Save" />
        </div>
    </form>
 
 