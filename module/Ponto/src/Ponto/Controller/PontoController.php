<?php
namespace Ponto\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Ponto\Model\Ponto;
use Ponto\Form\PontoForm;

class PontoController extends AbstractActionController
{
    protected $pontosTable;
    protected $usuariosTable;
    protected $id_usuario = 1;

    public function indexAction()
    {
        $horas_dia = $this->getUsuariosTable()->getUsuario($this->id_usuario)->horas_dia;
        return new ViewModel(array(
                                   'title' => "Controle do Ponto",
                                   'message' => $this->getFlashMessenger(),
                                   'dias' => $this->getPontosTable()->getDias($horas_dia, $this->id_usuario), 
                                   'saldoTotal' => $this->getPontosTable()->getSaldo($horas_dia, $this->id_usuario)
                                   )
        );
    }
    
    public function anoAction()
    {
    	$ano = (int)$this->params('ano');
    	
    	$mes = (int)$this->params('mes');
    	
    	$horas_dia = $this->getUsuariosTable()->getUsuario($this->id_usuario)->horas_dia;
    	
    	return new ViewModel(array(
    			'title' => "Controle do Ponto",
    			'dias' => $this->getPontosTable()->getDias($horas_dia, $this->id_usuario, $mes, $ano),
    			'saldoTotal' => $this->getPontosTable()->getSaldo($horas_dia, $this->id_usuario, $mes, $ano),
    			'ano' => $ano,
    			'mes' => $mes
    	));
    }


    public function mesAction()
    {
        $id = (int)$this->params('id');
        $mes = date("Y-m", mktime(0, 0, 0, $id, 10));
        $horas_dia = $this->getUsuariosTable()->getUsuario($this->id_usuario)->horas_dia;
        return new ViewModel(array(
                                   'title' => "Controle do Ponto",
                                   'dias' => $this->getPontosTable()->getDias($horas_dia, $this->id_usuario, $mes), 
                                   'saldoTotal' => $this->getPontosTable()->getSaldo($this->id_usuario, $mes))
        );
    }
    
    public function addAction()
    { 
        $form = new PontoForm();
        $form->get('submit')->setAttribute('value', 'Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $ponto = new Ponto();
            $form->setInputFilter($ponto->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ponto->exchangeArray($form->getData());
                $ponto->id_usuario = $this->id_usuario;
                $this->getPontosTable()->savePonto($ponto);

                // Redirect to list of pontos
                return $this->redirect()->toRoute('ponto');
            }
        }

        return array('form' => $form, 'title' => "Controle do Ponto");
    }

    public function editAction()
    { 
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('ponto', array('action'=>'add'));
        }
        $ponto = $this->getPontosTable()->getPonto($id);

        $form = new PontoForm();
        $form->bind($ponto);
        $form->get('submit')->setAttribute('value', 'Edit');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {                
                $ponto->id_usuario = $this->id_usuario;
                $this->getPontosTable()->savePonto($ponto);
                // Redirect to list of pontos
                return $this->redirect()->toRoute('ponto');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
            'title' => "Controle do Ponto"
        );
    }

    public function deleteAction()
    {
        $id = (int)$this->params('id');
        if (!$id) {
            return $this->redirect()->toRoute('ponto');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost()->get('del', 'No');
            if ($del == 'Yes') {
                $id = (int)$request->getPost()->get('id');
                $this->getPontosTable()->deletePonto($id);
            }

            // Redirect to list of pontos
            return $this->redirect()->toRoute('ponto');
        }

        return array(
            'id' => $id,
            'ponto' => $this->getPontosTable()->getPonto($id),
            'title' => "Controle do Ponto"
        );
    }

    public function getPontosTable()
    {
        if (!$this->pontosTable) {
            $sm = $this->getServiceLocator();
            $this->pontosTable = $sm->get('Ponto\Model\PontosTable');
        }
        return $this->pontosTable;
    }
    
    public function getUsuariosTable()
    {
        if (!$this->usuariosTable) {
            $sm = $this->getServiceLocator();
            $this->usuariosTable = $sm->get('Ponto\Model\UsuariosTable');
        }
        return $this->usuariosTable;
    }

    // Filter Flash Messenger
    private function getFlashMessenger()
    {
        $messenger = array();
        $flashMessenger = $this->flashMessenger(); 
  
        if ($flashMessenger->hasSuccessMessages())
        {
            $text = $flashMessenger->getSuccessMessages();
            $messenger['alert-success'] = array_shift($text);
        }
        if ($flashMessenger->hasErrorMessages())
        {
            $text = $flashMessenger->getErrorMessages();
            $messenger['alert-error'] = array_shift($text);
        }

        return $messenger;
    }
}
