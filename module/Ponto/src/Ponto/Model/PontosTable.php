<?php

namespace Ponto\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class PontosTable extends AbstractTableGateway
{
    protected $table    = 'pontos';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Ponto());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getDias($horas_dia, $id_usuario, $mes = null, $ano = null)
    {
        if(empty($ano)) $ano = date("Y");
        
        if(empty($mes)) $mes = date("m");
        
        $mesSeguinte = date('Y-m-d', strtotime($ano.'-'.$mes. ' + 1 months'));

        $sql = "SELECT *, SEC_TO_TIME((TIME_TO_SEC(manha_fim) - TIME_TO_SEC(manha_ini))+(TIME_TO_SEC(tarde_fim) - TIME_TO_SEC(tarde_ini))) AS horas_dia,
                SEC_TO_TIME(((TIME_TO_SEC(manha_fim) - TIME_TO_SEC(manha_ini))+(TIME_TO_SEC(tarde_fim) - TIME_TO_SEC(tarde_ini))) - TIME_TO_SEC('{$horas_dia}')) AS saldo
                FROM {$this->table}  
                WHERE data >= '{$ano}-{$mes}-01' AND data < '{$mesSeguinte}' AND id_usuario = {$id_usuario}
                ORDER BY data";
        
        return $this->adapter->query($sql, 'execute');
    }

    public function getSaldo($horas_dia, $id_usuario, $mes = null, $ano = null)
    {          
        
        if(empty($ano)) $ano = date("Y");
        
        if(empty($mes)) $mes = date("m");
        
        $mesSeguinte = date('Y-m-d', strtotime($ano.'-'.$mes. ' + 1 months'));

        $sql = "SELECT SEC_TO_TIME(SUM(((TIME_TO_SEC(manha_fim) - TIME_TO_SEC(manha_ini))+(TIME_TO_SEC(tarde_fim) - TIME_TO_SEC(tarde_ini))) - TIME_TO_SEC('{$horas_dia}'))) AS saldo 
                     FROM {$this->table} 
                     WHERE id_usuario = {$id_usuario}
                        AND data >= '{$ano}-{$mes}-01' 
                        AND data < '{$mesSeguinte}' 
                        AND ((((TIME_TO_SEC(manha_fim) - TIME_TO_SEC(manha_ini))+(TIME_TO_SEC(tarde_fim) - TIME_TO_SEC(tarde_ini))) - TIME_TO_SEC('{$horas_dia}')) > TIME_TO_SEC('00:10:00') 
                            OR (((TIME_TO_SEC(manha_fim) - TIME_TO_SEC(manha_ini))+(TIME_TO_SEC(tarde_fim) - TIME_TO_SEC(tarde_ini))) - TIME_TO_SEC('{$horas_dia}')) < TIME_TO_SEC('-00:10:00'))";

        $statement = $this->adapter->query($sql, 'execute');

        $result = $statement->current();

        return $result;
    }

    public function getPonto($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function savePonto(Ponto $ponto)
    {
        $data = array(
            'manha_ini' => $ponto->manha_ini,
            'manha_fim'  => $ponto->manha_fim,
            'tarde_ini' => $ponto->tarde_ini,
            'tarde_fim'  => $ponto->tarde_fim,
            'data'  => $ponto->data,
            'id_usuario'  => $ponto->id_usuario
        );

        $id = (int)$ponto->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getPonto($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deletePonto($id)
    {
        $this->delete(array('id' => $id));
    }

}
