<?php
/**
 * virtual-classroom
 *
 *
 * @author   BrainCert
 * @category Recording List
 * @package  virtual-classroom
 * @since    2.7
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script('vlcr_script',VC_URL.'/js/vlcr_script.js');

wp_enqueue_script('vlcr_video',VC_URL.'/js/vlcr_video.js');

echo '<h3>View Recordings</h3>';

if(isset($_REQUEST['task'])){
	include_once('vlcr_action_task.php');	
}
$vc_obj = new vlcr_class();
$vc_setting=$vc_obj->vlcr_setting_check();
if($vlcr_setting==1){
    echo "Please setup API key and URL";
    return;
}

if($_REQUEST['id'] && $_REQUEST['type']=="viewrecording"){
    $_REQUEST['cid'] = $_REQUEST['id'];
}
$search = isset($_REQUEST['search']) ? $_REQUEST['search'] : '';
$targetpage = "admin.php?page=".VC_FOLDER."/vlcr_setup.php/RecordingList";  	//your file name  (the name of this file)
$limit = 10; 								//how many items to show per page
$result=$vc_obj->vlcr_listrecording($search,$limit,$_REQUEST['cid']);

if(isset($result[0]['id'])){
?>
<div class="video-area">
  <div class="video-list-area">
    <h4>Recordings List : </h4>
    <select name="videourl" id="videourl">
    <?php foreach($result  as $i => $item){ ?>
        <option value="<?php echo esc_url($item['record_path'])?>"><?php echo $item['fname'] ? esc_attr($item['fname']) : 'Recording - '.esc_attr($i);?></option>
    <?php } ?>    
    </select>
  </div>


<video
    id="my-video"
    class="video-js vjs-default-skin"
    controls
    width="800" height="350"
    >
</video>
</div>
<?php
}else{ ?>
    <h2>
    <div class="error">
                <p><?php echo esc_attr($result['Recording']); ?></h2></p></div>
<?php } ?>
<script type="text/javascript">


 (function ($) {
        $(document).ready(function () {
            var videourl = jQuery('#videourl').val();
            var player = videojs('my-video', {
                controls: true,
                sources: [{src: videourl, type: 'video/mp4'}],
                techOrder: ['youtube', 'html5']
            });


            $('#videourl').on('change', function () {
               var videourl = jQuery('#videourl').val();
                var sources = [{"type": "video/mp4", "src": videourl}];
                player.pause();
                player.src(sources);
                player.load();
                player.play();

            });


        });
    })(jQuery);

</script>