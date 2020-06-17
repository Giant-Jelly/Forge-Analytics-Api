<?php


namespace App\Controller;


use App\ApiError\ApiError;
use App\Entity\Payment;
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
 * @Route("/payments", name="payemnts")
 * Class PaymentController
 * @package App\Controller
 */
class PaymentController extends AbstractController
{
    const TYPES = [
        'coach' => Payment::TYPE_COACH,
        'affiliate' => Payment::TYPE_AFFILIATE
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
     * @Route("/total/{type}", name="total")
     * @param string|null $type
     * @return Response
     */
    public function totalPayments(string $type = null): Response
    {
        if ($type && !array_key_exists($type, self::TYPES)) {
            $errors[] = (new ApiError("Requested type not found, possible types are: " . implode(", ", array_keys(self::TYPES)) . ".", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $criteria = [];
        if ($type) {
            $criteria = ['role' => self::TYPES[$type]];
        }

        $payments = $this->em->getRepository(Payment::class)->count($criteria);

        return new ApiResponse($payments);
    }

    /**
     * @Route("/{payment}", name="payment")
     * @param Request $request
     * @param Payment|null $payment
     * @return Response
     * @throws ExceptionInterface
     */
    public function payment(Request $request, Payment $payment = null): Response
    {
        if (!$payment) {
            $errors[] = (new ApiError("Payment not found, or no payment ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        // Checks the URL for '?params=param,param,param' AND add the id and username by default
        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'user';

        $data = $this->serializer->normalize($payment, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}
