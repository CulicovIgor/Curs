<?php
/**
 * Created by PhpStorm.
 * User: igory
 * Date: 4/4/17
 * Time: 4:54 PM
 */

namespace AppBundle\Controller\PublicAPI;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Request\ParamFetcherInterface;

class AllController extends FOSRestController
{

    /**
     * ### Succeseful response ###
     *     {
     *          "meta": {
     *              "code": 200,
     *              "flush": []
     *          },
     *          "response": {
     *              "me": {
     * @see sandbox..
     *              }
     *          }
     *      }
     *
     * @ApiDoc(
     *  section = "All",
     *  resource = true,
     *  statusResponse = {
     *    200 = "Returned when successful",
     *    403 = "Returned when something wrong, details in message"
     *  },
     * )
     *
     * @Get("/me")
     * @return array
     */
    public function getMeAction()
    {
        $data =
        "\"user_id\":\"238\",
        \"nickname\":\"Jora Jora\",
        \"email\":\"jora444@gmail.com\",
        \"lang\":\"ru\",
        \"accounts\":[
        {
            \"account_id\":\"1\",
            \"account_title\":\"MAIB Card\",
            \"account_type\":\"1\",
            \"account_cur\":\"MDL\",
            \"account_amount\":\"450.25\",
            \"account_icon\":\"http://novaforen.com/finanses/icons/account_card.png\"
        },
            {
                \"account_id\":\"2\",
                \"account_title\":\"Наличные Бумажник\",
                \"account_type\":\"2\",
                \"account_cur\":\"MDL\",
                \"account_amount\":\"2570.35\",
                \"account_icon\":\"http://novaforen.com/finanses/icons/account_numerar.png\"
            },
                {
                    \"account_id\":\"3\",
                    \"account_title\":\"Сбережения Матрас\",
                    \"account_type\":\"2\",
                    \"account_cur\":\"USD\",
                    \"account_amount\":\"910.75\",
                    \"account_icon\":\"http://novaforen.com/finanses/icons/account_numerar.png\"
                }
            ]
        }";
        return $this
            ->container
            ->get('api_formatter')
            ->getResponseForRetrivedData(
                $data
            );
    }
    /*
    GET /currencies
    {
        "currencies":[
        {
            "name": "MDL",
          "ex_to_usd": "19.8"
        },
        {
            "name": "USD",
          "ex_to_usd": "1.0"
        },
        {
            "name":"EUR",
          "ex_to_usd":"1.07"
        },
        {
            "name":"RUB",
          "ex_to_usd":"57.5"
        },
        {
            "name":"UAH",
          "ex_to_usd":"25.2"
        },
        {
            "name":"RON",
          "ex_to_usd":"4.05"
        }
      ]
    }

    GET /events/types
    {
        "events":[
        {
            "event_type":"1",
          "event_title":"Заработок",
          "event_icon":"http://novaforen.com/finanses/icons/money_earned.png"
        },
        {
            "event_type":"2",
          "event_title":"Продукты",
          "event_icon":"http://novaforen.com/finanses/icons/money_lost.png"
        },
        {
            "event_type":"3",
          "event_title":"Коммунальные расходы",
          "event_icon":"http://novaforen.com/finanses/icons/money_lost.png"
        }
      ]

    }

    GET /accounts/{account_id}/history?limit=limit&offset=offset
        Header: auth
    {
        "user_id":"238",
      "account_id":"1",
      "account_title":"MAIB Card",
      "account_type":"1",
      "account_cur":"MDL",
      "account_amount":"450.25",
      "account_icon":"http://novaforen.com/finanses/icons/account_card.png",
      "history":[
        {
            "time":"1490607227",
          "event_type":"1",
          "amount":"200",
          "event_icon":"http://novaforen.com/finanses/icons/money_lost.png",
          "event_title":"Заработок",
          "event_desc":"Получил на чай"
        },
        {
            "time":"1490507235",
          "event_type":"2",
          "amount":"-372.48",
          "event_icon":"http://novaforen.com/finanses/icons/money_lost.png",
          "event_title":"Продукты",
          "event_desc":"Купил хавчика"
        },
        {
            "time":"1490307000",
          "event_type":"1",
          "amount":"200.75",
          "event_icon":"http://novaforen.com/finanses/icons/money_earned.png",
          "event_title":"Заработок",
          "event_desc":"Нашел на улице"
        },
        {
            "time":"1480606000",
          "event_type":"3",
          "amount":"-992",
          "event_icon":"http://novaforen.com/finanses/icons/money_lost.png",
          "event_title":"Коммунальные расходы",
          "event_desc":"Заплатил за коммунальные услуги"
        },
        {
            "time":"1470600041",
          "event_type":"1",
          "amount":"221.45",
          "event_icon":"http://novaforen.com/finanses/icons/money_earned.png",
          "event_title":"Заработок",
          "event_desc":"Сэкономил"
        }
      ]
    }

    GET /accounts/types
    {
        "total":"3",
      "types":[
        {
            "account_type":"1",
          "account_icon":"http://novaforen.com/finanses/icons/account_card.png"
        },
        {
            "account_type":"2",
          "account_icon":"http://novaforen.com/finanses/icons/account_numerar.png"
        }
      ]
    }

    POST:
    POST /login
    {
        "auth":"yRQYnWzskCZUxPwaQupWkiUzKELZ49eM7oWxAQK_ZXw",
      "exp":"1490592450"
    }

    POST /accounts/new
    Header: auth
    Params: title, desc, currency, amount, type
    {
        "user_id":"238",
      "account_id":"1",
      "account_title":"MAIB Card",
      "account_type":"1",
      "account_cur":"MDL",
      "account_amount":"450.25",
      "account_icon":"http://novaforen.com/finanses/icons/account_card.png"
    }

    POST /accounts/{account_id}/add
    Header: auth
    Params: event_type, event_desc, amount
    {
        "time":"1490507235",
      "event_type":"2",
      "amount":"-372.48",
      "event_icon":"http://novaforen.com/finanses/icons/money_lost.png",
      "event_title":"Продукты",
      "event_desc":"Купил хавчика"
    }
    */
}