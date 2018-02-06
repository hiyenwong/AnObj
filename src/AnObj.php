<?php
  /**
  * Array convert to a Anonymous Object
  */

  namespace hiyen\AnObj;

  class AnObj
  {
    protected $methods = array();
    protected $properties = array();

  	public function __construct(array $options)
  	{

  		foreach($options as $key => $opt) {
  			//integer, string, float, boolean, array
  			if(is_array($opt) || is_scalar($opt)) {
  				$this->properties[$key] = $opt;
  				unset($options[$key]);
  			}
  		}

  		$this->methods = $options;
  		 foreach($this->properties as $k => $value) {

         $this->{$k} = $value;

         if (is_array($value))
           $this->{$k} = new self($value);

       }

  	}

  	public function __call($name, $arguments)
  	{
  		$callable = null;
  		if (array_key_exists($name, $this->methods))
  			$callable = $this->methods[$name];
  		elseif(isset($this->$name))
  			$callable = $this->$name;

  		if (!is_callable($callable))
  			throw new BadMethodCallException("Method {$name} does not exists");

  		return call_user_func_array($callable, $arguments);
  	}
  }
