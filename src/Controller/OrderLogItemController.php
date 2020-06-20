<?php

namespace App\Controller;

use App\ApiError\ApiError;
use App\Entity\OrderLogItem;
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
 * @Route("/orderLogItems", name="orderLogItems_")
 * Class OrderLogItemController
 * @package App\Controller
 */
class OrderLogItemController extends AbstractController
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
    public function totalOrderLogItems(): Response
    {
        $totalOlis = $this->em->getRepository(OrderLogItem::class)->count([]);

        return new ApiResponse($totalOlis);
    }

    /**
     * @Route("/orderLogItems", name="orderLogItems")
     * @param Request $request
     * @return Response
     * @throws ExceptionInterface
     */
    public function orderLogItems(Request $request): Response
    {
        $olis = $this->em->getRepository(OrderLogItem::class)->findBy([]);
        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'message';
        $params[] = 'order';

        $data = [];

        foreach ($olis as $oli) {
            $data[] = $this->serializer->normalize($oli, null, [AbstractNormalizer::ATTRIBUTES => $params]);
        }

        return new ApiResponse($data);
    }

    /**
     * @Route("/{orderLogItem}", name="orderLogItem")
     * @param Request $request
     * @param OrderLogItem|null $orderLogItem
     * @return Response
     * @throws ExceptionInterface
     */
    public function orderLogItem(Request $request, OrderLogItem $orderLogItem = null): Response
    {
        if (!$orderLogItem) {
            $errors[] = (new ApiError("Order Log Item not found, or no Order Log Item ID submitted", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $params = explode(',', $request->get('params'));
        $params[] = 'id';
        $params[] = 'message';
        $params[] = 'order';

        $data = $this->serializer->normalize($orderLogItem, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}