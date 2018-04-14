<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron">
         <div class="col-xs-12">
            <div class="col-md-12">
               <div class="row">
                    <div class="col-md-2">
                       <img src="<?php echo $model->u_image;?>">            
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-2">
                                <b><?php echo $model->u_media;?></b><br>Posts
                            </div>
                            <div class="col-md-2">
                                <b><?php echo $model->u_followed_by;?></b><br>Followers
                            </div>   
                            <div class="col-md-2">
                                <b><?php echo $model->u_follows;?></b><br>Following
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 text-left text-bold">
                               <h4> <?php echo ucwords($model->u_first_name).' '.ucwords($model->u_last_name);?></h4>
                            </div>                        
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="text-align:left">
                                <?php echo nl2br($model->u_bio);?>
                            </div>                        
                        </div>
                    </div>
                </div>
            </div>
        </div>       
    </div>
    <div class="body-content">
    

    </div>
</div>
