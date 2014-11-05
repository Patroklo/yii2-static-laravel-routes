<?php

namespace cyneek\yii2\routes;

use Yii;
use DirectoryIterator;
use cyneek\yii2\routes\components\Route;


class Module extends \yii\base\Module
{
	public $controllerNamespace = 'vendor\cyneek\yii2\routes\controllers';

	/**
	 * @var bool
	 */
	public $enablePretttyUrl		= TRUE;

	/**
	 * @var bool
	 */
	public $enableStrictParsing		= TRUE;

	/**
	 * @var bool
	 */
	public $showScriptName			= FALSE;

	/**
	 * String array that will hold all the directories in which we will have
	 * the routes files
	 *
	 * @var string[]
	 */
	public $routes_dir = [];


	public function init()
	{

		parent::init();

		// basic urlManager configuration
		$this->initUrlManager();

		// load urls into urlManager
		$this->loadUrlRoutes($this->routes_dir);
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
		Yii::$app->urlManager->enablePrettyUrl 		= $this->enablePretttyUrl;
		Yii::$app->urlManager->enableStrictParsing 	= $this->enableStrictParsing;
		Yii::$app->urlManager->showScriptName 		= $this->showScriptName;
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
		if ( ! is_array($routesDir))
		{
			$routesDir = [$routesDir];
		}

		foreach ($routesDir as $dir)
		{
			if (!is_string($dir))
			{
				continue;
			}

			if (is_dir($dir))
			{
				/** @var \DirectoryIterator $fileInfo */
				foreach (new DirectoryIterator($dir) as $fileInfo)
				{

					if ($fileInfo->isDot()) continue;

					if ($fileInfo->isFile() && $fileInfo->isReadable())
					{
						// loads the file and executes the Route:: calls
						include_once($fileInfo->getPathName());
					}
				}
			}
			else
			{
				throw new \Exception($dir.' it\'s not a valid directory.');
			}
		}
	}
}
