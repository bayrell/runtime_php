<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2020 "Ildar Bikmamatov" <support@bayrell.org>
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
class DateTime extends \Runtime\BaseStruct
{
	public $__y;
	public $__m;
	public $__d;
	public $__h;
	public $__i;
	public $__s;
	public $__ms;
	public $__tz;
	/**
	 * Create date time from timestamp
	 */
	static function create($ctx, $time=-1, $tz="UTC")
	{
		if ($time == -1) $time = time();
		$dt = new \DateTime();
		$dt->setTimezone(new \DateTimeZone($tz));
		$dt->setTimestamp($time);
		return static::fromObject($ctx, $dt);
		return null;
	}
	/**
	 * Convert to timestamp
	 */
	static function strtotime($ctx, $s, $tz="UTC")
	{
		$date = new \DateTime($dateStr, new \DateTimeZone($tz));
		$timestamp = $date->format('U');
	}
	/**
	 * Create date from string
	 */
	static function fromString($ctx, $s, $tz="UTC")
	{
		$time = static::strtotime($ctx, $s);
		return static::create($ctx, $time, $tz);
	}
	/**
	 * Returns datetime
	 * @param string tz
	 * @return DateTime
	 */
	static function now($ctx, $tz="UTC")
	{
		return static::create($ctx, -1, $tz);
	}
	/**
	 * Returns timestamp
	 * @return int
	 */
	function getTimestamp($ctx)
	{
		$dt = $this->toObject($ctx);
		return $dt->getTimestamp();
		return null;
	}
	function timestamp($ctx)
	{
		return $this->getTimestamp($ctx);
	}
	/**
	 * Returns day of week
	 * @return int
	 */
	function getDayOfWeek($ctx)
	{
		$dt = $this->toObject($ctx);
		return $dt->format("w");
		return null;
	}
	/**
	 * Return db datetime
	 * @return string
	 */
	function getDateTime($ctx, $tz="UTC")
	{
		$dt = $this->toObject($ctx);
		$dt->setTimezone( new \DateTimeZone($tz) );
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/**
	 * Return date
	 * @return string
	 */
	function getDate($ctx, $tz="UTC")
	{
		$value = $this->getDateTime($ctx, $tz);
		return \Runtime\rs::substr($ctx, $value, 0, 10);
	}
	/**
	 * Return datetime in RFC822
	 * @return string
	 */
	function getRFC822($ctx)
	{
		$dt = $this->toObject($ctx);
		return $dt->format(\DateTime::RFC822);
		return "";
	}
	/**
	 * Return datetime in ISO8601
	 * @return string
	 */
	function getISO8601($ctx)
	{
		$dt = $this->toObject($ctx);
		return $dt->format(\DateTime::ISO8601);
		return "";
	}
	private function toObject($ctx)
	{
		$dt = new \DateTime();
		$dt->setTimezone( new \DateTimeZone($this->tz) );
		$dt->setDate($this->y, $this->m, $this->d);
		$dt->setTime($this->h, $this->i, $this->s);
		return $dt;
	}
	
	public static function fromObject($ctx, $dt)
	{
		$y = (int)$dt->format("Y");
		$m = (int)$dt->format("m");
		$d = (int)$dt->format("d");
		$h = (int)$dt->format("H");
		$i = (int)$dt->format("i");
		$s = (int)$dt->format("s");
		$tz = $dt->getTimezone()->getName();
		return new \Runtime\DateTime($ctx, Dict::from(["y"=>$y,"m"=>$m,"d"=>$d,"h"=>$h,"i"=>$i,"s"=>$s,"tz"=>$tz]));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__y = 0;
		$this->__m = 0;
		$this->__d = 0;
		$this->__h = 0;
		$this->__i = 0;
		$this->__s = 0;
		$this->__ms = 0;
		$this->__tz = "UTC";
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
		return "Runtime.BaseStruct";
	}
	static function getClassInfo($ctx)
	{
		return \Runtime\Dict::from([
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f&3)==3)
		{
			$a[]="y";
			$a[]="m";
			$a[]="d";
			$a[]="h";
			$a[]="i";
			$a[]="s";
			$a[]="ms";
			$a[]="tz";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "y") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "m") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "d") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "h") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "i") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "s") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ms") return \Runtime\Dict::from([
			"t"=>"int",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "tz") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx,$f=0)
	{
		$a = [];
		if (($f&4)==4) $a=[
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}