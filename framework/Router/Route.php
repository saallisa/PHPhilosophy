<?php

namespace Phphilosophy\Router;

use Phphilosophy\Application\Config;

/**
 * Phphilosophy route entity
 *
 * @author      Lisa Saalfrank <lisa.saalfrank@web.de>
 * @copyright   2015-2016 Lisa Saalfrank
 * @license	    http://opensource.org/licenses/MIT MIT License
 * @since       0.1.0
 * @version     0.1.0
 * @package     Phphilosophy
 * @subpackage  Router
 */
class Route {
    
    /**
     * @access  private
     * @var     string  The route pattern
     */
    private $pattern;
    
    /**
     * @access  private
     * @var     callable
     */
    private $action;
    
    /**
     * @access  private
     * @var     array   The http method
     */
    private $methods = [];

    /**
     * @access  private
     * @var     \Phphilosophy\Application\Config    App config
     */
    private $config;
    
    /**
     * @access  public
     * @param   string          $pattern    The route pattern
     * @param   mixed           $action     The route action 
     * @param   array|string    $methods    The route methods
     */
    public function __construct($pattern, $action, $methods)
    {
        $this->config = Config::getInstance();
        $this->setPattern($pattern);
        $this->setAction($action);
        $this->setMethods($methods);
    }
    
    /**
     * @access  public
     * @param   string  $pattern    The route pattern
     * @return  void
     */
    public function setPattern($pattern) {
        $this->pattern = '/' . trim($pattern, '/');
    }
    
    /**
     * @access  public
     * @return  string  The route pattern
     */
    public function getPattern() {
        return $this->pattern;
    }
    
    /**
     * @access  public
     * @param   mixed   $action     The route action
     * @return  void
     */
    protected function setAction($action)
    {
        // If the route action is a callable, just save it
        if (is_callable($action)) {
            $this->action = $action;
        }
        
        // If the route action is an array (Controller), create new callable
        if (is_array($action)) {
            $this->action = $this->createCallable($action[0], $action[1]);
        }
        
        // If the string notation was used
        if ($this->isController($action) !== false) {
            $array = explode('@', $action);
            $this->action = $this->createCallable($array[0], $array[1]);
        }
    }
    
    /**
     * @access  public
     * @param   string      $controller     The route controller
     * @param   string      $method         The route action
     * @return  callable    The route action
     */
    private function createCallable($controller, $method)
    {
        // The namespace to be used with controllers
        $namespace = '\\' . $this->config->get('app.name') . '\\Controller\\';
        $controller = $namespace . $controller;
        
        // Create new anonymous function which calls controller -> method
        $callable = function() use ($controller, $method) {
            $class = new $controller;
            return call_user_func_array([$class, $method], func_get_args());
        };
        return $callable;
    }
    
    /**
     * @access  private
     * @param   string      $controller     The string to be checked
     * @return  boolean     Whether the string is a valid Controller notation
     */
    private function isController($controller) {
        if (is_string($controller)) {
            return preg_match('#^[A-Za-z]+[@][A-Za-z]+$#', $controller);
        }
        return false;
    }
    
    /**
     * @access  public
     * @return  callable    The route action
     */
    public function getAction() {
        return $this->action;
    }
    
    /**
     * @access  public
     * @param   string|array    $methods    The route method(s)
     * @return  void
     */
    protected function setMethods($methods)
    {
        if (is_array($methods)) {
            $this->methods = $methods;
        } else {
            $this->methods[] = $methods;
        }
    }
    
    /**
     * @access  public
     * @return  array   The route methods
     */
    public function getMethods() {
        return $this->methods;
    }
}