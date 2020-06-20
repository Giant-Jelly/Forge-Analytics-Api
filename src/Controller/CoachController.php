<?php


namespace App\Controller;


use App\ApiError\ApiError;
use App\Entity\Coach;
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
 * @Route("/coaches", name="coaches_")
 * Class CoachController
 * @package App\Controller
 */
class CoachController extends AbstractController
{
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
            return ['id' => $innerObject->getId(), 'username' => $innerObject->getUsername()];
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'user' => $relationshipCallback,
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total", name="total")
     * @return Response
     */
    public function totalCoaches(): Response
    {
        $coaches = $this->em->getRepository(Coach::class)->count([]);

        return new ApiResponse($coaches);
    }

    /**
     * @Route("/{coach}", name="coach")
     * @param Request $request
     * @param Coach|null $coach
     * @return Response
     * @throws ExceptionInterface
     */
    public function user(Request $request, Coach $coach = null): Response
    {
        if (!$coach) {
            $errors[] = (new ApiError("Coach not found, or no Coach ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        // Checks the URL for '?params=param,param,param' AND add the id and username by default
        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'locale';
        $params[] = 'user';

        $data = $this->serializer->normalize($coach, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}