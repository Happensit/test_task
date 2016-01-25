<?php

namespace Task\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 */
abstract class BaseController {

    private $request;
    private $app;
    private $templateEngine;

    /**
     * BaseController constructor.
     */
    public function __construct() {
        $twigLoader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
        $twig = new \Twig_Environment($twigLoader, array('charset' => "utf-8"));
        $this->templateEngine = $twig;
    }

    /**
     * @param $app
     */
    public function setApplication($app) {
        $this->app = $app;
    }

    /**
     * @return mixed
     */
    public function getApplication() {
        return $this->app;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * @return mixed
     */
    public function getDb() {

        return $this->app['db'];
    }

//    public function getCache() {
//        return $this->app['cache'];
//    }

    /**
     * @param $serviceName
     * @return mixed
     */
    public function getService($serviceName) {
        return $this->app[$serviceName];
    }

    /**
     * @param $fileName
     * @param array $arguments
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($fileName, array $arguments = []) {
        return new Response($this->templateEngine->render($fileName, $arguments + [
            'request' => $this->getService('request'),
          ]));
    }
}