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
// TODO move to user order listener + enititymanager persist flush
        $orderId = $request->request->get('order_id');
        $order = $repository->findOneBy(['id'=> $orderId]);
        $order->setStatus('Finished');

        //$invoice = $order->getInvoice();
        $details = $order->getDetails();

        $objectDetails = array();
        $objectNames = array();
        $objectprices = array();
        foreach($details as $detail ) {

            $objectDetail = $detail->getQuantity();
            $price = $detail->getObjects()->getPrice();
            $objectName = $detail->getObjects()->getName();

            array_push($objectDetails, $objectDetail);
            array_push($objectNames, $objectName);

            foreach($price as $prices) {
                array_push($objectprices, $prices->getValue());
            }
        }

        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->renderView('pdf.html.twig',[
            'order' => $order,
            'names' => $objectNames,
            'details' => $objectDetails,
            'prices' => $objectprices,

        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'inline; filename="myfilename.pdf"',
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
    public function addorder(Request $request )
    {
        $entityManager = $this->getDoctrine()->getManager();


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

        $priceObject = new Price();
        $priceObject->setValue($price);
        $priceObject->getDescription('initial price');
        $object->addPrice($priceObject);
        $entityManager->persist($priceObject);

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

}