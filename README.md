# Yii2 Laravel-like routes

Routing and filtering extension system for Yii2 framework that emulates the Laravel routing system.

## What's Laravel-like routes?

This module changes the route system definition of Yii2 in order to, instead of having to define the routes in the config file of the application now will be possible to make a series of files that hold the routes that the user will define for his web. This module lets the calling to a series of methods that will define the system routes in a more intuitive way that the basic Yii2 system getting it's inspiration from the routing system defined by Laravel.

Developed by Joseba Juániz ([@Patroklo](http://twitter.com/Patroklo))

[Spanish Readme version](https://github.com/Patroklo/yii2-static-laravel-routes/blob/master/README_spanish.md)

## Minimum requirements

* Yii2
* Php 5.4 or above

## Future plans

* Pass manual parameters to the filters.
* Automatic system to make RESTFul Routes.

## License

This is free software. It is released under the terms of the following BSD License.

Copyright (c) 2014, by Cyneek
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. Neither the name of Cyneek nor the names of its contributors
   may be used to endorse or promote products derived from this software
   without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDER "AS IS" AND ANY
EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

## Instalation

* Install [Yii 2](http://www.yiiframework.com/download)
* Install package via [composer](http://getcomposer.org/download/) `"cyneek/yii2-routes": "dev-master"`
* Update config file _'config/web.php'_

```
'bootstrap' => ['log', 'routes'];
...
'components' => [
	'route' => [
		'class' => 'cyneek\yii2\routes\components\route',
	],
]
...
'modules' => [
	'routes' => [
		'class' => 'cyneek\yii2\routes\Module',
		'routes_dir' => array('../routes')
	]
	// set custom modules here
],
```
* Make a _routes_ directory in your @app level.
* Insert files in this directory using the Route class definition.
* Profit!

## Deactivating module

It's possible to deactivate the routing module just adding to the definition of "routes" array in the config the value "active" = FALSE.

```
'modules' => [
		'routes' => [
            'class' => 'cyneek\yii2\routes\Module',
			'active' => FALSE
		]
		// set custom modules here
    ],
```

## Definition

Note:

This routing system uses the Yii2 urlManager parameters by default:

* enablePretttyUrl = TRUE

* enableStrictParsing = TRUE

* showScriptName = FALSE


### Basic routing

The basic routing methods will make a new route comprehensible by the Yii2 system.

```
Route::get('user',            'user/index');
Route::post('user/(:any)',    'user/load/$1');
Route::put('user/(:any),      'user/update/$1');
Route::delete('user/(:any)',  'user/delete/$1');
Route::head('user',           'user/index');
Route::patch('user/(:any),    'user/update/$1');
```

The developer also can use two additional methods that will let him work with more than one http verbs at the same time.

`any` will let the route work under any HTTP method (GET, POST, PUT, DELETE, HEAD, PATCH),

```
Route::any('user',            'user/index');
```

`match` let's the developer to define manually a group of HTTP verbs that will the only ones that respond to this route.

```
Route::match(['GET', 'POST'], 'user', 'user/index');
```

### Named routes

Additionally there can be defined a group of additional properties in the basic routing methods.
 
 One of them it's the route naming, that will let the developer use their defined web url using only it's name instead of use all their chain.
 
```
Route::set_name('user_update', 'admin/user/load/');

Route::get('user', 'user/index', ['as' => 'user']);
```

To get a route url by it's name there is only necessary to

```
echo Route::named('user');

redirect(Route::named('user'));
```

In case the route has named parameters, it's possible to define values for them in a second parameter of the method `named`

```
Route::named('user', ['id' => 12]);
```

If the route has optional named parameters without a defined value, they won't be shown in the method's return url string.

### Named parameters

It's possible to define named parameters instead of the regular expressions used by Yii2 in the parameter routing system. This will let the developer to use them in getting it's values during the application lifetime.

There are two additional defined wildcards, `(:any)` that matches with the regular expression "any character" and `(:num)` that matches with the regex "any number".

```
Route::any('user/{id}',     'user/load');
```

There are two ways of defining named parameters. The global definition, that will assign that regular expression or wildcard to all parameters with that name in all the routes defined in the application, and the local definition, that will only affect the route in which it's defined and will REWRITE any global route with the same name in this route.

#### Global definition

```
Route::pattern('id',        '\d+');
Route::pattern('name',      '(:any)');
```

#### Local definition

```
Route::any('user/{id}',     'user/load')->where('id', '\d+');
```

It's also possible to use arrays in the local definition to assign more than one parameter at the same time.

```
Route::any('user/{id}/{name}',     'user/load')->where(['id' => '\d+', 'name' => '(:any)']);
```

### Optional named routes

There also can be defined optional parameters. This will let Yii2 use the route having or not an URI defined in that position. The definition of optional parameters it's the same as the normal named parameters but adding a question mark `?` in it's definition.
 
```
Route::any('user/{id?}',    'user/load')->where('id', '\d+');
```

This will make Yii2 to accept the routes "user" and "user/12" having being necessary only one line for that.

It's possible to stack different optional parameters in the same route, being also possible to access all the routes which the different permutations will make.

```
Route::any('user/{id?}/{name?}',   'user/load');
```

This will make possible to access to "user/12/john", "user/john", "user/12", "user".

### Getting named parameter values

The route class has also a syntactic sugar method that let access to the parameter values defined in the route.

```
Route::input('id');
```

### Route filters

There is also possible to define Yii2 type or manual filters in the Route class. This coexists at the same time with the normal filtering system of Yii2 that defines the filters in the `behaviors` method of each Controller. It's only an additional option that lets the developer to define filters by route level instead of Controllar and Action level. 

There are two types of filters that the developer can use in the routing system. The Yii2 normal filters and a special kind of route that uses closures defined in the user as filters.

#### Yii2 filters

To assign a filter of this type in a route there is only necessary to add an additional entry in the options parameter called `filter`. This will make that, when the route is called, the system will search for this filter and execute it.

```
Route::any('user/{id}', 'user/load', ['filter' => 'logged_in']);
```

To define the filter, it's necessary to make an array with the basic data of a normal Yii2 filter and give it a name.

```
Route::filter('logged_in', [
		  'class' => \yii\filters\AccessControl::className(),
		  'except' => [ 'user/default/login'],
			'rules' => [
				[
					'actions' => ['logout'],
					'allow' => true,
					'roles' => ['@'],
				],
			],
  ]);
```

#### Manual filters

Additionally  its possible to define special filters make manually by the developer in the form of anonymous functions or closures that let run Yii2 code inside of them.

To assign a filter of this kind in a route it's necessary to add a new additional entry in the options parameter that can be `before` in the case we want the filter to be launched before the Controller's Action is executed or `after` in case we want to launch it after that.

```
Route::any('user/{id}', 'user/load', ['before' => 'check_this']);
```

To define the filter, it's required to make a closure and assign it to the Route class.

```
Route::filter('check_this', function(){
				if (Route::input('id') > 12)
				{
						throw new \yii\web\NotFoundHttpException(\Yii::t('yii', 'Page not found.'));
				}
				else
				{
					return TRUE;
				}
		});
```

#### Multiple filters

```
Route::any('user/{id}', 'user/load', ['before' => ['logged_in', 'check_params']]);
Route::any('user/{id}', 'user/load', ['filter' => 'logged_in|check_params']);
```

#### Pattern based filters

You may also specify that a filter applies to an entire set of routes based on their URI.

```
Route::when('admin\/(.*)', ['filter' =>'logged_in']);
```

In the example above, the `admin` filter would be applied to all routes beginning with `admin/`.

You may also constrain pattern filters by HTTP verbs:

```
Route::when('admin\/(.*)', ['filter' =>'logged_in'], [get]);
```

### Route groups

This lets the developer adding a series of options in a group of routes masively. It's main utility it's to add url prefixes to a group of routes.

```
Route::group(['prefix' => 'admin', 'filter' => 'logged_in'], function(){
     Route::post('update/(:any)', 'user/update');
});
```

### Subdomain routing

Sometimes an application can give support to some subdomains. For that it's possible to define specific routes for that subdomains.

It's only necessary to define the parameter that will hold the subdomain and the Route class will make the heavy lifting.

```
Route::any('user/{id}', 'user/load', ['domain' => '{id}']);
```

## Database Routing

As an addition, now it's possible to add routes into a database table which will be loaded before the routing phase. If there is a cache system defined, the query will be cached indefinitelly with a tag dependency that can be reseted calling the "resetDBCache" Module method.

By default the database routing will be deactivated. To use it there should be defined the "activate_database_routes" module parameter as true.

The values in the table should be stored like:

* type (string) (obligatory)
> The route type (any, post, get...)
    
* uri (string) (obligatory)
> The uri that will be listened to make the route.

* route (string) (obligatory)
> The controller and method that will be loaded in the system.

* config (array) (optional)
> The special configurations defined for this routes.
		
* app (string) (obligatory) 
> The active app in this moment in the system (app-frontend, app-backend, app-basic).


```
type	uri	        route	    config	app
post	user/{id}	user/load	NULL	app-frontend
```

