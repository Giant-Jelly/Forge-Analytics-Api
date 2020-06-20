<?php


namespace App\Controller;


use App\ApiError\ApiError;
use App\Entity\DiscountCode;
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
 * @Route("/discountCodes", name="discountCodes_")
 * Class DiscountCodeController
 * @package App\Controller
 */
class DiscountCodeController extends AbstractController
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

        $affiliationCallback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            $affiliations = $outerObject->getAffiliation();
            $data = "No affiliation";

            if ($affiliations) {
                $data = ['id' => $affiliations->getId()];
            }

            return $data;
        };

        $defaultContext = [
            AbstractNormalizer::CALLBACKS => [
                'createdAt' => $dateCallback,
                'orders' => $relationshipCallback,
                'affiliation' => $affiliationCallback
            ]
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total", name="total")
     * @return Response
     */
    public function totalDiscountCodes(): Response
    {
        $disTotal = $this->em->getRepository(DiscountCode::class)->count([]);

        return new ApiResponse($disTotal);
    }

    /**
     * @Route("/discountCodes", name="discountCodes")
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     */
    public function discountCodes(Request $request): Response
    {
        $disCodes = $this->em->getRepository(DiscountCode::class)->findBy([]);
        $params = explode(',', $request->get('params'));

        $params[] = 'id';
        $params[] = 'code';
        $params[] = 'affiliation';

        $data = [];

        /** @var DiscountCode $discountcode */
        foreach ($disCodes as $disCode) {
            $data[] = $this->serializer->normalize($disCode, null, [AbstractNormalizer::ATTRIBUTES => $params]);
        }
        return new ApiResponse($data);
    }

    /**
     * @Route("/{discountCode}", name="discountCode")
     * @param Request $request
     * @param DiscountCode|null $discountCode
     * @return Response
     * @throws ExceptionInterface
     */
    public function discountCode(Request $request, DiscountCode $discountCode = null): Response
    {
        if (!$discountCode) {
            $errors[] = (new ApiError("Discount Code not found, or no Discount Code ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $params = explode(',', $request->get('params'));
        $params[] = 'id';

        $data = $this->serializer->normalize($discountCode, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}