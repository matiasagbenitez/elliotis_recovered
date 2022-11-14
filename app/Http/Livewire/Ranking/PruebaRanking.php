<?php

namespace App\Http\Livewire\Ranking;

use Livewire\Component;
use App\Models\Tendering;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Date;

class PruebaRanking extends Component
{
    public $tendering, $answeredHashes, $offers;

    // Tipos de ofertas
    public $productsOkQuantitiesOk = [];
    public $productsOkQuantitiesNotOk = [];
    public $productsNotOkQuantitiesOk = [];
    public $productsNotOkQuantitiesNotOk = [];

    public function mount(Tendering $tendering)
    {
        $this->tendering = $tendering;
        $this->answeredHashes = $tendering->hashes->where('answered', true);

        foreach ($this->answeredHashes as $hash) {
            $this->offers[] = $hash->offer;
        }

        $this->clasificacion();
    }

    public function clasificacion()
    {
        foreach ($this->offers as $offer) {
            if ($offer->products_ok && $offer->quantities_ok) {
                $this->productsOkQuantitiesOk[] = $offer;
            } elseif ($offer->products_ok && !$offer->quantities_ok) {
                $this->productsOkQuantitiesNotOk[] = $offer;
            } elseif (!$offer->products_ok && $offer->quantities_ok) {
                $this->productsNotOkQuantitiesOk[] = $offer;
            } elseif (!$offer->products_ok && !$offer->quantities_ok) {
                $this->productsNotOkQuantitiesNotOk[] = $offer;
            }
        }
        // dd($this->productsOkQuantitiesOk);
        $this->seleccion();
    }

    public function seleccion()
    {
        $maxTenderingTotal = $this->tendering->total * 1.2;
        $maxTenderingDeliveryDate = Date::parse($this->tendering->end_date)->addDays(5);

        // Array de arrays con todos los tipos de ofertas
        $collection = [
            $this->productsOkQuantitiesOk,
            $this->productsOkQuantitiesNotOk,
            $this->productsNotOkQuantitiesOk,
            $this->productsNotOkQuantitiesNotOk
        ];

        $i = 0;

        // RECORREMOS LA COLECCIÓN DE OFERTAS
        foreach ($collection as $subCollection) {

            // RECORREMOS CADA SUBCOLECCIÓN DE OFERTAS PARA DETECTAR PRECIOS ADECUADOS
            foreach ($subCollection as $key => $offer) {

                if ($offer->products_ok && $offer->quantities_ok) {
                    // -------------------------------- TOTAL Y FECHA DE ENTREGA --------------------------------//
                    if ($offer->total > $maxTenderingTotal || $offer->delivery_date > $maxTenderingDeliveryDate) {
                        unset($subCollection[$key]);
                    }
                } else {
                    // -------------------------------- TOTAL --------------------------------//
                    $subtotalSimulated = 0;
                    foreach ($offer->products as $product) {
                        $subtotalSimulated += $product->pivot->quantity * $product->cost;
                    }
                    $ivaSimulated = $subtotalSimulated * 0.21;
                    $totalSimulated = $subtotalSimulated + $ivaSimulated;
                    $maxTenderingTotalSimulated = $totalSimulated * 1.2;
                    $offer->total > $maxTenderingTotalSimulated ? $expensiveOffer = true : $expensiveOffer = false;

                    // -------------------------------- CANTIDAD --------------------------------//
                    $minimumQuantity = $this->tendering->products->where('id', $product->id)->first()->pivot->quantity * 0.8;
                    $insufficientQuantity = false;

                    foreach ($offer->products as $product) {
                        if ($product->pivot->quantity < $minimumQuantity) {
                            $insufficientQuantity = true;
                            break;
                        }
                    }

                    // -------------------------------- FECHA DE ENTREGA --------------------------------//
                    $offer->delivery_date > $maxTenderingDeliveryDate ? $lateDeliveryDate = true : $lateDeliveryDate = false;


                    // -------------------------------- EVALUACIÓN FINAL --------------------------------//
                    if ($expensiveOffer || $insufficientQuantity || $lateDeliveryDate) {
                        unset($subCollection[$key]);
                    }
                }
            }

            if ($i == 0) {
                $collection[0] = $subCollection;
                $size0 = count($subCollection);
            } elseif ($i == 1) {
                $collection[1] = $subCollection;
                $size1 = count($subCollection);
            } elseif ($i == 2) {
                $collection[2] = $subCollection;
                $size2 = count($subCollection);
            } elseif ($i == 3) {
                $collection[3] = $subCollection;
                $size3 = count($subCollection);
            }

            $i++;
        }
        // dd('size[0]: ' . $size0 . ' size[1]: ' . $size1 . ' size[2]: ' . $size2 . ' size[3]: ' . $size3);
        // dd($collection);
        $this->eleccion($collection);
    }

