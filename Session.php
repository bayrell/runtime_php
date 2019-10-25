<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2018-2019 "Ildar Bikmamatov" <support@bayrell.org>
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
class Session extends \Runtime\CoreProvider
{
	public $__user_id;
	public $__session_key;
	static function isApp($__ctx, $s)
	{
		return $s->user_id < 0;
	}
	static function isUser($__ctx, $s)
	{
		return $s->user_id > 0;
	}
	static function isValid($__ctx, $s)
	{
		return $s->user_id != 0 && $s->session_key != 0;
	}
	/* ======================= Class Init Functions ======================= */
	function _init($__ctx)
	{
		parent::_init($__ctx);
		$this->__user_id = 0;
		$this->__session_key = "";
	}
	function assignObject($__ctx,$o)
	{
		if ($o instanceof \Runtime\Session)
		{
			$this->__user_id = $o->__user_id;
			$this->__session_key = $o->__session_key;
		}
		parent::assignObject($__ctx,$o);
	}
	function assignValue($__ctx,$k,$v)
	{
		if ($k == "user_id")$this->__user_id = $v;
		else if ($k == "session_key")$this->__session_key = $v;
		else parent::assignValue($__ctx,$k,$v);
	}
	function takeValue($__ctx,$k,$d=null)
	{
		if ($k == "user_id")return $this->__user_id;
		else if ($k == "session_key")return $this->__session_key;
		return parent::takeValue($__ctx,$k,$d);
	}
	function getClassName()
	{
		return "Runtime.Session";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Session";
	}
	static function getParentClassName()
	{
		return "Runtime.CoreProvider";
	}
	static function getClassInfo($__ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($__ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Session",
			"name"=>"Runtime.Session",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($__ctx,$f)
	{
		$a = [];
		if (($f|3)==3)
		{
			$a[] = "user_id";
			$a[] = "session_key";
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