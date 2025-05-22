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
$vc_setting=$vc_obj->vlcr_setting_check();
if($vc_setting==1){
    echo "Please setup API key and URL";
    return;
}

$id=$_REQUEST['id'];
$result=$vc_obj->vlcr_attendanceReport($id);
$class_detail=$vc_obj->vlcr_instructorPreview($id);
$class_duration_min = round(intval($class_detail[0]['duration']) / 60);
 
//echo "<pre>";print_r($result);echo "</pre>";//exit;
?>
  <div class="panel-heading"><h3 class="panel-title">Class Attendees</h3></div>
<div class="wrap" style="width: 200%;border: 1px solid;padding: 35px;border-radius: 10px;">

<?php if($result['Report']){
  echo '<div class="update-nag">'.esc_attr($result['Report']).'</div>';
  return;
} 
if(isset($result['status']) && $result['status']=='error'){
    echo '<div class="update-nag">'.esc_attr($result['error']).'</div>';
    return;
}
if(!isset($result['0']['classId'])){
    echo '<div class="update-nag">No record found</div>';
    return;
}
?>

 <?php if(count($result)>0){ ?>
<div id="container" style="width: 100%;">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>

<?php }?>
<table class="wp-list-table widefat striped"> 
  <thead> 
    <tr class="filters"> 
    <th style="width: 2%">ID</th> 
      <th style="width: 20%">Name</th> 
      <th style="width: 18%">Duration</th>
      <th style="width: 15%">Time in</th> 
      <th style="width: 15%">Time out</th>
      <th style="width: 15%">Attendence</th> 
    </tr>
   </thead> 
 <tbody> 
 <?php 
 $i=1;
 $grapharray_teacher = array();
 foreach($result as $data){ 
  $user_info="";
  if($data['userId'] !=0){
    $user_info = $vc_obj->vlcr_get_user_info($data['userId']);
    $grapharray_teacher[$i]['email']= $user_info->user_email;
  }
  $spent_time = strtotime($data['duration']) - strtotime('TODAY');
  $grapharray_teacher[$i]['spent_time']= intval($spent_time / 60);
  ?>
  
 <tr> 
  <td width="5%"><b><?php echo esc_attr($i);?></b></td> 
  <td>
  <?php if($data['userId']!=0){ ?>
  <b><?php echo esc_attr($user_info->display_name);?></b><br><span style="font-size: 12px;">(<?php echo esc_attr($user_info->user_email);?>)</span> <?php } ?></td> 
  <td><?php echo esc_attr($data['duration'])."(".esc_attr($data['percentage']).")";?> </td> 
  <td style="font-size: 13px;">
    <?php foreach ($data['session'] as $time) {?>
        <i class="icon icon-calendar"></i>  <?php echo esc_attr($time['time_in']);?><br>
    <?php } ?>
  </td>

  <td style="font-size: 13px;">
    <?php foreach ($data['session'] as $time) { ?>
        <i class="icon icon-calendar"></i>  <?php echo esc_attr($time['time_out']);?><br>
    <?php } ?>
  </td>
    
  <td><span class="label label-success"><i class="fa fa-ok"></i> <?php echo esc_attr($data['attendance']);?></span></td> 
 </tr> 
 <?php $i++; } ?>
</tbody> 
</table> 
</div>
<?php $count = round( $class_duration_min / 5);?>
    <?php wp_enqueue_script('vlcr.jquery.min', VC_URL. '/js/vlcr.chart.bundle.js');?>
    <style>
    canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
         
    }
    </style>
   
   <?php 
   $color_array = array("#ce0704", "#0315ab", "#7d2020","#8e116b","#43118e","#114d8e","#118e79","#118e17","#568e11","#8e5e11","#420807","#9bb995","#3a1b00");
   ?>
   
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var barChartData = {
            labels: [<?php for ($i = 0; $i <= $count; $i++) {?>'<?php echo esc_attr($i*5);?>-<?php echo esc_attr($i*5+5);?>'<?php if($i!=$count){ ?>,<?php }} ?>],
            datasets: [

            <?php
            $m=0;
             foreach ($grapharray_teacher as $key => $value){ 
              $spenttime = floor($value['spent_time'] / 5);
              ?>
            {
                label: "<?php echo esc_attr($value['email']);?>",
                backgroundColor: '<?php echo esc_attr($color_array[$key])?>',
                borderColor: '<?php echo esc_attr($color_array[$key])?>',
                borderWidth: 1,
                data: [
                    <?php for ($i = 0; $i <= $count; $i++){?><?php echo ($i==$spenttime) ? esc_attr($value['spent_time']) : '""';?><?php if($i!=$count){?>,<?php }?><?php } ?>
                ]
            },
        <?php
        $m=$m+25;
         } ?>
         ]

        };
var myChart = new Chart(ctx, {
    type: 'bar',
    data: barChartData,

    options: {
        width:500,
        height:300,
        scaleShowGridLines: false,
        showScale: false,
        maintainAspectRatio: this.maintainAspectRatio,
        barShowStroke: false,
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>

  