<?php

namespace App\Controller;

use App\Entity\Employee;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\EmployeeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* @Route("/api/amazing-employees", name="api_employees")
*/

class ApiEmployeesController extends AbstractController
{
    /**
    * @Route(
    *    "",
    *    name="cget",  
    *    methods={"GET"}
    *   )
    */

    public function index(Request $request, EmployeeRepository $employeeRepository): Response
    {
        if($request->query->has('term')) {
            $people = $employeeRepository->findByTerm($request->query->get('term'));

            return $this->json($people);
        }

        return $this->json($employeeRepository->findAll());
    }

    /**
    * @Route(
    *    "/{id}", 
    *    name="get",  
    *    methods={"GET"},
    *    requirements= {
    *           "id": "\d+"
    *   }
    *  )
    */
    
    public function show(int $id, EmployeeRepository $employeeRepository): Response
    {
        $data = $employeeRepository->find($id);

        dump($id);
        dump($data);
        
        return $this->json($data);
    }

    /**
    * @Route(
    *    "", 
    *    name="post", 
    *    methods={"POST"}
    *  )
    */

    public function add(
        Request $request,
        EntityManagerInterface $entityManager
        ): Response {

        $data = $request->request;

        $employee = new Employee();

        $employee->setName($data->get('name'));
        $employee->setEmail($data->get('email'));
        $employee->setAge($data->get('age'));
        $employee->setCity($data->get('city'));
        $employee->setPhone($data->get('phone'));

        $entityManager->persist($employee);

        $entityManager->flush();

        dump($employee);

        return $this->json([
            'method' => 'POST',
            'description' => 'Crea un recurso empleado (employee)',
        ]);
    }

    /**
    * @Route(
    *    "/{id}", 
    *    name="put", 
    *    methods={"PUT"},
    *    requirements= {
    *           "id": "\d+"
    *   }
    *  )
    */

    public function update(int $id): Response {
        return $this->json([
            'method' => 'PUT',
            'description' => 'Actualiza un recurso empleado completo (employee) con id: '.$id.'.',
        ]);
    }

    /**
    * @Route(
    *    "/{id}", 
    *    name="delete", 
    *    methods={"DELETE"}
    *  )
    */

    public function delete(): Response {
        return $this->json([
            'method' => 'DELETE',
            'description' => 'Borra un recurso empleado (employee)',
        ]);
    }
}
