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
class LambdaChain extends \Runtime\BaseStruct
{
	public $__name;
	public $__value;
	public $__chain;
	public $__pos;
	public $__is_async;
	function logName($ctx)
	{
		return $this->getClassName($ctx) . \Runtime\rtl::toStr(" -> ") . \Runtime\rtl::toStr($this->name) . \Runtime\rtl::toStr(" -> [") . \Runtime\rtl::toStr($this->pos) . \Runtime\rtl::toStr("] ") . \Runtime\rtl::toStr($this->value);
	}
	function addClassItem($ctx, $class_name, $class_method_name, $class_item, $info)
	{
		return $this->copy($ctx, \Runtime\Dict::from(["value"=>$class_name . \Runtime\rtl::toStr("::") . \Runtime\rtl::toStr($class_method_name)]));
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__name = "";
		$this->__value = "";
		$this->__chain = "";
		$this->__pos = 0;
		$this->__is_async = false;
	}
	function getClassName()
	{
		return "Runtime.LambdaChain";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.LambdaChain";
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
			$a[]="chain";
			$a[]="pos";
			$a[]="is_async";
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
		if ($field_name == "chain") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "pos") return \Runtime\Dict::from([
			"t"=>"double",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "is_async") return \Runtime\Dict::from([
			"t"=>"bool",
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