<?php
namespace ddStash;

class Snippet extends \DDTools\Snippet {
	protected
		$version = '1.3.0',
		
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
	 * @version 1.0.2 (2023-03-09)
	 * 
	 * @return {string}
	 */
	private function run_get(){
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
		
		$result = \DDTools\ObjectTools::getPropValue([
			'object' => $this->storage,
			'propName' => implode(
				'.',
				$keys
			)
		]);
		
		if (is_null($result)){
			$result = '';
		}elseif (
			is_object($result) ||
			is_array($result)
		){
			$result = \DDTools\ObjectTools::convertType([
				'object' => $result,
				'type' => 'stringJsonAuto'
			]);
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