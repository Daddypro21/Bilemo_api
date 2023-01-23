<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Configuration;
use App\Repository\ProductRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




class ProductController extends AbstractController
{


                
    /**
     * 
     * Cette méthode permet de récupérer l'ensemble des produits.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des produits",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"getProduct"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="La page que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     *
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     description="Le nombre d'éléments que l'on veut récupérer",
     *     @OA\Schema(type="int")
     * )
     * @OA\Tag(name="Products")
     *
     * @param ProductRepository $productRepo
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */

    #[Route('/api/products', name: 'app_product',methods:'GET')]
    public function getAllProducts( Request $request,ProductRepository $productRepo,
    SerializerInterface $serializer,TagAwareCacheInterface $cache): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getAllProducts-" . $page . "-" . $limit;
        
        $jsonProductList = $cache->get($idCache, function (ItemInterface $item) use ($productRepo, $page, $limit, $serializer) {
            $item->tag("productsCache");
            $ProductList = $productRepo->findAllWithPagination($page, $limit);
            $context = SerializationContext::create()->setGroups(['getProduct']);
            return $serializer->serialize($ProductList, 'json', $context);
        });
       
        return new JsonResponse($jsonProductList, Response::HTTP_OK,[], true);
    }


    /**
     * Cette méthode permet de récupérer un produit.
     *
     * @OA\Response(
     *     response=200,
     *     description="Retourne un produit",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"getProduct"}))
     *     )
     * )
     * 
     *  @OA\Tag(name="Products")
     */
    #[Route('/api/products/{id}', name: 'app_product_detail',methods:'GET')]
    public function getDetailProduct(Product $product,ProductRepository $productRepo,SerializerInterface $serializer,
    ):JsonResponse
    {
        $context = SerializationContext::create()->setGroups(['getProduct']);
        $jsonProduct = $serializer->serialize($product,'json',$context);
        return new JsonResponse($jsonProduct,Response::HTTP_OK,['accept'=>'json'],true);
    }

   
    /**
     * Cette méthode permet de supprimer un produit.
     * 
     * 
     *  @OA\Tag(name="Products")
     */
    #[Route('/api/products/{id}', name: 'app_delete_product',methods:'DELETE')]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer ce produit')]
    public function deleteProduct( Product $product, EntityManagerInterface $em, TagAwareCacheInterface $cachePool):JsonResponse
    {
        $cachePool->invalidateTags(["productsCache"]);
        $em->remove($product);
        $em->flush();
        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }

    

     
}   
