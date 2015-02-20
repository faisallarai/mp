<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\models\UserSetting */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-setting-form">
    <?php 
    $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']]); // important         
         ?>

        <div class="col-md-8">
         <!-- Nav tabs -->
         <ul class="nav nav-tabs" role="tablist">
           <li class="active"><a href="#general" role="tab" data-toggle="tab"><?= Yii::t('frontend','General Settings') ?></a></li>
           <li><a href="#photo" role="tab" data-toggle="tab"><?= Yii::t('frontend','Upload Photo') ?></a></li>
         </ul>
         <!-- Tab panes -->
         <div class="tab-content">
           <div class="tab-pane active vertical-pad" id="general">
               <?= $form->field($model, 'reminder_eve')->checkBox(['label' => Yii::t('frontend','Send final reminder the day before a meeting'), 'uncheck' =>  $model::SETTING_NO, 'checked' => $model::SETTING_YES]); ?> 

               <?= $form->field($model, 'reminder_hours')
                       ->dropDownList(
                           $model->getEarlyReminderOptions(),   
           	                ['prompt'=>Yii::t('frontend','When would you like your early reminder?')] 
           	            )->label(Yii::t('frontend','Early reminders')) ?>

                  <?= $form->field($model, 'contact_share')->checkbox(['label' =>Yii::t('frontend','Share my contact information with meeting participants'),'uncheck' =>  $model::SETTING_NO, 'checked' => $model::SETTING_YES]); ?> 

                  <?= $form->field($model, 'no_email')->checkbox(['label' =>Yii::t('frontend','Turn off all email'),'uncheck' =>  $model::SETTING_NO, 'checked' => $model::SETTING_YES]); ?>
                </div>
           <div class="tab-pane vertical-pad" id="photo">

           </div> <!-- end of upload photo tab -->
           <div class="form-group">
               <?= Html::submitButton(Yii::t('frontend', 'Save Settings'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
           </div>
         </div> <!-- end tab content -->
         </div> <!--end left col -->
         <div class="col-md-4">
           
         </div> <!--end rt col -->
    <?php ActiveForm::end(); ?>

</div>