<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
    
      $name = "urbano filho";
      //$products = $this->getDoctrine()->getRepository(Product::class)->findAll();
      //var_dump($products);exit;

      $manager = $this->getDoctrine()->getManager();


      //dump($address->getUser());

      /*$user = new User();
      $user->setFirstName('Usuario2');
      $user->setLastName('test2e');
      $user->setEmail('tese2@gmail.com');
      $user->setPassword('123456');
      $user->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
      $user->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));


      $address = new Address();
      $address->setAddress('address2');
      $address->setNumber(222);
      $address->setCity('brasilia');
      $address->setState('df');
      $address->setZipCode('123456');
      $address->setUser($user);

      $manager->persist($address);
      $manager->flush();*/

      
      $user = $this->getDoctrine()->getRepository(User::class)->find(2);
      $order = $this->getDoctrine()->getRepository(Order::class)->find(2);

      $user->removeOrder($order);
      $manager->flush();

      /*$order = new Order();
      $order->setReference('CODIGO_dois');
      $order->setItems('items2');
      $order->setUser($user);
      $order->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
      $order->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

      $manager->persist($order);
      $manager->flush();
      */

     // $product = $this->getDoctrine()->getRepository(Product::class)->find(2);

      /*$category = new Category();
      $category->setName('computadores');
      $category->setSlug('computadores');
      $category->setCreatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));
      $category->setUpdatedAt(new \DateTime('now', new \DateTimeZone('America/Sao_Paulo')));

      $product->setCategory($category);

      $manager->flush();
*/
    //dump($product->getCategories()->toArray());

      //echo $_SERVER ['REMOTE_ADDR'];exit;

      return $this->render('index.html.twig',compact('name','user'));
    }

    /**
     * @Route("/product/{slug}", name="product_single")
     */
    public function product($slug)
    {
    
      return $this->render('single.html.twig',compact('slug'));
    }
}
