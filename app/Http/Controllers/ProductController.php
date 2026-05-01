<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

if (!class_exists('SimpleXLSX')) {
    require_once app_path('SimpleXLSX.php');
}

class ProductController extends Controller
{
   public function index() {
    try {
        $produkty = DB::table('products')->orderBy('id', 'asc')->get();
        } catch (\Exception $e) 
        {
         // Ak tabuľka neexistuje, vrátime prázdne pole, aby Blade nezhavaroval
         $produkty = collect(); 
        }
        return view('produkty', compact('produkty'));
    }

    public function import(Request $request) {
        if (!$request->hasFile('excel_file')) return redirect()->back()->with('error', 'Vyberte súbor');

        if ($xlsx = \SimpleXLSX::parse($request->file('excel_file')->getRealPath())) {
            $rows = $xlsx->rows();
            array_shift($rows); // Preskočiť hlavičku

            DB::beginTransaction();
            try {
                $count = 0;
                foreach ($rows as $row) {
                    if (empty($row[0])) continue;

                    // Ošetrenie cien a rozmerov (výmena čiarky za bodku)
                    $cena_mj = isset($row[16]) ? (float)str_replace(',', '.', $row[16]) : 0;
                    $rozmer_bal = isset($row[3]) ? (float)str_replace(',', '.', $row[3]) : 1;
                    if ($rozmer_bal <= 0) $rozmer_bal = 1;

                    DB::table('products')->updateOrInsert(
                        ['id_vyrobok' => $row[0]], 
                        [
                            'nazov'             => $row[1] ?? '',
                            'Rozmer'            => $row[2] ?? '',
                            'rozmer_balenie'    => $rozmer_bal,
                            'merj'              => $row[4] ?? '',
                            'balenie_typ'       => $row[5] ?? '',
                            'Popis1'            => $row[6] ?? '',
                            'popis2_skrateny'   => $row[7] ?? '',
                            'kratky_popis'      => $row[8] ?? '',
                            'technicke_info'    => $row[9] ?? '',
                            'cele_balenie'      => $row[10] ?? '',
                            'mn_cele_balenie'   => $row[11] ?? '',
                            'balenie_ks_karton' => $row[12] ?? '',
                            'hmotnost_objem'    => $row[13] ?? '',
                            'nazov_strany'      => $row[14] ?? '',
                            'cislo_strany'      => isset($row[15]) ? (int)$row[15] : null,
                            'cena_mj'           => $cena_mj,
                            'cena_mj_ks'        => isset($row[17]) ? (float)str_replace(',', '.', $row[17]) : 0,
                            'updated_at'        => now(),
                        ]
                    );
                    $count++;
                }
                DB::commit();
                return redirect()->back()->with('success', "Importovaných $count produktov.");
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Chyba: ' . $e->getMessage());
            }
        }
    }

    public function reset() {
        DB::table('products')->truncate();
        return redirect()->back()->with('success', 'Sklad bol kompletne vymazaný.');
    }

    public function search(Request $request) {
        $term = $request->query('q', '');
        $results = DB::table('products')
            ->where('nazov', 'LIKE', '%' . $term . '%')
            ->orWhere('id_vyrobok', 'LIKE', '%' . $term . '%')
            ->limit(50)->get();
        return response()->json($results);
    }
}