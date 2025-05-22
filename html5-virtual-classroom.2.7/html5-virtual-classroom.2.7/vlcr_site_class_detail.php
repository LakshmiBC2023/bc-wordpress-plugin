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

$vc_obj = new vlcr_class();
global $wpdb;
$row = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$wpdb->prefix . 'virtualclassroom_settings',''));
if(!$row)
{
echo "Please setup API key and URL";
return;
}
//date_default_timezone_set('UTC');
global $post;
//wp_enqueue_script('vlcr_script',VC_URL.'js/countdown.js');
//wp_enqueue_style( 'vlcr_jquery-ui', VC_URL.'/css/vlcr_jquery-ui.css');
//wp_enqueue_script('vlcr_jquery',VC_URL.'/js/vlcr_jquery-ui.js');
wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');
wp_enqueue_script('vlcr_video',VC_URL.'/js/vlcr_video.js');

$current_user = wp_get_current_user();
$login_user_email = esc_html( $current_user->user_email );

$ogurl = get_permalink($post->ID).'&pcid='.$_REQUEST['pcid'];
if(strpos(get_permalink($post->ID),'?')===false){
    $ogurl = get_permalink($post->ID).'?pcid='.$_REQUEST['pcid'];
}

$islearner = $_REQUEST['islearner'];
$isinstructor = $_REQUEST['isinstructor'];

$task = $_REQUEST['task'];
$key = $row->braincert_api_key;
$base_url = $row->braincert_base_url;

$explode_base_url = explode('/',$base_url);
array_pop($explode_base_url);
$braincert_base_url = implode('/', $explode_base_url); 

$id=$_REQUEST['pcid'];

if($task == "returnpayment"){
    $table_name = $wpdb->prefix . 'virtualclassroom_purchase';
    $rows_affected = $wpdb->insert( $table_name, array( 'class_id' => $_REQUEST['class_id'], 'mc_gross' => $_REQUEST['amount'], 'payer_id' => get_current_user_id(), 'payment_mode' => $_REQUEST['payment_mode'], 'date_puchased' => now() ));
    header('Location:'.$ogurl);
}

$result=$vc_obj->vlcr_learnerPreview($id);
$pricelist=$vc_obj->vlcr_get_priceList($id);
$listdiscount=$vc_obj->vlcr_listdiscount('','',$id);
$paymentInfo=$vc_obj->vlcr_get_paymentInfo();
$getplan=$vc_obj->vlcr_getplan();
$login_user_group_classes=$vc_obj->vlcr_get_loginusergroup();
 
if($task=="class_checkout"){
    $vc_obj->vlcr_get_class_checkout();
}
if($task=="validatecoupon"){
    $vc_obj->vlcr_class_validatecoupon();
}
if($task=="vlcr_view_class_recording"){
    $vc_obj->vlcr_view_class_recording();
}



    if($result[0]['status'] == "Upcoming"){
        $class = "vc-alert vc-alert-warning";
    }
    if($result[0]['status'] == "Past"){
         $class = "vc-alert vc-alert-danger";
    }
    if($result[0]['status'] == "Live"){
        $class = "vc-alert vc-alert-success";
    }
$currencysym='';
switch(strtoupper($result[0]['currency'])){
    case "GBP":
        $currencysym = "£";
    break;
    case "CAD":
        $currencysym = "$";
    break;
    case "AUD":
        $currencysym = "$";
    break;
    case "EUR":
        $currencysym = "€";
    break;
    case "INR":
        $currencysym = "₹";
    break;
    default:
        $currencysym = '$';     

}
 ?>
