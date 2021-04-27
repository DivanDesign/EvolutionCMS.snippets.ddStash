# (MODX)EvolutionCMS.snippets.ddStash changelog


## Версия 1.3 (2021-04-28)
* \* Внимание! Требуется PHP >= 5.6.
* \* Внимание! Требуется (MODX)EvolutionCMS.libraries.ddTools >= 0.49.1.
* \+ Параметры → `save`: Также может быть задан, как [HJSON](https://hjson.github.io/) или как нативный PHP объект или массив (например, для вызовов через `$modx->runSnippet`).
* \+ Запустить сниппет без DB и eval можно через `\DDTools\Snippet::runSnippet` (см. примеры в README).
* \+ `\ddStash\Snippet`: Новый класс. Весь код сниппета перенесён туда.
* \* README:
	* \* Документация:
		* \+ Установка → Используя (MODX)EvolutionCMS.libraries.ddInstaller.
		* \* Примеры: Исправлен некорректный формат JSON.
	* \+ Ссылки.
* \* Composer.json:
	* \+ `homepage`.
	* \+ `support`
	* \+ `authors`.
	* \* `require` → `dd/evolutioncms-libraries-ddtools`: Исправлено устаревшее название библиотеки.


## Версия 1.2.1 (2020-05-08)
* \* При попытке получить несуществующий элемент будет возвращена пустая строка.


## Версия 1.2 (2020-05-03)
* \+ Добавлена возможность предотвратить перезапись полей при расширении пустыми значениями (параметр `save_extendExistingWithEmpty`, см. README).
* \+ README_ru.
* \+ CHANGELOG_ru.
* \* README: Небольшие улучшения.


## Версия 1.1 (2020-04-29)
* \+ Добавлена возможность возвращать объекты и массивы в формате JSON (см. README).
* \+ Добавлена возможность расширения существующих объектов вместо их перезаписи (см. параметр `save_extendExisting`).
* \+ README, CHANGELOG: Стиль улучшен.
* \* REAMDE → Описание параметров → `save`: Небольшие улучшения.
* \* Composer.json:
	* \* `version`: Исправлен формат.
	* \+ `keywords` → `stash`.
	* \+ `require`.


## Версия 1.0 (2019-10-31)
* \+ Первый релиз.


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />
<style>ul{list-style:none;}</style>