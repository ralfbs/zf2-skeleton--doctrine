<?php

namespace Application\Controller;

use Application\Entity\Dummy;
use Zend\Mvc\Controller\AbstractActionController;

class DummyController extends AbstractActionController
{

    public function indexAction()
    {

    }

    public function jsonAction() {
        $view = new JsonModel();
        $view->setVariable('foo',array('bar', 'bar1'));
        return $view;
    }

    public function editAction()
    {
        $kunde = new Dummy();
        $kunde->setClient("Demokunde");
        $kunde->setAdresse('Köln');

        $entityManager = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $entityManager->persist($kunde);

        $entityManager->flush();
    }

    public function deleteAction()
    {

    }

}