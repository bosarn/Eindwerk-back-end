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


class AdminController extends AbstractController
{
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
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
     * @Route( "/admin/editobject/{id}", requirements={"id" = "\d+"}, name="edit_object")
     * @param Request $request
     * @param PrintedobjectRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function editobject (Request $request, PrintedobjectRepository $repository){
        $uploads_directory=$this->getParameter('uploads_directory');
        $entityManager = $this->getDoctrine()->getManager();


        $id = $request->get('id');
        $name = $request->get('object_name');
        $printtime = $request->get('object_printtime');
        $size = $request->get('object_size');
        $price = $request->get('object_price');


        $object = $repository->findOneBy(['id' => $id]);

        $object->setName($name);
        $object->setSize($size);
        $object->setPrintTime($printtime);
/**
        $removeFiles = $object->getFiles();
        foreach( $removeFiles as $file ){
            $object->removeFile($file);
        }
        $removeImages = $object->getImages();
        foreach( $removeImages as $image ){
            $object->removeFile($image);
        }
 * */
        // TODO Files add and images add in edit
        $lastprice = $object->getCurrentPrice();

        foreach($lastprice as $try) {
            $try->setPriceend(New \DateTime());
        }

        $newPrice = new Price();
        $newPrice -> setValue($price);
        $newPrice -> setDescription('Changedprice');
        $object->addPrice($newPrice);

/*
        foreach( $files as $file ) {
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
*/
        $entityManager->persist($object);
        $entityManager->flush();


        return $this->redirect('/admin/objects');
    }

}
