<?php

namespace App\Http\Controllers;

use App\Models\Zakaznik;
use Illuminate\Http\Request;

class ZakaznikController extends Controller
{
    // Zobrazenie zoznamu zákazníkov
    public function index()
    {
        $zakaznici = Zakaznik::orderBy('meno', 'asc')->get();
        return view('zakaznici', compact('zakaznici'));
    }

    // API pre našepkávač v Cenovej ponuke
    public function search(Request $request)
    {
        // JavaScript posiela 'q', tak ho tu musíme takto chytiť
        $q = $request->query('q'); 

        if (!$q) {
            return response()->json([]);
        }

        $results = Zakaznik::where('meno', 'LIKE', "%{$q}%")
            ->orWhere('ico', 'LIKE', "%{$q}%")
            ->limit(10)
            ->get();

        return response()->json($results);
    }

    // Uloženie nového zákazníka
    public function store(Request $request)
        {
            // Zozbierame úplne všetky polia, ktoré prišli z formulára
            $data = $request->all();

            // Ak by niekto nevyplnil zľavy, doplníme nuly, aby to nehádzalo chybu
            $data['default_discount_base'] = $request->input('default_discount_base') ?? 0;
            $data['default_discount_obj'] = $request->input('default_discount_obj') ?? 0;

            // Keďže máš v Modeli $fillable správne nastavený, toto uloží všetkých 14 polí
            \App\Models\Zakaznik::create($data);

            return redirect()->back()->with('success', 'Zákazník ' . $request->meno . ' bol úspešne pridaný so všetkými údajmi.');
    }
    //EDIT ZAKAZNIKA ULOZENEHO
    
    // 1. Táto funkcia zobrazí editačný formulár (zakaznici_edit.blade.php)
    // 1. Táto funkcia zobrazí editačný formulár (zakaznici_edit.blade.php)
    public function edit($id)
    {
        $zakaznik = Zakaznik::findOrFail($id);
        return view('zakaznici_edit', compact('zakaznik'));
    }

    // 2. Táto funkcia spracuje odoslaný formulár a uloží zmeny
    public function update(Request $request, $id)
    {
        $zakaznik = Zakaznik::findOrFail($id);
        
        // Uloží všetky polia, ktoré prišli z formulára
        $zakaznik->update($request->all());

        return redirect('/zakaznici')->with('success', 'Zákazník ' . $zakaznik->meno . ' bol úspešne upravený.');
    }

    public function destroy($id)
        {
            // Použijeme plnú cestu k modelu, aby sme predišli chybe "Class not found"
            $zakaznik = \App\Models\Zakaznik::find($id);

            if (!$zakaznik) {
                return response()->json(['error' => 'Zákazník neexistuje'], 404);
            }

            $zakaznik->delete();

            return response()->json(['success' => true]);
    }
}

