<?php

namespace backend\components;

use Yii;

class ParserDateTime
{

    public static function parseToTimestamp($value, $pattern = 'dd/MM/yyyy') {
        if ($value === null || empty($value)) return false;
        $tokens=self::tokenize($pattern);
        $i=0;
        $n=strlen($value);
        foreach($tokens as $token)
        {
            switch($token)
            {
                case 'yyyy':
                {
                    if(($year=self::parseInteger($value,$i,4,4))!==null)
                        $i+=4;
                    break;
                }
                case 'yy':
                {
                    if(($year=self::parseInteger($value,$i,1,2))!==null)
                        $i+=strlen($year);
                    break;
                }
                case 'MM':
                {
                    if(($month=self::parseInteger($value,$i,2,2))!==null)
                        $i+=2;
                    break;
                }
                case 'M':
                {
                    if(($month=self::parseInteger($value,$i,1,2))!==null)
                        $i+=strlen($month);
                    break;
                }
                case 'dd':
                {
                    if(($day=self::parseInteger($value,$i,2,2))!==null)
                        $i+=2;
                    break;
                }
                case 'd':
                {
                    if(($day=self::parseInteger($value,$i,1,2))!==null)
                        $i+=strlen($day);
                    break;
                }
                default:
                {
                    // If the separator pattern doesn't exist in the value, then ignore it
                    // eg: a space
                    if (strpos($value, $token)===false)
                        break;

                    $tn=strlen($token);
                    if($i>=$n || substr($value,$i,$tn)!==$token)
                        return false;
                    $i+=$tn;
                    break;
                }
            }
        }
        if($i<$n) // somethings gone wrong
            return false;

        // Defaults to the date/time for the local timezone
        // If you don't want to use Yii::app()-localtime->localNow() then simply replace with the php date() function
        // Yii::app()->localtime-> = LocalTime::
        if(!isset($year))
            $year=isset($defaults['year']) ? $defaults['year'] : date('Y');
        if(!isset($month))
            $month=isset($defaults['month']) ? $defaults['month'] : date('n');
        if(!isset($day))
            $day=isset($defaults['day']) ? $defaults['day'] : date('j');

        $year=(int)$year;
        $month=(int)$month;
        $day=(int)$day;

        if (date_create_from_format('d/m/Y', $day . '/' . $month . '/' . $year)) {
            return mktime(00, 00,00, $month, $day, $year);
        } else {
            return false;
        }
    }

    /*
     * @return midnight timestamp
     */
    public static function getTimeStamp()
    {
        return mktime(00, 00, 00, date('m'), date('d'), date('Y'));
    }

    /*
     * @param string $value the date string to be parsed
     * @param integer $offset starting offset
     * @param integer $minLength minimum length
     * @param integer $maxLength maximum length
     */
    protected static function parseInteger($value,$offset,$minLength,$maxLength)
    {
        for($len=$maxLength;$len>=$minLength;--$len)
        {
            $v=substr($value,$offset,$len);
            if(ctype_digit($v) && strlen($v)>=$minLength)
                return $v;
        }
        // Changed by Russell England to null rather than false
        return null;
    }

    /*
     * @param string $pattern the pattern that the date string is following
     */
    private static function tokenize($pattern)
    {
        if(!($n=strlen($pattern)))
            return [];
        $tokens=[];
        for($c0=$pattern[0],$start=0,$i=1;$i<$n;++$i)
        {
            if(($c=$pattern[$i])!==$c0)
            {
                $tokens[]=substr($pattern,$start,$i-$start);
                $c0=$c;
                $start=$i;
            }
        }
        $tokens[]=substr($pattern,$start,$n-$start);
        return $tokens;
    }
}