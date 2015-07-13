<?php

namespace backend\controllers;

use backend\models\Restore;
use backend\models\UploadBackup;
use common\models\Image;
use yii\web\Controller;
use yii\base\Exception;
use yii\data\ArrayDataProvider;
use yii\web\HttpException;
use yii\web\UploadedFile;

class BackupController extends Controller
{
    public $tables = [];
    public $fp ;
    public $file_name;
    public $_path = null;
    public $back_temp_file = 'db_backup_';
    public $view_table = [];
    public $count = 0;

    protected function getPath()
    {
        if ( isset ($this->module->path )) $this->_path = $this->module->path;
        else $this->_path = \Yii::$app->basePath .'/_backup/';

        if ( !file_exists($this->_path ))
        {
            mkdir($this->_path );
            chmod($this->_path, '777');
        }
        return $this->_path;
    }

    public function execSqlFile($sqlFile)
    {
        $message = "ok";

        if ( file_exists($sqlFile))
        {
            $sqlArray = file_get_contents($sqlFile);

            $cmd = \Yii::$app->db->createCommand($sqlArray);
            try	{
                $cmd->execute();
            }
            catch(Exception $e)
            {
                $message = $e->getMessage();
            }

        }
        return $message;
    }
    public function getColumns($tableName)
    {
        $sql = 'SHOW CREATE TABLE `'.$tableName . '`';
        $cmd = \Yii::$app->db->createCommand($sql);
        $table = $cmd->queryOne();

        $is_view = false;

        $create_query = '';

        if (array_key_exists('Create Table', $table)) {
            $create_query = $table['Create Table'] . ';';
        } else if(array_key_exists('Create View', $table)) {
            $is_view = true;
            $create_query .= $table['Create View'] . ';';
        }

        if (!$is_view) {
            $create_query = preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query);
            $create_query = preg_replace('/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query);
        } else if ($is_view) {
            $create_query = preg_replace('/^CREATE (.*) VIEW/', 'CREATE OR REPLACE VIEW', $create_query);
        }

        if ( $this->fp)
        {
            if(!$is_view) {
                $this->writeComment('TABLE `' . addslashes($tableName) . '`');
                $final = 'DROP TABLE IF EXISTS `' . addslashes($tableName) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
                fwrite($this->fp, $final);
            } else if ($is_view) {
                $this->view_table[$this->count]['view_name'] = 'VIEW `' . addslashes($tableName) . '`';
                $this->view_table[$this->count]['schema'] = $create_query . PHP_EOL . PHP_EOL;
                $this->count++;
            }
        }
        else
        {
            $this->tables[$tableName]['create'] = $create_query;
            return $create_query;
        }
    }

    public function getData($tableName)
    {
        $sql = 'SHOW CREATE TABLE `'.$tableName . '`';
        $cmd = \Yii::$app->db->createCommand($sql);
        $table = $cmd->queryOne();

        if (array_key_exists('Create View', $table)) {
            return;
        }

        $sql = 'SELECT * FROM `'.$tableName . '`';
        $cmd = \Yii::$app->db->createCommand($sql);
        $dataReader = $cmd->query();

        $data_string = '';

        foreach($dataReader as $data)
        {
            $itemNames = array_keys($data);
            $itemNames = array_map("addslashes", $itemNames);
            $items = join('`,`', $itemNames);
            $itemValues = array_values($data);
            $itemValues = array_map("addslashes", $itemValues);
            $valueString = '';
            foreach ($itemValues as $itemValue) {
                if (is_numeric($itemValue)) {
                    $valueString .= $itemValue . ",";
                } else if ($itemValue == '') {
                    $valueString .= NULL;
                } else {
                    $valueString .= "'" . $itemValue . "',";
                }
            }
            $valueString = trim($valueString, ",");
            $valueString = "(" . $valueString . ")";
            $values ="\n" . $valueString;
            if ($values != "")
            {
                $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES" . rtrim($values, ",") . ";" . PHP_EOL;
            }
        }

        if ( $data_string == '')
            return null;

        if ( $this->fp)
        {
            $this->writeComment('TABLE DATA '.$tableName);
            $final = $data_string.PHP_EOL.PHP_EOL.PHP_EOL;
            fwrite ( $this->fp, $final );
        }
        else
        {
            $this->tables[$tableName]['data'] = $data_string;
            return $data_string;
        }
    }

    public function getTables($dbName = null)
    {
        $sql = 'SHOW TABLES';
        $cmd = \Yii::$app->db->createCommand($sql);
        $tables = $cmd->queryColumn();
        return $tables;
    }

