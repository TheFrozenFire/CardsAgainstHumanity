<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace EdpCardsClient;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig() {
    	return array(
    		'factories' => array(
	    		'edpcardsclient_gamemapper' => function($sm) {
	    			$mapper = new \EdpCardsClient\Mapper\Game;
	    			
	    			$api = $sm->get('edpcardsclient_httpclient');
	    			$api->setUri($api->getUri()."/games");
	    			
	    			$mapper->setHttpClient($api);
	    			
	    			return $mapper;
	    		},
	    		'edpcardsclient_playermapper' => function($sm) {
	    			$mapper = new \EdpCardsClient\Mapper\Player;
	    			
	    			$api = $sm->get('edpcardsclient_httpclient');
	    			$api->setUri($api->getUri()."/players");
	    			
	    			$mapper->setHttpClient($api);
	    			
	    			return $mapper;
	    		},
	    		'edpcardsclient_httpclient' => function($sm) {
	    			$config = $sm->get('config');
	    			$url = isset($config["edpcardsclient_service_url"])?$config["edpcardsclient_service_url"]:null;
	    			return new \Zend\Http\Client($url);
	    		}
	    	),
	    	'shared' => array(
	    		'edpcardsclient_httpclient' => false
	    	)
    	);
    }
}
