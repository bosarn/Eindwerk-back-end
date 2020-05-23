<?php


namespace App\Controller;

use App\Entity\Printer;
use App\Entity\Files;
use App\Repository\OrdersRepository;
use App\Repository\OrderDetailsRepository;
use App\Entity\OrderDetails;
use App\Entity\Orders;
use App\Entity\Printedobject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpClient\HttpClient;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/objects", name="app_admin_objects")
     * @return Response
     */
    public function objects()
    {
        return $this->render('admin/objects.html.twig');
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
     * @param Printer $printer
     * @param Files $files
     * @return void
     */

public function sendFileToPrinter(Printer $printer, Files $files){
    //todo
    // get printer id >> IP API key
    // does http post to pritner
    // adjust file to quantity minus 1
    // redirects


}
}

