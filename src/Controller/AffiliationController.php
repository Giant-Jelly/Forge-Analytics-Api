<?php


namespace App\Controller;


use App\ApiError\ApiError;
use App\Entity\Affiliation;
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
 * @Route("/affiliations", name="affiliations_")
 * Class UserController
 * @package App\Controller
 */
class AffiliationController extends AbstractController
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
            return ['id' => $innerObject->getId()];
        };


        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'affiliate' => $relationshipCallback,
                'discountCode' => $relationshipCallback
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total", name="total")
     * @return Response
     */
    public function totalAffiliations(): Response
    {
        $affiliations = $this->em->getRepository(Affiliation::class)->count([]);

        return new ApiResponse($affiliations);
    }

    /**
     * @Route("/{affiliation}", name="affiliation")
     * @param Request $request
     * @param Affiliation|null $affiliation
     * @return Response
     * @throws ExceptionInterface
     */
    public function affiliation(Request $request, Affiliation $affiliation = null): Response
    {
        if (!$affiliation) {
            $errors[] = (new ApiError("Affiliation not found, or no affiliation ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        // Checks the URL for '?params=param,param,param' AND add the id and username by default
        $params = explode(',', $request->get('params'));
        $params[] = 'id';

        $data = $this->serializer->normalize($affiliation, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}
