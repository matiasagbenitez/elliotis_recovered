<?php

use App\Models\Hash;
use App\Models\Tendering;

function verifyProducts(Tendering $tendering, Hash $hash) {

    // Array of product_id of the tendering
    $tenderingProductsId = $tendering->products->pluck('id')->toArray();
    sort($tenderingProductsId);

    // Array of product_id of the offer
    $offerProductsId = $hash->offer->products->pluck('id')->toArray();
    sort($offerProductsId);

    // If the offer has the same products as the tendering, then $products_ok = true
    $products_ok = $tenderingProductsId == $offerProductsId;

    return $products_ok;
}
