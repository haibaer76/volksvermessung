<?php
 
/***
 * Name:       library.uri.php
 * About:      a URI library for TinyMVC
 * Copyright:  (C) Monte Ohrt, All rights reserved.
 * Author:     Monte Ohrt, monte [at] ohrt [dot] com
 * Credits:    pablo77
 
 example usage: 
 
 $this->load->library('uri');
 // gets third segment from URI
 $this->uri->segment(3);
 // get key/val associative array starting with the third segment
 $uri = $this->uri->uri_to_assoc(3);
 // assign params to an indexed array, starting with third segment
 $uri = $this->uri->uri_to_array(3);
 
 ***/
 
 
 
class URI {
 
	var $path = null;
	var $controller_name = null;
 
  function __construct()
	{
    if (isset($_SERVER['ORIG_PATH_INFO'])){
      $path_info = $_SERVER['ORIG_PATH_INFO']; 
    }elseif (isset($_SERVER['PATH_INFO'])){
      $path_info = $_SERVER['PATH_INFO'];
    }else{
      $path_info = null; 
    }
    if(!empty($path_info)) {
			$this->path = explode('/',$path_info);
			$this->controller_name = $this->path[1];
      $this->path = array_slice($this->path,2);
    }
  }
 
  function segment($index)
	{
    if(!empty($this->path[$index-1]))
      return $this->path[$index-1];
    else 
      return false;
  }
 
  function uri_to_assoc($index)
  {
    $assoc = array();
    for($x = count($this->path), $y=$index-1; $y<$x; $y+=2)
    {
      $assoc_idx = $this->path[$y];
      $assoc[$assoc_idx] = isset($this->path[$y+1]) ? $this->path[$y+1] : null;
    }
    return $assoc;
  }
 
  function uri_to_array($index=0)
  {
    if(is_array($this->path))
      return array_slice($this->path,$index);
    else
      return false;
  }
 
 
}
 
?>
