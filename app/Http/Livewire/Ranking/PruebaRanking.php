<?php

namespace App\Http\Livewire\Ranking;

use Livewire\Component;
use App\Models\Tendering;
use App\Models\PurchaseOrder;
use App\View\Components\GuestLayout;
use Illuminate\Support\Facades\Date;

class PruebaRanking extends Component
{
    public $tendering, $answeredHashes, $offers;
    public $bestFinalOffer;
    public $bestFinalOfferHash;

    // Messages
    public $offerTypeOffer, $equalTotalsMessage, $equalPurchasesCountMessage, $equalDeliveryDateMessage, $equalCreatedAtMessage, $randomMessage;
    public $hasPurchaseOrderCreated;

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

        $this->hasPurchaseOrderCreated = $tendering->is_approved ? true : false;

        $this->clasificacion();
    }

    public function clasificacion()
    {
        foreach ($this->offers as $offer) {
            if ($offer->products_ok && $offer->quantities_ok && !$offer->hash->cancelled) {
                $this->productsOkQuantitiesOk[] = $offer;
            } elseif ($offer->products_ok && !$offer->quantities_ok && !$offer->hash->cancelled) {
                $this->productsOkQuantitiesNotOk[] = $offer;
            } elseif (!$offer->products_ok && $offer->quantities_ok && !$offer->hash->cancelled) {
                $this->productsNotOkQuantitiesOk[] = $offer;
            } elseif (!$offer->products_ok && !$offer->quantities_ok && !$offer->hash->cancelled) {
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
                    $this->offerTypeMessage = 'Productos completos y cantidades correctas';
                    break;
                case 1:
                    $this->offerTypeMessage = 'Productos completos y cantidades incorrectas';
                    break;
                case 2:
                    $this->offerTypeMessage = 'Productos incompletos y cantidades correctas';
                    break;
                case 3:
                    $this->offerTypeMessage = 'Productos incompletos y cantidades incorrectas';
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

            $this->bestFinalOffer = $bestOffer;
            if ($bestOffer) {
                $this->bestFinalOfferHash = $bestOffer->hash->hash;
                $this->tendering->update([
                    'is_analyzed' => true,
                ]);

                // Create a best offer for the tendering
                $this->tendering->bestOffer()->create([
                    'tendering_id' => $this->tendering->id,
                    'offer_id' => $bestOffer->id,
                ]);
            }

            if ($bestOffer != null) {

                if (isset($equalTotals)) {
                    if ($equalTotals) {
                        $this->equalTotalsMessage = 'Hubo empate en el total de las ofertas.';
                    } else {
                        $this->equalTotalsMessage = 'El proveedor ganó por ofrecer el mejor precio.';
                    }
                }

                if (isset($equalPurchasesCount)) {
                    if ($equalPurchasesCount) {
                        $this->equalPurchasesCountMessage = 'Hubo empate en la cantidad de compras del proveedor.';
                    } else {
                        $this->equalPurchasesCountMessage = 'El proveedor ganó por tener más compras realizadas a su favor.';
                    }
                }

                if (isset($equalDeliveryDate)) {
                    if ($equalDeliveryDate) {
                        $this->equalDeliveryDateMessage = 'Hubo empate en la fecha de entrega.';
                    } else {
                        $this->equalDeliveryDateMessage = 'El proveedor ganó por ofrecer una fecha de entrega más cercana.';
                    }
                }

                if (isset($equalCreatedAt)) {
                    if ($equalCreatedAt) {
                        $this->equalCreatedAtMessage = 'Hubo empate en la fecha de respuesta.';
                    } else {
                        $this->equalCreatedAtMessage = 'El proveedor ganó por ser el primero en responder.';
                    }
                }

                if (isset($random)) {
                    if ($random) {
                        $this->randomMessage = 'La oferta se eligió aleatoriamente.';
                    }
                }

                break;
            }
        }

    }

    public function createPurchaseOrder()
    {
        try {
            $purchaseOrder = PurchaseOrder::create([
                'user_id' => auth()->user()->id,
                'supplier_id' => $this->bestFinalOffer->hash->supplier->id,
                'registration_date' => now(),
                'subtotal' => $this->bestFinalOffer->subtotal,
                'iva' => $this->bestFinalOffer->iva,
                'total' => $this->bestFinalOffer->total,
                'observations' => $this->bestFinalOffer->observations
            ]);

            // Create a purchase order detail for each item in best offer
            foreach ($this->bestFinalOffer->products as $product) {
                $purchaseOrder->products()->attach($product->id, [
                    'quantity' => $product->pivot->quantity,
                    'price' => $product->pivot->price,
                    'subtotal' => $product->pivot->subtotal
                ]);
            }

            // Save
            $purchaseOrder->save();

            $this->tendering->update([
                'is_approved' => true,
            ]);

            $this->emit('success', 'Orden de compra creada correctamente.');
        } catch (\Exception $e) {
            $this->emit('error', 'Ocurrió un error al crear la orden de compra.');
        }
    }


    public function render()
    {
        // return view('livewire.ranking.prueba-ranking');
        return view('livewire.ranking.prueba-ranking')->layout('layouts.guest');
    }
}
