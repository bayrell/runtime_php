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
class Monad
{
	public $val;
	public $err;
	function __construct($ctx, $value, $err=null)
	{
		$this->val = $value;
		$this->err = null;
	}
	/**
	 * Return attr of object
	 */
	function attr($ctx, $attr_name)
	{
		if ($this->val == null || $this->err != null)
		{
			return $this;
		}
		return new \Runtime\Monad($ctx, \Runtime\rtl::attr($ctx, $this->val, \Runtime\Collection::from([$attr_name]), null));
	}
	/**
	 * Call function on value
	 */
	function call($ctx, $f)
	{
		if ($this->val == null || $this->err != null)
		{
			return $this;
		}
		$res = null;
		$err = null;
		try
		{
			
			$res = $f($ctx, $this->val);
		}
		catch (\Exception $_ex)
		{
			if ($_ex instanceof \Runtime\Exceptions\RuntimeException)
			{
				$e = $_ex;
				$res = null;
				$err = $e;
			}
			throw $_ex;
		}
		return new \Runtime\Monad($ctx, $res, $err);
	}
	/**
	 * Call async function on value
	 */
	function callAsync($ctx, $f)
	{
		if ($this->val == null || $this->err != null)
		{
			return $this;
		}
		$res = null;
		$err = null;
		try
		{
			
			$res = $f($ctx, $this->val);
		}
		catch (\Exception $_ex)
		{
			if ($_ex instanceof \Runtime\Exceptions\RuntimeException)
			{
				$e = $_ex;
				$res = null;
				$err = $e;
			}
			throw $_ex;
		}
		return new \Runtime\Monad($ctx, $res, $err);
	}
	/**
	 * Call method on value
	 */
	function callMethod($ctx, $method_name, $args=null)
	{
		if ($this->val == null || $this->err != null)
		{
			return $this;
		}
		$res = null;
		$err = null;
		try
		{
			
			$f = \Runtime\rtl::method($ctx, $this->val->getClassName($ctx), $method_name);
			if ($args != null)
			{
				$f = \Runtime\rtl::apply($ctx, $f, $args);
			}
			$res = $f($ctx, $this->val);
		}
		catch (\Exception $_ex)
		{
			if ($_ex instanceof \Runtime\Exceptions\RuntimeException)
			{
				$e = $_ex;
				$res = null;
				$err = $e;
			}
			throw $_ex;
		}
		return new \Runtime\Monad($ctx, $res, $err);
	}
	/**
	 * Call async method on value
	 */
	function callMethodAsync($ctx, $method_name, $args=null)
	{
		if ($this->val == null || $this->err != null)
		{
			return $this;
		}
		$res = null;
		$err = null;
		try
		{
			
			$f = \Runtime\rtl::method($ctx, $this->val->getClassName($ctx), $method_name);
			if ($args != null)
			{
				$f = \Runtime\rtl::apply($ctx, $f, $args);
			}
			$res = $f($ctx, $this->val);
		}
		catch (\Exception $_ex)
		{
			if ($_ex instanceof \Runtime\Exceptions\RuntimeException)
			{
				$e = $_ex;
				$res = null;
				$err = $e;
			}
			throw $_ex;
		}
		return new \Runtime\Monad($ctx, $res, $err);
	}
	/**
	 * Call function on monad
	 */
	function monad($ctx, $f)
	{
		return $f($ctx, $this);
	}
	/**
	 * Returns value
	 */
	function value($ctx)
	{
		if ($this->val == null || $this->err != null)
		{
			return null;
		}
		return $this->val;
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		$this->val = null;
		$this->err = null;
	}
	function getClassName()
	{
		return "Runtime.Monad";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Monad";
	}
	static function getParentClassName()
	{
		return "";
	}
	static function getClassInfo($ctx)
	{
		return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_CLASS,
			"class_name"=>"Runtime.Monad",
			"name"=>"Runtime.Monad",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
	}
	static function getFieldsList($ctx,$f)
	{
		$a = [];
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "val") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Monad",
			"name"=> $field_name,
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "err") return new \Runtime\Annotations\IntrospectionInfo($ctx, [
			"kind"=>\Runtime\Annotations\IntrospectionInfo::ITEM_FIELD,
			"class_name"=>"Runtime.Monad",
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