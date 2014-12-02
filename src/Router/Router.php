<?php
/**
 * Created by PhpStorm.
 * User: adrienbrussolo
 * Date: 01/12/2014
 * Time: 12:27
 */

namespace Router;

use Symfony\Component\Yaml\Exception\RuntimeException;

class Router implements \Countable {

    private $routes = [];

    public function count(){

        return count($this->routes);

    }

    public function addRoute(array $route){

        foreach($route as $nameroute => $info){

            if ( isset($this->routes[$nameroute]) ){

                throw new \RuntimeException(sprintf('Cannot override route "%s".', $nameroute));

            }
            $this->routes[$nameroute] = $info;

        }



    }

    public function getRoute($url){

//        if( !empty($this->routes[$url]) ){
//
//            return $this->routes[$url];
//
//        }



            $routing = []; // array de sortie qui doit être comparé

            foreach( $this->routes as $route ){

                if (preg_match('/^'.$route['pattern'].'$/', $url, $matches)){

                    list($controller, $action ) = explode(':', $route['connect']);

                    // crée deux variables avec le contenu de l'explode

                    $routing = [
                        'controller'    => $controller,
                        'action'        => $action,
                        'params'        => $this->getParams($route, $matches)
                    ];

                    return $routing;
                }


            }

            throw new \RuntimeException('pas de route correspondante');
    }


    public function getParams($route, $matches){

        if( empty($route['params']) ){

            return null;

        }else{

            $values = [];

            foreach( explode(',', $route['params'] ) as $p){

                $p = trim($p);
                $values[$p] = $matches[$p];  // $values['ip'] = 1

            }

            return $values;

            // on explode $route['params'] pour voir tous les parametres

        }



    }


} 