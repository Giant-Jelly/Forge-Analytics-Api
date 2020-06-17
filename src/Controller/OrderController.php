<?php

namespace App\Controller;

use App\ApiError\ApiError;
use App\Entity\Order;
use App\Response\ApiResponse;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/orders", name="orders_")
 * Class OrderController
 * @package App\Controller
 */
class OrderController extends AbstractController
{
    const STATUSES = [
        'pending' => Order::STATUS_PENDING,
        'available' => Order::STATUS_AVAILABLE,
        'assigned' => Order::STATUS_TAKEN,
        'completed' => Order::STATUS_COMPLETE,
        'cancelled' => Order::STATUS_CANCELLED,
        'refunded' => Order::STATUS_REFUNDED
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
                'coach' => $relationshipCallback
            ],
        ];

        $normalizer = new GetSetMethodNormalizer(null, null, null, null, null, $defaultContext);

        $this->serializer = new Serializer([$normalizer], $encoders);
    }

    /**
     * @Route("/total/{status}", name="total")
     * @param string|null $status
     * @return Response
     */
    public function totalOrders(string $status = null): Response
    {
        // Check if a status is being searched for, if it is, check that it exists in the statuses array, if it doesn't: return error
        if ($status && !array_key_exists($status, self::STATUSES)) {
            $errors[] = (new ApiError("Requested status not found, possible status are: " . implode(", ", array_keys(self::STATUSES)) . ".", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $criteria = [];
        // if there is a valid status being searched for, set it as the criteria
        if ($status) {
            $criteria = ['status' => self::STATUSES[$status]];
        }

        // Search for orders that match the criteria (it CAN be empty, in which case, give ALL orders)
        $orderTotal = $this->em->getRepository(Order::class)->count($criteria);

        return new ApiResponse($orderTotal);
    }

    /**
     * @Route("/total-amount/{status}", name="total_amount")
     * @param string|null $status
     * @return Response
     */
    public function totalOrdersAmount(string $status = null): Response
    {
        // Check if a status is being searched for, if it is, check that it exists in the statuses array, if it doesn't: return error
        if ($status && !array_key_exists($status, self::STATUSES)) {
            $errors[] = (new ApiError("Requested status not found, possible status are: " . implode(", ", array_keys(self::STATUSES)) . ".", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $criteria = [];
        // if there is a valid status being searched for, set it as the criteria
        if ($status) {
            $criteria = ['status' => self::STATUSES[$status]];
        }

        $orders = $this->em->getRepository(Order::class)->findBy($criteria);
        $ordersAmount = 0;

        /** @var Order $order */
        foreach ($orders as $order) {
            $ordersAmount += $order->getAmount();
        }

        return new ApiResponse($ordersAmount);
    }

    /**
     * @Route("/orders/{status}", name="orders")
     * @param Request $request
     * @param string $status
     * @return Response
     * @throws ExceptionInterface
     */
    public function orders(Request $request, string $status = null): Response
    {
        // Check if a status is being searched for, if it is, check that it exists in the statuses array, if it doesn't: return error
        if ($status && !array_key_exists($status, self::STATUSES)) {
            $errors[] = (new ApiError("Requested status not found, possible status are: " . implode(", ", array_keys(self::STATUSES)) . ".", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $criteria = [];
        // if there is a valid status being searched for, set it as the criteria
        if ($status) {
            $criteria = ['status' => self::STATUSES[$status]];
        }

        $orders = $this->em->getRepository(Order::class)->findBy($criteria);
        $params = explode(',', $request->get('params'));
        $params[] = 'id';

        $data = [];

        /** @var Order $order */
        foreach ($orders as $order) {
            $data[] = $this->serializer->normalize($order, null, [AbstractNormalizer::ATTRIBUTES => $params]);
        }
        return new ApiResponse($data);
    }

    /**
     * @Route("/{order}", name="order")
     * @param Request $request
     * @param Order $order
     * @return Response
     * @throws ExceptionInterface
     */
    public function order(Request $request, Order $order = null): Response
    {
        if (!$order) {
            $errors[] = (new ApiError("Order not found", 404))->getError();

            return new ApiResponse(null, 404, $errors);
        }

        $params = explode(',', $request->get('params'));

        $data = $this->serializer->normalize($order, null, [AbstractNormalizer::ATTRIBUTES => $params]);

        return new ApiResponse($data);
    }
}
