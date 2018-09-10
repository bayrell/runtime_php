<?php
/*!
 *  Bayrell Runtime Library
 *
 *  (c) Copyright 2016-2018 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.bayrell.org/licenses/APACHE-LICENSE-2.0.html
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime;
use Runtime\rtl;
class DateTime extends $CoreObject{
	protected $year;
	protected $month;
	protected $day;
	protected $hour;
	protected $minute;
	protected $second;
	protected $microseconds;
	protected $timezone;
	public function getClassName(){return "Runtime.DateTime";}
	public static function getParentClassName(){return "CoreObject";}
	protected function _init(){
		parent::_init();
		$this->year = 0;
		$this->month = 0;
		$this->day = 0;
		$this->hour = 0;
		$this->minute = 0;
		$this->second = 0;
		$this->microseconds = 0;
		$this->timezone = "UTC";
	}
	public function assignValue($variable_name, $value){
		if ($variable_name == "year") $this->year = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "month") $this->month = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "day") $this->day = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "hour") $this->hour = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "minute") $this->minute = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "second") $this->second = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "microseconds") $this->microseconds = rtl::correct($value, "int", 0, "");
		else if ($variable_name == "timezone") $this->timezone = rtl::correct($value, "string", "UTC", "");
		else parent::assignValue($variable_name, $value);
	}
	public function takeValue($variable_name, $default_value = null){
		if ($variable_name == "year") return $this->year;
		else if ($variable_name == "month") return $this->month;
		else if ($variable_name == "day") return $this->day;
		else if ($variable_name == "hour") return $this->hour;
		else if ($variable_name == "minute") return $this->minute;
		else if ($variable_name == "second") return $this->second;
		else if ($variable_name == "microseconds") return $this->microseconds;
		else if ($variable_name == "timezone") return $this->timezone;
		return parent::takeValue($variable_name, $default_value);
	}
	public function getVariablesNames($names){
		parent::getVariablesNames($names);
		$names->push("year");
		$names->push("month");
		$names->push("day");
		$names->push("hour");
		$names->push("minute");
		$names->push("second");
		$names->push("microseconds");
		$names->push("timezone");
	}
	function __construct(){
	}
	function now(){
	}
	function setDate($year, $month, $day){
	}
	function setTime($hour, $minute, $second){
	}
	function setTimestamp($unixtime){
	}
	function setTimezone($timezone){
	}
	function getYear(){
	}
	function getMonth(){
	}
	function getDay(){
	}
	function getHour(){
	}
	function getMinute(){
	}
	function getSecond(){
	}
	function getMicrosecond(){
	}
	function getTimestamp(){
	}
	function getRFC822(){
	}
	function getISO8601(){
	}
	function getUTC(){
	}
}