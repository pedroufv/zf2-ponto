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
        
        $this->setAttribute('role', 'form');
		
        // add filtro para os elemento
        $this->setInputFilter($inputFilter->getinputFilter());
         
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden'
        ));
        
        $this->add(array(
            'name' 		 => 'artist',
        	'type'  	 => 'text',
            'attributes' => array(
            	'class' 		=> 'form-control',
            	'placeholder' 	=> 'Digite o nome do artista'
            ),
            'options' 	 => array(
                'label' => 'Artist'
            )
        ));
        
        $this->add(array(
            'name' 		 => 'title',
        	'type' 		 => 'text',
        	'attributes' => array(
            	'class' 		=> 'form-control',
            	'placeholder' 	=> 'Digite titulo do álbum'
            ),
            'options'	 => array(
                'label' => 'Title'
            )
        ));
        
        $this->add(array(
            'name' 		 => 'submit',
        	'type'  	 => 'submit',
            'attributes' => array(                
                'value' => 'Enviar',
            	'class' => 'btn btn-success'
            ),
        ));
    }
}