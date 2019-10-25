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
class LambdaChain extends \Runtime\CoreStruct
{
	public $__name;
	public $__value;
	public $__chain;
	public $__pos;
	public $__is_await;
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__name = "";
		$this->__value = "";
		$this->__chain = "";
		$this->__pos = 0;
		$this->__is_await = false;
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\Annotations\LambdaChain)
		{
			$this->__name = $o->__name;
			$this->__value = $o->__value;
			$this->__chain = $o->__chain;
			$this->__pos = $o->__pos;
			$this->__is_await = $o->__is_await;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "name")$this->__name = $v;
		else if ($k == "value")$this->__value = $v;
		else if ($k == "chain")$this->__chain = $v;
		else if ($k == "pos")$this->__pos = $v;
		else if ($k == "is_await")$this->__is_await = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "name")return $this->__name;
		else if ($k == "value")return $this->__value;
		else if ($k == "chain")return $this->__chain;
		else if ($k == "pos")return $this->__pos;
		else if ($k == "is_await")return $this->__is_await;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Annotations.LambdaChain";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Annotations";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Annotations.LambdaChain";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreStruct";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Annotations.LambdaChain",
			"name"=>"Runtime.Annotations.LambdaChain",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "name";
			$a[] = "value";
			$a[] = "chain";
			$a[] = "pos";
			$a[] = "is_await";
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