<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<div class="row">
<?php if(isset($feeds['data']) && !empty($feeds['data'])){
	$i=0;
	foreach ($feeds['data'] as $key => $item) { 

		$link = $item['link'];		
		$caption = isset($item['caption']) ? $item['caption']['text'] : '';

		$url = "";

		
?>	
	 <div class="col-md-4">
		  <a href="<?php echo $link; ?>" target="_blank" class="instagram-post">

		  	<?php if($item['type']=='image'){
			
				$url = $item['images']['low_resolution']['url'];
			?>
			<img src="<?php echo $url?>" width="320" height="240">;
			<?php }elseif($item['type']=='video'){

				$url = $item['videos']['low_resolution']['url'];
			?>
			<!-- <iframe width="420" height="270" src="<?php echo $url?>" ></iframe> -->
			<video width="320" height="240" controls>
			    <source src="<?php echo $url?>" type="video/mp4">
			</video>
			<?php }elseif($item['type']=='carousel'){

				$url = $item['carousel_media'][0]['images']['low_resolution']['url'];	
			?>		
				<img src="<?php echo $url?>" width="320" height="240">;
			<?php }?>	
			       
	        <!-- <div class="caption"><?php //echo nl2br($caption); ?></div> -->
	    </a>
	</div>
<?php
		$i++;
		if($i==3){
			$i=0;
			echo '<div class="row"><div class="col-md-12"><hr></div></div>';
		}
	}
 }?>
</div>