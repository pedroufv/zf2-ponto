<?php
namespace Ponto\Form;

use Zend\Form\Form;

class PontoForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('ponto');
        
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'manha_ini',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Manha - Entrada',
            ),
        ));

        $this->add(array(
            'name' => 'manha_fim',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => utf8_encode('Manha - Saida'),
            ),
        ));

        $this->add(array(
            'name' => 'tarde_ini',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Tarde - Entrada',
            ),
        ));

        $this->add(array(
            'name' => 'tarde_fim',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => utf8_encode('Tarde - Saida'),
            ),
        ));

        $this->add(array(
            'name' => 'data',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Dia',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

    }
}
