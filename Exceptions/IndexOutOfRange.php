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
namespace Runtime\Exceptions;
class IndexOutOfRange extends \Runtime\Exceptions\RuntimeException
{
	function __construct($ctx, $pos, $prev=null)
	{
		parent::__construct($ctx, $ctx->translate($ctx, "Runtime", "Index out of range. Pos: %pos%", \Runtime\Dict::from(["pos"=>$pos])), \Runtime\rtl::ERROR_INDEX_OUT_OF_RANGE, $prev);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.Exceptions.IndexOutOfRange";
	}
	static function getCurrentNamespace()
	{
		return "Runtime.Exceptions";
	}
	static function getCurrentClassName()
	{
		return "Runtime.Exceptions.IndexOutOfRange";
	}
	static function getParentClassName()
	{
		return "Runtime.Exceptions.RuntimeException";
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