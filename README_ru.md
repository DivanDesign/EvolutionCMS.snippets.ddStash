# (MODX)EvolutionCMS.snippets.ddStash

Сохраняйте данные в формате [JSON](https://ru.wikipedia.org/wiki/JSON) или [Query string](https://ru.wikipedia.org/wiki/Query_string), затем расширяйте по необходимости и используйте позже без запросов к базе данных.


## Использует

* PHP >= 5.6
* [(MODX)EvolutionCMS](https://github.com/evolution-cms/evolution) >= 1.1
* [(MODX)EvolutionCMS.libraries.ddTools](http://code.divandesign.biz/modx/ddtools) >= 0.49.1


## Документация


### Установка


#### Вручную


##### 1. Элементы → Сниппеты: Создайте новый сниппет со следующими параметрами

1. Название сниппета: `ddStash`.
2. Описание: `<b>1.2.1</b> Сохраняйте данные в формате JSON или QueryString, затем расширяйте по необходимости и используйте позже без запросов к базе данных.`.
3. Категория: `Core`.
4. Анализировать DocBlock: `no`.
5. Код сниппета (php): Вставьте содержимое файла `ddStash_snippet.php` из архива.


##### 2. Элементы → Управление файлами

1. Создайте новую папку `assets/snippets/ddStash/`.
2. Извлеките содержимое архива в неё (кроме файла `ddStash_snippet.php`).


#### Используя [(MODX)EvolutionCMS.libraries.ddInstaller](https://github.com/DivanDesign/EvolutionCMS.libraries.ddInstaller)

Просто вызовите следующий код в своих исходинках или модуле [Console](https://github.com/vanchelo/MODX-Evolution-Ajax-Console):

```php
//Подключение (MODX)EvolutionCMS.libraries.ddInstaller
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddInstaller/require.php'
);

//Установка (MODX)EvolutionCMS.snippets.ddStash
\DDInstaller::install([
	'url' => 'https://github.com/DivanDesign/EvolutionCMS.snippets.ddStash',
	'type' => 'snippet'
]);
```

* Если `ddStash` отсутствует на вашем сайте, `ddInstaller` просто установит его.
* Если `ddStash` уже есть на вашем сайте, `ddInstaller` проверит его версию и обновит, если нужно. 


### Описание параметров

* `save`
	* Описание: Сохранить данные в хранилище для последующего использования. Вложенные объекты также поддерживаются, см. примеры ниже.
	* Допустимые значения:
		* `stringJsonObject` — в виде [JSON](https://ru.wikipedia.org/wiki/JSON)
		* `stringHjsonObject` — в виде [HJSON](https://hjson.github.io/)
		* `stringQueryFormated` — в виде [Query string](https://en.wikipedia.org/wiki/Query_string)
		* Также может быть задан, как нативный PHP объект или массив (например, для вызовов через `$modx->runSnippet`).
			* `arrayAssociative`
			* `object`
	* Значение по умолчанию: —
	
* `save_extendExisting`
	* Описание: Расширить существующие объекты вместо их перезаписи.
	* Допустимые значения:
		* `0`
		* `1`
	* Значение по умолчанию: `0`
	
* `save_extendExistingWithEmpty`
	* Описание: Перезаписыать поля пустыми значениями (см. примеры ниже).  
		Следующие значения трактуются, как пустые:
		* `""` — an empty string
		* `[]` — an empty array
		* `{}` — an empty object
		* `null`
	* Допустимые значения:
		* `0`
		* `1`
	* Значение по умолчанию: `1`
	
* `get`
	* Описание: Ключ для получения данных из хранилища.
	* Допустимые значения: `string`
	* Значение по умолчанию: —
	
* `get_tpl`
	* Описание: Шаблон вывода.
		
		Доступные плейсхолдеры:
		* `[+snippetResult+]` — данные из хранилища
		
	* Допустимые значения:
		* `stringChunkName`
		* `string` — передавать код напрямую без чанка можно начиная значение с `@CODE:`
	* Значение по умолчанию: `'@CODE:[+snippetResult+]'`
	
* `storage`
	* Описание: Хранилище данных.
	* Допустимые значения:
		* `'post'` — `$_POST`
		* `'session'` — `$_SESSION`
	* Значение по умолчанию: `'post'`


### Примеры


#### Сохранить какие-нибудь данные

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Иван",
			"lastName": "[[ddGetDocumentField?
				&docId=`1`
				&docField=`lastName`
			]]",
			"children": [
				{
					"firstName": "Алиса"
				},
				{
					"firstName": "Роберт"
				}
			]
		},
		"someData": "someValue"
	}`
]]
```

	
#### Получить сохранённые данные


##### Вы можете получить значение поля любого уровня вложенности

```
[[ddStash? &get=`someData`]]
```

Вернёт `someValue`.

```
[[ddStash? &get=`userData.firstName`]]
```

Вернёт `Иван`.

```
[[ddStash? &get=`userData.children.0.firstName`]]
```

Вернёт `Алиса`.


##### Ещё вы можете получить объекты в виде JSON

Если значение поля — объект или массив, оно будет возвращено в формате JSON.


###### Получить первого ребёнка Ивана

```
[[ddStash? &get=`userData.children.0`]]
```

Вернёт:

```json
{
	"firstName": "Алиса"
}
```


###### Получить всех детей Ивана:

```
[[ddStash? &get=`userData.children`]]
```

Вернёт:

```json
[
	{
		"firstName": "Алиса"
	},
	{
		"firstName": "Роберт"
	}
]
```


###### Получить всю информацию об Иване

```
[[ddStash? &get=`userData`]]
```

Вернёт:

```json
{
	"firstName": "Иван",
	"lastName": "Иванов",
	"children": [
		{
			"firstName": "Алиса"
		},
		{
			"firstName": "Роберт"
		}
	]
}
```


#### Сохранение: Расширить существующий объект вместо его перезаписи (``&save_extendExisting=`1` ``)

Для начала сохраняем какой-нибудь объект:

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Чак",
			"lastName": "Иванов",
			"children": [
				{
					"firstName": "Алиса"
				},
				{
					"firstName": "Роберт"
				}
			]
		}
	}`
]]
```

Затем если мы просто сохраним объект с таким же ключём (`userData`):

```
[[ddStash?
	&save=`{
		"userData": {
			"middleName": "Рэй",
			"lastName": "Норрис"
		}
	}`
]]
```

Сниппет перепишет предыдущий сохранённый объект полностью:

```
[[ddStash? &get=`userData`]]
```

Вернёт:

```json
{
	"middleName": "Рэй",
	"lastName": "Норрис"
}
```

И так, если мы всё же хотим именно расширить первый объект, просто используем параметр `save_extendExisting`:

```
[[ddStash?
	&save=`{
		"userData": {
			"middleName": "Рэй",
			"lastName": "Норрис"
		}
	}`
	&save_extendExisting=`1`
]]
```

В этом случае сниппет рекурсивно расширит первый объект данными второго:

```
[[ddStash? &get=`userData`]]
```

Вернёт:

```json
{
	"firstName": "Чак",
	"middleName": "Рэй",
	"lastName": "Норрис",
	"children": [
		{
			"firstName": "Алиса"
		},
		{
			"firstName": "Роберт"
		}
	]
}
```


#### Сохранение: Расширение объектов без перезаписи полей пустыми значениями (``&save_extendExistingWithEmpty=`0` ``)

По умолчанию, полдя с пустыми занчениями (например, `''`) обрабатываются, как и все остальные значения и перезаписывают при расширении непустые. 

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Иван",
			"lastName": "Тесла",
			"discipline": "Электротехника"
		}
	}`
]]
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Никола",
			"lastName": ""
		}
	}`
	&save_extendExisting=`1`
]]
```

Вернёт:

```json
{
	"firstName": "Никола",
	"lastName": "",
	"discipline": "Электротехника"
}
```

Пустое значение `lastName` из второго объекта перезаписало непустое значение `lastName` из первого объекта.

Если нам такое не нужно и мы хотим игнориовать пустые значения, просто изпользуем параметр `save_extendExistingWithEmpty` == `0`:

```php
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Иван",
			"lastName": "Тесла",
			"discipline": "Электротехника"
		}
	}`
]]
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Никола",
			"lastName": ""
		}
	}`
	&save_extendExisting=`1`
	&save_extendExistingWithEmpty=`0`
]]
```

Вернёт:

```json
{
	"firstName": "Никола",
	"lastName": "Тесла",
	"discipline": "Электротехника"
}
```


#### Запустить сниппет через `\DDTools\Snippet::runSnippet` без DB и eval

```php
//Подключение (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Запуск (MODX)EvolutionCMS.snippets.ddStash
\DDTools\Snippet::runSnippet([
	'name' => 'ddStash',
	'params' => [
		'get' => 'userData.firstName'
	]
]);
```


## Ссылки

* [Home page](https://code.divandesign.biz/modx/ddstash)
* [Telegram chat](https://t.me/dd_code)
* [Packagist](https://packagist.org/packages/dd/evolutioncms-snippets-ddstash)


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />