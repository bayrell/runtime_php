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
namespace Runtime;
use Runtime\rtl;
use Runtime\AsyncTask;
use Runtime\CoreObject;
use Runtime\Vector;
class AsyncThread extends CoreObject{
	protected $f;
	protected $pos;
	protected $res;
	protected $err;
	protected $run_stack;
	protected $catch_stack;
	/**
	 * Constructor
	 */
	function __construct(){
		$super();
		$this->reset();
	}
	/**
	 * Reset AsyncThread
	 */
	function reset(){
		$this->pos = "0";
		$this->res = null;
		$this->err = null;
		$this->run_stack = new Vector();
		$this->catch_stack = new Vector();
	}
	/**
	 * Returns current position
	 * @return string
	 */
	function current(){
		return $this->pos;
	}
	/**
	 * Returns result of the prev function
	 * @return mixed
	 */
	function result(){
		return $this->res;
	}
	/**
	 * Returns error of the prev function
	 * @return mixed
	 */
	function getError(){
		return $this->err;
	}
	/**
	 * Clear error
	 */
	function clearError(){
		$this->err = null;
	}
	/**
	 * Resolve thread with result
	 * @param mixed res
	 */
	function resolve($res){
		$this->res = $res;
		$this->next();
		$this->forward();
		return "resolve";
	}
	/**
	 * Set position
	 * @param string pos
	 */
	function jump($pos){
		$this->pos = $pos;
		return "jump";
	}
	/**
	 * Resolve Exception
	 * @param mixed res
	 */
	function error($err){
		if ($this->catch_stack->count() == 0){
			$this->err = $err;
			$this->next(true);
		}
		else {
			$this->err = $err;
			$this->pos = $this->catch_stack->pop();
		}
		$this->forward();
		return "error";
	}
	/**
	 * Push catch
	 * @param string pos
	 */
	function catchPush($pos){
		$this->catch_stack->push($pos);
	}
	/**
	 * Pop catch
	 */
	function catchPop(){
		$this->catch_stack->pop();
	}
	/**
	 * Call next
	 */
	function next($is_error = false){
		if ($this->run_stack->count() == 0){
			$this->pos = "-1";
			return ;
		}
		$task = $this->run_stack->pop();
		/* Restore pos */
		$this->f = $task->f;
		$this->pos = $task->pos;
		$this->catch_stack = $task->catch_stack;
		if ($is_error){
			if ($this->catch_stack->count() == 0){
				$this->pos = "-1";
			}
			else {
				$this->pos = $this->catch_stack->pop();
			}
		}
		return "next";
	}
	/**
	 * Forward call
	 */
	function forward(){
		$is_browser = rtl::isBrowser();
		return "forward";
	}
	/**
	 * Run async await
	 */
	function run($f){
		$this->pos = "0";
		$this->f = $f;
		$this->forward();
	}
	/**
	 * Call if thread is ended
	 */
	function end(){
		$this->pos = "-1";
		return "end";
	}
	/* ======================= Class Init Functions ======================= */
	public function getClassName(){return "Runtime.AsyncThread";}
	public static function getCurrentClassName(){return "Runtime.AsyncThread";}
	public static function getParentClassName(){return "Runtime.CoreObject";}
	protected function _init(){
		parent::_init();
	}
	public static function getFieldsList($names, $flag=0){
	}
	public static function getFieldInfoByName($field_name){
		return null;
	}
	public static function getMethodsList($names){
	}
	public static function getMethodInfoByName($method_name){
		return null;
	}
}