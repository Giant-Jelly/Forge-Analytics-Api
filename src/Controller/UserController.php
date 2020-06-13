<?php

namespace App\Controller;

use App\ApiError\ApiError;
use App\Entity\User;
use App\Response\ApiResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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

        $users = $this->em->getRepository(User::class)->findBy($criteria);

        return new ApiResponse($users);
    }
}