<?php

namespace App\Controller;

use App\ApiError\ApiError;
use App\Entity\User;
use App\Response\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/user", name="user")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    const ROLES = [
      'user' => User::ROLE_USER,
      'coach' => User::ROLE_COACH,
      'headcoach' => User::ROLE_HEAD_COACH,
      'admin' => User::ROLE_ADMIN,
      'superadmin' => User::ROLE_SUPER_ADMIN
    ];

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        $encoders = [new JsonEncoder()];

        $dateCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format(\DateTime::ISO8601) : '';
        };

        $relationshipCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return ['id' => $innerObject->getId()];
        };

        $ordersCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            $orders = $outerObject->getOrders();
            $data = [];
            foreach ($orders as $order) {
                $data[] = [
                    'id' => $order->getId()
                ];
            }
            return $data;
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'user' => $relationshipCallback,
                'coach' => $relationshipCallback,
                'orders' => $ordersCallback
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total/{role}", name="total")
     * @param string|null $role
     * @return Response
     */
    public function totalUsers(string $role = null): Response
    {
        if ($role && !array_key_exists($role, self::ROLES)) {
            $errors[] = (new ApiError("Requested role not found, possible roles are: " . implode(", ", array_keys(self::ROLES)) . ".", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $criteria = [];
        if ($role) {
            $criteria = ['role' => self::ROLES[$role]];
        }

        $users = $this->em->getRepository(User::class)->count($criteria);

        return new ApiResponse($users);
    }

    /**
     * @Route("/{user}", name="user")
     * @param Request $request
     * @param User $user
     * @return Response
     * @throws ExceptionInterface
     */
    public function user(Request $request, User $user = null): Response
    {
        if (!$user) {
            $errors[] = (new ApiError("User not found, or no user ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        // Checks the URL for '?params=param,param,param' AND add the id and username by default
        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'username';

        $data = $this->serializer->normalize($user, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}