<div id="modal-content-buying" class="modal">
    <?php if(isset($pricelist['Price']) && $pricelist['Price']=="No Price in this Class"){ ?>
        <div class="modal-content" style="overflow: hidden;">
        <span><b>Buying Option</b></span>
        <span class="close">&times;</span>
        <?php echo esc_attr($pricelist['Price']);?>
        </div>
    <?php }else{?>
    
    <div class="modal-content" style="overflow: hidden;">
    <span><b>Buying Option</b></span>
    <span class="close">&times;</span>

     <div class="card_error" style="display: none;color: #a94442;background-color: #f2dede;border-color: #ebccd1;border-radius: 5px;margin-bottom: 10px;padding: 8px;"></div>
     <?php
    $usecoupon = 0;
    for($i=0; $i<count($listdiscount); $i++){
        if($listdiscount[$i]['is_use_discount_code']){
            $usecoupon = 1;
        }
    }
    ?>
     <?php if($usecoupon){ ?>

        <div id="couponmsg" style="border-color: #ebccd1;border-radius: 5px;margin-bottom: 10px;padding: 8px;margin-top: 10px;display: none;"></div>
        <div style="float: right;margin-bottom: 10px;margin-top: 10px;" id="couponcontainer">
           <i class="icon-ticket icon-large" ></i>&nbsp;
           <input type="text" class="input"  placeholder="Enter coupon code" id="coupon_code" name="coupon_code" style="width: 220px;" />
           <button class="btn" id="btnapplycoupon" style="margin-left: 5px;">Apply</button>
        </div>
        <br>
    <?php }?>
     <table class="table table-bordered" id="cartcontainer">
            <thead class="alert alert-info">
                <tr class="success">
                    <th style="width: 40px;">#</th>
                    <th>Price($)</th>
                    <th>Duration</th>
                    <th>Access Type</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $xx = 0;
                foreach($pricelist as $i=>$value) {

                    $price = $value['scheme_price'];

                    $option_id = $value['id'];
                    $subprice = $price;
                    $subpricebeforecoupondiscount =$price;
                    $chk_price = '<span id="displayprice'.$xx.'">'.$currencysym.' '.number_format($price, 2).'</span>';
                    
                    
                    $duration = ($value['lifetime']=='1') ? "Unlimited" : $value['scheme_days'].($value['scheme_days']>1 ? " days" : " day");
                    $dur = ($value['lifetime']=='1') ? 9999 : $value['scheme_days'];
                    $times = ($value['times']==0) ? "Unlimited" : $value['numbertimes'].($value['numbertimes']>1 ? " times" : " time");  
                    $tms = ($value['times']==0) ? -1 : $value['numbertimes'];

            ?>
                <tr class="warning">
                <td>
                <input type="hidden" id="subpricebeforecoupondiscount<?php echo esc_attr($xx);?>" value="<?php echo esc_attr($subpricebeforecoupondiscount); ?>" />
                <input type="hidden" id="originalprice<?php echo esc_attr($xx);?>" value="<?php echo  esc_attr($price); ?>" />
                    <input type="radio" name="pricescheme" id="pricescheme<?php echo esc_attr($xx);?>" value="<?php echo esc_attr($subprice); ?>" duration="<?php echo esc_attr($dur); ?>" times="<?php echo esc_attr($tms); ?>" option_id="<?php echo esc_attr($option_id); ?>"/></td>
                    <td><?php echo esc_attr($chk_price); ?></td>
                    <td><?php echo esc_attr($duration); ?></td>
                    <td><?php echo esc_attr($times); ?></td>
                </tr>
                <?php if($xx==0){?> 
                <script>
                jQuery(document).ready(function () {
                    jQuery("#pricescheme<?php echo esc_attr($xx);?>").trigger("click");
                });
                </script>
                <?php }?>
            <?php

            $xx++;
                }
            ?>
            </tbody>
        </table>
        
         <div id="paymentcontainer">
         <input type="hidden" id="priceoptioncounter" value="<?php echo esc_attr($xx);?>" />
        <input type="hidden" id="class_coupon_code" value="" />
         <?php
            if ($paymentInfo['type'] == '1') {
        ?>
        <div class="row">
            <div class="span5">
                <fieldset>
                    <p style="display:none" class="alert payment-message"></p>
                    <input type="hidden" name="access_token" id="access_token" value="<?php echo esc_attr($paymentInfo['access_token'])?>">
                    <input type="hidden" name="item_number" id="item_number" value="">
                    <div class="control-group">
                        <label style="width: 140px; padding-top: 5px; float: left; text-align: right;">Cardholder Name</label>
                        <div style="margin-left: 160px;">
                            <input type="text" tabindex="4" class="required" name="full_name" id="full_name">
                        </div>
                    </div>
                    <div class="control-group">
                        <label style="width: 140px; padding-top: 5px; float: left; text-align: right;">Card Number &amp; CCV</label>
                        <div style="margin-left: 160px;">
               <input type="text" tabindex="5" name="card-number" class="card-number stripe-sensitive required" autocomplete="off" style="width: 130px;" maxlength="16">
               <input type="text" tabindex="6" name="card-cvc" class="card-cvc stripe-sensitive required" autocomplete="off" style="width: 50px;" maxlength="16">
                            <i class="icon-lock"></i>
                        </div>
                    </div>
                    <div class="control-group">
                        <label style="width: 140px; padding-top: 5px; float: left; text-align: right;">Expiration Date</label>
                        <div style="margin-left: 160px;">
                            <select tabindex="7" class="card-expiry-month stripe-sensitive required" style="width: 60px;">
                                <option selected="" value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
                            <span> / </span>
                            <select tabindex="8" name="card-expiry-year" class="card-expiry-year stripe-sensitive required" style="width: 80px;"><option selected="" value="2013">2013</option><option value="2014">2014</option><option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option></select>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="span3 helptext">
                <p>Your payment information is encrypted and securely processed by <a href="https://stripe.com" target="_blank" class="stripe">Stripe</a>.</p>
            </div>
        </div>
        <?php  } else {    ?>

        <img src="<?php echo esc_url(VC_URL)?>/images/secured-by-paypal.jpg" /> 

        <?php  }  ?>
        </div>
   
   
    <input type="hidden" name="class_final_amount" id="class_final_amount" value="">
    <input type="hidden" name="class_price_id" id="class_price_id" value="">
    <h5 style="float: left;font-size: 20px;line-height: 35px;margin: 0;" class="price_class">Subtotal:&nbsp;&nbsp;</h5>
    <div style="float: left;margin-top:4px;font-color:blue;" class="price_class" id="subvalue"></div>
    <div id="btncontainer" style="float: right;"> <button id="btnCheckout" class="btn btn-primary">Buy Class</button>
    </div><div id="txtprocessing" style="display:none;float: right;">Processing... Don't close.</div>  
    <p></p>  
    </div>
    <?php } ?>
