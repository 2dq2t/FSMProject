<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\Route $model
 * @var ActiveForm $form
 */

$this->title = Yii::t('app', 'Create Route');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Routes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
	<?php
	echo lavrentiev\yii2toastr\Toastr::widget([
		'type' => (!empty($message['type'])) ? $message['type'] : 'success',
		'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
		'message' => (!empty($message['message'])) ? $message['message'] : 'Message Not Set!',
		'clear' => false,
		'options' => [
			"closeButton" => true,
			"positionClass" => "toast-top-right",
			"timeOut" => (!empty($message['duration'])) ? Html::encode($message['duration']) : 0,
		]
	]);
	?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">

	<?= Html::a('<i class="fa fa-angle-left"></i> '. Yii::t('app', 'Back'), ['route/index'], ['class' => 'btn btn-default btn-circle']) ?>

    <?= Html::submitButton('<i class="fa fa-check-circle"></i> ' . Yii::t('app', 'Save &amp; Continue'), ['class' => 'btn green-haze btn-circle', 'name' => 'action', 'value' => 'next']) ?>

	<?= Html::submitButton('<i class="fa fa-check"></i> ' . Yii::t('app', 'Create') , ['class' => 'btn green-haze btn-circle', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<div class="row">
	<div class="col-md-12">
		<div class="portlet box green">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-tags"></i><?= $this->title ?>
				</div>
				<div class="tools">
					<a href="javascript:;" class="collapse">
					</a>
					</a>
					<a href="javascript:;" class="reload">
					</a>
					<a href="javascript:;" class="remove">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<!-- BEGIN FORM-->
				<?php $form = ActiveForm::begin(); ?>
				<div class="form-body">
					<div class="form-group">
						<?= $form->field($model, 'route')->textInput(['placeholder' => Yii::t('app', 'Enter route name')]) ?>
					</div>
				</div>
				<div class="form-actions">
					<div class="btn-set pull-right">
						<?php echo $this->blocks['submit']; ?>
					</div>
				</div>
				<?php ActiveForm::end(); ?>
				<!-- END FORM-->
			</div>
		</div>
	</div>
</div>