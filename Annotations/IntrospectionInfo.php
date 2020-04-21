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
namespace Runtime\Annotations;
class IntrospectionInfo extends \Runtime\CoreStruct
{
	const ITEM_CLASS="class";
	const ITEM_FIELD="field";
	const ITEM_METHOD="method";
	public $__class_name;
	public $__kind;
	public $__name;
	public $__annotations;
	/**
	 * Returns true if has annotations by class_name
	 * @string class_name
	 * @return bool
	 */
	function filterAnnotations($ctx, $class_name)
	{
		if ($this->annotations == null)
		{
			return null;
		}
		return $this->annotations->filter($ctx, \Runtime\lib::isInstance($ctx, $class_name))->toCollection($ctx);
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->__class_name = "";
		$this->__kind = "";
		$this->__name = "";
		$this->__annotations = null;
	}
	function assignObject($ctx,$o)
	{
		if ($o instanceof \Runtime\Annotations\IntrospectionInfo)
		{
			$this->__class_name = $o->__class_name;
			$this->__kind = $o->__kind;
			$this->__name = $o->__name;
			$this->__annotations = $o->__annotations;
		}
		parent::assignObject($ctx,$o);
	}
	function assignValue($ctx,$k,$v)
	{
		if ($k == "class_name")$this->__class_name = $v;
		else if ($k == "kind")$this->__kind = $v;
		else if ($k == "name")$this->__name = $v;
		else if ($k == "annotations")$this->__annotations = $v;
		else parent::assignValue($ctx,$k,$v);
	}
	function takeValue($ctx,$k,$d=null)
	{
		if ($k == "class_name")return $this->__class_name;
		else if ($k == "kind")return $this->__kind;
		else if ($k == "name")return $this->__name;
		else if ($k == "annotations")return $this->__annotations;
		return parent::takeValue($ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Annotations.IntrospectionInfo";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Annotations";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Annotations.IntrospectionInfo";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=>"Runtime.Annotations.IntrospectionInfo",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "class_name";
			$a[] = "kind";
			$a[] = "name";
			$a[] = "annotations";
		}
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "ITEM_CLASS") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ITEM_FIELD") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "ITEM_METHOD") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "class_name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "kind") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "name") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "annotations") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Annotations.IntrospectionInfo",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		return null;
	}
	static function getMethodsList($ctx)
	{
		$a = [
		];
		return \Runtime\Collection::from($a);
	}
	static function getMethodInfoByName($ctx,$field_name)
	{
		return null;
	}
}