    public function StartBackup($addcheck = true)
    {
        $this->file_name =  $this->path . $this->back_temp_file . date('Y.m.d_H.i.s') . '.sql';

        $this->fp = fopen( $this->file_name, 'w+');

        if ( $this->fp == null )
            return false;
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
        if ( $addcheck )
        {
            fwrite ( $this->fp,  'SET AUTOCOMMIT=0;' .PHP_EOL );
            fwrite ( $this->fp,  'START TRANSACTION;' .PHP_EOL );
            fwrite ( $this->fp,  'SET SQL_QUOTE_SHOW_CREATE = 1;'  .PHP_EOL );
        }
        fwrite ( $this->fp, 'SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;'.PHP_EOL );
        fwrite ( $this->fp, 'SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;'.PHP_EOL );
        fwrite ( $this->fp, 'SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE="TRADITIONAL,ALLOW_INVALID_DATES";'.PHP_EOL);
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL . PHP_EOL);

        $this->writeComment('START BACKUP');

        $this->writeComment('Schema fsmdb');
        fwrite ($this-> fp, 'DROP SCHEMA IF EXISTS `fsmdb` ;'.PHP_EOL.PHP_EOL);
        $this->writeComment('Schema fsmdb');
        fwrite($this->fp, 'CREATE SCHEMA IF NOT EXISTS `fsmdb` DEFAULT CHARACTER SET utf8 ;'.PHP_EOL);
        fwrite($this->fp, 'USE `fsmdb` ;'.PHP_EOL.PHP_EOL);

        return true;
    }

    public function EndBackup($addcheck = true)
    {
        foreach ($this->view_table as $view) {
            $this->writeComment($view['view_name']);
            fwrite ( $this->fp, $view['schema']);
        }
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
        fwrite ( $this->fp, 'SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;'.PHP_EOL );
        fwrite ( $this->fp, 'SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;'.PHP_EOL );
        fwrite ( $this->fp, 'SET SQL_MODE=@OLD_SQL_MODE;'.PHP_EOL);

        if ( $addcheck )
        {
            fwrite ( $this->fp,  'COMMIT;' .PHP_EOL );
        }
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
        $this->writeComment('END BACKUP');
        fclose($this->fp);
        $this->fp = null;
    }

    public function writeComment($string)
    {
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
        fwrite ( $this->fp, '-- '.$string .PHP_EOL );
        fwrite ( $this->fp, '-- -------------------------------------------'.PHP_EOL );
    }

    public function actionCreate()
    {
        $flashError = '';
        $flashMsg = '';

        $tables = $this->getTables();

        if(!$this->StartBackup())
        {
            //render error
            $flashError = 'error';
            $flashMsg = 'Some errors creating the file';
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

        $flashError = 'success';
        $flashMsg = 'The file was created !!!';
        \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
        $this->redirect(['index']);
    }

    public function actionDelete($file = null) {

        $file = $_GET[0]['filename'];

        if (isset($file)) {
            $sqlFile = $this->getPath() . basename($file);
            if (file_exists($sqlFile)) {
                unlink($sqlFile);
                $flashError = 'success';
                $flashMsg = 'The file ' . $sqlFile . ' was successfully deleted.';
            } else {
                $flashError = 'error';
                $flashMsg = 'The file ' . $sqlFile . ' was not found.';
            }
        } else {
            $flashError = 'error';
            $flashMsg = 'The file ' . $file . ' was not found.';
        }
        \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
        $this->redirect(['index']);
    }

    protected function getFileList()
    {
        $path = $this->getPath();
        $list = [];
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

    public function actionDownload($filename = null)
    {
        $errors = [];
        $filePath = $this->getPath() . basename($filename);

        if (!is_file($filePath)) {
            $errors[] = 'file not found';
        }
        if ($errors !== []) {
            throw new HttpException(403, implode(', ', $errors));
        }

        \Yii::$app->response->sendFile($filePath, $filename);
    }

    public function actionRestore($file = null) {
        $flashError = '';
        $flashMsg = '';

        $file = $_GET[0]['filename'];
//
//        $this->updateMenuItems();
        $sqlFile = $this->path . basename($file);
        if (isset($file)) {
            $sqlFile = $this->path . basename($file);
            $flashError = 'success';
            $flashMsg = 'Restore success.';
        } else {
            $flashError = 'error';
            $flashMsg = 'Problems with the file name';
        }
        $this->execSqlFile($sqlFile);

        \Yii::$app->getSession()->setFlash($flashError, $flashMsg);
        $this->redirect(array('index'));
    }

    public function actionUpload() {
        $model = new UploadBackup();
        if ($model->load(\Yii::$app->request->post())) {

            $file = UploadedFile::getInstance($model, 'upload_file');
            if ($file->saveAs($this->getPath() . $file)) {
                // redirect to success page
                return $this->redirect(['index']);
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

}
