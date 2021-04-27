<?php
/**
 * ddStash
 * @version 1.2.1 (2020-05-08)
 * 
 * @see README.md
 * 
 * @copyright 2019–2020 DD Group {@link http://www.DivanDesign.biz }
 */

//# Include
//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);


//# Prepare params
$params = \DDTools\ObjectTools::extend([
	'objects' => [
		//Defaults
		(object) [
			'save' => null,
			'save_extendExisting' => false,
			'save_extendExistingWithEmpty' => true,
			'get' => null,
			'get_tpl' => null,
			'storage' => 'post'
		],
		$params
	]
]);

$params->save_extendExisting = boolval($params->save_extendExisting);
$params->save_extendExistingWithEmpty = boolval($params->save_extendExistingWithEmpty);


//# Run
//The snippet must return an empty string even if result is absent
$snippetResult = '';

switch ($params->storage){
	case 'session':
		$params->storage = &$_SESSION;
	break;
	
	case 'post':
	default:
		$params->storage = &$_POST;
	break;
}

//Save to stash
if (!is_null($params->save)){
	$params->save = \DDTools\ObjectTools::convertType([
		'object' => $params->save,
		'type' => 'objectArray'
	]);
	
	foreach (
		$params->save as
		$dataName =>
		$dataValue
	){
		$dataName =
			'ddStash.' .
			$dataName
		;
		
		//If need to extend existing
		if (
			$params->save_extendExisting &&
			isset($params->storage[$dataName])
		){
			$params->storage[$dataName] = \DDTools\ObjectTools::extend([
				'objects' => [
					$params->storage[$dataName],
					$dataValue
				],
				'overwriteWithEmpty' => $params->save_extendExistingWithEmpty
			]);
		}else{
			$params->storage[$dataName] = $dataValue;
		}
	}
}

//Get from stash
if (!is_null($params->get)){
	//Unfolding support (e. g. `parentKey.someKey.0`)
	$keys =	explode(
		'.',
		$params->get
	);
	
	//Correct parent key
	$keys[0] =
		'ddStash.' .
		$keys[0]
	;
	
	//If parent exists
	if (isset($params->storage[$keys[0]])){
		$snippetResult = $params->storage;
		
		//Find needed value
		foreach (
			$keys as
			$key
		){
			//If need to see deeper
			if (is_array($snippetResult)){
				//If element exists
				if (isset($snippetResult[$key])){
					//Save it
					$snippetResult = $snippetResult[$key];
				}else{
					//Return empty string for non-existing elements
					$snippetResult = '';
					
					break;
				}
			}else{
				break;
			}
		}
	}
	
	if (
		is_object($snippetResult) ||
		is_array($snippetResult)
	){
		$snippetResult = json_encode(
			$snippetResult,
			//JSON_UNESCAPED_UNICODE — Не кодировать многобайтные символы Unicode | JSON_UNESCAPED_SLASHES — Не экранировать /
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
		);
	}
	
	if (
		//If template is used
		!empty($params->get_tpl) &&
		//And result is not empty
		!empty($snippetResult)
	){
		$snippetResult = \ddTools::parseText([
			'text' => $modx->getTpl($params->get_tpl),
			'data' => [
				'snippetResult' => $snippetResult
			]
		]);
	}
}


return $snippetResult;
?>