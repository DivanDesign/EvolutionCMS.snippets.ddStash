# (MODX)EvolutionCMS.snippets.ddStash

Save data as [JSON](https://en.wikipedia.org/wiki/JSON) or [Query string](https://en.wikipedia.org/wiki/Query_string), then extend if needed and use it later without database queries.


## Requires

* PHP >= 5.6
* [(MODX)EvolutionCMS](https://github.com/evolution-cms/evolution) >= 1.1
* [(MODX)EvolutionCMS.libraries.ddTools](http://code.divandesign.biz/modx/ddtools) >= 0.49.1


## Documentation


### Installation


#### Manually


##### 1. Elements → Snippets: Create a new snippet with the following data

1. Snippet name: `ddStash`.
2. Description: `<b>1.3</b> Save data as JSON or QueryString, then extend if needed and use it later without database queries.`.
3. Category: `Core`.
4. Parse DocBlock: `no`.
5. Snippet code (php): Insert content of the `ddStash_snippet.php` file from the archive.


###### 2. Elements → Manage Files

1. Create a new folder `assets/snippets/ddStash/`.
2. Extract the archive to the folder (except `ddStash_snippet.php`).


#### Using [(MODX)EvolutionCMS.libraries.ddInstaller](https://github.com/DivanDesign/EvolutionCMS.libraries.ddInstaller)

Just run the following PHP code in your sources or [Console](https://github.com/vanchelo/MODX-Evolution-Ajax-Console):

```php
//Include (MODX)EvolutionCMS.libraries.ddInstaller
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddInstaller/require.php'
);

//Install (MODX)EvolutionCMS.snippets.ddStash
\DDInstaller::install([
	'url' => 'https://github.com/DivanDesign/EvolutionCMS.snippets.ddStash',
	'type' => 'snippet'
]);
```

* If `ddStash` is not exist on your site, `ddInstaller` will just install it.
* If `ddStash` is already exist on your site, `ddInstaller` will check it version and update it if needed.


### Parameters description

* `save`
	* Desctription: Data to save in stash. Nested objects are supported too, see examples below.
	* Valid values:
		* `stringJsonObject` — as [JSON](https://en.wikipedia.org/wiki/JSON)
		* `stringHjsonObject` — as [HJSON](https://hjson.github.io/)
		* `stringQueryFormated` — as [Query string](https://en.wikipedia.org/wiki/Query_string)
		* It can also be set as a native PHP object or array (e. g. for calls through `$modx->runSnippet`):
			* `arrayAssociative`
			* `object`
	* Default value: —
	
* `save_extendExisting`
	* Desctription: Extend an existing object instead of overwriting it.
	* Valid values:
		* `0`
		* `1`
	* Default value: `0`
	
* `save_extendExistingWithEmpty`
	* Desctription: Overwrite fields with empty values (see examples below).  
		The following values are considered to be empty:
		* `""` — an empty string
		* `[]` — an empty array
		* `{}` — an empty object
		* `null`
	* Valid values:
		* `0`
		* `1`
	* Default value: `1`
	
* `get`
	* Desctription: Data key for getting from stash.
	* Valid values: `string`
	* Default value: —
	
* `get_tpl`
	* Desctription: Output template.
		
		Available placeholders:
		* `[+snippetResult+]` — data from stash
		
	* Valid values:
		* `stringChunkName`
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


#### Save: Extend an existing object instead of overwriting it (``&save_extendExisting=`1` ``)

First you save some object:

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Chuck",
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
	}`
]]
```

Then if you just save object with the same key (`userData`):

```
[[ddStash?
	&save=`{
		"userData": {
			"middleName": "Ray",
			"lastName": "Norris"
		}
	}`
]]
```

The snippet will overwrite previous saved object completely:

```
[[ddStash? &get=`userData`]]
```

Returns:

```json
{
	"middleName": "Ray",
	"lastName": "Norris"
}
```

So, if you want to extend the first object just use the `save_extendExisting` parameter:

```
[[ddStash?
	&save=`{
		"userData": {
			"middleName": "Ray",
			"lastName": "Norris"
		}
	}`
	&save_extendExisting=`1`
]]
```

In this case the snippet will recursive extend the first object with the data from the second:

```
[[ddStash? &get=`userData`]]
```

Returns:

```json
{
	"firstName": "Chuck",
	"middleName": "Ray",
	"lastName": "Norris",
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


#### Save: Extend without overwriting fields with empty values (``&save_extendExistingWithEmpty=`0` ``)

By default, empty field values (e. g. `''`) are treated as other values and will replace non-empty ones.

```
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "John",
			"lastName": "Tesla",
			"discipline": "Electrical engineering"
		}
	}`
]]
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Nikola",
			"lastName": ""
		}
	}`
	&save_extendExisting=`1`
]]
```

Returns:

```json
{
	"firstName": "Nikola",
	"lastName": "",
	"discipline": "Electrical engineering"
}
```

Empty `lastName` from the second object replaced non-empty `lastName` from the first.

If you want to ignore empty values, just use `save_extendExistingWithEmpty` == `0`:

```php
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "John",
			"lastName": "Tesla",
			"discipline": "Electrical engineering"
		}
	}`
]]
[[ddStash?
	&save=`{
		"userData": {
			"firstName": "Nikola",
			"lastName": ""
		}
	}`
	&save_extendExisting=`1`
	&save_extendExistingWithEmpty=`0`
]]
```

Returns:

```json
{
	"firstName": "Nikola",
	"lastName": "Tesla",
	"discipline": "Electrical engineering"
}
```


#### Run the snippet through `\DDTools\Snippet::runSnippet` without DB and eval

```php
//Include (MODX)EvolutionCMS.libraries.ddTools
require_once(
	$modx->getConfig('base_path') .
	'assets/libs/ddTools/modx.ddtools.class.php'
);

//Run (MODX)EvolutionCMS.snippets.ddStash
\DDTools\Snippet::runSnippet([
	'name' => 'ddStash',
	'params' => [
		'get' => 'userData.firstName'
	]
]);
```


## Links

* [Home page](https://code.divandesign.biz/modx/ddstash)
* [Telegram chat](https://t.me/dd_code)
* [Packagist](https://packagist.org/packages/dd/evolutioncms-snippets-ddstash)


<link rel="stylesheet" type="text/css" href="https://DivanDesign.ru/assets/files/ddMarkdown.css" />