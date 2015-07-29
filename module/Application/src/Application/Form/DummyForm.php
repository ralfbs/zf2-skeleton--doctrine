<?php

namespace Application\Form;


use Zend\Form\Element\Text;
use Zend\Form\Form;


class DummyForm extends Form
{

    public function __construct()
    {
        parent::__construct();

        $eingabe = new Text('artikel');
        $eingabe->setLabel("Artikel:");
        $this->add($eingabe);

        $this->add(array(
            'name' => 'anzahl',
            'type' => 'Text',
            'options' => array('label' => 'Anzahl')));

        $this->add(array(
            'name' => 'preis',
            'type' => 'Text',
            'options' => array('label' => 'Preis'),
            'attributes' => array('data-rel' => 'preis',
                'style' => 'border: 1px solid red')));
    }
}