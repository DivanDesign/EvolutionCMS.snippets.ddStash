<?php
/**
 * ddStash
 * @version 1.3 (2021-04-28)
 * 
 * @see README.md
 * 
 * @copyright 2019–2021 DD Group {@link https://DivanDesign.biz }
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