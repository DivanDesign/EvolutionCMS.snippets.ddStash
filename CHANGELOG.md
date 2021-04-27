# (MODX)EvolutionCMS.snippets.ddStash changelog


## Version 1.3 (2021-04-28)
* \* Attention! PHP >= 5.6 is required.
* \* Attention! (MODX)EvolutionCMS.libraries.ddTools >= 0.49.1 is required.
* \+ Parameters → `save`: Can also be set as [HJSON](https://hjson.github.io/) or as a native PHP object or array (e. g. for calls through `$modx->runSnippet`).
* \+ You can just call `\DDTools\Snippet::runSnippet` to run the snippet without DB and eval (see README → Examples).
* \+ `\ddStash\Snippet`: The new class. All snippet code was moved here.
* \* README:
	* \* Documentation:
		* \+ Installation → Using (MODX)EvolutionCMS.libraries.ddInstaller.
		* \* Examples: Fixed wrong JSON format.
	* \+ Links.
* \* Composer.json:
	* \+ `homepage`.
	* \+ `support`
	* \+ `authors`.
	* \* `require` → `dd/evolutioncms-libraries-ddtools`: Fixed outdated name.


## Version 1.2.1 (2020-05-08)
* \* When you try to get non existent elements, an empty string will be returned.


## Version 1.2 (2020-05-03)
* \+ Added the ability to prevent overwriting fields with empty values (the `save_extendExistingWithEmpty` parameter, see README).
* \+ README_ru.
* \+ CHANGELOG_ru.
* \* README: Small improvements.


## Version 1.1 (2020-04-29)
* \+ Added the ability to return objects and arrays in JSON format (see README.md).
* \+ Added the ability to extend an existing object instead of overwriting it (see `save_extendExisting`).
* \+ README, CHANGELOG: Style improvements.
* \* REAMDE → Parameters description → `save`: Small improvements.
* \* Composer.json:
	* \* `version`: Fixed format.
	* \+ `keywords` → `stash`.
	* \+ `require`.


## Version 1.0 (2019-10-31)
* \+ The first release.


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />
<style>ul{list-style:none;}</style>