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
class Date extends \Runtime\BaseStruct
{
	public $__y;
	public $__m;
	public $__d;
	/**
	 * Return date
	 * @return string
	 */
	function getDate($ctx)
	{
		return $this->y . \Runtime\rtl::toStr("-") . \Runtime\rtl::toStr($this->m) . \Runtime\rtl::toStr("-") . \Runtime\rtl::toStr($this->d);
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__y = 0;
		$this->__m = 0;
		$this->__d = 0;
	}
	function getClassName()
	{
		return "Runtime.Date";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Date";
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