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
namespace Runtime\Annotations;
class IntrospectionClass extends \Runtime\CoreStruct
{
	public $__class_name;
	public $__class_info;
	public $__fields;
	public $__methods;
	public $__interfaces;
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__class_name = "";
		$this->__class_info = null;
		$this->__fields = null;
		$this->__methods = null;
		$this->__interfaces = null;
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\Annotations\IntrospectionClass)
		{
			$this->__class_name = $o->__class_name;
			$this->__class_info = $o->__class_info;
			$this->__fields = $o->__fields;
			$this->__methods = $o->__methods;
			$this->__interfaces = $o->__interfaces;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "class_name")$this->__class_name = $v;
		else if ($k == "class_info")$this->__class_info = $v;
		else if ($k == "fields")$this->__fields = $v;
		else if ($k == "methods")$this->__methods = $v;
		else if ($k == "interfaces")$this->__interfaces = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "class_name")return $this->__class_name;
		else if ($k == "class_info")return $this->__class_info;
		else if ($k == "fields")return $this->__fields;
		else if ($k == "methods")return $this->__methods;
		else if ($k == "interfaces")return $this->__interfaces;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Annotations.IntrospectionClass";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Annotations";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Annotations.IntrospectionClass";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Annotations.IntrospectionClass",
			"name"=>"Runtime.Annotations.IntrospectionClass",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "class_name";
			$a[] = "class_info";
			$a[] = "fields";
			$a[] = "methods";
			$a[] = "interfaces";
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
}