<?php

namespace Grechanyuk\Peshkariki;

use Grechanyuk\Peshkariki\Contracts\Peshkaricals;
use Grechanyuk\Peshkariki\Contracts\PeshkaricalsTakesPoint;
use Grechanyuk\Peshkariki\Exceptions\CourierAlreadyGo;
use Grechanyuk\Peshkariki\Exceptions\ExpiredTokenException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;

class Peshkariki
{
    private $token;
    private $url = 'https://api.peshkariki.ru/commonApi/';
    private $tokenFile = __DIR__ . '/../token';
    private $client;

    /**
     * Peshkariki constructor.
     */
    public function __construct()
    {
        $this->client = new Client(['base_uri' => $this->url]);

        $this->token = file_get_contents($this->tokenFile);
        if (empty($this->token)) {
            $this->auth();
        }

    }

    /**
     * @return bool
     */
    private function auth()
    {
        $request = new Request('POST', 'login');

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'login' => config('peshkariki.login'),
                    'password' => config('peshkariki.password')
                ]
            ]);

            if($response->getStatusCode() == 200) {
                $token = json_decode($response->getBody()->getContents());
                if ($token->success) {
                    file_put_contents($this->tokenFile, '');
                    file_put_contents($this->tokenFile, $token->response->token);
                    $this->token = $token->response->token;
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->auth();
            }
        } catch (GuzzleException $e) {
            //Ошибка соединения
        }

        return false;
    }

    /**
     * @param Peshkaricals|array $order
     * @param PeshkaricalsTakesPoint $takesPoint
     * @param bool $calculate
     * @param bool $clearing
     * @param bool $bonus_payment
     * @param int $cash
     * @param bool $who_pay
     * @param int $courier_additional
     * @param string $promoCode
     * @return bool|object
     */
    public function addDeliveryRequest($order, PeshkaricalsTakesPoint $takesPoint, bool $calculate = false, bool $clearing = false, bool $bonus_payment = false, $cash = 0, bool $who_pay = true, $courier_additional = 0, $promoCode = '')
    {
        $request = new Request('POST', 'addOrder');

        if(is_array($order)) {
            $options = [
                'inner_id' => 0,
                'comment' => '',
                'calculate' => $calculate,
                'clearing' => $clearing,
                'bonus_payment' => $bonus_payment,
                'cash' => $cash,
                'who_pay' => $who_pay,
                'courier_addition' => $courier_additional,
                'ewalletType' => config('peshkariki.ewalletType'),
                'ewallet' => config('peshkariki.ewallet'),
                'promo_code' => $promoCode,
                'city_id' => $order['city_id'],
                'route' => [
                    [
                        'name' => $takesPoint->getPeshkaricalsTakesPointName(),
                        'phone' => $takesPoint->getPeshkaricalsTakesPointTelephone(),
                        'city' => $takesPoint->getPeshkaricalsTakesPointSuburb(),
                        'street' => $takesPoint->getPeshkaricalsTakesPointStreet(),
                        'building' => $takesPoint->getPeshkaricalsTakesPointBuilding(),
                        'apartments' => $takesPoint->getPeshkaricalsTakesPointApartment(),
                        'subway_id' => $takesPoint->getPeshkaricalsTakesPointSubwayId(),
                        'time_from' => date('Y-m-d', strtotime($order['route']['time_from'])) . ' ' . config('peshkariki.time_from'),
                        'time_to' => date('Y-m-d H:i:s', strtotime($order['route']['time_to'])),
                        'target' => $takesPoint->getPeshkaricalsTakesPointComment(),
                        'return_dot' => 1
                    ],
                    $order['route']
                ]
            ];
        } else {
            $options = [
                'inner_id' => $order->getPeshkaricalsOrderId(),
                'comment' => $order->getPeshkaricalsShortDescription(),
                'calculate' => $calculate,
                'clearing' => $clearing,
                'bonus_payment' => $bonus_payment,
                'cash' => $cash,
                'who_pay' => $who_pay,
                'courier_addition' => $courier_additional,
                'ewalletType' => config('peshkariki.ewalletType'),
                'ewallet' => config('peshkariki.ewallet'),
                'promo_code' => $promoCode,
                'city_id' => $order->getPeshkaricalsCityId(),
                'route' => [
                    [
                        'name' => $takesPoint->getPeshkaricalsTakesPointName(),
                        'phone' => $takesPoint->getPeshkaricalsTakesPointTelephone(),
                        'city' => $takesPoint->getPeshkaricalsTakesPointSuburb(),
                        'street' => $takesPoint->getPeshkaricalsTakesPointStreet(),
                        'building' => $takesPoint->getPeshkaricalsTakesPointBuilding(),
                        'apartments' => $takesPoint->getPeshkaricalsTakesPointApartment(),
                        'subway_id' => $takesPoint->getPeshkaricalsTakesPointSubwayId(),
                        'time_from' => date('Y-m-d', strtotime($order->getPeshkaricalsClientTimeFrom())) . ' ' . config('peshkariki.time_from'),
                        'time_to' => date('Y-m-d H:i:s', strtotime($order->getPeshkaricalsClientTimeTo())),
                        'target' => $takesPoint->getPeshkaricalsTakesPointComment(),
                        'return_dot' => 1
                    ],
                    [
                        'name' => $order->getPeshkaricalsClientName(),
                        'phone' => $order->getPeshkaricalsClientTelephone(),
                        'city' => $order->getPeshkaricalsSuburb(),
                        'street' => $order->getPeshkaricalsClientStreet(),
                        'building' => $order->getPeshkaricalsClientBuilding(),
                        'apartments' => $order->getPeshkaricalsClientApartment(),
                        'subway_id' => $order->getPeshkaricalsClientSubwayId(),
                        'time_from' => $order->getPeshkaricalsClientTimeFrom(),
                        'time_to' => $order->getPeshkaricalsClientTimeTo(),
                        'target' => $order->getPeshkaricalsCommentToCourier(),
                        'return_dot' => 0,
                        'delivery_price_to_return' => $order->getPeshkaricalsReturnMoneyAmount(),
                        'items' => $order->getPeshkaricalsProducts()
                    ]
                ]
            ];
        }

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'orders' => [$options],
                    'token' => $this->token
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());

                if ($response->success) {
                    if ($calculate) {
                        return $response->response->delivery_price;
                    }

                    return $response->response;

                } elseif ($response->code == 12) {
                    throw new ExpiredTokenException();
                }
            }
        } catch (ExpiredTokenException $e) {
            if($this->auth()) {
                return $this->addDeliveryRequest($order, $takesPoint, $calculate, $clearing, $bonus_payment, $cash, $who_pay, $courier_additional, $promoCode);
            }
        } catch (GuzzleException $e) {
            //Ошибка запроса
        }

        return false;
    }

    /**
     * @param Peshkaricals $order
     * @return bool|string
     */
    public function cancelDeliveryRequest(Peshkaricals $order)
    {
        $request = new Request('POST', 'cancelOrder');

        try {
            $response = $this->client->send($request,
                [
                    'json' => [
                        'order_id' => $order->getPeshkaricalsOrderIdP(),
                        'token' => $this->token
                    ]
                ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());

                if ($response->success) {
                    return true;
                } elseif ($response->code == 12) {
                    throw new ExpiredTokenException();
                } elseif($response->code == 20) {
                    throw new CourierAlreadyGo();
                }
            }
        } catch (ExpiredTokenException $e) {
            if($this->auth()) {
                return $this->cancelDeliveryRequest($order);
            }
        } catch(CourierAlreadyGo $e) {
            return 'Отмена невозможна, курьер уже выехал';
        } catch (GuzzleException $e) {
            //Ошибка соединения
        }

        return false;
    }

    /**
     * @param Peshkaricals $order
     * @return bool
     */
    public function orderDetails(Peshkaricals $order) {
        $request = new Request('POST', 'orderDetail');

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'order_id' => $order->getPeshkaricalsOrderIdP(),
                    'token' => $this->token
                ]
            ]);

            if($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());

                if ($response->success) {
                    return $response->response;
                } elseif ($response->code == 12) {
                    throw new ExpiredTokenException();
                }
            }
        } catch (ExpiredTokenException $e) {
            if($this->auth()) {
                return $this->orderDetails($order);
            }
        } catch (GuzzleException $e) {
            //Ощибка соединения
        }

        return false;
    }

    /**
     * @return bool
     */
    public function checkBalance() {
        $request = new Request('POST', 'checkBalance');

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'token' => $this->token
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());
                if ($response->success) {
                    return $response->response;
                } elseif ($response->code == 12) {
                    throw new ExpiredTokenException();
                }
            }
        } catch (ExpiredTokenException $e) {
            if($this->auth()) {
                return $this->checkBalance();
            }
        } catch (GuzzleException $e) {
        }

        return false;
    }

    /**
     * @param $telephone
     * @return bool
     */
    public function checkTelephone($telephone) {
        $request = new Request('POST', 'checkPhone');

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'phone' => $telephone,
                    'token' => $this->token
                ]
            ]);

            if($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());

                if($response->success) {
                    foreach ($response->response as $item) {
                        if($item->success) {
                            return $item->description;
                        } else {
                            return $item->message;
                        }
                    }
                } elseif($response->code == 12) {
                    throw new ExpiredTokenException();
                }
            }

        } catch (ExpiredTokenException $e) {
            if($this->auth()) {
                return $this->checkTelephone($telephone);
            }
        } catch (GuzzleException $e) {
        }

        return false;
    }

    /**
     * @return bool
     */
    public function revokeToken() {
        $request = new Request('POST', 'revokeToken');

        try {
            $response = $this->client->send($request, [
                'json' => [
                    'token' => $this->token
                ]
            ]);
            if($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody()->getContents());
                if($response->success) {
                    return true;
                }
            }
        } catch (GuzzleException $e) {
        }

        return false;
    }
}