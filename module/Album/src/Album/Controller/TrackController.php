<?php
 
namespace Album\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManager; 
use Album\Form\AlbumForm;
use Album\Entity\Album;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
 
class AlbumController extends AbstractActionController 
{
    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;
 
    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;
    }
 
    public function getEntityManager()
    {
        if (null === $this->em) {
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        }
        return $this->em;
    }
 
    public function indexAction()
    {    
    	// retorna variaveis para a visao
        return new ViewModel(array(
            	'albums' => $this->getEntityManager()->getRepository('Album\Entity\Album')->findAll()
        	)
        );
    }
 
    public function addAction()
    {
    	// cria um novo formulario
        $form = new AlbumForm();
        
        // define o hydrato para o formulario
        $form->setHydrator(new DoctrineObject($this->getEntityManager(), 'Album\Entity\Album'));
        
        // altera um atributo do formulario
        $form->get('submit')->setAttribute('label', 'Add');
 
        // recupera requisicao
        $request = $this->getRequest();
        
        // verifica se a requisicao eh um post
        if ($request->isPost()) {
        	
            // instancia uma entidade album
        	$album = new Album();
            
            // garante o objeto seja preenchido com valores validos
            $form->bind($album);
			
            // preenche o formulario com valores enviados via post
            $form->setData($request->getPost());
            
            // verifica se os valores enviados para o formulario sao validos
            if ($form->isValid()) {
            	
            	// prepara a persistencia do objeto
            	$this->getEntityManager()->persist($album);
                
            	// executa instrucao previamente preparada
            	$this->getEntityManager()->flush();
 
                // redireciona para a lista de albuns
                return $this->redirect()->toRoute('album');
            }
        }
 		
        // retorna variaveis para a visao
        return new ViewModel(array('form' => $form));
    }
 
    public function editAction()
    {
        // recupera um parametro
    	$id = (int)$this->params('id');

    	// se nao foi possivel recuperar o parametro
        if (!$id) {
        	
        	// redireciona para add um novo album
            return $this->redirect()->toRoute('album', array('action'=>'add'));
        }
        
        // recupera no banco as informacoes das tracks pelo parametro passado
        $tracks = $this->getEntityManager()->getRepository('Album\Entity\Track')->findBy(array('album' => $id));
        
        if(count($tracks) > 0){
	        // recupera as informacoes do album
	        $album = $tracks[0]->getAlbum();
		} else{
			// recupera no banco as informacoes do album pelo parametro passado 
        	$album = $this->getEntityManager()->find('Album\Entity\Album', $id);
		}
 
        // instancia o formulario
        $form = new AlbumForm();
        
        // define o hydrato para o formulario
        $form->setHydrator(new DoctrineObject($this->getEntityManager(), 'Album\Entity\Album'));

        // altera um atributo do formulario
        $form->get('submit')->setAttribute('label', 'Edit');

        // garante o objeto seja preenchido com valores validos
        $form->bind($album);
        
		// recupera requisicao
        $request = $this->getRequest();
        
        // verifica se a requisicao eh post
        if ($request->isPost()) {
        	
        	// preenche o formulario com as informacoes passadas no post
            $form->setData($request->getPost());
            
            // verifica se os valores passados no formulario sao validos
            if ($form->isValid()) {
            	
                // prepara a persistencia do objeto
            	$this->getEntityManager()->persist($album);
                
                // executa instrucao previamente preparada
            	$this->getEntityManager()->flush();
 
                // redireciona para a lista de albuns
                return $this->redirect()->toRoute('album');
            }
        }
 		
        // retorna variaveis para a visao
        return new ViewModel(array(
        		'id' 		=> $id,
				'form' 		=> $form,
        		'tracks' 	=> $tracks
        	)
        );
    }
 
    public function deleteAction()
    {
        // recupera um parametro
    	$id = (int)$this->params('id');
    	
    	// se nao foi possivel recuperar o parametro
        if (!$id) {
        	
        	// redireciona para a lista de albuns
            return $this->redirect()->toRoute('album');
        }
 		
        // recupera a requisicao
        $request = $this->getRequest();
        
        // verifica se a requisicao eh via post
        if ($request->isPost()) {
        	
        	// recupera a opcao de remocao
            $del = $request->getPost()->get('del', 'No');
            
            // se a opcao de remocao for 'Yes'
            if ($del == 'Yes') {

            	// recupera as informacoes do album com o parametro passado
                $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
                
                // se o album existe
                if ($album) {
                	
                	// prepara a instrucao de remoca
                    $this->getEntityManager()->remove($album);
                    
                    // executa a instrucao preparada
                    $this->getEntityManager()->flush();
                }
            }
            
            // redireciona para a lista de albuns
            return $this->redirect()->toRoute('album');
        }
 
        // retorna variaveis para a visao
        return new ViewModel(array(
        		'id' 	=> $id,
        		'album' => $this->getEntityManager()->find('Album\Entity\Album', $id)
        	)
        );
    }
    
	public function editTrackAction()
    {
        // recupera um parametro
    	$id = (int)$this->params('id');

    	// se nao foi possivel recuperar o parametro
        if (!$id) {
        	
        	// redireciona para add um novo album
            return $this->redirect()->toRoute('album', array('action'=>'add-track'));
        }
        
        // recupera no banco as informacoes das tracks pelo parametro passado
        $tracks = $this->getEntityManager()->find('Album\Entity\Track', $id);
 
        // instancia o formulario
        $form = new AlbumForm();
        
        // define o hydrato para o formulario
        $form->setHydrator(new DoctrineObject($this->getEntityManager(), 'Album\Entity\Album'));

        // altera um atributo do formulario
        $form->get('submit')->setAttribute('label', 'Edit');

        // garante o objeto seja preenchido com valores validos
        $form->bind($album);
        
		// recupera requisicao
        $request = $this->getRequest();
        
        // verifica se a requisicao eh post
        if ($request->isPost()) {
        	
        	// preenche o formulario com as informacoes passadas no post
            $form->setData($request->getPost());
            
            // verifica se os valores passados no formulario sao validos
            if ($form->isValid()) {
            	
                // prepara a persistencia do objeto
            	$this->getEntityManager()->persist($album);
                
                // executa instrucao previamente preparada
            	$this->getEntityManager()->flush();
 
                // redireciona para a lista de albuns
                return $this->redirect()->toRoute('album');
            }
        }
 		
        // retorna variaveis para a visao
        return new ViewModel(array(
        		'id' 		=> $id,
				'form' 		=> $form,
        		'tracks' 	=> $tracks
        	)
        );
    }
 
    public function deleteTrackAction()
    {
        // recupera um parametro
    	$id = (int)$this->params('id');
    	
    	// se nao foi possivel recuperar o parametro
        if (!$id) {
        	
        	// redireciona para a lista de albuns
            return $this->redirect()->toRoute('album');
        }
 		
        // recupera a requisicao
        $request = $this->getRequest();
        
        // verifica se a requisicao eh via post
        if ($request->isPost()) {
        	
        	// recupera a opcao de remocao
            $del = $request->getPost()->get('del', 'No');
            
            // se a opcao de remocao for 'Yes'
            if ($del == 'Yes') {

            	// recupera as informacoes do album com o parametro passado
                $album = $this->getEntityManager()->find('Album\Entity\Album', $id);
                
                // se o album existe
                if ($album) {
                	
                	// prepara a instrucao de remoca
                    $this->getEntityManager()->remove($album);
                    
                    // executa a instrucao preparada
                    $this->getEntityManager()->flush();
                }
            }
            
            // redireciona para a lista de albuns
            return $this->redirect()->toRoute('album');
        }
 
        // retorna variaveis para a visao
        return new ViewModel(array(
        		'id' 	=> $id,
        		'album' => $this->getEntityManager()->find('Album\Entity\Album', $id)
        	)
        );
    }
}