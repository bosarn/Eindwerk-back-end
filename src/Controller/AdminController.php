<?php


namespace App\Controller;


use App\Entity\Images;
use App\Entity\Printer;
use App\Entity\Files;
use App\Repository\FilesRepository;
use App\Repository\OrdersRepository;
use App\Repository\OrderDetailsRepository;
use App\Repository\PrintedobjectRepository;
use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Printedobject;
use App\Repository\PrinterRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;
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
            array('date' => 'DESC')
        );

        return $this->render('admin/orders.html.twig', ['orders' => $orders,'printers'=> $printers]);
    }

    /**
     * @Route( "/admin/download-invoice/{id}", requirements={"id" = "\d+"}, name="download_invoice")
     * @param Request $request
     * @param OrdersRepository $repository
     * @return RedirectResponse
     */
    public function downloadinvoice(Request $request, OrdersRepository $repository )
    {

        $orderId = $request->request->get('order_id');
        $order = $repository->findOneBy(['id'=> $orderId]);
        $order->setStatus('Finished');
        $invoice = $order->getInvoice();


        $fileContent =  $invoice;
        $response = new Response($fileContent);

        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'Invoice-'.$orderId.'.txt'
        );
        $response->headers->set('Content-Disposition', $disposition);
        $response->send();

        return $this->redirect('/admin/orders');
    }

    /**
     * @Route( "/admin/sendfiletoprinter", name="send_file_to_printer")
     * @param Request $request
     * @param PrinterRepository $printerrepository
     * @param FilesRepository $filesRepository
     * @return RedirectResponse
     */

    public function sendFileToPrinter(Request $request, PrinterRepository $printerrepository, FilesRepository $filesRepository)
    {
        $printerid = $request->get('printer_select');
        $fileid = $request->get('file_id');

        $file = $filesRepository->findOneBy(['id' => $fileid]);
        $printer = $printerrepository->findOneBy(['id' => $printerid]);

        $printer->getLocation();
        $file->getGCODE();

        //todo
        // get printer id >> IP API key
        // does http post to pritner
        // if resposne 200
        // adjust file to quantity minus 1
        // printer status -> closed
        // redirects@return RedirectResponse
        // requirements={"id" = "\d+"}
        return $this->redirect('/admin/orders');

    }

    /**
     * @Route("/admin/object/add", name="add_object")
     * @param Request $request
     * @return RedirectResponse
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

            $newFiles->setGCODE(file_get_contents($file));
            $file->move($uploads_directory.'/files/', $filename);
            $newFiles->setName($originalFilename);
            $object->addFile($newFiles);
            $entityManager->persist($newFiles);
        }
        foreach ($images as $image) {
            $newImage = new Images();
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $imagefilename = md5(uniqid()).'.'.$image->guessExtension();
            $image->move($uploads_directory.'/images/', $imagefilename);
            $newImage->setPath('/uploads/images/'.$imagefilename);
            $newImage->setName($originalFilename);
            $object->addImage($newImage);
            $entityManager->persist($newImage);
        }

        $entityManager->persist($object);
        $entityManager->flush();

        return $this->redirect('/admin/objects');



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
        return $this->redirect('/admin/objects');
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
