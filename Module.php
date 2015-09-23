<?php

namespace cyneek\yii2\routes;

use cyneek\yii2\routes\components\Route;
use cyneek\yii2\routes\models\Route as RouteDb;
use DirectoryIterator;
use Yii;
use yii\caching\TagDependency;


class Module extends \yii\base\Module
{

    /**
     * Defines the database tableName
     */
    public static $tableName = 'site_routes';
    /**
     * @var bool
     */
    public $enablePretttyUrl = TRUE;
    /**
     * @var bool
     */
    public $enableStrictParsing = TRUE;
    /**
     * @var bool
     */
    public $showScriptName = FALSE;
    /**
     * String array that will hold all the directories in which we will have
     * the routes files
     *
     * @var string[]
     */
    public $routes_dir = [];
    /**
     * Defines if the routing system is active or not. It's useful for testing purposes
     *
     * @var bool
     */
    public $active = TRUE;
    /**
     * Defines if the routing system will use a database table to hold some of its routes
     *
     * @var bool
     */
    public $activate_database_routes = FALSE;

    /**
     * @inheritdoc
     *
     * @throws \Exception
     */
    public function init()
    {
        parent::init();

        if ($this->active === TRUE)
        {
            // basic urlManager configuration
            $this->initUrlManager();

            // load urls into urlManager
            $this->loadUrlRoutes($this->routes_dir);

            $this->loadDBRoutes();

            // get the route data (filters, routes, etc...)
            $routeData = Route::map();

            // add the routes
            foreach ($routeData as $from => $data)
            {
                Yii::$app->urlManager->addRules([$from => $data['to']]);
                $routeData[$from]['route'] = end(Yii::$app->urlManager->rules);
            }

            // only attaches the behavior of the active route
            foreach ($routeData as $from => $data)
            {
                if ($data['route']->parseRequest(Yii::$app->urlManager, Yii::$app->getRequest()) !== FALSE)
                {
                    foreach ($data['filters'] as $filter_name => $filter_data)
                    {
                        Yii::$app->attachBehavior($filter_name, $filter_data);
                    }
                }
            }

            // relaunch init with the new data
            Yii::$app->urlManager->init();
        }
    }

    /**
     * Initializes basic config for urlManager for using Yii2 as Laravel routes
     *
     * This method will set manually
     */
    function initUrlManager()
    {
        // custom initialization code goes here
        // routes should be always pretty url and strict parsed, any
        // url out of the route files will be treated as a 404 error.
        Yii::$app->urlManager->enablePrettyUrl = $this->enablePretttyUrl;
        Yii::$app->urlManager->enableStrictParsing = $this->enableStrictParsing;
        Yii::$app->urlManager->showScriptName = $this->showScriptName;
    }

    /**
     * Initializes basic config for urlManager for using Yii2 as Laravel routes
     *
     * This method will call [[buildRules()]] to parse the given rule declarations and then append or insert
     * them to the existing [[rules]].
     *
     * @param string[] $routesDir
     * @throws \Exception
     */
    function loadUrlRoutes($routesDir)
    {
        if (!is_array($routesDir))
        {
            $routesDir = [$routesDir];
        }

        foreach ($routesDir as $dir)
        {
            if (!is_string($dir))
            {
                continue;
            }

            $dir = Yii::getAlias($dir);

            if (is_dir($dir))
            {
                /** @var \DirectoryIterator $fileInfo */
                foreach (new DirectoryIterator($dir) as $fileInfo)
                {

                    if ($fileInfo->isDot())
                    {
                        continue;
                    }

                    if ($fileInfo->isFile() && $fileInfo->isReadable())
                    {
                        // loads the file and executes the Route:: calls
                        include_once($fileInfo->getPathName());
                    }
                }
            }
            else
            {
                throw new \Exception($dir . ' it\'s not a valid directory.');
            }
        }
    }

    /**
     * Load routes from Database if the $activate_database_routes parameter is true
     *
     * @throws \Exception
     */
    function loadDBRoutes()
    {
        if ($this->activate_database_routes === TRUE)
        {

            $dependency = new TagDependency(array('tags' => [self::className()]));

            $route_list = RouteDb::getDb()->cache(function ($db)
            {
                return RouteDb::find()->where(['app' => Yii::$app->id])
                    ->all();
            }, 0, $dependency);

            foreach ($route_list as $route)
            {
                $options = json_decode($route['config'], TRUE);

                if (is_null($options))
                {
                    $options = [];
                }

                Route::$route['type']($route['uri'], $route['route'], $options);
            }

        }
    }

    /**
     * Resets the cache of the route retrieval query only in case it's activated.
     *
     */
    function resetDBCache()
    {
        if (!is_null(Yii::$app->cache))
        {
            TagDependency::invalidate(Yii::$app->cache, [self::className()]);
        }
    }
}
