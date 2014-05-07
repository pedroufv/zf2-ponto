<?php
namespace Album\Form;

use Zend\Form\Form;
use Album\Form\Filter\AlbumFilter;

class AlbumForm extends Form
{
    public function __construct()
    {
		parent::__construct('album');
        
        $inputFilter = new AlbumFilter();
        
        $this->setAttribute('method', 'post');
        $this->setInputFilter($inputFilter->getinputFilter());
         
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'artist',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Artist',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Title',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Enviar',
                'id' => 'submitbutton',
            ),
        ));
    }
}