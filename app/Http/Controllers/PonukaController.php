<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ponuka;
use App\Models\PonukaItem;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PonukaController extends Controller
{
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Zistíme, či vytvárame novú ponuku alebo upravujeme existujúcu
            // (Zatiaľ tvoj JS posiela len nové, ale tu sme pripravení na update)
            if ($request->has('existujuce_id') && $request->existujuce_id) {
                $ponuka = Ponuka::find($request->existujuce_id);

                if (!$ponuka) {
                    $ponuka = new Ponuka();
                } else {
                    $ponuka->polozky()->delete();
                }
            } else {
                $ponuka = new Ponuka();
            }

            // 2. Uložíme hlavnú ponuku (Hlavička)
            $ponuka->customer_name = $request->zakaznik_meno; 
            
            // --- TOTO JE TO NOVÉ POLE ---
            $ponuka->title = $request->nazov_ponuky; 
            
            $ponuka->customer_data = $request->zakaznik_data; 
            $ponuka->discount_base = $request->zlava_zaklad ?? 0;
            $ponuka->discount_vol = $request->zlava_objem ?? 0;
            
            // Očistenie sumy od medzier a čiarky pre DB (decimal format)
            $total = str_replace(',', '.', str_replace(' ', '', $request->celkova_suma));
            $ponuka->total_sum = (float)$total;
            
            $ponuka->status = 'active';
            $ponuka->save();
            $ponuka->refresh();

            // 3. Uložíme položky
            if ($request->has('polozky') && is_array($request->polozky)) {
                foreach ($request->polozky as $item) {
                    $pItem = new PonukaItem();
                    $pItem->offer_id = $ponuka->id; 
                    $pItem->product_name = $item['nazov'];
                    $pItem->product_data = $item['full_data']; 
                    
                    $pItem->quantity = (float)str_replace(',', '.', $item['mnozstvo']);
                    $pItem->price_mj = (float)str_replace(',', '.', $item['cena_mj']);
                    $pItem->z_zaklad = $item['z1'] ?? 0;
                    $pItem->z_objekt = $item['z2'] ?? 0;
                    
                    $rowTotal = str_replace(',', '.', str_replace(' ', '', $item['spolu']));
                    $pItem->row_total = (float)$rowTotal;
                    $pItem->save();
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'id' => $ponuka->id]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Vymazanie ponuky
   // Premenuj metódu z destroy na delete (aby sedela s web.php)
public function delete($id)
{
    try {
        $ponuka = Ponuka::findOrFail($id);
        $ponuka->polozky()->delete(); // Zmaže položky (ak máš nastavený vzťah)
        $ponuka->delete();

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

// Pridaj chýbajúcu metódu pre hromadné mazanie
public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->ids;
            if (!empty($ids)) {
                // Zmažeme položky všetkých vybraných ponúk
                \App\Models\PonukaItem::whereIn('offer_id', $ids)->delete();
                // Zmažeme ponuky
                Ponuka::whereIn('id', $ids)->delete();
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 
            'message' => $e->getMessage()], 500);
        }
}


public function index()
{
        $zakaznici = \App\Models\Zakaznik::all(); 
        return view('ponuka', compact('zakaznici'));
    }

    public function archiv()
    {
        // Tu načítame ponuky zoraďené od najnovšej
        $ponuky = Ponuka::orderBy('created_at', 'desc')->get();
        return view('archiv', compact('ponuky'));
}

public function show($id)
{
    // Načítame ponuku aj s položkami
    $ponuka = Ponuka::with('polozky')->findOrFail($id);
    $zakaznici = \App\Models\Zakaznik::all(); 

    // Vrátime ten istý pohľad, kde tvoríš ponuky
    return view('ponuka', compact('ponuka', 'zakaznici'));
}

public function generatePdf($id)
{
    // 1. Načítame dáta z databázy aj s položkami
    $ponuka = Ponuka::with('polozky')->findOrFail($id);

    // 2. TENTO RIADOK TI CHÝBAL: Pripravíme PDF z view 'pdf_tlac'
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf_tlac', compact('ponuka'));

    // 3. Pošleme PDF do prehliadača
    return $pdf->stream('ponuka_'.$ponuka->id.'.pdf');
}
}