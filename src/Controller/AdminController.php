<?php


namespace App\Controller;


use App\Entity\Images;
use App\Entity\Printer;
use App\Entity\Files;
use App\Repository\OrdersRepository;
use App\Repository\OrderDetailsRepository;
use App\Repository\PrintedobjectRepository;
use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Printedobject;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;



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
     * @return Response
     */
    public function orders(OrdersRepository $repository )
    {
        //todo: find all by date desc && status ordered not finished
        $orders = $repository->findAll();

        return $this->render('admin/orders.html.twig', ['orders' => $orders]);
    }

    /**
     * @Route( "/admin/printinvoice/{id}", requirements={"id" = "\d+"}, name="print_invoice")
     *
     */
    public function printInvoice( )
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

    public function sendFileToPrinter($fil, $printe)
    {
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
    public function addobject(Request $request )
    {


        $entityManager = $this->getDoctrine()->getManager();
        $uploads_directory=$this->getParameter('uploads_directory');

        $name = $request->get('object_name');
        $printtime = $request->get('object_printtime');
        $size = $request->get('object_size');
        $price = $request->get('object_price');
        $files = $request->files->all()['object_files'];
        $images = $request->files->all()['object_images'];

        $object = new Printedobject();

        $object->setName($name);
        $object->setPrintTime($printtime);
        $object->setSize($size);
        //$object->setGCODE($price);

        foreach ($files as $file) {
            $newFiles = new Files();
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($uploads_directory, $filename);
            $newFiles->setGCODE('Change to Path file');
            $newFiles->setName($originalFilename);
            $object->addFile($newFiles);
            $entityManager->persist($newFiles);
        }
        foreach ($images as $image) {
            $newImage = new Images();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imagefilename = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($uploads_directory, $imagefilename);
            $newImage->setPath($imagefilename);
            $newImage->setName($originalFilename);
            $object->addImage($newImage);
            $entityManager->persist($newImage);
        }

        $entityManager->persist($object);
        $entityManager->flush();


        return new Response('Object added');


    }

    /**
     * @Route( "/admin/deleteobject/{id}", requirements={"id" = "\d+"}, name="delete_object")
     * @param Request $request
     * @param PrintedobjectRepository $repository
     * @return Response
     */

    public function deleteobject(Request $request, PrintedobjectRepository $repository)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $object = $repository->findOneBy(['id' => $id]);
        $em->remove($object);
        $em->flush();
        return new Response('Object deleted');
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
/**
 *



 */