</div>
<?php 
    wp_enqueue_script('dg', 'https://www.paypalobjects.com/js/external/dg.js');
    if(strpos($base_url, 'braincert.org') !== false) {
        $paypalurl = 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay';
    } else {
        $paypalurl = 'https://www.paypal.com/webapps/adaptivepayment/flow/pay';
    }

   // $paypalurl = 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay';
    //$paypalurl = 'https://www.paypal.com/webapps/adaptivepayment/flow/pay';
?>
<form action="<?php echo esc_url($paypalurl);?>" target="PPDGFrame" class="standard" >
<input type="image" id="submitBtn" value="Pay with PayPal" style="display: none;">
<input id="type" type="hidden" name="expType" value="lightbox">
<input id="paykey" type="hidden" name="paykey" value="">
</form>
<script type="text/javascript" charset="utf-8">
var embeddedPPFlow = new PAYPAL.apps.DGFlow({trigger: 'submitBtn'});
if (window != top) {  
top.location.replace(document.location); 
}  
embeddedPPFlow = top.embeddedPPFlow || top.opener.top.embeddedPPFlow;
embeddedPPFlow.closeFlow();
</script>
<style type="text/css">
    .ui-widget.ui-widget-content{
        position: fixed !important;
        top:5% !important;;
    }
