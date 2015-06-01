<?php

namespace spanjeta\modules\backup\controllers;

use Yii;
use yii\db\Exception;
use yii\web\Controller;
use spanjeta\modules\backup\models\UploadForm;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;

class DefaultController extends Controller
{
	public $menu = [];
	public $tables = [];
	public $fp ;
	public $file_name;
	public $_path = null;
	public $back_temp_file = 'db_backup_';
	//public $layout = '//layout2';


	public function actionCreate()
	{
		$tables = $this->getTables();

		if(!$this->StartBackup())
		{
			//render error
			Yii::$app->user->setFlash('success', "Error");
			return $this->render('index');
		}

		foreach($tables as $tableName)
		{
			$this->getColumns($tableName);
		}
		foreach($tables as $tableName)
		{
			$this->getData($tableName);
		}
		$this->EndBackup();

		$this->redirect(array('index'));
	}
	public function actionClean($redirect = true)
	{
		$ignore = array('tbl_user','tbl_user_role','tbl_event');
		$tables = $this->getTables();

		if(!$this->StartBackup())
		{
			//render error
			Yii::$app->user->setFlash('success', "Error");
			return $this->render('index');
		}

		$message = '';

		foreach($tables as $tableName)
		{
			if( in_array($tableName, $ignore)) continue;
			fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
			fwrite ( $this->fp, 'DROP TABLE IF EXISTS ' .addslashes($tableName) . ';'.PHP_EOL );
			fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );

			$message  .=  $tableName . ',';

		}
		$this->EndBackup();

		// logout so there is no problme later .
		Yii::$app->user->logout();

		$this->execSqlFile($this->file_name);
		unlink($this->file_name);
		$message .= ' are deleted.';
		Yii::$app->session->setFlash('success', $message);
		return $this->redirect(['index']);
	}
	public function actionDelete($id)
	{
	    $list = $this->getFileList();
	    $file = $list[$id];
		$this->updateMenuItems();
		if ( isset($file))
		{
			$sqlFile = $this->path . basename($file);
			if ( file_exists($sqlFile))
				unlink($sqlFile);
		}
		else throw new HttpException(404, Yii::t('app', 'File not found'));
		return $this->redirect(['index']);
	}

	public function actionDownload($file = null)
	{
		$this->updateMenuItems();
		if ( isset($file))
		{
			$sqlFile = $this->path . basename($file);
			if ( file_exists($sqlFile))
			{
				$request = \Yii::$app->response;
				$request->sendFile(basename($sqlFile),file_get_contents($sqlFile));
			}
		}
		throw new HttpException(404, Yii::t('app', 'File not found'));
	}

	protected function getFileList()
	{
	    $path = $this->path;
	    $dataArray = array();
	    $list = array();
	    $list_files = glob($path .'*.sql');
	    if ($list_files )
	    {
	        $list = array_map('basename',$list_files);
	        sort($list);
	    }
	    return $list;
	}
	public function actionIndex()
	{
		//$this->layout = 'column1';
		$this->updateMenuItems();
        $dataArray = [];

			$list = $this->getFileList();
			foreach ( $list as $id=>$filename )
			{
				$columns = array();
				$columns['id'] = $id;
				$columns['name'] = basename ( $filename);
				$columns['size'] = filesize ( $this->path. $filename);

				$columns['create_time'] = date( 'Y-m-d H:i:s', filectime($this->path .$filename) );
				$columns['modified_time'] = date( 'Y-m-d H:i:s', filemtime($this->path .$filename) );

				$dataArray[] = $columns;
			}

		$dataProvider = new ArrayDataProvider(['allModels'=>$dataArray]);
		return $this->render('index', array(
				'dataProvider' => $dataProvider,
		));
	}


	public function actionRestore($file = null)
	{
		$this->updateMenuItems();
		$message = 'OK. Done';
		$sqlFile = $this->path . 'install.sql';
		if ( isset($file))
		{
			$sqlFile = $this->path . basename($file);
		}

		$this->execSqlFile($sqlFile);
		return  $this->render('restore',array('error'=>$message));
	}

	public function actionUpload()
	{
		$model= new UploadForm();
		if(isset($_POST['UploadForm']))
		{
			$model->attributes = $_POST['UploadForm'];
			$model->upload_file = \yii\web\UploadedFile::getInstance($model,'upload_file');
			if($model->upload_file->saveAs($this->path . $model->upload_file))
			{
				// redirect to success page
				return $this->redirect(array('index'));
			}
		}

		return $this->render('upload',array('model'=>$model));
	}

	protected function updateMenuItems($model = null)
	{
		// create static model if model is null
		if ( $model == null ) $model = new UploadForm();

		switch( $this->action->id)
		{
			case 'restore':
				{
					$this->menu[] = array('label'=>Yii::t('app', 'View Site') , 'url'=>Yii::$app->HomeUrl);
				}
			case 'create':
				{
					$this->menu[] = array('label'=>Yii::t('app', 'List Backup') , 'url'=>array('index'));
				}
				break;
			case 'upload':
				{
					$this->menu[] = array('label'=>Yii::t('app', 'Create Backup') , 'url'=>array('create'));
				}
				break;
			default:
				{
					$this->menu[] = array('label'=>Yii::t('app', 'List Backup') , 'url'=>array('index'));
					$this->menu[] = array('label'=>Yii::t('app', 'Create Backup') , 'url'=>array('create'));
					$this->menu[] = array('label'=>Yii::t('app', 'Upload Backup') , 'url'=>array('upload'));
				//	$this->menu[] = array('label'=>Yii::t('app', 'Restore Backup') , 'url'=>array('restore'));
					$this->menu[] = array('label'=>Yii::t('app', 'Clean Database') , 'url'=>array('clean'));
					$this->menu[] = array('label'=>Yii::t('app', 'View Site') , 'url'=>Yii::$app->HomeUrl);
				}
				break;
		}
	}
}
