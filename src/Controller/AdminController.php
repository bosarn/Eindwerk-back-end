<?php


namespace App\Controller;


use App\Entity\Category;
use App\Entity\Images;
use App\Entity\Price;
use App\Entity\Printer;
use App\Entity\Files;
use App\Repository\CategoryRepository;
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
     * @param CategoryRepository $categoryRepository
     * @return RedirectResponse
     */
    public function addobject(Request $request, CategoryRepository $categoryRepository )
    {
        $entityManager = $this->getDoctrine()->getManager();
        $uploads_directory=$this->getParameter('uploads_directory');

        $name = $request->get('object_name');
        $printtime = $request->get('object_printtime');
        $size = $request->get('object_size');
        $description = $request->get('object_description');
        $price = $request->get('object_price');
        $files = $request->files->all()['object_files'];
        $images = $request->files->all()['object_images'];
        $categories = $request->get('object_Category');
        $publish = $request->get('object_publish');

        $object = new Printedobject();

        $object->setName($name);
        $object->setPrintTime($printtime);
        $object->setSize($size);
        $object->setDescription($description);
        $object->setPublished($publish);

        $priceObject = new Price();
        $priceObject->setValue($price);
        $priceObject->setDescription('initial price');
        $object->addPrice($priceObject);
        $entityManager->persist($priceObject);

        foreach ( $categories as $category) {
            if($category !== 'None...'){
                $newobjectCategory = $categoryRepository->findOneBy(['name'=> $category]);
                $newobjectCategory->addPrintedobject($object);
                $entityManager->persist($newobjectCategory);
            }


        }

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

        return $this->redirectToRoute('app_admin_objects');

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
        return $this->redirectToRoute('app_admin_objects');
    }


    /**
     * @Route( "/admin/search",  name="search_object")
     * @param Request $request
     * @param PrintedobjectRepository $repository
     * @return Response
     */

    public function searchobject(Request $request, PrintedobjectRepository $repository)
    {


        $searchterm = $request->get('search');
        $objects = $repository->findBySearch( $searchterm );


        return $this->render('admin/objects.html.twig', ['objects' => $objects]);

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
        $description = $request->get('object_description');
        $printtime = $request->get('object_printtime');
        $size = $request->get('object_size');
        $published = $request->get('object_published');
        $price = $request->get('object_price');
        $priceDescription = $request->get('object_price_description');
        $priceDate = $request->get('object_price_start');
        $images = $request->files->all()['object_images'];

        $object = $repository->findOneBy(['id' => $id]);
        if ($name !== '') {
            $object->setName($name);
        }
        if ($description !== '') {
            $object->setDescription($description);
        }
        if ($size !== '') {
            $object->setSize($size);
        }
        if ($printtime !== '') {
            $object->setPrintTime($printtime);
        }
        if ($published !== '') {

            $object->setPublished($published);
        }

        if ($images) {
            //remove previous images
            $removeImages = $object->getImages();
            foreach( $removeImages as $previousimage ){
                $entityManager->remove($previousimage);
                $entityManager->flush();
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

        }



        if($price !== ''){
            //Price manipulation: set previous price to end, set new price up
            $lastprice = $object->getCurrentPrice();

            foreach($lastprice as $try) {
                $try->setPriceend(New \DateTime());
            }

            $newPrice = new Price();
            $newPrice -> setValue($price);
            $newPrice -> setDescription($priceDescription);

            $date = new \DateTime($priceDate);
            $newPrice -> setPricestart($date);

            $object->addPrice($newPrice);
   }




        $entityManager->persist($object);
        $entityManager->flush();


        return $this->redirectToRoute('app_admin_objects');
    }

}
