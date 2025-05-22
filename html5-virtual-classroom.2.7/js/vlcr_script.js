jQuery(document).ready(function () {
	jQuery(".message" ).addClass("vc-alert vc-alert-danger");
	jQuery(".message" ).removeClass("message");
	jQuery(".system-message dt" ).css("display",'none');
	jQuery("#system-message > dd > ul" ).css("border","0");
		
    jQuery("#is_recurring_yes").click(function() {
            jQuery(".recurring_class").show("slow");
    });

    jQuery("#allow_change_language_1").click(function() {
            jQuery("#force_language").hide("slow");
    });
    jQuery("#allow_change_language_2").click(function() {
            jQuery("#force_language").show("slow");
    });
    jQuery(".record_1").click(function() {
            jQuery(".record_auto").show("slow");
            jQuery(".video_delivery").show("slow");
            
    });
    jQuery(".record_2").click(function() {
            jQuery(".record_auto").hide("slow");
             jQuery(".video_delivery").hide("slow");
    });
    
    
    
    jQuery("#is_recurring_no").click(function() {
            jQuery(".recurring_class").hide("slow");
    });

    if(jQuery('#is_recurring_yes').is(':checked')){
        jQuery(".recurring_class").show();
    }

    if(jQuery('#class_type_radio').is(':checked')){
        jQuery("#currencycontainer").hide("slow");
    }

    jQuery( "#class_type_radio").click(function() {
        jQuery("#currencycontainer").hide("slow");
    });

    jQuery( "#class_type_radio2").click(function() {
        jQuery("#currencycontainer").show("slow");
    });

    jQuery("#coupon-never-expires").click(function (event){
           if(jQuery(this).is(':checked') == true) {
        jQuery('#end_date').attr('disabled', '');
                jQuery('#end_date').val("");
        
        
            } else {
        jQuery('#end_date').removeAttr('disabled');
            }
        });
   jQuery("#use_discount_code").click(function (event){
        if(jQuery(this).is(':checked') == true) {
            jQuery("#use_discount_code_div").css("display", "block");
        } else {
            jQuery("#use_discount_code_div").css("display", "none");
        }
        });

	  jQuery("#lifetimechk").click(function (event){
            if(jQuery(this).is(':checked') == false) {
                jQuery('#scheme_days').removeAttr('disabled');
                jQuery('#scheme_days').focus();
                jQuery('#lifetime').val(0);
            } else {
                jQuery('#scheme_days').attr('disabled', 'disabled');
                jQuery('#scheme_days').val("");
                jQuery('#lifetime').val(1);
            }
        });

    if(jQuery('#times').val() == 1){
        jQuery("#numtimes_div").show();
    } 
jQuery( "#coupon-type" ).change(function() {
      if(jQuery( "#coupon-type" ).val() == '0'){
        jQuery("#fixed_amount").css("display", "inline-block");
        jQuery("#percentage").css("display", "none");
        }

      if(jQuery( "#coupon-type" ).val() == '1'){
        jQuery("#fixed_amount").css("display", "none");
        jQuery("#percentage").css("display", "inline-block");
        }
     });

	   if(jQuery( "#coupon-type" ).val() == '0'){
        jQuery("#fixed_amount").css("display", "inline-block");
        jQuery("#percentage").css("display", "none");
        }

      if(jQuery( "#coupon-type" ).val() == '1'){
        jQuery("#fixed_amount").css("display", "none");
        jQuery("#percentage").css("display", "inline-block");
        }

    jQuery("#times").change(function() {
        if(jQuery('option:selected', this).val() == 1){
            jQuery("#numtimes_div").show("slow");            
        } 
        else{
            jQuery("#numtimes_div").hide("slow");     
        }
    });
        
});

checkAll = function(a, b) {
    b || (b = "cb");
    if (a.form) {
        for (var c = 0, d = 0, f = a.form.elements.length; d < f; d++) {
            var e = a.form.elements[d];
            if (e.type == a.type && (b && 0 == e.id.indexOf(b) || !b)) e.checked = a.checked, c += !0 == e.checked ? 1 : 0
        }
        a.form.boxchecked && (a.form.boxchecked.value = c);
        return !0
    }
    return !1
};

isChecked = function(a, b) {
    "undefined" === typeof b && (b = document.getElementById("adminForm"));
    !0 == a ? b.boxchecked.value++ : b.boxchecked.value--
};

submitForm = function(a,b){
	var form = document.getElementById(a);
	form.action.value = b;
	form.submit();
};
