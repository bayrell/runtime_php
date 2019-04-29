<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2019 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime;
use Runtime\rtl;
use Runtime\CoreStruct;
class DateTime extends CoreStruct{
	protected $__y;
	protected $__m;
	protected $__d;
	protected $__h;
	protected $__u;
	protected $__s;
	protected $__ms;
	protected $__tz;
	
	public static function assignDatetime($dt, $obj)
	{
		$y = (int)$dt->format("Y");
		$m = (int)$dt->format("m");
		$d = (int)$dt->format("d");
		$h = (int)$dt->format("H");
		$i = (int)$dt->format("i");
		$s = (int)$dt->format("s");
		return $obj->copy({"y":$y,"m":$m,"d":$d,"h":$h,"i":$i,"s":$s});
	}
	public static function createDatetime($dt, $tz="UTC")
	{
		return static::assignDatetime($dt, new DateTime({"tz":tz}));
	}
	public static function getDatetime($obj)
	{
		$dt = new \DateTime("now", new \DateTimeZone($obj->tz));
		$dt->setDate($obj->y, $obj->m, $obj->d);
		$dt->setTime($obj->h, $obj->i, $obj->s);
		return $dt;
	}
	/**
	 * Returns datetime
	 * @param string tz
	 * @return DateTime
	 */
	static function now($tz = "UTC"){
		
		$dt = new \DateTime("now", new \DateTimezone($tz));
		return static::createDatetime($dt, $tz);
		return null;
	}
	/**
	 * Returns day of week
	 * @param DateTime obj
	 * @return int
	 */
	static function getDayOfWeek($obj){
		
		$dt = static::getDatetime(obj);
		return $dt->format("w");
		return null;
	}
	/**
	 * Returns timestamp
	 * @param DateTime obj
	 * @return int
	 */
	static function getTimestamp($obj){
		
		$dt = static::getDatetime(obj);
		return $dt->getTimestamp();
		return null;
	}
	/**
	 * Set timestamp
	 * @param int timestamp
	 * @param DateTime obj
	 * @return DateTime instance
	 */
	static function setTimestamp($timestamp, $obj){
		
		$dt = static::getDatetime($obj);
		$dt->setTimestamp($timestamp);
		return static::assignDatetime($dt, $obj);
		return null;
	}
	/**
	 * Change time zone
	 * @param string tz
	 * @param DateTime obj
	 * @return DateTime instance
	 */
	static function changeTimezone($tz, $obj){
		
		$dt = static::getDatetime($obj);
		$dt->setTimezone(new \DateTimeZone($tz));
		return static::assignDatetime($dt, $obj);
		return null;
	}
	/**
	 * Return datetime in RFC822
	 * @param DateTime obj
	 * @return string
	 */
	static function getRFC822($obj){
		
		$dt = static::getDatetime($obj);
		return $dt->format(\DateTime::RFC822);
		return "";
	}
	/**
	 * Return datetime in ISO8601
	 * @param DateTime obj
	 * @return string
	 */
	static function getISO8601($obj){
		
		$dt = static::getDatetime($obj);
		return $dt->format(\DateTime::ISO8601);
		return "";
	}
	/**
	 * Return datetime by GMT
	 * @param DateTime obj
	 * @return string
	 */
	static function getGMT($obj){
		
		$dt = static::getDatetime($obj);
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/**
	 * Return datetime by UTC
	 * @param DateTime obj
	 * @return string
	 */
	static function getUTC($obj){
		
		$dt = static::getDatetime($obj);
		$dt->setTimezone( new \DateTimeZone("UTC") ); 
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.DateTime";}
	public static function getCurrentClassName(){return "Runtime.DateTime";}
	public static function getParentClassName(){return "Runtime.CoreStruct";}
	protected function _init(){
		parent::_init();
		$this->__y = 0;
		$this->__m = 0;
		$this->__d = 0;
		$this->__h = 0;
		$this->__u = 0;
		$this->__s = 0;
		$this->__ms = 0;
		$this->__tz = "UTC";
	}
	public function assignObject($obj){
		if ($obj instanceof DateTime){
			$this->__y = $obj->__y;
			$this->__m = $obj->__m;
			$this->__d = $obj->__d;
			$this->__h = $obj->__h;
			$this->__u = $obj->__u;
			$this->__s = $obj->__s;
			$this->__ms = $obj->__ms;
			$this->__tz = $obj->__tz;
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value, $sender = null){
		if ($variable_name == "y")$this->__y = rtl::convert($value,"int",0,"");
		else if ($variable_name == "m")$this->__m = rtl::convert($value,"int",0,"");
		else if ($variable_name == "d")$this->__d = rtl::convert($value,"int",0,"");
		else if ($variable_name == "h")$this->__h = rtl::convert($value,"int",0,"");
		else if ($variable_name == "u")$this->__u = rtl::convert($value,"int",0,"");
		else if ($variable_name == "s")$this->__s = rtl::convert($value,"int",0,"");
		else if ($variable_name == "ms")$this->__ms = rtl::convert($value,"int",0,"");
		else if ($variable_name == "tz")$this->__tz = rtl::convert($value,"string","UTC","");
		else parent::assignValue($variable_name, $value, $sender);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "y") return $this->__y;
		else if ($variable_name == "m") return $this->__m;
		else if ($variable_name == "d") return $this->__d;
		else if ($variable_name == "h") return $this->__h;
		else if ($variable_name == "u") return $this->__u;
		else if ($variable_name == "s") return $this->__s;
		else if ($variable_name == "ms") return $this->__ms;
		else if ($variable_name == "tz") return $this->__tz;
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names, $flag=0){
		if (($flag | 3)==3){
			$names->push("y");
			$names->push("m");
			$names->push("d");
			$names->push("h");
			$names->push("u");
			$names->push("s");
			$names->push("ms");
			$names->push("tz");
		}
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
	public function __get($key){ return $this->takeValue($key); }
	public function __set($key, $value){throw new \Runtime\Exceptions\AssignStructValueError($key);}
}