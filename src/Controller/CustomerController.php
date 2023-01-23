<?php

namespace App\Controller;

use App\Entity\UserCustomer;
use App\Repository\UserCustomerRepository;
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

class CustomerController extends AbstractController
{

    /**
     * Cette méthode permet de récupérer tous les clients liés à un utilisateurs.
     * 
     * @OA\Response(
     *     response=200,
     *     description="Retourne la liste des clients",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Product::class, groups={"getCustomer"}))
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
     * 
     *  @OA\Tag(name="Customers")
     */
    #[Route('/api/customers', name: 'app_customer',methods:'GET')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour acceder aux données d\'un client')]
    public function getAllCustomers( Request $request,UserCustomerRepository $customerRepo,
    SerializerInterface $serializer,TagAwareCacheInterface $cache): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $idCache = "getAllCustomers-" . $page . "-" . $limit;
        
        $jsonCustomerList = $cache->get($idCache, function (ItemInterface $item) use ($customerRepo, $page, $limit, $serializer) {
            $item->tag("customersCache");
            $customerList = $customerRepo->findAllWithPagination($page, $limit);
            $context = SerializationContext::create()->setGroups(['getCustomer']);
            

            return $serializer->serialize($customerList, 'json', $context);
        });
    
        return new JsonResponse( $jsonCustomerList, Response::HTTP_OK, [], true);
    }

    /**
      * Cette méthode permet de voir le détail d'un client.
      * 
      * 
      *  @OA\Tag(name="Customers")
      */
      #[Route('/api/customers/{id}', name: 'app_customer_detail',methods:'GET')]
      #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour acceder aux données d\'un client')]
      public function getDetailCustomer(UserCustomer $userCustomer,SerializerInterface $serializer,
      ):JsonResponse
      {
          $context = SerializationContext::create()->setGroups(['getCustomer']);
          $jsonUserCustomer = $serializer->serialize($userCustomer,'json',$context);
          return new JsonResponse( $jsonUserCustomer,Response::HTTP_OK,['accept'=>'json'],true);
      }

    /**
     * Cette méthode permet d'ajouter un client.
     * 
     * @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *         @OA\Property(property="firstname", type="string", example="John"),
     *         @OA\Property(property="lastname", type="string", example="Doe"),
     *         @OA\Property(property="email", type="string", example="John@gmail.com"),
     *      
     *       )
     *     )
     *   )
     * 
     * 
     *  @OA\Tag(name="Customers")
     */
    #[Route('/api/customers', name: 'app_add_customer',methods:'POST')]
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour ajouter un client')]
    public function addUserCustomer(
    Request $request, SerializerInterface $serializer, 
    EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator,ValidatorInterface $validator
    )
    {
    $userCustomer = $serializer->deserialize($request->getContent(),UserCustomer::class,'json');

    // On vérifie les erreurs
        $errors = $validator->validate($userCustomer);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }
        $userCustomer->setUserr($this->getUser());
        $em->persist($userCustomer);
        $em->flush();

        //On Renvoi le produit créé par l'utilisateur en json
        $context = SerializationContext::create()->setGroups(['getCustomer']);
        $jsonUserCustomer = $serializer->serialize($userCustomer,'json',$context);
        $location = $urlGenerator->generate('app_customer_detail', ['id' => $userCustomer->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse( $jsonUserCustomer, Response::HTTP_CREATED, ["Location" => $location], true);

    }

     /**
     * Cette méthode permet de supprimer un client.
     * 
     * 
     *  @OA\Tag(name="Customers")
     */

    #[Route('/api/customers/{id}', name: 'app_delete_customer',methods:'DELETE')]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour supprimer un client')]
    public function deleteCustomer( UserCustomer $userCustomer, EntityManagerInterface $em, TagAwareCacheInterface $cachePool):JsonResponse
    {
        $cachePool->invalidateTags(["customersCache"]);
        $em->remove($userCustomer);
        $em->flush();
        return new JsonResponse(null,Response::HTTP_NO_CONTENT);
    }
}
