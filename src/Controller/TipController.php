<?php


namespace App\Controller;


use App\ApiError\ApiError;
use App\Entity\Tip;
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
 * @Route("/tips", name="tips_")
 * Class TipController
 * @package App\Controller
 */
class TipController extends AbstractController
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
                'user' => $relationshipCallback,
                'coach' => $relationshipCallback,
                'order' => $relationshipCallback
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total", name="total")
     * @return Response
     */
    public function totalTips(): Response
    {
        $tipsTotal = $this->em->getRepository(Tip::class)->count([]);

        return new ApiResponse($tipsTotal);
    }

    /**
     * @Route("/{tip}", name="tip")
     * @param Request $request
     * @param Tip|null $tip
     * @return Response
     * @throws ExceptionInterface
     */
    public function tip(Request $request, Tip $tip = null): Response
    {
        if (!$tip) {
            $errors[] = (new ApiError("Tip not found, or no Tip ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $params = explode(',', $request->get('params'));
        $params[] = 'id';

        $data = $this->serializer->normalize($tip, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}
