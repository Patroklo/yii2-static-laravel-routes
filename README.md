# Yii2 Laravel-like routes


## ¿Qué es Laravel-like routes?


Este módulo modifica el sistema de definición de rutas de Yii2 para que, en lugar de necesitar ser definidas desde el archivo de configuración de la aplicación se pueda crear una serie de ficheros que contengan las rutas que el usuario quiera definir para su página web en las que se realizarán llamadas a una clase que gestione y almacene éstas hasta el momento de ejecución de la aplicación. Esta clase permite la llamada a una serie de métodos que permiten su definición y diseño de forma más intuitiva que lo que permite el sistema básico de Yii2 inspirándose en el sistema de enrutamiento definido por Laravel.


Escrito por Joseba Juániz (@Patroklo)

## Requisitos

Yii2

Php 5.4 or above

## Licencia

Esto es software libre. Está liberador bajo los términos de la siguiente licencia BSD

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

Comming soon...

## Definición

### Enrutamiento básico

Los métodos básicos se encargan de enrutar cada url basándose en los métodos de petición que hayamos usado para su definición como métodos en la clase de enrutamiento.

```

Route::get('user',          'user/index');
Route::post('user/(:any)',  'user/load/$1');
Route::put('user/(:any),    'user/update/$1');
Route::delete('user/(:any)','user/delete/$1');
Route::head('user',         'user/index');
Route::patch('user/(:any),  'user/update/$1');
Route::options('user/(:any),'user/load/$1');

```





Routing and filtering extension system for yii2 framework that emulates the laravel routing system


Procrastination level -> I'll make the manual later.
