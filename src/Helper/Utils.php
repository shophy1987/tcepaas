<?php

namespace Tcepaas\Helper;

use Tcepaas\Exception\ArgumentException;

class Utils
{
    static public function checkEmptyStr($var, $name)
    {
        if (!(is_string($var) && ($var != ''))) {
            throw new ArgumentException("can not be empty string", $name);
        }
    }

    // 非负数
    static public function checkNonnegativeInt($var, $name)
    {
        if (!(is_int($var) && $var >= 0)) {
            throw new ArgumentException("need nonnegative int", $name);
        } 
    }

    // 正整数
    static public function checkPositiveInt($var, $name)
    {
        if (!(is_int($var) && $var > 0)) {
            throw new ArgumentException("need positiveInt", $name);
        } 
    }

    static public function checkEmptyArray(&$var, $name)
    {
        if (!is_array($var) || count($var) == 0) {
            throw new ArgumentException("can not be empty array", $name);
        }
    }

    static public function checkArrayNonnegativeInt(&$array, $key)
    {
        if (!isset($array[$key])) {
            throw new ArgumentException("required parameters are missing", $key);
        }
        if (!(is_int($array[$key]) && $array[$key] >= 0)) {
            throw new ArgumentException("need unsigned int", $key);
        }
    }

    static public function checkArrayPositiveInt(&$array, $key)
    {
        if (!isset($array[$key])) {
            throw new ArgumentException("required parameters are missing", $key);
        }
        if (!(is_int($array[$key]) && $array[$key] > 0)) {
            throw new ArgumentException("need unsigned int", $key);
        }
    }

    static public function checkArrayEmptyStr(&$array, $key)
    {
        if (!isset($array[$key])) {
            throw new ArgumentException("required parameters are missing", $key);
        }
        if (!(is_string($array[$key]) && ($array[$key] != ''))) {
            throw new ArgumentException("can not be empty string", '['.$key.']');
        }
    }

    static public function checkEmptyStrArray(&$array, $key)
    {
        if (!isset($array[$key])) {
            throw new ArgumentException("required parameters are missing", $key);
        }
        if (!self::notEmptyStrArray($array[$key])) {
            throw new ArgumentException("can not be empty array", $key);
        }
    }

    static public function checkPositiveIntArray(&$array, $key)
    {
        if (!isset($array[$key])) {
            throw new ArgumentException("required parameters are missing", $key);
        }
        if (!self::positiveIntArray($array[$key])) {
            throw new ArgumentException("can not be empty array", $key);
        }
    }

    static function notEmptyStrArray(&$var) 
    {
        if (!is_array($var))
            return false;

        foreach ($var as $_val) {
            if (is_array($_val) && !self::notEmptyArray($_val))
                return false;
            if (!is_string($_val) || $_val == '')
                return false;
        }

        return true;
    }

    static function positiveIntArray(&$var) 
    {
        if (!is_array($var))
            return false;

        foreach ($var as $_val) {
            if (is_array($_val) && !self::notEmptyArray($_val))
                return false;
            if (!is_int($_val) || $_val <= 0)
                return false;
        }

        return true;
    }

    static public function arrayGet(&$array, $key, $default=null)
    {
        if (array_key_exists($key, $array))
            return $array[$key];
        return $default;
    }

    static public function setIfNotNull($var, $name, &$args)
    {
        if (!is_null($var)) {
            $args[$name] = $var;
        }
    }

	/**
	 * 数组 转 对象
	 *
	 * @param array $arr 数组
	 * @return object
	 */
	static public function Array2Object($arr) {
		if (gettype($arr) != 'array') {
			return;
		}
		foreach ($arr as $k => $v) {
			if (gettype($v) == 'array' || getType($v) == 'object') {
				$arr[$k] = (object)self::Array2Object($v);
			}
		}

		return (object)$arr;
	}

	/**
	 * 对象 转 数组
	 *
	 * @param object $obj 对象
	 * @return array
	 */
	static public function Object2Array($object) { 
		if (is_object($object) || is_array($object)) {
            $array = array();
			foreach ($object as $key => $value) {
                if ($value == null) continue;
				$array[$key] = self::Object2Array($value);
			}
            return $array;
		}
		else {
			return $object;
		}
	}
    //数组转XML
    static public function Array2Xml($rootName, $arr)
    {
        $xml = "<".$rootName.">";
        foreach ($arr as $key=>$val) {
            if (is_numeric($val)) {
                $xml.="<".$key.">".$val."</".$key.">";
            } else {
                 $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</".$rootName.">";
        return $xml;
    }

    //将XML转为array
    static public function Xml2Array($xml)
    {    
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $values;
    }
}
