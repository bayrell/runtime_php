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
class Entity extends \Runtime\BaseStruct
{
	public $__name;
	public $__value;
	public $__params;
	/* Functions */
	function className($ctx)
	{
		return ($this->name != "") ? (($this->value != "") ? ($this->value) : ($this->name)) : ("");
	}
	function logName($ctx)
	{
		return $this->getClassName($ctx) . \Runtime\rtl::toStr(" -> ") . \Runtime\rtl::toStr((($this->value != "") ? ($this->name . \Runtime\rtl::toStr(" -> ") . \Runtime\rtl::toStr($this->value)) : ($this->name)));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__name = "";
		$this->__value = "";
		$this->__params = \Runtime\Dict::from([]);
	}
	function getClassName()
	{
		return "Runtime.Entity";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Entity";
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
			$a[]="name";
			$a[]="value";
			$a[]="params";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "name") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "value") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "params") return \Runtime\Dict::from([
			"t"=>"Runtime.Dict",
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