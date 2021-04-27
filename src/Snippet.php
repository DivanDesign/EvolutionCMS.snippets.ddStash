<?php
namespace ddStash;

class Snippet extends \DDTools\Snippet {
	protected
		$version = '1.2.1',
		
		$params = [
			//Defaults
			'save' => null,
			'save_extendExisting' => false,
			'save_extendExistingWithEmpty' => true,
			'get' => null,
			'get_tpl' => null,
			'storage' => 'post'
		],
		
		$paramsTypes = [
			'save_extendExisting' => 'boolean',
			'save_extendExistingWithEmpty' => 'boolean'
		]
	;
	
	/**
	 * run
	 * @version 1.0 (2021-04-28)
	 * 
	 * @return {string}
	 */
	public function run(){
		//The snippet must return an empty string even if result is absent
		$result = '';
		
		switch ($this->params->storage){
			case 'session':
				$this->params->storage = &$_SESSION;
			break;
			
			case 'post':
			default:
				$this->params->storage = &$_POST;
			break;
		}
		
		//Save to stash
		if (!is_null($this->params->save)){
			$this->params->save = \DDTools\ObjectTools::convertType([
				'object' => $this->params->save,
				'type' => 'objectArray'
			]);
			
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
					isset($this->params->storage[$dataName])
				){
					$this->params->storage[$dataName] = \DDTools\ObjectTools::extend([
						'objects' => [
							$this->params->storage[$dataName],
							$dataValue
						],
						'overwriteWithEmpty' => $this->params->save_extendExistingWithEmpty
					]);
				}else{
					$this->params->storage[$dataName] = $dataValue;
				}
			}
		}
		
		//Get from stash
		if (!is_null($this->params->get)){
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
			if (isset($this->params->storage[$keys[0]])){
				$result = $this->params->storage;
				
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
		}
		
		return $result;
	}
}