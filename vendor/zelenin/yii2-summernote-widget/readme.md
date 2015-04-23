# Yii2 Summernote widget

[Yii2](http://www.yiiframework.com) [Summernote](http://summernote.org) widget. Super simple WYSIWYG editor on Bootstrap

## Installation

### Composer

The preferred way to install this extension is through [Composer](http://getcomposer.org/).

Either run

	php composer.phar require zelenin/yii2-summernote-widget "dev-master"

or add

	"zelenin/yii2-summernote-widget": "dev-master"

to the require section of your composer.json

## Usage

	<?= $form->field($model, 'content')->widget(Summernote::className(), [
		'clientOptions' => [
			...
		]
	]) ?>

or

	<?= Summernote::widget([
		'name' => 'editor_id',
		'clientOptions' => [
			...
		]
	]) ?>

See [clientOptions](http://summernote.org/#/example)

## Author

[Aleksandr Zelenin](https://github.com/zelenin/), e-mail: [aleksandr@zelenin.me](mailto:aleksandr@zelenin.me)
