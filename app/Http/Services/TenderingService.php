<?php

namespace App\Http\Services;

use App\Models\Offer;
use App\Models\User;
use App\Models\Product;
use App\Models\Tendering;
use App\Notifications\NewTenderingRequired;

class TenderingService
{
    public static function notificate($task_id, $task_name)
    {
        $products = Product::where('is_buyable', true)->where('real_stock', '<', 100)->get();
        $detail = [];

        foreach ($products as $product) {
            if ($product->real_stock < $product->minimum_stock - $product->reposition) {
                $reposition = $product->minimum_stock - $product->real_stock + $product->reposition;
            } else {
                $reposition = $product->reposition;
            }

            $detail[] = [
                'product_id' => $product->id,
                'reposition' => $reposition,
            ];
        }

        // Notificar al usuario 1
        $user = User::find(1);
        $user->notify(new NewTenderingRequired($task_id, $task_name, $detail));
    }

    public static function init(Tendering $tendering)
    {
        // OBTENEMOS LAS OFERTAS
        $answered_hashes = $tendering->hashes->where('answered', true)->where('cancelled', false);
        foreach ($answered_hashes as $hash) {
            $offers[] = $hash->offer;
        }
        TenderingService::classifyOffers($offers);
    }

    // ARREGLOS PARA CLASIFICAR LAS OFERTAS

    static $productsOK_quantitiesOK = [];
    static $productsOK_quantitiesNOTOK = [];
    static $productsNOTOK_quantitiesOK = [];
    static $productsNOTOK_quantitiesNOTOK = [];

    static $bestOffer = null;

    public static function classifyOffers($offers)
    {
        // foreach ($offers as $offer) {
        //     if ($offer->products_ok && $offer->quantities_ok) {
        //         self::$productsOK_quantitiesOK[] = $offer;
        //     } elseif ($offer->products_ok && !$offer->quantities_ok) {
        //         self::$productsOK_quantitiesNOTOK[] = $offer;
        //     } elseif (!$offer->products_ok && $offer->quantities_ok) {
        //         self::$productsNOTOK_quantitiesOK[] = $offer;
        //     } elseif (!$offer->products_ok && !$offer->quantities_ok) {
        //         self::$productsNOTOK_quantitiesNOTOK[] = $offer;
        //     }
        // }

        self::$bestOffer = self::chooseBestOffer($offers);

        // Get tendering
        if (self::$bestOffer) {
            $offer = Offer::find(self::$bestOffer->id);
            $tendering = $offer->hash->tendering;
            $tendering->bestOffer()->create([
                'tendering_id' => $tendering->id,
                'offer_id' => $offer->id,
            ]);
        }

        // dd(self::$bestOffer->id);
    }

    public static function chooseBestOffer($offers) {
        usort($offers, function ($offer1, $offer2) {
            $products1 = $offer1['products'];
            $products2 = $offer2['products'];

            $productCount1 = $products1->sum('pivot.quantity');
            $productCount2 = $products2->sum('pivot.quantity');

            if ($productCount1 != $productCount2) {
                return $productCount2 - $productCount1;
            }

            $pricePerProduct1 = $offer1['total'] / $productCount1;
            $pricePerProduct2 = $offer2['total'] / $productCount2;

            if ($pricePerProduct1 != $pricePerProduct2) {
                return $pricePerProduct1 - $pricePerProduct2;
            }

            $deliveryDateDifference = strtotime($offer1['delivery_date']) - strtotime($offer2['delivery_date']);

            if ($deliveryDateDifference != 0) {
                return $deliveryDateDifference;
            }

            return rand(0, 1) * 2 - 1;
        });

        return $offers[0];
    }

}
