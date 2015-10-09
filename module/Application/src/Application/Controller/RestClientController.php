<?php

/**
 * Zippel Media
 *
 * @copyright Copyright (c) 2015 by hr-interactive. All rights reserved.
 * @author    Ralf Schneider
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Response;
use Zend\Http\Request;
use Zend\Http\Client;
use Zend\Stdlib\Parameters;
use Zend\View\Model\JsonModel;

/**
 * Class RestClientController
 *
 * @package Application\Controller
 */
class RestClientController extends AbstractActionController
{

    public function weatherAction()
    {
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));
        $request->setUri('http://api.openweathermap.org/data/2.5/weather');

        // $request->setMethod('POST');
        // $request->setPost(new Parameters(array('q' => 'London,uk')));

        $request->setMethod('GET');
        $request->setQuery(new Parameters(array('q'     => 'Cologne,de',
                                                'units' => 'metric',
                                                'lang'  => 'de'
        )));

        // $client = new Client();
        $client = $this->getServiceLocator()->get('HttpClient');
        $response = $client->dispatch($request);
        $data = json_decode($response->getBody(), true);

        return new JsonModel($data);
    }

    public function pimCoreCountAction()
    {
        //http://www.pimcore.dml/webservice/rest/asset-count?apikey=6e7bb15450a5b974279c631693fedfce76eaefa459f4531e1405ae9e7efb5609
        $request = new Request();
        $request->getHeaders()->addHeaders(array(
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
        ));
        $request->setUri('http://www.pimcore.dml/webservice/rest/asset-count');

        // $request->setMethod('POST');
        // $request->setPost(new Parameters(array('q' => 'London,uk')));

        $request->setMethod('GET');
        $request->setQuery(new Parameters(array('apikey'     => '6e7bb15450a5b974279c631693fedfce76eaefa459f4531e1405ae9e7efb5609')));

        // $client = new Client();
        $client = $this->getServiceLocator()->get('HttpClient');
        $response = $client->dispatch($request);
        $data = json_decode($response->getBody(), true);

        return new JsonModel($data);
    }
}
