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
class DateTime extends \Runtime\CoreStruct
{
	public $__y;
	public $__m;
	public $__d;
	public $__h;
	public $__u;
	public $__s;
	public $__ms;
	public $__tz;
	/**
	 * Create date time from timestamp
	 */
	static function timestamp($__ctx, $time, $tz="UTC")
	{
		$dt = new \DateTime();
		$dt->setTimezone(new \DateTimeZone($tz));
		$dt->setTimestamp($time);		
		return static::fromObject($dt);
		return null;
	}
	/**
	 * Output dbtime
	 */
	static function dbtime($__ctx, $time, $tz="UTC")
	{
		$dt = new \DateTime();
		$dt->setTimezone(new \DateTimeZone($tz));
		$dt->setTimestamp($time);
		return $dt->format('Y-m-d H:i:s');
		return "";
	}
	/**
	 * Returns datetime
	 * @param string tz
	 * @return DateTime
	 */
	static function now($__ctx, $tz="UTC")
	{
		$dt = new \DateTime("now", new \DateTimezone($tz));
		return static::createDatetime($dt, $tz);
		return null;
	}
	/**
	 * Returns day of week
	 * @return int
	 */
	function getDayOfWeek($__ctx)
	{
		$dt = static::getDatetime(obj);
		return $dt->format("w");
		return null;
	}
	/**
	 * Returns timestamp
	 * @return int
	 */
	function getTimestamp($__ctx)
	{
		$dt = static::getDatetime(obj);
		return $dt->getTimestamp();
		return null;
	}
	/**
	 * Set timestamp
	 * @param int timestamp
	 * @return DateTime instance
	 */
	function setTimestamp($__ctx, $timestamp)
	{
		$dt = static::getDatetime($obj);
		$dt->setTimestamp($timestamp);
		return static::assignDatetime($dt, $obj);
		return null;
	}
	/**
	 * Change time zone
	 * @param string tz
	 * @return DateTime instance
	 */
	function changeTimezone($__ctx, $tz)
	{
		$dt = static::getDatetime($obj);
		$dt->setTimezone(new \DateTimeZone($tz));
		return static::assignDatetime($dt, $obj);
		return null;
	}
	/**
	 * Return datetime in RFC822
	 * @return string
	 */
	function getRFC822($__ctx)
	{
		$dt = static::getDatetime($obj);
		return $dt->format(\DateTime::RFC822);
		return "";
	}
	/**
	 * Return datetime in ISO8601
	 * @return string
	 */
	function getISO8601($__ctx)
	{
		$dt = static::getDatetime($obj);
		return $dt->format(\DateTime::ISO8601);
		return "";
	}
	/**
	 * Return db datetime
	 * @return string
	 */
	function getDBTime($__ctx)
	{
		$dt = static::getDatetime($obj);
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/**
	 * Return datetime by UTC
	 * @return string
	 */
	function getUTC($__ctx)
	{
		$dt = this.getDatetime($obj);
		$dt->setTimezone( new \DateTimeZone("UTC") ); 
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__y = 0;
		$this->__m = 0;
		$this->__d = 0;
		$this->__h = 0;
		$this->__u = 0;
		$this->__s = 0;
		$this->__ms = 0;
		$this->__tz = "UTC";
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\DateTime)
		{
			$this->__y = $o->__y;
			$this->__m = $o->__m;
			$this->__d = $o->__d;
			$this->__h = $o->__h;
			$this->__u = $o->__u;
			$this->__s = $o->__s;
			$this->__ms = $o->__ms;
			$this->__tz = $o->__tz;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "y")$this->__y = $v;
		else if ($k == "m")$this->__m = $v;
		else if ($k == "d")$this->__d = $v;
		else if ($k == "h")$this->__h = $v;
		else if ($k == "u")$this->__u = $v;
		else if ($k == "s")$this->__s = $v;
		else if ($k == "ms")$this->__ms = $v;
		else if ($k == "tz")$this->__tz = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "y")return $this->__y;
		else if ($k == "m")return $this->__m;
		else if ($k == "d")return $this->__d;
		else if ($k == "h")return $this->__h;
		else if ($k == "u")return $this->__u;
		else if ($k == "s")return $this->__s;
		else if ($k == "ms")return $this->__ms;
		else if ($k == "tz")return $this->__tz;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.DateTime";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.DateTime";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.DateTime",
			"name"=>"Runtime.DateTime",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "y";
			$a[] = "m";
			$a[] = "d";
			$a[] = "h";
			$a[] = "u";
			$a[] = "s";
			$a[] = "ms";
			$a[] = "tz";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($__ctx,$field_name)
	{
		return null;
	}
	static function getMethodsList($__ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($__ctx,$field_name)
	{
		return null;
	}
	
	public static function fromObject($dt)
	{
		$y = (int)$dt->format("Y");
		$m = (int)$dt->format("m");
		$d = (int)$dt->format("d");
		$h = (int)$dt->format("H");
		$i = (int)$dt->format("i");
		$s = (int)$dt->format("s");
		$tz = $dt->getTimezone()->getName();
		return new DateTime( Dict::create(["y"=>$y,"m"=>$m,"d"=>$d,"h"=>$h,"i"=>$i,"s"=>$s,"tz"=>$tz]) );
	}
}