    public function eleccion($receivedCollection)
    {
        $collection = $receivedCollection;
        $bestOffer = null;
        $equalTotalOffers = [];
        $equalPurchasesCountOffers = [];
        $equalDeliveryDateOffers = [];
        $equalCreatedAtOffers = [];
        $i = 0;

        for($i = 0; $i < 4; $i++) {

            // Tipo de oferta
            switch ($i) {
                case 0:
                    $offerType = 'Productos completos y cantidades correctas';
                    break;
                case 1:
                    $offerType = 'Productos completos y cantidades incorrectas';
                    break;
                case 2:
                    $offerType = 'Productos incompletos y cantidades correctas';
                    break;
                case 3:
                    $offerType = 'Productos incompletos y cantidades incorrectas';
                    break;
            }

            if (count($collection[$i]) > 0) {

                $offers = collect($collection[$i])->sortBy('total');

                if (count($offers) > 1) {

                    // Buscamos la oferta más barata. Si hay empate, las guardamos en un array
                    $bestOffer = $offers->first();
                    foreach ($offers as $offer) {
                        if ($offer->total < $bestOffer->total) {
                            $bestOffer = $offer;
                        } elseif ($offer->total > $bestOffer->total) {
                            unset($offers[$offer->id]);
                        } elseif ($offer->total == $bestOffer->total) {
                            $equalTotalOffers[] = $offer;
                        }
                    }
                    $equalTotals = count($equalTotalOffers) > 1 ? true : false;

                    // Si hay empate entre totales, elegimos aquella oferta cuyo proveedor tenga más compras realizadas
                    if ($equalTotals) {
                        $offers = collect($equalTotalOffers);
                        $bestOffer = $equalTotalOffers[0];
                        foreach ($equalTotalOffers as $offer) {
                            if ($offer->hash->supplier->total_purchases > $bestOffer->hash->supplier->total_purchases) {
                                $bestOffer = $offer;
                                if (count($equalPurchasesCountOffers) > 0) {
                                    $aux = $equalPurchasesCountOffers[0];
                                    if ($offer->hash->supplier->total_purchases > $aux->hash->supplier->total_purchases) {
                                        $equalPurchasesCountOffers = [];
                                        $equalPurchasesCountOffers[] = $offer;
                                    } elseif ($offer->hash->supplier->total_purchases == $aux->hash->supplier->total_purchases) {
                                        $equalPurchasesCountOffers[] = $offer;
                                    }
                                }
                            } elseif ($offer->hash->supplier->total_purchases == $bestOffer->hash->supplier->total_purchases) {
                                $equalPurchasesCountOffers[] = $offer;
                            }
                        }
                        $equalPurchasesCount = in_array($bestOffer, $equalPurchasesCountOffers) && count($equalPurchasesCountOffers) > 1  ? true : false;
                    }

                    // Si hay empate en cantidad de compras, elegimos aquella oferta con fecha de entrega más cercana
                    if ($equalTotals && $equalPurchasesCount) {
                        $offers = collect($equalPurchasesCountOffers)->sortBy('delivery_date');
                        $bestOffer = $offers->first();
                        foreach ($offers as $offer) {
                            if ($offer->delivery_date < $bestOffer->delivery_date) {
                                $bestOffer = $offer;
                            } elseif ($offer->delivery_date > $bestOffer->delivery_date) {
                                unset($offers[$offer->id]);
                            } elseif ($offer->delivery_date == $bestOffer->delivery_date) {
                                $equalDeliveryDateOffers[] = $offer;
                            }
                        }
                        $equalDeliveryDate = count($equalDeliveryDateOffers) > 1 ? true : false;
                    }

                    // Si hay empate en fecha de entrega, elegimos aquella oferta haya sido creada antes
                    if ($equalTotals && $equalPurchasesCount && $equalDeliveryDate) {
                        $offers = collect($equalDeliveryDateOffers)->sortBy('created_at');
                        $bestOffer = $offers->first();
                        foreach ($offers as $offer) {
                            if ($offer->created_at < $bestOffer->created_at) {
                                $bestOffer = $offer;
                            } elseif ($offer->created_at > $bestOffer->created_at) {
                                unset($offers[$offer->id]);
                            } elseif ($offer->created_at == $bestOffer->created_at) {
                                $equalCreatedAtOffers[] = $offer;
                            }
                        }
                        $equalCreatedAt = count($equalCreatedAtOffers) > 1 ? true : false;
                    }

                    // Si hay empate en fecha de creación, elegimos aleatoriamente
                    if ($equalTotals && $equalPurchasesCount && $equalDeliveryDate && $equalCreatedAt) {
                        $offers = collect($equalCreatedAtOffers);
                        $bestOffer = $offers->random();
                        $random = true;
                    }

                } else {
                    $bestOffer = $offers->first();
                }
            }

            if ($bestOffer != null) {

                if (isset($bestOffer)) {
                    echo('ID #' . $bestOffer->id . '<br>');
                    echo('La oferta pertenece a la categoría: ' . $offerType . '<br>');
                } else {
                    echo('No hay ofertas para evaluar <br>');
                }

                if (isset($equalTotals)) {
                    if ($equalTotals) {
                        echo('Hay empate en totales<br>');
                    } else {
                        echo('No hay empate en totales<br>');
                    }
                }

                if (isset($equalPurchasesCount)) {
                    if ($equalPurchasesCount) {
                        echo('Hay empate en cantidad de compras<br>');
                    } else {
                        echo('No hay empate en cantidad de compras<br>');
                    }
                }

                if (isset($equalDeliveryDate)) {
                    if ($equalDeliveryDate) {
                        echo('Hay empate en fecha de entrega<br>');
                    } else {
                        echo('No hay empate en fecha de entrega<br>');
                    }
                }

                if (isset($equalCreatedAt)) {
                    if ($equalCreatedAt) {
                        echo('Hay empate en fecha de creación<br>');
                    } else {
                        echo('No hay empate en fecha de creación<br>');
                    }
                }

                if (isset($random)) {
                    echo('Se eligió aleatoriamente<br>');
                }

                break;
            }
        }

    }


    public function render()
    {
        // return view('livewire.ranking.prueba-ranking');
        return view('livewire.ranking.prueba-ranking')->layout(GuestLayout::class);
    }
}
