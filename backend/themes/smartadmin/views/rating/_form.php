<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\StarRating;

/* @var $this yii\web\View */
/* @var $model backend\models\Rating */
/* @var $form yii\widgets\ActiveForm */
?>
<?php foreach (Yii::$app->session->getAllFlashes() as $message):; ?>
    <?php
    echo \kartik\widgets\Growl::widget([
        'type' => (!empty($message['type'])) ? $message['type'] : 'danger',
        'title' => (!empty($message['title'])) ? Html::encode($message['title']) : 'Title Not Set!',
        'icon' => (!empty($message['icon'])) ? $message['icon'] : 'fa fa-info',
        'body' => (!empty($message['message'])) ? Html::encode($message['message']) : 'Message Not Set!',
        'showSeparator' => true,
        'delay' => 1, //This delay is how long before the message shows
        'pluginOptions' => [
            'delay' => (!empty($message['duration'])) ? $message['duration'] : 3000, //This delay is how long the message shows for
            'placement' => [
                'from' => (!empty($message['positonY'])) ? $message['positonY'] : 'top',
                'align' => (!empty($message['positonX'])) ? $message['positonX'] : 'right',
            ]
        ]
    ]);
    ?>
<?php endforeach; ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?php if (!$model->isNewRecord): ?>
        <?= Html::a('Preview', ['category/index'], ['class' => 'btn btn-info', 'target' => '_blank']) ?>
    <?php endif; ?>
    <?= Html::a('Back', ['category/index'], ['class' => 'btn btn-danger']) ?>

    <?php if ($model->isNewRecord): ?>
        <?= Html::submitButton('Save & Go next', ['class' => 'btn btn-success', 'name' => 'action', 'value' => 'next']) ?>
    <?php endif; ?>

    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary', 'name' => 'action' , 'value' => 'save']) ?>

</div>
<?php $this->endBlock('submit'); ?>

<section id="widget-grid">
    <!-- START ROW -->
    <div class="row">

        <!-- NEW COL START -->
        <article class="col-sm-12 col-md-12 col-lg-12">

            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1">
                <header>
                    <?php if($model->isNewRecord) { ?>
                        <span class="widget-icon"> <i class="fa fa-plus"></i> </span>
                        <h2>Create Rating</h2>
                    <?php } else { ?>
                        <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                        <h2>Update Rating</h2>
                    <?php } ?>
                </header>

                <!-- widget div-->
                <div>

                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->

                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <?php $form = ActiveForm::begin(['options' => ['class' => 'smart-form']]); ?>
                        <header>
                            <?php echo $this->title; ?>
                        </header>

                        <fieldset>
                            <section>
                                <label class="input">
                                    <?= $form->field($model, 'Rating')->widget(StarRating::classname(), [
                                        'name' => 'rating_21',
                                        'pluginOptions' => [
                                            'min' => 0,
                                            'max' => 5,
                                            'step' => 0.5,
                                            'size' => 'lg',
                                            'starCaptions' => [
                                                '0.5' => 'Extremely Poor',
                                                1 => 'Very Poor',
                                                '1.5' => 'Poor',
                                                2 => 'Ok',
                                                '2.5' => 'Good',
                                                3 => 'Very Good',
                                                '3.5' => 'Extremely Good',
                                            ],
                                            'starCaptionClasses' => [
                                                '0.5' => 'text-danger',
                                                1 => 'text-danger',
                                                '1.5' => 'text-warning',
                                                2 => 'text-info',
                                                '2.5' => 'text-primary',
                                                3 => 'text-success',
                                                '3.5' => 'text-success'
                                            ],
                                        ],
                                    ]); ?>
                                </label>
                            </section>
                            <section>
                                <label class="input">
                                    <?= $form->field($model, 'Description')->textInput(['maxlength' => 100]) ?>
                                </label>
                            </section>
                        </fieldset>

                        <footer>
                            <?php echo $this->blocks['submit']; ?>
                        </footer>

                        <?php ActiveForm::end(); ?>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
        <!-- END COL -->

    </div>
</section>
