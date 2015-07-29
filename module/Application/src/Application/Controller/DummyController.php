<?php

namespace Application\Controller;

use Application\Entity\Dummy;
use Zend\Mvc\Controller\AbstractActionController;

class DummyController extends AbstractActionController
{

    public function indexAction()
    {

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


    public function pdfAction()
    {
        // disable DOMPDF's internal autoloader if you are using Composer
        define('DOMPDF_ENABLE_AUTOLOAD', false);
        define('DOMPDF_UNICODE_ENABLED', true);
        define("DOMPDF_DEFAULT_PAPER_SIZE", "a4");

        // include DOMPDF's default configuration
        require_once 'vendor/dompdf/dompdf/dompdf_config.inc.php';
        $dompdf = new \DOMPDF();


        $view = new ViewModel();
        $view->setTemplate('application/dummy/index');
        $view->setVariable('foo', 'bar');

        $html = $this->getServiceLocator()->get('viewrenderer')->render($view);

        $dompdf->load_html($html);
        $dompdf->render();
        $dompdf->stream('foo.pdf', array('Attachment' => false));
    }

}