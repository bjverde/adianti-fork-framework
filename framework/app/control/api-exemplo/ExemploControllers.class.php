<?php
class ExemploControllers
{
    private $database = 'api-exemplo';
    private $repository = 'ExemploDbApi';
    private $tpdo = null;

    public function __construct(){
    }
    public function getDatabase(){
        return $this->database;
    }
    public function getRepository(){
        return $this->repository;
    }   
    //--------------------------------------------------------------------------------
    public function getById($id)
    {
        try{
            TTransaction::open($this->getDatabase()); // open transaction

            $criteria = new TCriteria;
            $criteria->add( new TFilter('id','=', $id) );
            //echo $criteria->dump();

            //$criteria->setProperty('limit' , 1); //Mostra apenas um registro
            //$criteria->setProperty('order' , 'id_system_user_advogado desc'); //Ordenação

            //Mostra SQL na tela
            /*
            TTransaction::setLoggerFunction( function($message) {
                echo $message . '<br>';
            });
            */

            // load using repository
            $repository = new TRepository($this->getRepository());
            $listObj = $repository->load($criteria);
            TTransaction::close();

            $listId = array();
            foreach ($listObj as $adv){ 
                $listId[]=$adv->id_system_user_advogado; 
            }
            //var_dump($listId);
            return $listId;
        }catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}//fim classe