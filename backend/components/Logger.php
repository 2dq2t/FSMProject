<?php

namespace backend\components;

use Yii;
use DateTime;
use RuntimeException;
use yii\helpers;
use yii\helpers\FileHelper;

/**
 * Class Logger is contain attributes and functions correlate log
 */
class Logger {
    const INFO = 'INFO';
    const ERROR = 'ERROR';
    const WARNING = 'WARNING';


    /**
     * This holds the instance's logger
     * @var
     */
    private static $instance;


    /**
     * Directory to the logs
     * @var string
     */
    public $logDirectory;


    /**
     * Path to the log file
     * @var string
     */
    private $logFilePath;

    /**
     * This holds the file handle for this instance's log file
     * @var resource
     */
    private static $fileHandle;

    public $config = [
        'extension' => 'txt',
        'dateFormat' => 'Y-m-d G:i:s.u',
        'prefix' => 'log_'
    ];

    /**
     * The construct class logger
     * @param array $config
     */
    private function __construct(array $config = [])
    {
        if (!$this->logDirectory) {
            $this->logDirectory = Yii::getAlias('@backend').DIRECTORY_SEPARATOR. 'logs'.DIRECTORY_SEPARATOR;

            if (!file_exists($this->logDirectory)) {
                FileHelper::createDirectory($this->logDirectory, 0777);
            }
        }

        $this->config = array_merge($this->config, $config);

//        $this->setLogFilePath($this->logDirectory);
//        if(file_exists($this->logFilePath) && !is_writable($this->logFilePath)) {
//            throw new RuntimeException(Yii::t('app', 'The file could not be written to. Check that appropriate permissions have been set.'));
//        }
//        $this->setFileHandle('a');
//
//        if ( ! self::$fileHandle) {
//            throw new RuntimeException(Yii::t('app', 'The file could not be opened. Check permissions.'));
//        }
    }

    public static function getInstance()
    {
        if (!self::$instance)
        {
            self::$instance = new Logger();
        }
        return self::$instance;
    }

    /**
     * @param string $logDirectory
     */
    public function setLogFilePath($logDirectory) {
        $this->logFilePath = $logDirectory.DIRECTORY_SEPARATOR.$this->config['prefix'].date('Y-m-d').'.'.$this->config['extension'];
    }

    /**
     * @param $writeMode
     *
     * @internal param resource $fileHandle
     */
    public function setFileHandle($writeMode) {
        self::$fileHandle = fopen($this->logFilePath, $writeMode);
    }

    /**
     * Class destructor
     */
    public function __destruct()
    {
        if (self::$fileHandle) {
            fclose(self::$fileHandle);
        }
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $beforeChangeValues
     * @param array $afterChangeValues
     * @param int $userId
     * @return null
     */
    public static function log($level, $message, $userId, array $beforeChangeValues = [], array $afterChangeValues = [])
    {
        self::getInstance()->setLogFilePath(self::getInstance()->logDirectory);
        if(file_exists(self::getInstance()->logFilePath) && !is_writable(self::getInstance()->logFilePath)) {
            throw new RuntimeException(Yii::t('app', 'The file could not be written to. Check that appropriate permissions have been set.'));
        }
        self::getInstance()->setFileHandle('a');

        if ( ! self::$fileHandle) {
            throw new RuntimeException(Yii::t('app', 'The file could not be opened. Check permissions.'));
        }

        $changeValues = !empty($beforeChangeValues) && !empty($afterChangeValues) ? self::getInstance()->changeValues($beforeChangeValues, $afterChangeValues) : '';
        $message = self::getInstance()->formatMessage($level, $message, $userId, $changeValues);
        self::write($message);
    }

    /**
     * Writes a line to the log without prepending a status or timestamp
     *
     * @param string $message Line to write to the log
     * @return void
     */
    public function write($message)
    {
        if (null !== self::$fileHandle) {
            if (fwrite(self::$fileHandle, $message) === false) {
                throw new RuntimeException(Yii::t('app', 'The file could not be written to. Check that appropriate permissions have been set.'));
            } else {
                fflush(self::$fileHandle);
            }
        }
    }

    private function formatMessage($level, $message, $userId, array $changeValues = [])
    {
        $message = "[{$this->getTimestamp()}][{$level}]: {$message}, " . Yii::t('app', 'Employee Id: ') . "{$userId}";

        if (!empty($changeValues)) {
            $message .=", " . Yii::t('app','Changed values: ') .  PHP_EOL . $this->indent($this->contextToString($changeValues));
        }

        return $message.PHP_EOL;
    }

    /**
     * Gets the correctly formatted Date/Time for the log entry.
     *
     * PHP DateTime is dump, and you have to resort to trickery to get microseconds
     * to work correctly, so here it is.
     *
     * @return string
     */
    private function getTimestamp()
    {
        $originalTime = microtime(true);
        $micro = sprintf("%06d", ($originalTime - floor($originalTime)) * 1000000);
        $date = new DateTime(date('Y-m-d H:i:s.'.$micro, $originalTime));

        return $date->format($this->config['dateFormat']);
    }

    /**
     * Takes the given context and coverts it to a string.
     *
     * @param  array $context The Context
     * @return string
     */
    private function contextToString($context)
    {
        $export = '';
        foreach ($context as $key => $value) {
            $export .= "{$key}: ";
            $export .= preg_replace(array(
                '/=>\s+([a-zA-Z])/im',
                '/array\(\s+\)/im',
                '/^  |\G  /m'
            ), array(
                '=> $1',
                'array()',
                '    '
            ), str_replace('array (', '(', var_export($value, true)));
            $export .= PHP_EOL;
        }
        return str_replace(array('\\\\', '\\\''), array('\\', '\''), rtrim($export));
    }

    /**
     * Indents the given string with the given indent.
     *
     * @param  string $string The string to indent
     * @param  string $indent What to use as the indent.
     * @return string
     */
    private function indent($string, $indent = '    ')
    {
        return $indent.str_replace("\n", "\n".$indent, $string);
    }

    /**
     * Gets the change value
     *
     * @param array $beforeChangeValues
     * @param array $afterChangeValues
     * @return array
     */
    private function changeValues(array $beforeChangeValues, array $afterChangeValues)
    {
        $different = array_diff_assoc($beforeChangeValues, $afterChangeValues);
        $arr = [];
        foreach($different as $key => $value) {
            $arr[$key] = [$beforeChangeValues[$key] => $afterChangeValues[$key]];
        }

        return $arr;
    }
}
