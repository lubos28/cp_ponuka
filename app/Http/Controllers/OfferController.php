<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OfferController extends Controller
{
    public function store(Request $request)
{
    try {
        return DB::transaction(function () use ($request) {
            
            // 1. Uložíme hlavnú ponuku (Tabuľka: offers)
            $offerId = DB::table('offers')->insertGetId([
                'customer_name' => $request->klient, // z JS posielaš 'klient'
                'discount_base' => $request->z_zaklad ?? 0,
                'discount_vol'  => $request->z_objem ?? 0,
                'total_sum'     => 0, // Dopočítame o pár riadkov nižšie
                'status'        => 'active',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            $totalSum = 0;

            // 2. Uložíme položky (Tabuľka: offer_items)
            foreach ($request->items as $item) {
                // Vyčistíme sumu riadku pre istotu (z "120.50 €" na "120.50")
                $rowTotal = (float)preg_replace('/[^\d.]/', '', str_replace(',', '.', $item['spolu']));
                $totalSum += $rowTotal;

                DB::table('offer_items')->insert([
                    'offer_id'     => $offerId,
                    'product_name' => $item['nazov'],    // z JS posielaš 'nazov'
                    'quantity'     => $item['mnozstvo'], // z JS posielaš 'mnozstvo'
                    'price_mj'     => $item['cena_mj'],
                    'z_zaklad'     => $item['zlava_z'],
                    'z_objekt'     => $item['zlava_o'],
                    'row_total'    => $rowTotal,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            // 3. Aktualizujeme celkovú sumu
            DB::table('offers')->where('id', $offerId)->update(['total_sum' => $totalSum]);

            return response()->json(['success' => true, 'id' => $offerId]);
        });
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}