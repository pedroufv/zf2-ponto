<?php

namespace Ponto\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class UsuariosTable extends AbstractTableGateway
{
    protected $table    = 'usuarios';


    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Usuario());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
    
    public function getUsuario($id_usuario)
    {
        $id  = (int) $id_usuario;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

}
