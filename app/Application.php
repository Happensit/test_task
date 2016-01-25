<?php

namespace Task;

use Pimple\Container;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\Common\Cache\RedisCache;

class Application extends Container
{

    /**
     * Application constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $app = $this;

        $this['routes'] = function () {
            return new RouteCollection();
        };
        $this['request_context'] = function () {
            return new RequestContext();
        };

        $this['db'] = function () use ($app) {
            $connectionParams = isset($app['db.connectionParams']) ? $app['db.connectionParams'] : array();
            return \Doctrine\DBAL\DriverManager::getConnection($connectionParams);
        };

//        $this['cache'] = function () use ($app) {
//            $redis = new \Redis() or die("Cannot load Redis module.");
//            $redis->connect("127.0.0.1", 6379);
//            $cache = new RedisCache();
//            $cache->setRedis($redis);
//            $cache->setNamespace('task_');
//
//            return $cache;
//        };
    }

    /**
     * Run Application.
     */
    public function run()
    {
        $request = $this['request'] = Request::createFromGlobals();
        $routes = $this['routes'];
        $context = $this['request_context'];
        $context->fromRequest($this['request']);
        $matcher = new UrlMatcher($routes, $context);
        $resolver = new ControllerResolver();

        try {
            $request->attributes->add($matcher->match($request->getPathInfo()));
            $controller = $resolver->getController($request);
            $arguments = $resolver->getArguments($request, $controller);
            if (is_array($controller) && ($controller[0] instanceof \Task\Controllers\BaseController)) {
                $controller[0]->setRequest($request);
                $controller[0]->setApplication($this);
            }
            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $e) {
            $response = new JsonResponse(["errors" => ["type" => "Not found"]], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            $response = new JsonResponse(["errors" => ["type" => $e->getMessage(), "stacktrace" => $e->getTraceAsString()]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set(
          "Access-Control-Allow-Methods",
          "GET,POST,PUT,DELETE,OPTIONS"
        );
        $response->headers->set("Access-Control-Allow-Headers", "Content-Type");
        $response->headers->set("Server", "Test task REST");

        return $response;
    }

    /**
     * @param $pattern
     * @param array $defaults
     */
    public function get($pattern, array $defaults)
    {
        $this->addRoute($pattern.'_GET', $pattern, $defaults, 'GET');
    }

    /**
     * @param $pattern
     * @param array $defaults
     */
    public function post($pattern, array $defaults)
    {
        $this->addRoute($pattern.'_POST', $pattern, $defaults, 'POST');
    }

    /**
     * @param $pattern
     * @param array $defaults
     */
    public function path($pattern, array $defaults)
    {
        $this->addRoute($pattern.'_PATCH', $pattern, $defaults, 'PATCH');
    }

    /**
     * @param $pattern
     * @param array $defaults
     */
    public function delete($pattern, array $defaults)
    {
        $this->addRoute($pattern.'_DELETE', $pattern, $defaults, 'DELETE');
    }

    /**
     * @param $name
     * @param $pattern
     * @param array $defaults
     * @param string $method
     */
    public function addRoute($name, $pattern, array $defaults, $method = 'GET')
    {
        $this['routes']->add($name, new Route($pattern, $defaults, [], [], '', [], [$method]));
    }

    /**
     * @param $url
     * @param int $status
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

}