<?php

namespace App\Controller;

use App\Repository\OrdersRepository;
use App\Repository\PrintedobjectRepository;
use App\Repository\PrinterRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Repository\CategoryRepository;

class ViewRenderController extends AbstractController
{

    /**
     * @Route("/", name="app_homepage")
     * @param PrinterRepository $repository
     * @return Response
     */
    public function homepage ( PrinterRepository $repository)
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $printers = $repository->findAll();
        return $this->render('homepage.html.twig', [
            'printers' => $printers,
            'printers_serialized' => $serializer->serialize($printers, 'json'),

        ]);
    }

    /**
     * @Route("/object/form", name="app_object_form")
     * @param CategoryRepository $repository
     * @return Response
     */
    public function objectform (CategoryRepository $repository )
    {
        $categories = $repository->findAll();

        return $this->render('admin/addobject.html.twig',['categories' => $categories]);
    }

    /**
     * @Route("/order/form", name="app_order_form")
     * @param PrintedobjectRepository $repository
     * @return Response
     */
    public function orderform (PrintedobjectRepository $repository)
    {
        $objects = $repository->findAll();
        return $this->render('admin/addorder.html.twig', ['objects' => $objects]);
    }

    /**
     * @Route("/admin/objects", name="app_admin_objects")
     * @param PrintedobjectRepository $repository
     * @return Response
     */
    public function objects(PrintedobjectRepository $repository)
    {
        $objects = $repository->findAll();
        return $this->render('admin/objects.html.twig', ['objects' => $objects]);
    }

    /**
     * @Route("/admin/orders", name="app_admin_orders")
     * @param OrdersRepository $repository
     * @param PrinterRepository $printer
     * @return Response
     */
    public function orders(OrdersRepository $repository, PrinterRepository $printer)
    {
        // todo get printer via api calls call for status -> adjust db status
        $printers =$printer->findAll();
        // setstatus == api called data
        $orders = $repository->findBy(
            array('status'=> 'test'),
            array('date' => 'ASC')
        );
        return $this->render('admin/orders.html.twig', ['orders' => $orders,'printers'=> $printers]);
    }
    /**
     * @Route( "/admin/object/{id}", requirements={"id" = "\d+"}, name="object_detail")
     * @param Request $request
     * @param PrintedobjectRepository $repository
     * @return Response
     */
    public function objectdetail (Request $request, PrintedobjectRepository $repository)
    {
        $id = $request->get('id');
        $object = $repository->findOneBy(['id' => $id]);

        return $this->render('admin/objectdetails.html.twig', ['object' => $object]);
    }
}