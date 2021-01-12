<?php

namespace Drupal\custom_module\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Controller for getting the node data in JSON format.
 */
class JsonRepresentationController extends ControllerBase {

  /**
   * Implementation of jsonrepresentation().
   */
  public function jsonrepresentation() {
  	// Get value Site API varibale 
  	$siteapikey_var = \Drupal::config('siteapikey.configuration')->get('siteapikey');
  	// Get current path
  	$current_path = \Drupal::service('path.current')->getPath();
	$path_args = explode('/', $current_path);
	// Get nid from URL
	$nid = $path_args[3];
	// Get apikey from URL
	$apikey = $path_args[2];

	// Node load
	$nodeObj =  Node::load($nid);

	if (($nodeObj->getType() == 'page') && ($siteapikey_var == $apikey) && ($siteapikey_var != 'No API Key yet')) {
	    $json_array = array();
	      $json_array['data'] = array(
	        'title' =>  $nodeObj->getTitle(),
	        'body' =>  $nodeObj->get('body')->value,
	      );

	      // return json data
    	return new JsonResponse($json_array);
	}
	else {
		$json_array['data'] = array(
	        'Message' =>  'access denied',
	      );
    	return new JsonResponse($json_array);
	}
  }
}