<?php


namespace App\Controller;

use App\Entity\Printer;
use App\Entity\Files;
use App\Repository\OrdersRepository;
use App\Repository\OrderDetailsRepository;
use App\Repository\PrintedobjectRepository;
use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Printedobject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class AdminController extends AbstractController
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
     * @param OrdersRepository $orderDetails
     * @return Response
     */
    public function orders(OrdersRepository $repository, OrdersRepository $orderDetails)
    {
        //todo: find all by date desc && status ordered not finished
        $orders = $repository->findAll();

        return $this->render('admin/orders.html.twig', ['orders' => $orders]);
    }

    /**
     * @Route( "/admin/printinvoice/{id}", requirements={"id" = "\d+"}, name="print_invoice")
     *
     */
    public function printInvoice(Orders $orders)
    {
        //todo
        // downloads file with invoice
        // sets order status to complete
        // redirects @return RedirectResponse
    }

    /**
     * @Route( "/admin/sendfiletoprinter/{id}", requirements={"id" = "\d+"}, name="send_file_to_printer")
     * @param $fil
     * @param $printe
     * @return void
     */

public function sendFileToPrinter($fil,$printe){
    //todo
    // get printer id >> IP API key
    // does http post to pritner
    // adjust file to quantity minus 1
    // redirects@return RedirectResponse
    //Printer $printer, Files $files

}

    /**
     * @Route("/admin/object/add", name="add_object")
     * @param Request $request
     * @return Response
     */
    public function addobject (Request $request){
        $entityManager = $this -> getDoctrine() ->getManager();

        $name = $request->get('object_name');
        $printtime = $request->get('object_printtime');
        $size = $request->get('object_size');
        // todo find substr after word file, push to array
        $files = $request->get('object_files');
        $images = $request->get('object_images');
        $prices = $request->get('object_prices');


        $object = new Printedobject();


        $object->setName($name);
        $object->setPrintTime($printtime);
        $object->setSize($size);

        foreach($files as $file) {
            $newFiles = new Files();
            $filledFiles = $newFiles ->setGCODE($file);
            $object->addFile($filledFiles);
        }

        $entityManager->persist($object);
        $entityManager->flush();
        return new Response('Object added');
        // todo redirect repsonse
    }
}

/**
 *



 */