</style>
<script type="text/javascript">
function loginpopup(surl){
    window.location.href ="<?php echo esc_attr(site_url());?>/wp-login.php?redirect_to="+surl;
}
jQuery(document).ready(function (){

    jQuery(".close").click(function(event) {
        jQuery(".modal").hide();
    });

    jQuery("body").on('click',"#btnFreeCheckout",   function() {
            jQuery("#btncontainer").css('display','none');
            jQuery("#txtprocessing").css('display','');
            var orgamount = jQuery("#class_final_amount").val();
            var class_id = '<?php echo esc_attr($id);?>';
            var price_id = jQuery("#class_price_id").val();
            var cancelUrl = '<?php echo esc_url($ogurl)?>';
            var returnUrl = '<?php echo esc_url($ogurl);?>&task=returnpayment&class_id='+class_id+'&amount='+orgamount+'&payment_mode=paypal';

            var card_holder_name = jQuery(".full_name").val();
            var card_number = jQuery(".card-number").val();
            var card_cvc = jQuery(".card-cvc").val();
            var card_expiry_month = jQuery(".card-expiry-month").val();
            var card_expiry_year = jQuery(".card-expiry-year").val();
            var student_email = '<?php echo esc_attr($login_user_email);?>';
            var class_coupon_code = jQuery("#class_coupon_code").val();
            jQuery.ajax({
                url: "<?php echo esc_url($ogurl); ?>&task=class_checkout",
                type: "POST",
                data: {class_id: class_id,price_id:price_id,cancelUrl:cancelUrl,returnUrl:returnUrl,card_holder_name:card_holder_name,card_number:card_number,card_cvc:card_cvc,card_expiry_month:card_expiry_month,card_expiry_year:card_expiry_year,student_email:student_email,coupon_code:class_coupon_code},
                success: function(result) {
                    var obj = jQuery.parseJSON(result);
                    var url = "<?php echo esc_url($ogurl);?>&task=returnpayment&class_id="+class_id+"&amount="+orgamount+"&payment_mode=discount";
                    window.top.location.href = url;
                }
            });
         });

        jQuery("#btnCheckout").click(function (event) {

            var plan_commission = '<?php echo esc_attr($getplan['commission']);?>';
            <?php if($paymentInfo['type'] == '0'){ ?> 
            if(plan_commission==0){
                jQuery('#paypal_form_one_time').submit();
                return false;
            }
            <?php } ?>

            jQuery("#btncontainer").css('display','none');
            jQuery("#txtprocessing").css('display','');

            var orgamount = jQuery("#class_final_amount").val();
            var class_id = '<?php echo esc_attr($id);?>';
            var price_id = jQuery("#class_price_id").val();
            var cancelUrl = '<?php echo esc_url($ogurl)?>';
            var returnUrl = '<?php echo esc_url($ogurl);?>&task=returnpayment&class_id='+class_id+'&amount='+orgamount+'&payment_mode=paypal';

            var card_holder_name = jQuery(".full_name").val();
            var card_number = jQuery(".card-number").val();
            var card_cvc = jQuery(".card-cvc").val();
            var card_expiry_month = jQuery(".card-expiry-month").val();
            var card_expiry_year = jQuery(".card-expiry-year").val();
            var student_email = '<?php echo esc_attr($login_user_email);?>';
            var class_coupon_code = jQuery("#class_coupon_code").val();
            jQuery.ajax({
                url: "<?php echo esc_url($ogurl); ?>&task=class_checkout",
                type: "POST",
                data: {class_id: class_id,price_id:price_id,cancelUrl:cancelUrl,returnUrl:returnUrl,card_holder_name:card_holder_name,card_number:card_number,card_cvc:card_cvc,card_expiry_month:card_expiry_month,card_expiry_year:card_expiry_year,student_email:student_email,coupon_code:class_coupon_code},
                success: function(result) {
                    var obj = jQuery.parseJSON(result);

                    if(obj.status=="error"){
                        jQuery(".card_error").show().html(obj.error);
                    }
                    if(obj.status=="ok"){
                        jQuery(".card_error").hide();
                        if(obj.payKey){
                            jQuery("#paykey").val(obj.payKey);
                            jQuery("#submitBtn").trigger('click');
                            jQuery('#modal-content-buying').hide();

                        }else{
                            if(obj.charge_id){
                            var url = "<?php echo esc_url($ogurl);?>&task=returnpayment&class_id="+class_id+"&amount="+orgamount+"&payment_mode=stripe";
                            window.top.location.href = url;
                            }    

                        }   
                    }
                    jQuery("#btncontainer").css('display','block');
                    jQuery("#txtprocessing").css('display','none');
                }
            });
         });

        jQuery('input[name=pricescheme]').click(function (event) {
            var selval = jQuery(this).val();
            jQuery('#subvalue').text("<?php echo esc_attr($currencysym);?>" + selval);
            var _amnt=returnMoney(selval);
            var _option_id=jQuery(this).attr('option_id');
            jQuery("#class_final_amount").val(_amnt);
            jQuery("#one_time_amount").val(_amnt);
            var class_id = '<?php echo esc_attr($id);?>';
            var returnUrl_one_time = '<?php echo esc_attr($ogurl);?>&task=returnpayment&class_id='+class_id+'&amount='+_amnt+'&payment_mode=paypal';  
            jQuery("#return_url").val(returnUrl_one_time);

            var base_url_api = '<?php if(strpos($base_url, 'braincert.org') !== false) { echo "https://www.braincert.org/";}else{ echo "https://www.braincert.com/";}?>';

            var ipnurl = base_url_api+'index.php?option=com_classroomengine&view=classdetails&task=returnpaypalapi&Id='+class_id+'&student_email=<?php echo esc_attr($current_user->user_email);?>&item_number='+_option_id;

            jQuery(".one_time_notify_url").val(ipnurl);
            jQuery("#class_price_id").val(_option_id);
        });
        jQuery("#btnapplycoupon").click(function (event) {
            if (jQuery("#coupon_code").val() == "") {
                alert("Please enter coupon code!");
                return;
            }
        
            var class_id = '<?php echo esc_attr($id);?>';

            jQuery.ajax({
                url: "<?php echo esc_url($ogurl); ?>&task=validatecoupon",
                cache:false,
                data: {class_id: class_id, coupon_code: jQuery("#coupon_code").val()},
                success: function (result) {  
                    var result = jQuery.parseJSON(result);
                    if (result.status == "ok") {

                        var cnt = jQuery("#priceoptioncounter").val();
                        var discount_type = result.discount_type
                        var discount_value = parseFloat(result.discount_value);
                        jQuery("#class_coupon_code").val(jQuery("#coupon_code").val());
                        var coupon100 = 0;
                        for(i=0;i<cnt;i++){
                            var baseprice = parseFloat(jQuery("#subpricebeforecoupondiscount"+i).val());
                            var originalprice = parseFloat(jQuery("#originalprice"+i).val());
                            disprice = discount_value;
                            if(discount_type=='percentage') {
                             disprice = (baseprice*discount_value)/100;
                            }
                          
                            var newprice = baseprice - disprice;
                            newprice = newprice.toFixed(2);
                            originalprice = originalprice.toFixed(2);
                            
                            if(newprice <= 0 ){
                                coupon100 = 1;
                            }
                           
                           jQuery("#pricescheme"+i).val(newprice);
                           html = '<strike style="font-style: italic;" ><?php echo esc_attr($currencysym);?>'+originalprice+'</strike></span>&nbsp;<span style="color: red;" ><?php echo esc_attr($currencysym); ?> '+newprice+'</span>';
                           jQuery("#displayprice"+i).html(html);
                           jQuery("#couponmsg").css('display', 'block').css('color', '#468847').css('background-color', '#dff0d8');
                           jQuery("#couponmsg").html("Coupon has been applied");
                        if(coupon100==1){
                                 opened = 1;
                                 jQuery('#cartcontainer').css('display','none');
                                 jQuery('#couponcontainer').css('display','none');
                                 jQuery('#paymentcontainer').css('display','none');
                                 jQuery('.price_class').css('display','none');
                                 
                                  jQuery('#btncontainer').html('<button id="btnFreeCheckout" class="btn btn-success">Enroll</button></div><div id="txtprocessing" style="display:none;">Processing... Don\'t close.');
                                 jQuery("#couponmsg").html("100% Coupon has been applied");
       
                            }
                            jQuery("#pricescheme0").trigger("click");
                        }
                    }
                    else{
                            
                            jQuery("#couponmsg").css('color', '#a94442').css('background-color', '#f2dede').css('display', 'block');
                            jQuery("#couponmsg").html("The coupon code that you entered is invalid. Please enter a different code");
                         }
                },
                type: "POST"
            });

        });
    });

        function returnMoney(number) {
                var nStr = '' + Math.round(parseFloat(number) * 100) / 100;
                var x = nStr.split('.');
                var x1 = x[0];
                var x2 = x.length > 1 ? '.' + x[1] : '.00';
                var rgx = /(\d+)(\d{3})/;
                
                while (rgx.test(x1)) {
                    x1 = x1.replace(rgx, '$1' + ',' + '$2');
                }
                
                return x1 + x2;
        }
        function buyingbtn(classid){
            jQuery("#modal-content-buying").show();
            jQuery("#pricescheme0").trigger("click");

        }
        function viewRecordedVideo(filename,fname){
            jQuery(".video_scr").attr("src",filename);
            jQuery( "#modal_recording_content" ).show();
        }
        function popup(url)
        {
         params  = 'width='+screen.width;
         params += ', height='+screen.height;
         params += ', top=0, left=0'
         params += ', fullscreen=yes,directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,addressbar=no';

         newwin=window.open(url,'windowname4', params);
         if (window.focus) {newwin.focus()}
         return false;
        }
    </script>

