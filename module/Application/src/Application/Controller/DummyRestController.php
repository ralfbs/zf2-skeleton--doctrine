<?php



namespace Application\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Zend\Authentication\Storage\Session;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;


/**
 * Class DummyRestController
 *
 * @package Application\Controller
 */
class DummyRestController extends AbstractRestfulController
{


    public function getList()
    {
        # code...
        return null;
    }

    public function get($id)
    {
        # code...
    }

    public function create($data)
    {
        # code...
    }

    public function update($id, $data)
    {
        # code...
    }

    public function delete($id)
    {
        # code...
    }


}