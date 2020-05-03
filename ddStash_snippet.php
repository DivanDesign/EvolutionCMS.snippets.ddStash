<?php
/**
 * ddStash
 * @version 1.2 (2020-05-03)
 * 
 * @see README.md
 * 
 * @copyright 2019–2020 DD Group {@link http://www.DivanDesign.biz }
 */

//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//The snippet must return an empty string even if result is absent
$snippetResult = '';


//Prepare storage
if (!isset($storage)){
	$storage = 'post';
}

switch ($storage){
	case 'session':
		$storage = &$_SESSION;
	break;
	
	case 'post':
	default:
		$storage = &$_POST;
	break;
}


//Save to stash
if (isset($save)){
	$save_extendExisting =
		isset($save_extendExisting) ?
		boolval($save_extendExisting) :
		false
	;
	$save_extendExistingWithEmpty =
		(
			isset($save_extendExistingWithEmpty) &&
			$save_extendExistingWithEmpty == '0'
		) ?
		false :
		true
	;
	
	$save = \ddTools::encodedStringToArray($save);
	
	foreach (
		$save as
		$dataName =>
		$dataValue
	){
		$dataName =
			'ddStash.' .
			$dataName
		;
		
		//If need to extend existing
		if (
			$save_extendExisting &&
			isset($storage[$dataName])
		){
			$storage[$dataName] = \DDTools\ObjectTools::extend([
				'objects' => [
					$storage[$dataName],
					$dataValue
				],
				'overwriteWithEmpty' => $save_extendExistingWithEmpty
			]);
		}else{
			$storage[$dataName] = $dataValue;
		}
	}
}

//Get from stash
if (isset($get)){
	//Unfolding support (e. g. `parentKey.someKey.0`)
	$keys =	explode(
		'.',
		$get
	);
	
	//Correct parent key
	$keys[0] =
		'ddStash.' .
		$keys[0]
	;
	
	//If parent exists
	if (isset($storage[$keys[0]])){
		$snippetResult = $storage;
		
		//Find needed value
		foreach (
			$keys as
			$key
		){
			//If need to see deeper
			if (
				is_array($snippetResult) &&
				isset($snippetResult[$key])
			){
				$snippetResult = $snippetResult[$key];
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
		isset($get_tpl) &&
		//And result is not empty
		!empty($snippetResult)
	){
		$snippetResult = \ddTools::parseText([
			'text' => $modx->getTpl($get_tpl),
			'data' => [
				'snippetResult' => $snippetResult
			]
		]);
	}
}


return $snippetResult;
?>