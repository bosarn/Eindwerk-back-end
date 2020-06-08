<?php


namespace App\Controller;


use App\Entity\Images;
use App\Entity\Price;
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
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminOrderController extends AbstractController
{

    /**
     * @Route( "/admin/download-invoice/{id}", requirements={"id" = "\d+"}, name="download_invoice")
     * @param Request $request
     * @param OrdersRepository $repository
     * @return Response
     */
    public function downloadinvoice(Request $request, OrdersRepository $repository )
    {
        $entityManager = $this->getDoctrine()->getManager();

        $orderId = $request->request->get('order_id');
        $order = $repository->findOneBy(['id'=> $orderId]);
        $order->setStatus('Finished');
        $orderinvoicepath = $order->getInvoice();

        $url = $orderinvoicepath;
        $content = file_get_contents($url);
        $entityManager->persist($order);
        $entityManager->flush();

        return new Response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'inline; filename="invoice.pdf"',
        ]);


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
     * @Route("/admin/order/add", name="add_order")
     * @param Request $request
     * @return RedirectResponse
     */
    public function addorder(Request $request, printedObjectRepository $repository, UserRepository $userRepository )
    {
        $entityManager = $this->getDoctrine()->getManager();



        $address = $request->get('shipping_adress');
        $user = $request->get('user');
        $objectID = $request->get('object_select');
        $amount = $request->get('object_amount');

        $order = new Orders();

        $userobject = $userRepository->findOneBy(['id'=> $user]);
        $order->setUser($userobject);
        $order->setShippingAdress($address);



        $x=0;
foreach( $objectID as $id){

    $detail = new OrderDetails();
    $object = $repository->findOneBy(['id'=> $id]);
    $detail->setObjects($object);
    $detail->setQuantity( $amount[$x]);
    $detail->setObjectStatus('removestatusplease');
    $order->addDetail($detail);
   $x+=1;
}



        $entityManager->persist($order);
        $entityManager->flush();

        return $this->redirect('/admin/objects');

    }

}