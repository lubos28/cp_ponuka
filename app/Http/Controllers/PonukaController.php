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
        dd($templatePath);
    }

    $spreadsheet = IOFactory::load($templatePath);
    $sheet = $spreadsheet->getActiveSheet();

    // HLAVIČKA
    $menoZakaznika = trim(explode(',', $ponuka->customer_name)[0]);
    $zakaznik = \App\Models\Zakaznik::where('meno', $menoZakaznika)->first();

    if ($zakaznik) {
        $odberatel =
            $zakaznik->meno . "\n" .
            $zakaznik->ulica . "\n" .
            $zakaznik->psc . " " . $zakaznik->mesto . "\n" .
            "IČO: " . $zakaznik->ico . "   DIČ: " . $zakaznik->dic;
    } else {
        $odberatel = $ponuka->customer_name;
    }

    $sheet->setCellValue('G5', $odberatel);
    $sheet->getStyle('G5')->getAlignment()->setWrapText(true);

    $sheet->setCellValue('M3', $ponuka->id . '/2026');
    $sheet->setCellValue('E6', $ponuka->created_at ? $ponuka->created_at->format('d.m.Y') : now()->format('d.m.Y'));

    // POLOŽKY
$startRow = 10;
$pocetPoloziek = count($ponuka->polozky);

// vždy chceme: položky + 1 prázdna dvojica
$pocetDvojic = $pocetPoloziek + 1;

// šablóna už má 2 dvojice: 9-10 a 11-12
$extraDvojice = max(0, $pocetDvojic - 2);

if ($extraDvojice > 0) {
    $sheet->insertNewRowBefore(13, $extraDvojice * 2);
}

// pomocná funkcia: kopíruje formát bunku po bunke
$copyPairStyle = function ($sourceTop, $destTop) use ($sheet) {
    foreach (range('A', 'O') as $col) {
        $sheet->duplicateStyle($sheet->getStyle($col . $sourceTop), $col . $destTop);
        $sheet->duplicateStyle($sheet->getStyle($col . ($sourceTop + 1)), $col . ($destTop + 1));
    }

    $sheet->getRowDimension($destTop)->setRowHeight($sheet->getRowDimension($sourceTop)->getRowHeight());
    $sheet->getRowDimension($destTop + 1)->setRowHeight($sheet->getRowDimension($sourceTop + 1)->getRowHeight());
};

$sumCells = [];

// najprv nastavíme formát všetkým dvojiciam vrátane prázdnej
for ($i = 0; $i < $pocetDvojic; $i++) {
    $topRow = 9 + ($i * 2);

    // 0 = sivá z 9-10, 1 = biela z 11-12, 2 = sivá...
    $sourceTop = ($i % 2 === 0) ? 9 : 11;

    $copyPairStyle($sourceTop, $topRow);

    // vyčisti obsah dvojice, formát ostane
    foreach (range('A', 'O') as $col) {
        $sheet->setCellValue($col . $topRow, '');
        $sheet->setCellValue($col . ($topRow + 1), '');
    }
}

foreach ($ponuka->polozky as $index => $polozka) {
    $currentRow = $startRow + ($index * 2);
    $topRow = $currentRow - 1;

    $data = $polozka->product_data;

    if (is_string($data)) {
        $data = json_decode($data, true);
    }

    $merj = $data['merj'] ?? '';

    if ($data) {
        $sheet->setCellValue('A' . $topRow, $data['id_vyrobok'] ?? '');
    }

    $sheet->setCellValue('A' . $currentRow, $index + 1);

    $nazov = str_replace('/', '', $polozka->product_name);
    $sheet->setCellValue('C' . $topRow, trim($nazov));

    if ($data) {
        $sheet->setCellValue('C' . $currentRow, 'celé balenie: ' . ($data['mn_cele_balenie'] ?? ''));
    }

    $sheet->setCellValue('E' . $topRow, $polozka->quantity);
    $sheet->getStyle('E' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('# ##0 "' . $merj . '"');

    if ($data) {
        $sheet->setCellValue('E' . $currentRow, $data['rozmer_balenie'] ?? 0);
        $sheet->getStyle('E' . $currentRow)
            ->getNumberFormat()
            ->setFormatCode('# ##0 "' . $merj . '/ks"');
    }

    $sheet->setCellValue('G' . $topRow, $polozka->price_mj);
    $sheet->getStyle('G' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('# ##0.00 "€/' . $merj . '"');

    if ($data && ($data['merj'] ?? '') !== 'ks') {
        $rozmer = (float)($data['rozmer_balenie'] ?? 0);
        $cena = (float)($polozka->price_mj ?? 0);

        $sheet->setCellValue('G' . $currentRow, $cena * $rozmer);
        $sheet->getStyle('G' . $currentRow)
            ->getNumberFormat()
            ->setFormatCode('# ##0.00 "€/ks"');
    }

    $sheet->setCellValue('I' . $topRow, (float)($polozka->z_zaklad ?? 0));
    $sheet->getStyle('I' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('0 "%"');

    $sheet->setCellValue('K' . $topRow, (float)($polozka->z_objekt ?? 0));
    $sheet->getStyle('K' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('0 "%"');

    $sheet->setCellValue('M' . $topRow, '=G' . $topRow . '*(100-I' . $topRow . ')/100');
    $sheet->getStyle('M' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('# ##0.00 "€"');

    if (($data['merj'] ?? '') !== 'ks') {
        $sheet->setCellValue('M' . $currentRow, '=G' . $currentRow . '*(100-I' . $currentRow . ')/100');
        $sheet->getStyle('M' . $currentRow)
            ->getNumberFormat()
            ->setFormatCode('# ##0.00 "€"');
    }

    $sheet->setCellValue('O' . $topRow, '=E' . $topRow . '*M' . $topRow . '*(100-K' . $topRow . ')/100');
    $sheet->getStyle('O' . $topRow)
        ->getNumberFormat()
        ->setFormatCode('# ##0.00 "€"');

    $sumCells[] = 'O' . $topRow;
}

// SUMÁR
$summaryRow = 14 + ($extraDvojice * 2);

$sheet->setCellValue('M' . $summaryRow, 'spolu bez DPH');
$sheet->setCellValue('O' . $summaryRow, '=SUM(' . implode(',', $sumCells) . ')');

$sheet->setCellValue('M' . ($summaryRow + 1), 'DPH 23%');
$sheet->setCellValue('O' . ($summaryRow + 1), '=O' . $summaryRow . '*0.23');

$sheet->setCellValue('M' . ($summaryRow + 2), 'spolu s DPH');
$sheet->setCellValue('O' . ($summaryRow + 2), '=O' . $summaryRow . '+O' . ($summaryRow + 1));

foreach (['O' . $summaryRow, 'O' . ($summaryRow + 1), 'O' . ($summaryRow + 2)] as $cell) {
    $sheet->getStyle($cell)
        ->getNumberFormat()
        ->setFormatCode('# ##0.00 "€"');
}

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