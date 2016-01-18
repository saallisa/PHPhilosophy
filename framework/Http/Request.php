<?php

namespace Phphilosophy\Http;

/**
 * Phphilosophy Request
 *
 * @author      Lisa Saalfrank <lisa.saalfrank@web.de>
 * @copyright   2015-2016 Lisa Saalfrank
 * @license	    http://opensource.org/licenses/MIT MIT License
 * @since       0.1.0
 * @version     0.1.0
 * @package     Phphilosophy
 * @subpackage  Http
 */
class Request {
    
    /**
     * @access  private
     * @var     array   Array with request information
     */
    private $request = [];
    
    /**
     * @access  private
     * @var     array   Array with get/post data
     */
    private $input = [];
    
    /**
     * @access  protected
     * @var     \Phphilosophy\Http\Request  Instance of this class
     */
    protected static $instance;
    
    /**
     * Saves the request data into an array
     * @access  protected
     * @return  void
     */
    protected function setRequest()
    {
        // The HTTP request URI
        $this->request['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
        
        // The HTTP request method
        $this->request['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];
        
        // The query string (with GET data)
        $this->request['QUERY_STRING'] = (isset($_SERVER['QUERY_STRING'])) ? $_SERVER['QUERY_STRING'] : '';
        
        // The PHP Input Stream (with POST data)
        $this->request['INPUT_STREAM'] = (string) file_get_contents('php://input');
    }
    
    /**
     * Saves the request data into an array
     * @access  private
     */
    private function __construct() {
        $this->setRequest();
    }
    
    /**
     * Replacement for the now private constructor
     * @access  public
     * @return  \Phphilosophy\Http\Request  Self
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * @access  public
     * @return  string  The request method
     */
    public function method() {
        return $this->request['REQUEST_METHOD'];
    }
    
    /**
     * Returns the request URI as a string
     * @access  public
     * @return  string  The request uri
     */
    public function uri() {
        return $this->request['REQUEST_URI'];
    }
    
    /**
     * Returns the segments of the request URI
     * @access  public
     * @return  array   The array with URI segments
     */
    public function uriSegments()
    {
        $uri = new Uri($this->request['REQUEST_URI']);
        return $uri->getSegments();
    }
    
    /**
     * @access  private
     * @param   string|null $key        GET|POST-key
     * @param   mixed|null  $default    default value
     * @param   string      $method     method
     * @return  mixed|array
     */
    private function getInput($key = null, $default = null, $method = 'get')
    {
        // If run the first time, parses the query string
        $this->isParsed($method);
        
        // Checks, whether a specific value was requested
        if (isset($key)) {
            
            // Does the requested value exist?
            if (isset($this->input[$method][$key])) {
                
                // Positive: return the value
                return $this->input[$method][$key];  
            } 
            
            // Negative: the default value
            return $default;
        }
            
        // return the entire array
        return $this->input[$method];
    }
    
    /**
     * @access  private
     * @param   string  $method     Method
     * @return  void
     */
    private function isParsed($method)
    {
        // Checks whether get/post data has been parsed and saved.
        // If it wasn't, retrieve it, parse it and save it.
        if (!isset($this->input[$method])) {
            
            // Result array
            $input = [];
            
            // source of user input (Input stream or query String)
            $source = ($method == 'get') ? 'QUERY_STRING' : 'INPUT_STREAM';
            
            // Parse input and save to input array
            parse_str($this->request[$source], $input);
            
            // "Cache" Get|Post data
            $this->input[$method] = $input;
        }
    }
    
    /**
     * @access  public
     * @param   string|null $key        get key
     * @param   mixed|null  $default    default value
     * @return  mixed|array
     */
    public function get($key = null, $default = null) {
        return $this->getInput($key, $default);
    }
    
    /**
     * @access  public
     * @param   string|null $key        get key
     * @param   mixed|null  $default    default value
     * @return  mixed|array
     */
    public function post($key = null, $default = null) {
        return $this->getInput($key, $default, 'post');
    }
}