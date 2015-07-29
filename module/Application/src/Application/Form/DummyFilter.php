<?php

namespace Application\Form;

use Zend\InputFilter\InputFilter;

class DummyFilter extends InputFilter
{
    public function __construct()
    {
        $this->add(array(
            'name' => 'artikel',
            'required' => false,
            'filters' => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),),
        ));

        $this->add(array(
            'name' => 'anzahl',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'inclusive' => true,
                        'min' => 1, 'max' => 10)))));

        $this->add(array(
            'name' => 'preis',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'between',
                    'options' => array(
                        'inclusive' => true,
                        'min' => 1, 'max' => 1000)))));

    }

}