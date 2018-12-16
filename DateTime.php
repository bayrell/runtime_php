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
use Runtime\CoreObject;
use Runtime\Interfaces\CloneableInterface;
use Runtime\Interfaces\SerializeInterface;
class DateTime extends CoreObject implements CloneableInterface, SerializeInterface{
	protected $y;
	protected $m;
	protected $d;
	protected $h;
	protected $u;
	protected $s;
	protected $ms;
	protected $tz;
	/**
	 * Set date
	 * @param int y - Year
	 * @param int m - Month
	 * @param int d - Day
	 * @return DateTime instance
	 */
	function setDate($y, $m, $d){
		$this->y = $y;
		$this->m = $m;
		$this->d = $d;
		return $this;
	}
	/**
	 * Set time
	 * @param int h - Hour
	 * @param int i - Minute
	 * @param int s - Second
	 * @return DateTime instance
	 */
	function setTime($h, $i, $s){
		$this->h = $h;
		$this->i = $i;
		$this->s = $s;
		return $this;
	}
	/**
	 * Set year
	 * @param int y - Year
	 * @return DateTime instance
	 */
	function setYear($y){
		$this->y = $y;
		return $this;
	}
	/**
	 * Set month
	 * @param int m - Month
	 * @return DateTime instance
	 */
	function setMonth($m){
		$this->m = $m;
		return $this;
	}
	/**
	 * Set day
	 * @param int d - Day
	 * @return DateTime instance
	 */
	function setDay($d){
		$this->d = $d;
		return $this;
	}
	/**
	 * Set hour
	 * @param int h - Hour
	 * @return DateTime instance
	 */
	function setHour($h){
		$this->h = $h;
		return $this;
	}
	/**
	 * Set minute
	 * @param int i - Minute
	 * @return DateTime instance
	 */
	function setMinute($i){
		$this->i = $i;
		return $this;
	}
	/**
	 * Set second
	 * @param int s - Second
	 * @return DateTime instance
	 */
	function setSecond($s){
		$this->s = $s;
		return $this;
	}
	/**
	 * Set microsecond
	 * @param int ms - Microsecond
	 * @return DateTime instance
	 */
	function setMicrosecond($ms){
		$this->ms = $ms;
		return $this;
	}
	/**
	 * Set time zone
	 * @param string tz
	 * @return DateTime instance
	 */
	function setTimezone($tz){
		$this->tz = $tz;
		return $this;
	}
	/**
	 * Returns year
	 * @return int
	 */
	function getYear(){
		return $this->y;
	}
	/**
	 * Returns month
	 * @return int
	 */
	function getMonth(){
		return $this->m;
	}
	/**
	 * Returns day
	 * @return int
	 */
	function getDay(){
		return $this->d;
	}
	/**
	 * Returns hour
	 * @return int
	 */
	function getHour(){
		return $this->h;
	}
	/**
	 * Returns minute
	 * @return int
	 */
	function getMinute(){
		return $this->i;
	}
	/**
	 * Returns second
	 * @return int
	 */
	function getSecond(){
		return $this->s;
	}
	/**
	 * Returns microsecond
	 * @return int
	 */
	function getMicrosecond(){
		return $this->ms;
	}
	/**
	 * Returns time zone
	 * @return string
	 */
	function getTimezone(){
		return $this->tz;
	}
	
	public function assignDatetime($dt){
		$this->y = (int)$dt->format("Y");
		$this->m = (int)$dt->format("m");
		$this->d = (int)$dt->format("d");
		$this->h = (int)$dt->format("H");
		$this->i = (int)$dt->format("i");
		$this->s = (int)$dt->format("s");
	}
	public function getDatetime(){
		$dt = new \DateTime("now", new \DateTimeZone($this->tz));
		$dt->setDate($this->y, $this->m, $this->d);
		$dt->setTime($this->h, $this->i, $this->s);
		return $dt;
	}
	/**
	 * Returns datetime
	 * @param string tz
	 * @return DateTime
	 */
	static function now($tz = "UTC"){
		
		$obj = new DateTime();
		$dt = new \DateTime("now", new \DateTimezone($tz));
		$obj->assignDatetime($dt);
		$obj->setTimezone($tz);
		return $obj;
		return null;
	}
	/**
	 * Returns day of week
	 * @return int
	 */
	function getDayOfWeek(){
		
		$dt = $this->getDatetime();
		return $dt->format("w");
		return null;
	}
	/**
	 * Returns timestamp
	 * @return int
	 */
	function getTimestamp(){
		
		$dt = $this->getDatetime();
		return $dt->getTimestamp();
		return null;
	}
	/**
	 * Set timestamp
	 * @param int timestamp
	 * @return DateTime instance
	 */
	function setTimestamp($timestamp){
		
		$dt = $this->getDatetime();
		$dt->setTimestamp($timestamp);
		$this->assignDatetime($dt);
		return $this;
	}
	/**
	 * Change time zone
	 * @param string tz
	 * @return DateTime instance
	 */
	function changeTimezone($tz){
		
		$dt = $this->getDatetime();
		$dt->setTimezone( new \DateTimeZone($tz) );
		$this->setTimezone($tz);
		$this->assignDatetime($dt);
		return $this;
	}
	/**
	 * Return datetime in RFC822
	 * @return string
	 */
	function getRFC822(){
		
		$dt = $this->getDatetime();
		return $dt->format(\DateTime::RFC822);
		return "";
	}
	/**
	 * Return datetime in ISO8601
	 * @return string
	 */
	function getISO8601(){
		
		$dt = $this->getDatetime();
		return $dt->format(\DateTime::ISO8601);
		return "";
	}
	/**
	 * Return datetime by GMT
	 * @return string
	 */
	function getGMT(){
		
		$dt = $this->getDatetime();
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/**
	 * Return datetime by UTC
	 * @return string
	 */
	function getUTC(){
		
		$dt = $this->getDatetime();
		$dt->setTimezone( new \DateTimeZone("UTC") ); 
		return $dt->format("Y-m-d H:i:s");
		return "";
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.DateTime";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
		$this->y = 0;
		$this->m = 0;
		$this->d = 0;
		$this->h = 0;
		$this->u = 0;
		$this->s = 0;
		$this->ms = 0;
		$this->tz = "UTC";
	}
	public function assignObject($obj){
		if ($obj instanceof DateTime){
		}
		parent::assignObject($obj);
	}
	public function assignValue($variable_name, $value){
		parent::assignValue($variable_name, $value);
	}
	public function takeValue($variable_name, $default_value = null){
		return parent::takeValue($variable_name, $default_value);
	}
	public static function getFieldsList($names){
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
}