<?php if($row->sharing_code){ 
    wp_enqueue_script('addthis_widget', 'https://s7.addthis.com/js/300/addthis_widget.js#pubid='.esc_attr($row->sharing_code).'');
 } ?>

<div class="addthis_sharing_toolbox"></div>
<div style="padding-top: 10px;padding: 25px;margin-top:5px;border-top: 1px ridge;border-left: ridge;width: 168%;border-radius: 21px; box-shadow: 10px 10px 10px #0101ab45;" class="class-detail l-preview">
<br />

    
                

        <div class="class-details-title" style="border: none;">
            <div style="width: 80%;float: left;"><?php echo esc_attr($result[0]['title']); ?></div>
            <div style="width: 30%;" class=" span12 status-div">
                <?php if($result[0]['isCancel']==1 || $result[0]['isCancel']==2){ ?>
                    <span class="vc-alert vc-alert-danger class-status">Cancled</span>
                <?php }else{ ?>
                    <span class="<?php echo esc_attr($class);?> class-status"><?php echo esc_attr($result[0]['status']); ?></span>
                <?php }?>
                
            </div>
        </div>
        <hr style="border-bottom: 4px solid #d1d1d1;clear: both;">

<div style="width:97%;" class="well col-md-8">
    <div style="margin-top:2px;"></div>
        <p class="datecalrow"><span class="vctitlepink">Date and Time:</span> 
        <?php if($result[0]['status'] =='Upcoming' && !empty($result[0]['class_next_date'])) { ?>
                                 <i class="icon icon-calendar"></i> <?php echo esc_attr(gmdate('M j, Y', $result[0]['class_next_date']));

                            }else {?>   

         <i class="icon icon-calendar"></i>&nbsp;<?php echo esc_attr(gmdate("M j, Y",strtotime($result[0]['date']))); }?>  
        <i class="icon icon-time"></i> <?php echo esc_attr($result[0]['start_time']); ?> 
    
    <br>
        <span class="vctitlepink">Time Zone:</span> <?php echo esc_attr($result[0]['timezone_label']); ?> 
    <br> 
        <span class="vctitlepink">Duration:</span> <?php echo esc_attr($result[0]['duration'])/60; ?> minutes 
    <br> 
    <span class="vctitlepink">Description:</span>
    <div>  <?php echo esc_attr($result[0]['description']); ?> </div>
    </p>
    <p class="datecalrow">
        <span class="vctitlepink">Keywords:</span> <?php echo esc_attr($result[0]['keyword']); ?>
    </p>
    
    <?php
                            $item = $result[0];
                            $enrolled  = $wpdb->get_var($wpdb->prepare($wpdb->prepare("SELECT count(*) FROM ".$wpdb->prefix."virtualclassroom_purchase WHERE `class_id` = %s AND payer_id=%s",array($id,get_current_user_id())),''));
                            
                            $isteacher  = $wpdb->get_var($wpdb->prepare($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %s",array(get_current_user_id())),''));

                            $current_user = wp_get_current_user();
                            

                            if($isinstructor==1 || $item['instructor_id']==$current_user->ID){
                                $enrolled = 1;
                            }

                            if(( ($item['ispaid']==1 && $item['status']!="Past" && $enrolled==0 && $current_user->ID !=0 && $isteacher == 0 ) || ($item['ispaid']==1 && $islearner==1) ) && get_current_user_id() !=0 && $item['isCancel']==0){?>
                                <button class="btn btn-danger btn-sm" onclick="buyingbtn(<?php echo esc_attr($id); ?>); return false;" id=""><h4  style="margin: 0px;" class=" "><i class="icon-shopping-cart icon-white"></i> Buy</h4></button>
                                <?php
                            }
                            if((($item['status'] == "Live" && $enrolled) || $item['ispaid']==0 || $isteacher == 1 ) && get_current_user_id() !=0 && $item['isCancel']==0){

                            $data1['userId'] = sanitize_text_field($current_user->ID);
                            $data1['userName'] = sanitize_text_field($current_user->display_name);
                            $data1['lessonName'] = sanitize_text_field($item['title']);
                            $data1['courseName'] = sanitize_text_field($item['title']);
                            global $wpdb;
                            $is_tchr  = $wpdb->get_var($wpdb->prepare("SELECT is_teacher FROM ".$wpdb->prefix."virtualclassroom_teacher WHERE `user_id` = %s",array($current_user->ID)));

                            $data1['isTeacher'] = 0;
                            if($item['instructor_id']>0 && $item['instructor_id']==$current_user->ID){
                                    $data1['isTeacher'] = 1;       
                            }else if($item['created_by']>0 && $item['created_by']==$current_user->ID){
                                $data1['isTeacher'] = 1;
                            }else if($is_tchr == 1){
                                $data1['isTeacher'] = 1; 
                            }
                            if($islearner==1){
                                $data1['isTeacher'] = 0;     
                            }
                            $data1['task'] = sanitize_text_field('getclasslaunch');
                            $data1['apikey'] = sanitize_text_field($key);
                            $data1['class_id'] = sanitize_text_field($item['id']);
                            $launchurl = (object)$vc_obj->vlcr_get_curl_info($data1);
                           /* echo $is_tchr.'is_tchr';*/
                           $mins = $item['class_starts_in'] / 60;
                           $before_time=0;
                           if(strtolower($item['status'])=="upcoming" && $mins>0 && $mins<=30 && $is_tchr==1 ){
                                $item['status'] = "live";
                                $before_time=1;
                            } 
                            $url='';
                            if(isset($launchurl->encryptedlaunchurl) && strtolower($item['status']) == "live"){
                                    $url = str_replace("'\'","",$launchurl->encryptedlaunchurl);
                             }
                            if($url){ ?>
                            <br>
                            <?php 
                               global $post;
                                 ?>
                                <?php if($before_time==1 && $islearner!=1){ ?>
                                    <div> 
                                        <a target="_blank" class="btn btn-primary btn-lg" style="font-weight: bold;" id="launch-btn" onclick="popup('<?php echo esc_url($url) ?>'); return false;">Enter to prepare class</a>
                                    </div>    
                                <?php }else{ ?>
                                    <div> 
                                        <a target="_blank" class="btn btn-primary btn-lg" style="font-weight: bold;" id="launch-btn" onclick="popup('<?php echo esc_url($url) ?>'); return false;">Launch</a>
                                    </div>
                                <?php }?>
                                
                                <?php } ?>
                                <?php
                              }else{ ?>
                                <?php if(get_current_user_id() ==0 && $item['isCancel']==0){ ?> 
                                <button class="btn btn-danger btn-sm"  onclick="loginpopup('<?php echo esc_url(get_permalink($post->ID)); ?>'); return false;"><h4  style="margin: 0px;" class="">Login</h4></button>
                                <br style="margin-bottom: 20px;">
                                <?php } ?>
                              <?php }
$item = $result[0];
//echo "<pre>";print_r($item);echo "</pre>";
$diff=$item['class_starts_in'];
wp_enqueue_script('vlcr_countdown', VC_URL. '/js/vlcr_countdown.js');

    if((($item['ispaid'] == 1 && $item['status'] =="Upcoming" && $enrolled ) || ( $item['status'] =="Upcoming" && $item['ispaid'] == 0 ) || $isteacher == 1 ) && $before_time!=1 && $item['isCancel']==0){  ?>
        <script type="application/javascript">

    var myCountdownTest = new Countdown({
                        width   : 400, 
                        height: 70,
                        time:<?php echo esc_attr($diff) ;?>
                       });


    var counter_diff = <?php echo esc_attr($diff) ;?>;
    var is_reloaded=0;
    var interval = setInterval(function() {
        counter_diff--;
        console.log(counter_diff);
        if(counter_diff<=0 && is_reloaded==0){
            is_reloaded=1;    
            location.reload();         
        }
    }, 1000);
    </script> 

<?php   } ?>

  
    

    <?php
    $limit = 20;                                //how many items to show per page
    $results=$vc_obj->vlcr_listrecording('',$limit,$item['id']);
    $has_access=0;

    $current_user = wp_get_current_user();
    $is_shared  = $wpdb->get_var($wpdb->prepare("SELECT count(*) FROM ".$wpdb->prefix."virtualclassroom_shared_users WHERE `class_id` = %s AND email=%s",array($item['id'].$current_user->user_email)));

    if(!empty($allowClass_list)){
        if (in_array($item['id'], $allowClass_list)){
            $has_access=1;
        }
    }
    $is_super_admin = is_super_admin(get_current_user_id());
    if($enrolled>0 || $isteacher==1 || $is_shared>0 || $is_super_admin>0){
        $has_access=1;
    }
    
    if($item['record'] != 0 && $has_access==1){?>

    <br>
    <div class="listing">
        <div style="display: inline;border: 1px solid #d1d1d1;border-width: 1px 1px 0 1px;margin: 0 5px 0 0;padding: 10px 10px 6px 10px;border-radius: 5px 5px 0px 0px;font-weight: bold;">Class Recordings</div>
        <table class="table table-bordered">
        <thead class="alert alert-info" style="background: #00b9eb;color: white;">
            <tr>
                <th style="width: 35px;">#</th>
                <th>Name</th>
                <th>Date/Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            for($i = 0; $i < count($results) ; $i++ ) {
            $result = $results[$i];
            if($result['status']==1){
            ?>
            <tr>
            <td><b><?php echo esc_attr($i+1);?></b></td>
            <td><?php echo $result['fname']?esc_attr($result['fname']):"Recording ".esc_attr($i+1);?> </td>
            <td><?php echo esc_attr($result['date_recorded']);?></td>
            <td><i class="fa fa-facetime-video"></i>&nbsp;<a  href="javascript:void(0)" onclick="viewRecordedVideo('<?php echo esc_url($result['record_path']); ?>', '<?php echo rawurlencode($result['fname']); ?>');">View Class Recording</a> </td>
            </tr>
            <?php } } ?>
        </tbody>
        </table>
    </div>
    <?php }?>
</div>
</div>

<div id="modal_recording_content" class="modal">
    <div class="modal-content">
        <span><b>View Recording</b></span>
        <span class="close">&times;</span>
        <div>
        <video width="800" height="500" controls class="video_scr" src=""></video>
        </div>
    </div>
</div>

<form action="https://www.<?php echo strpos($base_url, 'braincert.org') !== false ? 'sandbox.' : ''; ?>paypal.com/cgi-bin/webscr" method="post" class="paypal-form" target="_top" id="paypal_form_one_time">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="amount" id="one_time_amount" value="">
<input type="hidden" name="business" value="<?php echo esc_attr($paymentInfo['paypal_id']); ?>">
<input type="hidden" name="item_name" value="<?php echo esc_attr($result[0]['title']); ?>">
<input type="hidden" name="currency_code" value="<?php echo esc_attr(strtoupper($result[0]['currency'])); ?>">
<input type="hidden" name="no_note" value="1">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="rm" value="1">
<input type="hidden" name="custom" value="">
<input type="hidden" name="return" id="return_url" value="">
<input type="hidden" name="cancel_return" value="<?php echo esc_url($ogurl); ?>">
<input type="hidden" name="notify_url" class="one_time_notify_url" value="">
</form>

<style type="text/css">
   @media(max-width: 430px){.class-detail{width: 170% !important;}#wpadminbar{width: 153%;}#masthead{width: 153%;}}

</style>