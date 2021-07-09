<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/products", name="admin_")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="index_products")
     */
    public function index(ProductRepository $productRepository): Response
    {
  
        $products = $productRepository->findAll();
 
        return $this->render('admin/product/index.html.twig', compact('products'));
    }

/**
     * @Route("/upload")
     */
    //public function upload(Request $request, UploadService $uploadService): Response
    //{
      //  /** @var UploadedFile[] $photos */
       // $photos = $request->files->get('photos');
       // $uploadService->upload($photos, 'products');
   
       // return new Response('Upload');
    //}

    /**
     * @Route("/create", name="create_products")
     */
    public function create(Request $request, UploadService $uploadService): Response
    {
        $form = $this->createForm(ProductType::class, new Product());

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
     
           $product =  $form->getData();
           $product->setCreatedAt();
           $product->setUpdatedAt();

           $photosUpload = $form['photos']->getData();

           if($photosUpload){
            $photosUpload = $uploadService->upload($photosUpload, 'products');

            //dd($photosUpload);
            $photosUpload = $this->makeProductPhotoEntities($photosUpload);
            $product->addManyProductPhoto($photosUpload);
           }

           $this->getDoctrine()->getManager()->persist($product);
           $this->getDoctrine()->getManager()->flush();

          $this->addFlash('success', 'Produto criado com sucesso!');
    
          return $this->redirectToRoute('admin_index_products');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/edit/{product}", name="edit_products")
     */
    public function edit($product, Request $request, ProductRepository $productRepository, EntityManagerInterface $em,  UploadService $uploadService): Response
    {
        //$product  = $this->getDoctrine()->getRepository(Product::class)->find($product);
        $product  = $productRepository->find($product);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if($form->isSubmitted()){
           $product =  $form->getData();
           $product->setUpdatedAt();

           $photosUpload = $form['photos']->getData();

           if($photosUpload){
            $photosUpload = $uploadService->upload($photosUpload, 'products');
            //dd($photosUpload);
            $photosUpload = $this->makeProductPhotoEntities($photosUpload);
            $product->addManyProductPhoto($photosUpload);

           }

           //$this->getDoctrine()->getManager()->flush();
           $em->flush();

          $this->addFlash('success', 'Produto atualizado com sucesso!');
    
          return $this->redirectToRoute('admin_edit_products',['product' => $product->getId()]);
        }

        return $this->render('admin/product/edit.html.twig',[
            'form' => $form->createView(),
            'productPhotos' => $product->getProductPhotos()
        ]);
    }

    /**
     * @Route("/update/{product}", name="update_products", methods={"POST"})
     */
    public function update($product, Request $request,ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        
        try{
            #Atualizar os dados
            $data = $request->request->all();

            $product  = $productRepository->find($product);

            $product->setNome($data['nome']);
            $product->setDescription($data['description']);
            $product->setBody($data['body']);
            $product->setSlug($data['slug']);
            $product->setPrice($data['price']);
            $product->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $em->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products',['product' => $product->getId()]);
    
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    /**
     * @Route("/remove/{product}", name="remove_products")
     */
    public function remove($product, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {
        try {
            #Removendo
            $product  = $productRepository->find($product);
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Produto removido com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    private function makeProductPhotoEntities($uploadedPhotos){
        $entities = [];
        foreach ($uploadedPhotos as $photo) {
            $productPhoto = new ProductPhoto();
            $productPhoto->setPhoto($photo);
            $productPhoto->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
            $productPhoto->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

            $entities[] = $productPhoto;
        }
        return $entities;
    }
}
