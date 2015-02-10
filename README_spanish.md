# Yii2 Laravel-like routes

Extensión de enrutamiento y filtros para el framework Yii2 que emula el sistema de rutas de Laravel.

## ¿Qué es Laravel-like routes?


Este módulo modifica el sistema de definición de rutas de Yii2 para que, en lugar de necesitar ser definidas desde el archivo de configuración de la aplicación se pueda crear una serie de ficheros que contengan las rutas que el usuario quiera definir para su página web en las que se realizarán llamadas a una clase que gestione y almacene éstas hasta el momento de ejecución de la aplicación. Esta clase hace posible la llamada a una serie de métodos que permiten su definición y diseño de forma más intuitiva que lo que permite el sistema básico de Yii2 inspirándose en el sistema de enrutamiento definido por Laravel.


Desarrollado por Joseba Juániz ([@Patroklo](http://twitter.com/Patroklo))

[English Readme version](https://github.com/Patroklo/yii2-static-laravel-routes/blob/master/README.md)

## Requisitos

* Yii2
* Php 5.4 o superior

## Planes de futuro

* Pasar parámetros manuales a los filtros.
* Crear sistema automático de hacer rutas RESTFul.


## Licencia

Esto es software libre. Está liberado bajo los términos de la siguiente licencia BSD

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


## Instalación

* Instalar [Yii 2](http://www.yiiframework.com/download)
* Instalar el paquete via [composer](http://getcomposer.org/download/) `"cyneek/yii2-routes": "dev-master"`
* Modificar el fichero de configuración _'config/web.php'_

```
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
* Hacer una carpeta _routes_ en el nivel de @app.
* Insertar fichero en la carpeta _route_ usando la definición de la clase Route.
* Profit!

## Desactivando el módulo

Es posible desactivar el módulo de enrutamiento símplemente añadiendo a la definición del array "routes" en el fichero config el valor "active" = FALSE.

```
'modules' => [
		'routes' => [
            'class' => 'cyneek\yii2\routes\Module',
			'active' => FALSE
		]
		// set custom modules here
    ],
```

## Definición

Nota:

Este sistema de enrutamiento usa los siguiente parámetros de la clase urlManager de Yii2 por defecto:

* enablePretttyUrl = TRUE

* enableStrictParsing = TRUE

* showScriptName = FALSE

### Enrutamiento básico

Los métodos básicos se encargan de enrutar cada url basándose en los métodos de petición que hayamos usado para su definición como métodos en la clase de enrutamiento.

```
Route::get('user',            'user/index');
Route::post('user/(:any)',    'user/load/$1');
Route::put('user/(:any),      'user/update/$1');
Route::delete('user/(:any)',  'user/delete/$1');
Route::head('user',           'user/index');
Route::patch('user/(:any),    'user/update/$1');
```

El desarrollador también puede utilizar dos métodos adicionales que le permitiran trabajar con varios métodos de petición http.

`any` permitirá que la ruta funcione bajo cualquier método HTTP (GET, POST, PUT, DELETE, HEAD, PATCH).

```
Route::any('user',            'user/index');
```

`match` permite al desarrollador definir manualmente una serie de métodos HTTP que serán los que Yii2 escuche para lanzar esta ruta.

```
Route::match(['GET', 'POST'], 'user', 'user/index');
```

### Rutas con nombre

Adicionalmente se pueden definir una serie de propiedades adicionales a los métodos usados en el enrutamiento básico.

Uno de ellos es el nominar rutas de forma que se pueda trabajar en el futuro con sus direcciones web usando tan sólo el nombre que se les ha otorgado.

```
Route::set_name('user_update', 'admin/user/load/');

Route::get('user', 'user/index', ['as' => 'user']);
```

Para obtener la url de una ruta por su nombre tan sólo habrá que hacer:

```
echo Route::named('user');

redirect(Route::named('user'));
```

En el caso de que se trate de una ruta que contenga parámetros nominados, se podrá definir valores para éstos en un segundo parámetro en el método `named`.

```
Route::named('user', ['id' => 12]);
```

Si la ruta tiene parámetros opcionales nominados a los que no se le ha definido un valor, éstos no aparecerán en la url de retorno del método.


### Parametros de rutas

Se pueden definir parámetros de rutas con nombres propios en lugar de utilizar las expresiones regulares de Yii2. Esto permitirá al desarrollador el usarlas también en la obtención de los valores que tienen estos parámetros.

Se han definido adicionalmente las wildcards `(:any)` que corresponde a una expresión regular con cualquier tipo de carácter y `(:num)` que obliga a que el parámetro sólo pueda contener números.

```
Route::any('user/{id}',     'user/load');
```

Hay dos formas de definir parámetros. La definición global, que asignará la expresión regular o el wildcard a todos los parámetros con ese nombre en todas las rutas que se definan en la aplicación, y la definición local, que tan sólo afectará a la ruta en la que es definida y SOBREESCRIBIRÁ cualquier ruta global que coincida con ese nombre de parámetro en esa ruta.

#### Definición global

```
Route::pattern('id',        '\d+');
Route::pattern('name',      '(:any)');
```

#### Definición local

```
Route::any('user/{id}',     'user/load')->where('id', '\d+');
```

También se pueden utilizar arrays en la definición local.

```
Route::any('user/{id}/{name}',     'user/load')->where(['id' => '\d+', 'name' => '(:any)']);
```

### Parámetros de rutas opcionales

También se pueden definir parámetros opcionales. Esto permitirá a Yii2 usar la ruta habiendo o no una URI definido en esa posición. La definición de parámetros opcionales es igual que la de parámetros normales pero añadiendo un cierre de interrogación `?` a su definición.


```
Route::any('user/{id?}',    'user/load')->where('id', '\d+');
```

Esto hará que Yii2 acepte la rutas "user" y "user/12" habiendose necesitado tan sólo una línea para ello.

Se pueden apilar diferentes parámetros opcionales en la misma ruta, siendo posible siempre acceder a todas rutas que las permutaciones posibles generen.

```
Route::any('user/{id?}/{name?}/{telephone?}',   'user/load');
```

### Accediendo a valores de parámetros

Se ha añadido una función de azúcar sintáctico a la clase de Route que retorna el valor de ésta.

```
Route::input('id');
```


### Filtros de rutas

Se pueden también definir filtros de tipo Yii2 o manuales en el sistema de rutas. Esto coexiste a la vez con la posibilidad de definirlos en el método `behaviors` de cada Controller. Se trata de una posibilidad adicional que permite el tener los filtros declarados en un mismo sitio y hacerlo en múltiples controllers a la vez, ya que, en lugar de funcionar como en Yii2, que los asigna a los Contollers y sus Actions, los filtros definidos a una ruta funcionan tan sólo con ella, separando así la relación Filtro - Controller.
 

Existe la posibilidad de utilizar 2 tipos de filtros en el módulo, los normales que utiliza Yii2 en sus aplicaciones, y uno específico que permite la introducción de funciones anónimas dentro de las cuales se ejecutará código definido por el usuario.

#### Filtros tipo Yii2

Para asignar un filtro de este tipo a una ruta hay que añadirle una entrada adicional al parámetro de opciones llamada `filter`. Esto hará que cuando la ruta se ejecute, busque el filtro y lo lance.

```
Route::any('user/{id}', 'user/load', ['filter' => 'logged_in']);
```

Para definir el filtro, hay que crear un array con los datos básicos de un filtro de Yii2 y asignarle un nombre.

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

#### Filtros manuales

Adicionalmente se pueden definir filtros especiales creados manualmente por el desarrollador en la forma de funcion anónima o closure que permite ejecutar código de Yii2 dentro de ellas.
 
Para asignar un filtro de este tipo en una ruta hay que añadir una entrada adicional al parámetro de opciones que puede ser `before` en caso de que se quiera que el filtro se ejecute antes del Action del Controler o `after` en caso de que eramos que se ejecute posteriormente.


```
Route::any('user/{id}', 'user/load', ['before' => 'check_this']);
```

Para definir el filtro, hay que crear una closure y asignársela a la clase Route.

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

#### Múltiples filtros

```
Route::any('user/{id}', 'user/load', ['before' => ['logged_in', 'check_params']]);
Route::any('user/{id}', 'user/load', ['filter' => 'logged_in|check_params']);
```

#### Filtros basados en patrones

También se puede especificar que un filtro se aplique a un grupo entero de rutas basadas en su URI.

```
Route::when('admin\/(.*)', ['filter' =>'logged_in']);
```

En el ejemplo superior, el filtro se aplicaría a todas las rutas que comiencen por `admin/`.

También se pueden aplicar verbos HTTP a este tipo de patrones.

```
Route::when('admin\/(.*)', ['filter' =>'logged_in'], [get]);
```


### Grupos de rutas

Permite al desarrollador añadir una serie de opciones a un grupo de rutas de forma masiva. Su principal utilidad puede ser la de añadir prefijos a éstas.

```
Route::group(['prefix' => 'admin', 'filter' => 'logged_in'], function(){
     Route::post('update/(:any)', 'user/update');
});
```


### Enrutamiento de subdominios

A veces una aplicación puede dar soporte a varios subdominios. Para ello es posible definir rutas específicas para los subdominios 

Tan sólo será necesario definir el parámetro que tendrá el subdominio y la clase de ruta se encargará de todo lo demás.

```
Route::any('user/{id}', 'user/load', ['domain' => '{id}']);
```
