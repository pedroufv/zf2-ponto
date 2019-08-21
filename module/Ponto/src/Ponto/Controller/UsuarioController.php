<?php
namespace Ponto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ponto\Model\Ponto;

class UsuarioController extends AbstractActionController
{
    protected $pontosTable;
    protected $usuariosTable;
    protected $id_usuario = 1;

    public function indexAction()
    { 
        $usuario = $this->getUsuariosTable()->getUsuario($this->id_usuario);
        return new ViewModel(array('usuario' => $usuario));
    }
    
    public function getUsuariosTable()
    {
        if (!$this->usuariosTable) {
            $sm = $this->getServiceLocator();
            $this->usuariosTable = $sm->get('Ponto\Model\UsuariosTable');
        }
        return $this->usuariosTable;
    }

}
