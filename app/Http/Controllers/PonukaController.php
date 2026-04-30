<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ponuka;
use App\Models\PonukaItem;
use Illuminate\Support\Facades\DB;
// Pre PDF
use Barryvdh\DomPDF\Facade\Pdf;
// Pre Excel
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PonukaController extends Controller
    {
        public function index()
        {
            $zakaznici = \App\Models\Zakaznik::all(); 
            return view('ponuka', compact('zakaznici'));
}

public function archiv()
        {
            $ponuky = Ponuka::orderBy('created_at', 'desc')->get();
            return view('archiv', compact('ponuky'));
}

public function show($id)
        {
            $ponuka = Ponuka::with('polozky')->findOrFail($id);
            $zakaznici = \App\Models\Zakaznik::all(); 
            return view('ponuka', compact('ponuka', 'zakaznici'));
}

public function store(Request $request)
    {
    try {
        DB::beginTransaction();

        if ($request->prepisat && $request->existujuce_id) {
            $ponuka = Ponuka::find($request->existujuce_id);
            if (!$ponuka) $ponuka = new Ponuka();
        } else {
            $ponuka = new Ponuka();
        }

        // Mapovanie hlavnej ponuky (tabuľka offers)
        $ponuka->customer_name = $request->zakaznik_meno;
        $ponuka->title         = $request->nazov_ponuky;
        $ponuka->discount_base = $request->zlava_zaklad ?? 0;
        $ponuka->discount_vol  = $request->zlava_objem ?? 0;
        $ponuka->total_sum     = $request->celkova_suma;
        $ponuka->status        = 'draft';
        $ponuka->save();

        if ($request->has('polozky')) {
            // Vymažeme staré položky
            $ponuka->polozky()->delete(); 
            
            foreach ($request->polozky as $p) {
                // Mapovanie položiek (tabuľka offer_items)
                $ponuka->polozky()->create([
                    'product_name' => $p['nazov'],     // z JS 'nazov' -> do DB 'product_name'
                    'quantity'     => $p['mnozstvo'],  // z JS 'mnozstvo' -> do DB 'quantity'
                    'price_mj'     => $p['cena_mj'],   // z JS 'cena_mj' -> do DB 'price_mj'
                    'z_zaklad'     => $p['z1'] ?? 0,   // z JS 'z1' -> do DB 'z_zaklad'
                    'z_objekt'     => $p['z2'] ?? 0,   // z JS 'z2' -> do DB 'z_objekt'
                    'row_total'    => $p['spolu'],     // z JS 'spolu' -> do DB 'row_total'
                    'product_data' => $p['full_data'] ?? null,
        ]);
    }
}

DB::commit();

return response()->json([
            'success' => true,
            'id'      => $ponuka->id,
            'message' => $request->prepisat ? 'Ponuka aktualizovaná' : 'Nová ponuka vytvorená'
    ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false, 
            'message' => 'Chyba v DB: ' . $e->getMessage()
        ], 500);
    }
}      

public function exportExcel($id)
    {
    $ponuka = Ponuka::with('polozky')->findOrFail($id);
    $templatePath = storage_path('app/templates/template_cp.xlsx');

    if (!file_exists($templatePath)) {
        return abort(404, 'Šablóna sa nenašla.');
    }

    $spreadsheet = IOFactory::load($templatePath);
    $sheet = $spreadsheet->getActiveSheet();

    // Hlavné údaje
    $sheet->setCellValue('M3', $ponuka->id . '/2026');
    $sheet->setCellValue('B10', $ponuka->customer_name); 
    $sheet->setCellValue('B11', $ponuka->title);

    // Položky
    $startRow = 15; 
    foreach ($ponuka->polozky as $index => $polozka) {
        $currentRow = $startRow + $index;
        
        $sheet->setCellValue('A' . $currentRow, $index + 1);
        $sheet->setCellValue('B' . $currentRow, $polozka->product_name); // ZMENA na product_name
        $sheet->setCellValue('G' . $currentRow, $polozka->quantity);     // ZMENA na quantity
        $sheet->setCellValue('H' . $currentRow, $polozka->price_mj);
        $sheet->setCellValue('J' . $currentRow, $polozka->z_zaklad . '%'); // ZMENA na z_zaklad
        $sheet->setCellValue('L' . $currentRow, $polozka->row_total);    // ZMENA na row_total
    }

    $sheet->setCellValue('L45', $ponuka->total_sum); 

    $fileName = "Ponuka_" . $ponuka->id . ".xlsx";
    return response()->streamDownload(function () use ($spreadsheet) {
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }, $fileName, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

public function generatePdf($id)
    {
        // Načítame ponuku aj s anglickými položkami
        $ponuka = Ponuka::with('polozky')->findOrFail($id);
        
        // DÔLEŽITÉ: V blade súbore pdf_tlac.blade.php musíš teraz 
        // používať {{ $polozka->product_name }} namiesto {{ $polozka->nazov }}
        $pdf = Pdf::loadView('pdf_tlac', compact('ponuka'));
        return $pdf->stream('ponuka_'.$ponuka->id.'.pdf');
}

public function delete($id)
    {
        try {
            $ponuka = Ponuka::findOrFail($id);
            // Toto funguje, lebo to ide cez reláciu definovanú v modeli Ponuka
            $ponuka->polozky()->delete();
            $ponuka->delete();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
}

public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->ids;
            if (!empty($ids)) {
                // OPRAVA: Uisti sa, že v DB je to 'offer_id' (podľa tvojho modelu PonukaItem)
                PonukaItem::whereIn('offer_id', $ids)->delete();
                Ponuka::whereIn('id', $ids)->delete();
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
}
} // Koniec triedy