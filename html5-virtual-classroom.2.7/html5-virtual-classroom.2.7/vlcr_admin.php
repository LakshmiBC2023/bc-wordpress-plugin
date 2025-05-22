<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category VLCR ADMIN
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div style="padding: 16px; margin-top: 11px; margin-right: 27px; border-radius: 5px; border: 1px solid #ccc; height: 50px;"><span class="item-title"><img src="<?php echo esc_attr(VC_URL)?>/images/logo_bc.png" style="float: left;"> <h2 style="margin: 0px; padding-top: 12px; padding-left: 66px;">Virtual Classroom</h2></div>
<span class="version_latest">You are using the latest version of Virtual Classroom 2.7</span>
<table width="98%" id="vc-panel" style="border: 1px solid rgb(204, 204, 204);">
  <tr>
     <td valign="top" width="65%" style="padding: 10px;"><div class="cpanel">
      <ul class="g" id="vc-items">
        <li> <a href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/ClassList'))?>"> <img width="32" src="<?php echo esc_attr(VC_URL)?>/images/integrations.png"> <span class="item-title"> <span>Classes</span> </span> </a>
        </li>
         <li> <a href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/TeacherList'))?>"> <img width="32" src="<?php echo esc_attr(VC_URL)?>/images/users.png"> <span class="item-title"> <span>Teachers</span> </span> </a>
         </li>  
          <li>
        <a href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/Configuration'))?>">
        <img width="32" src="<?php echo esc_attr(VC_URL)?>/images/icon-conf.png"> <span class="item-title"> <span>Configuration</span> </span></a>
        </li>
        <li> 
            <a href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/Payments'))?>"> <img width="32" src="<?php echo esc_attr(VC_URL)?>/images/payments.png"> <span class="item-title"> <span>Payments</span> </span> </a>
         </li>
         <li> 
            <a href="<?php echo esc_url(admin_url('admin.php?page='.VC_FOLDER.'/vlcr_setup.php/Permissions'))?>"> <img width="32" src="<?php echo esc_attr(VC_URL)?>images/Website_lock.png"> <span class="item-title"> <span>Permissions</span> </span> </a>
         </li>
     </ul></td>
    <td valign="top" style="padding:10px 10px 10px 0;">
        <div class="panel">
            <h4 id="param-page" class="title pane-toggler-down" style="margin: auto; border: 1px solid rgb(204, 204, 204); padding: 5px;">
                <span>About BrainCert</span>
            </h4>
            <div class="pane-slider content pane-down" style="padding-top: 0px; border: 1px solid #ccc; padding-bottom: 0px; overflow: hidden; height: auto;">
                <div style="padding: 15px;">
                    <p style="margin: 0;">BrainCert Virtual Classroom is tailor-made to deliver live classes, meetings, webinars, and conferences to audience anytime and anywhere!<br><br>
Schedule live classes, collect payments, record classes in HD - all from within your WordPress website.<br><br>
If this is your first time here, we recommend you to <a target="_blank" href="<?php echo esc_url('https://www.braincert.com/app/virtualclassroom')?>">signup for your API</a> key first.<br><br>
<a target="_blank" href="<?php echo esc_url('https://www.braincert.com/docs/api/vc')?>">Read API documentation</a>
<br><br>
Visit us <a target="_blank" href="<?php echo esc_url('https://www.braincert.com')?>">www.braincert.com</a>
</p>

               </div>
            </div>
        </div>
    </td>
  </tr>
</table>