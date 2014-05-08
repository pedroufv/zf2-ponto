<?php
namespace Album\Form\Filter;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class AlbumFilter implements InputFilterAwareInterface
{
	protected $inputFilter;

	public function setInputFilter(InputFilterInterface $inputFilter)
	{
		throw new \Exception("Not used");
	}

	/**
     * Configura os filtros do formulario album
     *
     * @return Zend\inputFilter\inputFilter
     */
    public function getinputFilter()
    {
    	if (!$this->inputFilter) {
            $inputFilter = new inputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => false,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'artist',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        	'messages' => array(
                        		'stringLengthInvalid' 	=> 'A informação enviada não é um texto',
                        		'stringLengthTooShort' 	=> 'O texto deve conter no mínimo %min% caracteres',
                        		'stringLengthTooLong' 	=> 'O texto deve conter no máximo %max% caracteres'
                        	)
                        )
                    ),
                	array(
		 				'name' => 'NotEmpty',
		 				'options' => array(
		 					'messages' => array(
		 						'isEmpty' => 'Essa campo não pode ser vazio'
		 					)
		 				)
	 				)
                )
            )));
            
            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        	'messages' => array(
                        		'stringLengthInvalid' 	=> 'A informação enviada não é um texto',
                        		'stringLengthTooShort' 	=> 'O texto deve conter no mínimo %min% caracteres',
                        		'stringLengthTooLong' 	=> 'O texto deve conter no máximo %max% caracteres'
                        	)
                        )
                    ),
                	array(
		 				'name' => 'NotEmpty',
		 				'options' => array(
		 					'messages' => array(
		 						'isEmpty' => 'Essa campo não pode ser vazio'
		 					)
		 				)
	 				)
                )
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}