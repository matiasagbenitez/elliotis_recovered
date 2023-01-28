<?php

namespace App\Http\Services;

class SaleService
{
    public static function test()
    {

    }

    public static function bestTry()
    {

    }
}

// Try to add the products to the order finding the sublots with the same product and enough quantity
        // If there is no sublot with enough quantity, ignore the product
        // Quantities of the same product can be combined between sublots (example, if 3 products are needed and there are 2 sublots with 2 product each, the order will be created with 2 from one sublot and 1 from the other)

