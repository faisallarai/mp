<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Collapse;
?>
<div class="panel panel-default">
  <!-- Default panel contents -->
  <div class="panel-heading"  role="tab" id="headingWho">
    <div class="row">
      <div class="col-lg-10 col-md-10 col-xs-10">
        <h4 class="meeting-view"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseWho" aria-expanded="true" aria-controls="collapseWho"><?= Yii::t('frontend','Who') ?></a></h4>
        <span class="hint-text"><?= Yii::t('frontend','add meeting participants') ?></span>
      </div>
    <div class="col-lg-2 col-md-2 col-xs-2">
      <div style="float:right;">
        <?= Html::a(Yii::t('frontend', ''), ['/participant/create', 'meeting_id' => $model->id], ['class' => 'btn btn-primary  glyphicon glyphicon-plus'])
        // .((!empty($participantProvider) && ($participantProvider->getCount()>0) )?' disabled':'')
        ?>
      </div>
    </div></div></div>
    <div id="collapseWho" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingWho">
      <div class="panel-body">
    <?php
    if (!empty($participantProvider) and $participantProvider->getCount()>0):
    ?>
    <?php //who
      echo $this->render('../participant/_buttons', [
          'model'=>$model,
          'participantProvider' => $participantProvider,
      ]);
     ?>
    <?php else: ?>
    <?php endif; ?>
  </div>
</div>

</div>
