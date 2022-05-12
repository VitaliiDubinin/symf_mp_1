<?php

namespace App\Controller;
use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CRUDController extends AbstractController
{
    #[Route('/crud/list', name: 'crud')]
    public function index(EntityManagerInterface $em): Response
    {
        
        $tasks =$em->getRepository(Task::class)->findBy([],['id'=>'DESC']);
        // $tasks =$em->getRepository(Task::class)->findAll();
 
     return $this->render('crud/index.html.twig', ['tasks'=>$tasks]);
        // return $this->json([
            // 'message' => 'Welcome to your new controller!',
            // 'path' => 'src/Controller/CRUDController.php',

        // ]);


    }
    // #[Route('/create', name: 'create_task', methods: ['POST'])]
    // public function create(Request $request, ManagerRegistry $doctrine): Response
    // {
    #[Route('/create', name: 'create_task', methods: ['POST'])]
    public function create(Request $request, ManagerRegistry $doctrine): Response
    {


        $title = trim($request->get("title"));
        $id = trim($request->get("id"));
        // if (!empty($id)){
        if ($id){
            $entityManager = $doctrine->getManager();
            $task =$entityManager->getRepository(Task::class)->find($id);
            $task->setTitle($title);
            $entityManager->flush();
            return $this->redirectToRoute('crud');
        }elseif(!empty($title)){

        // print_r($title.$id);
        // if (!empty($title)){

        $entityManager = $doctrine->getManager();
        $task = new Task();
        $task->setTitle($title);
        $entityManager->persist($task);
        $entityManager->flush();

  

        return $this->redirectToRoute('crud');

        }

    }
    // #[Route('/update{id}', name: 'update_task', methods:['POST'])]
    #[Route('/update/{id}', name: 'update_task')]
    public function update($id, ManagerRegistry $doctrine ): Response
    {

        $entityManager = $doctrine->getManager();
        $task =$entityManager->getRepository(Task::class)->find($id);
        $task->setStatus(!$task->getStatus());
        // $task->setStatus(3);
        $entityManager->flush();
        return $this->redirectToRoute('crud');




        //  $this->render('crud/index.html.twig');
        //  exit('crud update task:update a  task'.$id);
    }
    #[Route('/delete/{id}', name: 'delete_task')]
    public function delete($id,ManagerRegistry $doctrine ): Response
    {

        $entityManager = $doctrine->getManager();
        $task =$entityManager->getRepository(Task::class)->find($id);
        $entityManager->remove($task);
        $entityManager->flush();
        return $this->redirectToRoute('crud');
        //  $this->render('crud/index.html.twig');
        //  exit('crud delete task: delete a  task'.$id);
    }
}
