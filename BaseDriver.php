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
class BaseDriver extends \Runtime\BaseObject
{
	public $object_name;
	public $entity;
	function __construct($ctx, $object_name="", $entity=null)
	{
		parent::__construct($ctx);
		$this->object_name = $object_name;
		$this->entity = $entity;
	}
	/**
	 * Returns object name
	 */
	function getObjectName($ctx)
	{
		return $this->object_name;
	}
	/**
	 * Returns entity
	 */
	function getEntity($ctx)
	{
		return $this->entity;
	}
	/**
	 * Start driver
	 */
	function startDriver($ctx)
	{
	}
	/* ======================= Class Init Functions ======================= */
	function _init($ctx)
	{
		parent::_init($ctx);
		$this->object_name = "";
		$this->entity = null;
	}
	function getClassName()
	{
		return "Runtime.BaseDriver";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.BaseDriver";
	}
	static function getParentClassName()
	{
		return "Runtime.BaseObject";
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
		return \Runtime\Collection::from($a);
	}
	static function getFieldInfoByName($ctx,$field_name)
	{
		if ($field_name == "object_name") return \Runtime\Dict::from([
			"t"=>"string",
			"annotations"=>\Runtime\Collection::from([
			]),
		]);
		if ($field_name == "entity") return \Runtime\Dict::from([
			"t"=>"Runtime.Entity",
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