<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

?>
<?php if(isset($feeds['data']) && !empty($feeds['data'])){
	
	foreach ($feeds['data'] as $key => $val) { 
?>
	
	<div class="row">
		<div class="cd-md-4">
			<?php /*if($val['type']=='video'){?>
			
				<iframe src="<?php echo $val['videos']['standard_resolution']['url'];?>">      
			
			<?php }else*/if($val['type']=='image'){ ?>
			
				<img src="<?php echo $val['images']['standard_resolution']['url'];?>" alt="image">      
			
			<?php } ?>
		</div>
	</div>

<?php
	}
 }?>