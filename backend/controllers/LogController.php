<?php

namespace backend\controllers;

use backend\components\Logger;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

/**
 * Class LogController
 * @package backend\controllers
 */
class LogController extends Controller
{
    /**
     * Path to the log file
     * @var string
     */
    public $path;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className()
            ],
        ];
    }

    /**
     * List of all logs file
     * @return mixed
     */
    public function actionIndex()
    {
        $dataArray = [];

        $list = $this->getFileList();
        foreach ( $list as $id=>$filename )
        {
            $columns = [];
            $columns['id'] = $id;
            $columns['name'] = basename ( $filename);
            $columns['size'] = filesize ( $this->getPath(). $filename);

            $columns['create_time'] = date( 'Y-m-d H:i:s', filectime($this->getPath() .$filename) );
            $columns['modified_time'] = date( 'Y-m-d H:i:s', filemtime($this->getPath() .$filename) );

            $dataArray[] = $columns;
        }

        $dataProvider = new ArrayDataProvider(['allModels'=>$dataArray]);
        return $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Get logs directory
     * @return string
     */
    protected function getPath()
    {
        $this->path = Logger::getInstance()->logDirectory;

        if ( !file_exists($this->path ))
        {
            mkdir($this->path );
            chmod($this->path, '0777');
        }
        return $this->path;
    }

    /**
     * Get all the logs file in logs path
     * @return array
     */
    protected function getFileList()
    {
        $path = $this->getPath();
        $list = [];
        $list_files = glob($path . '*.' . Logger::getInstance()->config['extension']);
        if ($list_files )
        {
            $list = array_map('basename',$list_files);
            sort($list);
        }
        return $list;
    }

    /**
     * @param null $filename
     * @throws HttpException
     */
    public function actionDownload($filename = null)
    {
        $errors = [];
        $filePath = $this->getPath() . basename($filename);

        if (!is_file($filePath)) {
            $errors[] = \Yii::t('app', 'File not found');
        }
        if ($errors !== []) {
            throw new HttpException(403, implode(', ', $errors));
        }

        \Yii::$app->response->sendFile($filePath, $filename);
    }

    /**
     *
     */
    public function actionDelete()
    {
        $file = $_GET[0]['filename'];

        $logFile = $file;
        if (isset($file)) {
            $logFile = $this->getPath() . basename($file);

            if (file_exists($logFile)) {
//                chmod($logFile, 0777);
                unlink($logFile);

                $flashError = 'success';
                $flashMsg = \Yii::t('app', 'The file {file} was successfully deleted.', ['file' => $logFile]);
            } else {
                $flashError = 'error';
                $flashMsg = \Yii::t('app', 'The file {file} was not found.', ['file' => $logFile]);
            }
        } else {
            $flashError = 'error';
            $flashMsg = \Yii::t('app', 'The file {file} was not found.', ['file' => $logFile]);
        }
        \Yii::$app->getSession()->setFlash($flashError, [
            'type' => $flashError,
            'duration' => 3000,
            'icon' => 'fa fa-trash-o',
            'message' => $flashMsg,
            'title' => \Yii::t('app', 'Delete log'),
        ]);
        $this->redirect(['index']);
    }

    /**
     * @param null $filename
     * @return string|\yii\web\Response
     */
    public function actionView($filename = null)
    {
        $logFile = $filename;
        if (isset($filename)) {
            $logFile = $this->getPath() . basename($filename);

            if (file_exists($logFile) && $context = fopen($logFile, "r")) {
                return $this->render('view', ['context' => $context, 'filename' => $filename]);
            } else {
                $flashError = 'error';
                $flashMsg = \Yii::t('app', 'The file {file} was not found.', ['file' => $logFile]);
            }
        } else {
            $flashError = 'error';
            $flashMsg = \Yii::t('app', 'The file {file} was not found.', ['file' => $logFile]);
        }

        \Yii::$app->getSession()->setFlash($flashError, [
            'type' => $flashError,
            'duration' => $flashError == 'error' ? 0 : 3000,
            'icon' => 'fa fa-eye-open',
            'message' => $flashMsg,
            'title' => \Yii::t('app', 'View log'),
        ]);

        return $this->redirect(['index']);
    }
}