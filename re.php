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
class re
{
	/**
	 * Search regular expression
	 * @param string r regular expression
	 * @param string s string
	 * @return bool
	 */
	static function match($ctx, $r, $s)
	{
		$matches = [];
		if (preg_match("/" . $r . "/", $s, $matches))
		{
			return $matches != null;
		}
		
		return false;
	}
	/**
	 * Search regular expression
	 * @param string r regular expression
	 * @param string s string
	 * @return Vector result
	 */
	static function matchAll($ctx, $r, $s)
	{
		$matches = [];
		if (preg_match_all("/" . $r . "/i", $s, $matches))
		{
			$res = [];
			foreach ($matches as $index1 => $obj1)
			{
				foreach ($obj1 as $index2 => $val)
				{
					if (!isset($res[$index2])) $res[$index2] = [];
					$res[$index2][$index1] = $val;
				}
			}
			$res = array_map
			(
				function ($item) { return Collection::from($item); },
				$res
			);
			return Collection::from($res);
		}
		
		return null;
		return null;
	}
	/**
	 * Replace with regular expression
	 * @param string r - regular expression
	 * @param string replace - new value
	 * @param string s - replaceable string
	 * @return string
	 */
	static function replace($ctx, $r, $replace, $s)
	{
		return preg_replace("/" . $r . "/", $replace, $s);
	}
	/* ======================= Class Init Functions ======================= */
	function getClassName()
	{
		return "Runtime.re";
	}
	static function getCurrentNamespace()
	{
		return "Runtime";
	}
	static function getCurrentClassName()
	{
		return "Runtime.re";
	}
	static function getParentClassName()
	{
		return "";
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