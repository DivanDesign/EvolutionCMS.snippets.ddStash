<?php
/**
 * ddStash
 * @version 1.2.1 (2020-05-08)
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

return \DDTools\Snippet::runSnippet([
	'name' => 'ddStash',
	'params' => $params
]);
?>