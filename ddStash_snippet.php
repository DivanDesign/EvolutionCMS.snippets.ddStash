<?php
/**
 * ddStash
 * @version 1.0 (2019-10-31)
 * 
 * @see README.md
 * 
 * @copyright 2019 DivanDesign {@link http://www.DivanDesign.biz }
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
	$save = \ddTools::unfoldArray(\ddTools::encodedStringToArray($save));
	
	foreach (
		$save as
		$dataName =>
		$dataValue
	){
		$storage['ddStash.' . $dataName] = $dataValue;
	}
}


//Get from stash
if (
	isset($get) &&
	isset($storage['ddStash.' . $get])
){
	$snippetResult = $storage['ddStash.' . $get];
	
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