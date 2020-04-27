# (MODX)EvolutionCMS.snippets.ddStash

Сохранение данных для последующего использования.


## Requires

* PHP >= 5.4
* [(MODX)EvolutionCMS](https://github.com/evolution-cms/evolution) >= 1.1
* [(MODX)EvolutionCMS.libraries.ddTools](http://code.divandesign.biz/modx/ddtools) >= 0.28


## Documentation


### Installation

Elements → Snippets: Create a new snippet with the following data:

1. Snippet name: `ddStash`.
2. Description: `<b>1.0</b> Сохранение данных для последующего использования.`.
3. Category: `Core`.
4. Parse DocBlock: `no`.
5. Snippet code (php): Insert content of the `ddStash_snippet.php` file from the archive.


### Parameters description

* `save`
	* Desctription: Data to save in stash. Nested objects are supported too, see examples below.
	* Valid values:
		* `stirngJsonObject` — as [JSON](https://en.wikipedia.org/wiki/JSON)
		* `stringQueryFormated` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
	* Default value: —
	
* `get`
	* Desctription: Data key for getting from stash.
	* Valid values: `string`
	* Default value: —
	
* `get_tpl`
	* Desctription: Output template.
		
		Available placeholders:
		* `[+snippetResult+]` — Data from stash.
		
	* Valid values:
		* `string_chunkName`
		* `string` — use inline templates starting with `@CODE:`
	* Default value: `'@CODE:[+snippetResult+]'`
	
* `storage`
	* Desctription: Data storage.
	* Valid values:
		* `'post'` — `$_POST`
		* `'session'` — `$_SESSION`
	* Default value: `'post'`


### Examples


#### Save some data

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "John",
			"lastName": "[[ddGetDocumentField?
				&docId=`1`
				&docField=`lastName`
			]]",
			"children": [
				{
					"firstName": "Alice"
				},
				{
					"firstName": "Robert"
				}
			]
		},
		"someData": "someValue"
	}`
]]
```

	
#### Get saved data


##### You can get field value in any depth

```
[[ddStash? &get=`someData`]]
```

Returns `someValue`.

```
[[ddStash? &get=`userData.firstName`]]
```

Returns `John`.

```
[[ddStash? &get=`userData.children.0.firstName`]]
```

Returns `Alice`.


##### Also you can get objects in JSON

If field value is object or array, it will be returned in JSON format.


###### Get first John child

```
[[ddStash? &get=`userData.children.0`]]
```

Returns:

```json
{
	"firstName": "Alice"
}
```


###### Get all John children:

```
[[ddStash? &get=`userData.children`]]
```

Returns:

```json
[
	{
		"firstName": "Alice"
	},
	{
		"firstName": "Robert"
	}
]
```


###### Get all data about John

```
[[ddStash? &get=`userData`]]
```

Returns:

```json
{
	"firstName": "John",
	"lastName": "Doe",
	"children": [
		{
			"firstName": "Alice"
		},
		{
			"firstName": "Robert"
		}
	]
}
```


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />