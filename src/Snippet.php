<?php
namespace ddStash;

class Snippet extends \DDTools\Snippet {
	protected
		$version = '1.2.1',
		
		$params = [
			//Defaults
			'save' => [],
			'save_extendExisting' => false,
			'save_extendExistingWithEmpty' => true,
			'get' => '',
			'get_tpl' => null,
			'storage' => 'post'
		],
		
		$paramsTypes = [
			'save' => 'objectArray',
			'save_extendExisting' => 'boolean',
			'save_extendExistingWithEmpty' => 'boolean'
		]
	;
	
	private
		$storage = []
	;
	
	/**
	 * __construct
	 * @version 1.0 (2021-04-28)
	 * 
	 * @param $params {stdClass|arrayAssociative|stringJsonObject|stringHjsonObject|stringQueryFormatted}
	 */
	public function __construct($params = []){
		//Call base method
		parent::__construct($params);
		
		//Prepare storage
		switch ($this->params->storage){
			case 'session':
				$this->storage = &$_SESSION;
			break;
			
			case 'post':
			default:
				$this->storage = &$_POST;
			break;
		}
	}
	
	/**
	 * run
	 * @version 1.0.2 (2021-04-28)
	 * 
	 * @return {string}
	 */
	public function run(){
		//The snippet must return an empty string even if result is absent
		$result = '';
		
		//Save to stash
		if (!empty($this->params->save)){
			$this->run_save();
		}
		
		//Get from stash
		if (!empty($this->params->get)){
			$result = $this->run_get();
		}
		
		return $result;
	}
	
	/**
	 * run_save
	 * @version 1.0.1 (2021-04-28)
	 * 
	 * @return {void}
	 */
	private function run_save(){
		foreach (
			$this->params->save as
			$dataName =>
			$dataValue
		){
			$dataName =
				'ddStash.' .
				$dataName
			;
			
			//If need to extend existing
			if (
				$this->params->save_extendExisting &&
				isset($this->storage[$dataName])
			){
				$this->storage[$dataName] = \DDTools\ObjectTools::extend([
					'objects' => [
						$this->storage[$dataName],
						$dataValue
					],
					'overwriteWithEmpty' => $this->params->save_extendExistingWithEmpty
				]);
			}else{
				$this->storage[$dataName] = $dataValue;
			}
		}
	}
	
	/**
	 * run_get
	 * @version 1.0 (2021-04-28)
	 * 
	 * @return {string}
	 */
	private function run_get(){
		$result = '';
		
		//Unfolding support (e. g. `parentKey.someKey.0`)
		$keys =	explode(
			'.',
			$this->params->get
		);
		
		//Correct parent key
		$keys[0] =
			'ddStash.' .
			$keys[0]
		;
		
		//If parent exists
		if (isset($this->storage[$keys[0]])){
			$result = $this->storage;
			
			//Find needed value
			foreach (
				$keys as
				$key
			){
				//If need to see deeper
				if (is_array($result)){
					//If element exists
					if (isset($result[$key])){
						//Save it
						$result = $result[$key];
					}else{
						//Return empty string for non-existing elements
						$result = '';
						
						break;
					}
				}else{
					break;
				}
			}
		}
		
		if (
			is_object($result) ||
			is_array($result)
		){
			$result = json_encode(
				$result,
				//JSON_UNESCAPED_UNICODE — Не кодировать многобайтные символы Unicode | JSON_UNESCAPED_SLASHES — Не экранировать /
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			);
		}
		
		if (
			//If template is used
			!empty($this->params->get_tpl) &&
			//And result is not empty
			!empty($result)
		){
			$result = \ddTools::parseText([
				'text' => \ddTools::$modx->getTpl($this->params->get_tpl),
				'data' => [
					'snippetResult' => $result
				]
			]);
		}
		
		return $result;
	}
}