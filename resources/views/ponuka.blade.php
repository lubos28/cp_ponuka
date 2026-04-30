<!DOCTYPE html>
<html lang="sk">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cenová Ponuka | DÖRKEN</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
    
        :root {
            --dorken: #003399;
            --bg: #f8f9fa;
            --success: #28a745;
            --danger: #dc3545;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            margin: 0;
            font-size: 13px;
            color: #333;
        }

        /* FIXNÁ HLAVIČKA */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: var(--bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar {
            background: var(--dorken);
            color: #fff;
            padding: 10px 0; /* Kompaktná výška */
        }

        .navbar-inner {
            max-width: 1500px; /* Zjednotená šírka na 1500px */
            margin: auto;
            padding: 0 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-inner b {
            font-size: 14px; /* Logo Dörken */
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            font-size: 11px; /* Malé, čisté písmo */
            opacity: .8;
        }

        .nav-links a.active {
            opacity: 1 !important;
            border-bottom: 2px solid white;
            padding-bottom: 2px;
        }

        .header-content {
            max-width: 1550px;
            margin: 0 auto;
            background: white;
            padding: 15px 25px 0 25px;
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--dorken);
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .btn-save, .btn-pdf {
            border: none;
            color: white;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }

        .btn-save { background: var(--success); }
        .btn-pdf { background: var(--danger); }

        .header-grid {
            display: grid;
            grid-template-columns: 3fr 2fr;
            gap: 20px;
            margin-bottom: 15px;
            align-items: end;
        }

        label {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: var(--dorken);
            margin-bottom: 3px;
        }

        input {
            width: 100%;
            padding: 7px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
        }

        /* TABUĽKA */
        .table-container {
            max-width: 1550px;
            margin: 0 auto;
            background: white;
            padding: 0 25px 25px 25px;
        }

        table { width: 100%; border-collapse: collapse; }

        thead th {
            position: sticky;
            top: 135px;
            background: #f1f3f5;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            z-index: 10;
            border-bottom: 2px solid #ddd;
        }

        td { padding: 8px 5px; border-bottom: 1px solid #eee; vertical-align: top; }

        /* NAŠEPKÁVAČ */
        .search-results {
            position: absolute;
            background: white;
            border: 1px solid #ccc;
            width: 100%;
            z-index: 9999;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            max-height: 250px;
            overflow-y: auto;
            display: none;
        }

        .search-item {
            padding: 10px 15px; /* Trochu viac miesta pre prsty/myš */
            cursor: pointer;
            border-bottom: 1px solid #f0f0f0;
            display: flex; /* Zabezpečí správne zarovnanie vnútorných divov */
            transition: background 0.2s ease; /* Jemný prechod farieb */
        }

        .search-item:hover, .search-item.selected {
            background-color: #eef2f7;
            border-left: 3px solid var(--dorken);
        }

        .total-box {
            margin-top: 20px;
            text-align: right;
            background: var(--dorken);
            color: white;
            padding: 15px;
            font-size: 20px;
            font-weight: bold;
        }

        .btn-add {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 2px dashed #aaa;
            background: none;
            cursor: pointer;
            font-weight: bold;
            color: #666;
        }

        .info-sub { font-size: 10px; color: #888; display: block; margin-top: 2px; }

        @media print {
            .sticky-header, .btn-add, .btn-save, .btn-pdf { display: none !important; }
            .table-container { padding: 0; }
        }

        .search-item.selected {
            background-color: #007bff !important; /* Modrá farba je štandard pre "vybrané" */
            color: #fff !important; /* Biely text na modrom pozadí */
        }

        .search-item.selected .cust-city {
            color: #e0e0e0 !important;
        }

    .search-item:hover {
         background-color: #f1f3f5;
    }

    /* Posledný riadok nebude mať čiaru */
    .search-item:last-child {
        border-bottom: none;
    }

    body {
    -webkit-tap-highlight-color: transparent;
    touch-action: manipulation;
     overscroll-behavior: none;
    }   

    .table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    }
    @media (max-width: 768px) {

    thead th {
        font-size: 10px;
        padding: 6px 4px;
    }

    td {
        padding: 6px 4px;
        font-size: 12px;
    }

    input {
        padding: 6px;
        font-size: 16px;
    }

    .small-info {
        font-size: 9px;
    }
    }
    @media (max-width: 768px) {

        td button {
            font-size: 18px;
            padding: 6px 10px;
        }

    }

    input:focus {
    outline: 2px solid #00339933;
    border-color: #003399;
    }
    
    
    </style>
   
</head>
<body>

<div id="app-lock" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.4);
    z-index:99999;
    justify-content:center;
    align-items:center;
    color:white;
    font-size:18px;
    font-weight:bold;
">
    ⏳ Ukladám ponuku...
</div>         

<input type="hidden" id="existujuce_id" value="{{ $ponuka->id ?? '' }}">

<div class="sticky-header">
    <div class="navbar">
        <div class="navbar-inner">
            <b>DÖRKEN</b>
           <div class="nav-links">
                <a href="{{ url('/ponuka') }}" class="active">PONUKA</a>
                <a href="{{ url('/zakaznici') }}">ZÁKAZNÍCI</a>
                <a href="{{ url('/produkty') }}">PRODUKTY</a>
                <a href="{{ url('/archiv') }}">ARCHÍV</a>
            </div>
        </div>
    </div>

    <div class="header-content">
        <div class="action-bar" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px;">
            <h3 style="margin:0;color:var(--dorken)">
                {{ isset($ponuka) ? 'Úprava ponuky #' . $ponuka->id : 'Nová cenová ponuka' }}
            </h3>
            
            <div class="action-buttons" style="display: flex; gap: 10px;">
                <button class="btn-pdf" onclick="generatePDF()" style="padding: 8px 15px; cursor: pointer;">🖨 PDF / Tlač</button>
                
                @if(isset($ponuka))
                    <button onclick="saveOffer(true)" style="background: #ffc107; color: #000; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                        🔄 AKTUALIZOVAŤ (Prepísať)
                    </button>
                    <button onclick="saveOffer(false)" style="background: #17a2b8; color: #fff; border: none; padding: 8px 15px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                        ➕ ULOŽIŤ AKO NOVÚ
                    </button>
                @else
                    <button id="mainSaveBtn" onclick="saveOffer(false)" style="background: #28a745; color: white; border: none; padding: 8px 20px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                        💾 ULOŽIŤ PONUKU
                    </button>
                @endif      
            </div>
        </div>

        <div class="compact-header" style="display: flex; gap: 15px; align-items: flex-end; background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #ddd; margin: 10px 20px;">
            
            <div style="flex: 4; position: relative;">
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 5px; color: #555;">ZÁKAZNÍK (MENO / MESTO)</label>
                <input type="text" id="klient" autocomplete="off" 
                    placeholder="Hľadať klienta..." 
                    value="{{ $ponuka->customer_name ?? '' }}"
                    oninput="searchCustomer(this)" 
                    onkeydown="navigateCustomer(event)"
                    onfocus="this.select(); searchCustomer(this)"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
                <div id="customer-results" class="search-results" style="position: absolute; width: 100%; z-index: 1001;"></div>
            </div>

            <div style="flex: 3;">
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 5px; color: #555;">NÁZOV PROJEKTU / POZNÁMKA</label>
                <input type="text" id="nazov_ponuky" 
                    placeholder="napr. RD Stupava" 
                    value="{{ $ponuka->title ?? '' }}"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="width: 70px;">
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 5px; color: #555;">ZĽAVA Z%</label>
                <input type="number" id="z_zaklad" 
                    value="{{ $ponuka->discount_base ?? 0 }}" 
                    oninput="applyGlobal()" 
                    onfocus="this.select()"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
            </div>

            <div style="width: 70px;">
                <label style="display: block; font-size: 11px; font-weight: bold; margin-bottom: 5px; color: #555;">ZĽAVA O%</label>
                <input type="number" id="z_objem" 
                    value="{{ $ponuka->discount_vol ?? 0 }}" 
                    oninput="applyGlobal()" 
                    onfocus="this.select()"
                    style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; text-align: center;">
            </div>
        </div>
    </div>



    <div class="table-container" style="padding-bottom: 0;">
        <table>
            <thead>
                <tr>
                    <th style="width:40%">Produkt</th>
                    <th style="width:8%">Množstvo</th>
                    <th style="width:10%">Cena MJ</th>
                    <th style="width:8%">Zľava Z.</th>
                    <th style="width:8%">Zľava O.</th>
                    <th style="width:16%">Spolu</th>
                    <th style="width:5%"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="table-container">
    <table>
        <tbody id="tbody"></tbody>
    </table>
    <button class="btn-add" onclick="addRow()">+ PRIDAŤ PRODUKT (Alt+P)</button>
    <div class="total-box">
        CELKOM BEZ DPH: <span id="grandTotal">0.00</span> €
    </div>
</div>


<script>
const vsetciZakaznici = @json($zakaznici ?? []);
const BASE_URL = "{{ url('/') }}";
let rowId = 0;
let selectedCustomerFull = null;
let searchTimeout = null;

// NAČÍTANIE DÁT Z ARCHÍVU
const editovanaPonuka = @json($ponuka ?? null);

window.onload = () => {
    if (editovanaPonuka && editovanaPonuka.polozky) {
        document.getElementById('tbody').innerHTML = ''; 
        
        editovanaPonuka.polozky.forEach(item => {
            const tr = addRow();
            const currId = tr.id.replace('row-', '');
            
            // 1. OŠETRENIE JSON CHYBY:
            let pData = item.product_data;
            
            // Ak je to text, musíme ho parsovať. Ak je to už objekt, necháme ho tak.
            if (typeof pData === 'string') {
                try {
                    pData = JSON.parse(pData);
                } catch (e) {
                    console.error("Chyba pri parsovaní:", e);
                    pData = {}; 
                }
            }

            // 2. PRIRADENIE (Dôležité pre saveOffer)
            // Ukladáme to ako objekt, saveOffer si s tým poradí
            tr.dataset.fullProduct = typeof pData === 'object' ? JSON.stringify(pData) : pData;

            // 3. VYPLNENIE POLÍ
            tr.querySelector('.p-name').value = item.product_name;
            tr.querySelector('.p-qty').value = item.quantity;
            tr.querySelector('.p-price').value = parseFloat(item.price_mj).toFixed(2);
            tr.querySelector('.p-z1').value = item.z_zaklad;
            tr.querySelector('.p-z2').value = item.z_objekt;
            
            // 4. VIZUÁLNE DOPLNENIE Z OBJEKTU pData
            if (pData) {
                document.getElementById(`ean-${currId}`).innerText = 'Kód: ' + (pData.id_vyrobok || '-');
                document.getElementById(`pack-${currId}`).innerText = 'bal: ' + (pData.mn_cele_balenie || '-');
                
                const packSize = parseFloat(String(pData.rozmer_balenie ?? 1).replace(',', '.')) || 1;
                tr.querySelector('.p-pack').value = packSize;
            }
            
            calc(currId);
        });
        recalcTotal();
    } else {
        addRow();
        const kInput = document.getElementById('klient');
        if(kInput) kInput.focus();
    }
};

/* --- ZÁKAZNÍK SEARCH --- */
/* --- ZÁKAZNÍK: VYHĽADÁVANIE, NAVIGÁCIA, VÝBER --- */

function searchCustomer(input) {
    hideAllResults();
    const q = input.value.trim().toLowerCase();
    const box = document.getElementById('customer-results');
    
    // Filtrovanie
    const filtered = vsetciZakaznici.filter(c => {
        const meno = (c.meno || "").toLowerCase();
        const mesto = (c.mesto || "").toLowerCase();
        const ico = (c.ico || "").toLowerCase();
        if (!q) return true;
        return meno.includes(q) || mesto.includes(q) || ico.includes(q);
    }).slice(0, 10);

    box.innerHTML = '';
    if (filtered.length === 0) {
        box.style.display = 'none';
        return;
    }

    filtered.forEach((c, i) => {
        const div = document.createElement('div');
        div.className = 'search-item' + (i === 0 ? ' selected' : '');
        
        // VIZUÁL: Meno tučným, Mesto normálnym (bez zátvorky)
        div.innerHTML = `
            <div style="display: flex; justify-content: space-between; width: 100%; align-items: center;">
                <span class="c-name" style="font-weight: bold; color: #000;">${c.meno || 'Bez mena'}</span>
                <span class="c-city" style="font-weight: normal; color: #666; font-size: 0.9em; margin-left: 10px;">${c.mesto || ''}</span>
            </div>
        `;
        
        div.onmousedown = (e) => {
            e.preventDefault();
            selectCustomer(c);
        };
        box.appendChild(div);
    });
    box.style.display = 'block';
}

function selectCustomer(c) {
    if (!c) return;
    selectedCustomerFull = c; 
    
    // Zápis: Meno, Mesto
    const meno = c.meno || "";
    const mesto = c.mesto ? `, ${c.mesto}` : "";
    document.getElementById('klient').value = meno + mesto;

    // Dotiahnutie zliav
    document.getElementById('z_zaklad').value = c.default_discount_base || 0;
    document.getElementById('z_objem').value = c.default_discount_obj || 0;
    
    document.getElementById('customer-results').style.display = 'none';
    
    // Prepočet riadkov (uisti sa, že máš funkciu applyGlobal)
    if (typeof applyGlobal === "function") applyGlobal();

    // SKOK na produkt a OZNAČENIE (Select)
    setTimeout(() => {
        const firstProd = document.querySelector('#tbody .p-name');
        if (firstProd) {
            firstProd.focus();
            firstProd.select();
        }
    }, 50);
}

function navigateCustomer(e) {
    const box = document.getElementById('customer-results');
    const items = box.querySelectorAll('.search-item');
    if (box.style.display === 'none' || !items.length) return;

    let current = [...items].findIndex(i => i.classList.contains('selected'));

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        items[current]?.classList.remove('selected');
        const next = items[(current + 1) % items.length];
        next.classList.add('selected');
        next.scrollIntoView({ block: 'nearest' });
    } 
    else if (e.key === 'ArrowUp') {
        e.preventDefault();
        items[current]?.classList.remove('selected');
        const prev = items[(current - 1 + items.length) % items.length];
        prev.classList.add('selected');
        prev.scrollIntoView({ block: 'nearest' });
    } 
    else if (e.key === 'Enter') {
        e.preventDefault();
        const active = box.querySelector('.search-item.selected');
        if (active) {
            const nameToFind = active.querySelector('.c-name').innerText;
            const zakaznik = vsetciZakaznici.find(z => z.meno === nameToFind);
            if (zakaznik) selectCustomer(zakaznik);
        }
    }
    // Pridaj toto do navigateCustomer AJ do navigateSearch (na začiatok k ostatným klávesám)
    if (e.key === 'Escape') {
        hideAllResults();
        return;
    }
}



/* --- PRODUKTY --- */
function addRow() {
    rowId++;
    const tr = document.createElement('tr');
    tr.id = `row-${rowId}`;
    tr.innerHTML = `
        <td style="width:40%">
            <div style="position:relative">
                <input type="text" class="p-name" autocomplete="off" 
                    oninput="searchProducts(this, ${rowId})" 
                    onkeydown="navigateSearch(event, ${rowId})" 
                    onfocus="this.select(); searchProducts(this, ${rowId})"
                    placeholder="Hľadajte produkt...">
                <div class="search-results" id="res-${rowId}"></div>
                <span class="info-sub" id="ean-${rowId}">Kód: -</span>
            </div>
        </td>

        <td style="width:8%">
            <input type="number" class="p-qty" value="0" step="any" 
                oninput="calc(${rowId})" 
                onblur="validatePack(${rowId})" 
                onfocus="this.select()"
                onkeydown="handleQtyEnter(event, ${rowId})">
            <span class="info-sub" id="pack-${rowId}">bal: -</span>
            <input type="hidden" class="p-pack" value="1">
        </td>

        <td style="width:10%">
            <input type="number" class="p-price" value="0.00" step="0.01" oninput="calc(${rowId})">
        </td>

        <td style="width:8%">
            <input type="number" class="p-z1" 
                value="${document.getElementById('z_zaklad').value}" 
                oninput="calc(${rowId})"
                onfocus="this.select()">
        </td>

        <td style="width:8%">
            <input type="number" class="p-z2" 
                value="${document.getElementById('z_objem').value}" 
                oninput="calc(${rowId})"
                onfocus="this.select()">
        </td>

        <td style="width:16%">
            <span class="p-total" style="font-weight:bold">0.00</span> €
        </td>

        <td style="width:5%">
            <button tabindex="-1" onclick="removeRow(${rowId})" style="border:none; background:none; color:red; cursor:pointer">✕</button>
        </td>
    `;

    document.getElementById('tbody').appendChild(tr);
    return tr;
}

// DOPLNENÁ FUNKCIA PRE ENTER V MNOŽSTVE
function handleQtyEnter(e, id) {
    if (e.key === 'Enter') {
        e.preventDefault();
        validatePack(id);
        const rows = document.querySelectorAll('#tbody tr');
        const lastRowId = parseInt(rows[rows.length - 1].id.replace('row-', ''));
        if (id === lastRowId) {
            const nextTr = addRow();
            setTimeout(() => nextTr.querySelector('.p-name').focus(), 50);
        } else {
            const nextTr = document.getElementById(`row-${id}`).nextElementSibling;
            if (nextTr) nextTr.querySelector('.p-name').focus();
        }
    }
}

function searchProducts(input, id) {

    clearTimeout(searchTimeout);

    const q = input.value.trim();
    const box = document.getElementById(`res-${id}`);

    searchTimeout = setTimeout(() => {

        axios.get(BASE_URL + '/search-products', {
            params: { q }
        })
        .then(res => {
            box.innerHTML = '';
            if (!res.data.length) {
                box.style.display = 'none';
                return;
            }

            res.data.forEach((p, i) => {
                const div = document.createElement('div');
                div.className = 'search-item' + (i === 0 ? ' selected' : '');
                div.dataset.json = JSON.stringify(p);

                div.innerHTML = `
                    <div style="display:flex;justify-content:space-between;width:100%;align-items:center;">
                        <span style="font-weight:bold;">${p.nazov}</span>
                        <span style="color:blue;font-size:0.9em;">${p.Rozmer || ''}</span>
                    </div>
                `;

                div.onmousedown = () => selectProduct(p, id);
                box.appendChild(div);
            });

            box.style.display = 'block';
        });

    }, 200); // 👈 oneskorenie 200ms
}

function selectProduct(p, id) {

    const row = document.getElementById(`row-${id}`);
    row.dataset.productId = p.id || null;
    row.dataset.fullProduct = JSON.stringify(p);
    row.querySelector('.p-name').value = `${p.nazov} /${p.Rozmer || ''}/`;
    row.querySelector('.p-price').value = parseFloat(p.cena_mj || 0).toFixed(2);
    row.querySelector('.p-price').readOnly = true;
   
    const pack = parseFloat(String(p.rozmer_balenie ?? 1).replace(',', '.')) || 1;
    row.querySelector('.p-pack').value = pack;
    row.querySelector('.p-qty').value = pack;
    document.getElementById(`ean-${id}`).innerText = 'EAN: ' + (p.id_vyrobok || '-');
    document.getElementById(`pack-${id}`).innerText = 'bal: ' + (p.mn_cele_balenie || '-');
    document.getElementById(`res-${id}`).style.display = 'none';
    
    calc(id);
    
    setTimeout(() => {
        const qty = row.querySelector('.p-qty');
        qty.focus(); qty.select();
    }, 50);
}

function navigateSearch(e, id) {
    const box = document.getElementById(`res-${id}`);
    const items = box.querySelectorAll('.search-item');
    if (box.style.display === 'none' || !items.length) return;
    let curr = [...items].findIndex(i => i.classList.contains('selected'));
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        items[curr]?.classList.remove('selected');
        items[(curr + 1) % items.length].classList.add('selected');
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        items[curr]?.classList.remove('selected');
        items[(curr - 1 + items.length) % items.length].classList.add('selected');
    } else if (e.key === 'Enter') {
        e.preventDefault();
        const active = box.querySelector('.search-item.selected');
        if (active) selectProduct(JSON.parse(active.dataset.json), id);
    }
    // Pridaj toto do navigateCustomer AJ do navigateSearch (na začiatok k ostatným klávesám)
    if (e.key === 'Escape') {
        hideAllResults();
        return;
    }
}

function calc(id) {
    const row = document.getElementById(`row-${id}`);
    if (!row) return;
    const q = parseFloat(row.querySelector('.p-qty').value || 0);
    const p = parseFloat(row.querySelector('.p-price').value || 0);
    const z1 = parseFloat(row.querySelector('.p-z1').value || 0);
    const z2 = parseFloat(row.querySelector('.p-z2').value || 0);
    const total = q * p * (1 - (z1 + z2) / 100);
    row.querySelector('.p-total').innerText = total.toFixed(2);
    recalcTotal();
}

function recalcTotal() {
    let sum = 0;
    document.querySelectorAll('.p-total').forEach(el => sum += parseFloat(el.innerText || 0));
    document.getElementById('grandTotal').innerText = sum.toLocaleString('sk-SK', {minimumFractionDigits: 2});
}

function applyGlobal() {
    const z1 = document.getElementById('z_zaklad').value;
    const z2 = document.getElementById('z_objem').value;
    document.querySelectorAll('#tbody tr').forEach(tr => {
        tr.querySelector('.p-z1').value = z1;
        tr.querySelector('.p-z2').value = z2;
        calc(tr.id.replace('row-', ''));
    });
}

function validatePack(id) {
    const row = document.getElementById(`row-${id}`);
    const pack = parseFloat(row.querySelector('.p-pack').value || 1);
    let qty = parseFloat(row.querySelector('.p-qty').value || 0);
    if (qty > 0) {
        row.querySelector('.p-qty').value = Math.ceil(qty / pack) * pack;
        calc(id);
    }
}

function removeRow(id) {
    if (document.querySelectorAll('#tbody tr').length > 1) {
        document.getElementById(`row-${id}`).remove();
        recalcTotal();
    }
}

function generatePDF() {
    const id = document.getElementById('existujuce_id').value;

    // 1. Ak už existuje ID → rovno tlačíme
    if (id) {
        window.open("{{ url('/ponuka/pdf') }}/" + id, '_blank');
        return;
    }

    // 2. Inak najprv uložíme ponuku
    const menoKlienta = document.getElementById('klient').value;
    if (!menoKlienta) {
        alert("Najprv vyberte zákazníka!");
        return;
    }

    const polozky = [];
    document.querySelectorAll('#tbody tr').forEach(tr => {
        const nazovInput = tr.querySelector('.p-name');
        if (nazovInput && nazovInput.value.trim() !== "") {
            polozky.push({
                nazov: nazovInput.value,
                mnozstvo: tr.querySelector('.p-qty').value.replace(',', '.'),
                cena_mj: tr.querySelector('.p-price').value.replace(',', '.'),
                z1: tr.querySelector('.p-z1').value || 0,
                z2: tr.querySelector('.p-z2').value || 0,
                spolu: tr.querySelector('.p-total').innerText.replace(/\s/g, '').replace(',', '.'),
                full_data: tr.dataset.fullProduct ? JSON.parse(tr.dataset.fullProduct) : null
            });
        }
    });

    const data = {
        _token: "{{ csrf_token() }}",
        existujuce_id: null,
        prepisat: false,
        zakaznik_meno: menoKlienta,
        nazov_ponuky: document.getElementById('nazov_ponuky').value,
        zlava_zaklad: document.getElementById('z_zaklad').value || 0,
        zlava_objem: document.getElementById('z_objem').value || 0,
        celkova_suma: document.getElementById('grandTotal').innerText.replace(/\s/g, '').replace(',', '.'),
        polozky: polozky
    };

    axios.post("{{ url('/save-ponuka') }}", data)
        .then(res => {
            if (res.data.success) {
                const newId = res.data.id;

                // uloží ID do hidden inputu
                document.getElementById('existujuce_id').value = newId;

                // hneď otvorí PDF
                window.open("{{ url('/ponuka/pdf') }}/" + newId, '_blank');
            }
        })
        .catch(err => {
            console.error(err);
            alert("Chyba pri ukladaní.");
        });
}

function saveOffer(prepisat = false) {
    lockUI();

    const btn = document.activeElement;
    let originalBtnText = "";

    if (btn && btn.tagName === 'BUTTON') {
        btn.disabled = true;
        originalBtnText = btn.innerHTML;
        btn.innerHTML = "⏳ Ukladám...";
    }

    // 1. KĽÚČOVÁ ZMENA: ID ponuky
    let idZDB = document.getElementById('existujuce_id').value;
    // Ak prepisujeme, pošleme ID. Ak ukladáme ako novú, pošleme null, 
    // aby Laravel v controllery spravil $offer = new Offer();
    let idPosielane = prepisat ? idZDB : null;

    // 2. klient
    const menoKlienta = document.getElementById('klient').value;
    if (!menoKlienta) {
        alert("Prosím, vyberte alebo zadajte meno klienta.");
        unlockUI();
        if (btn) { btn.disabled = false; btn.innerHTML = originalBtnText; }
        return;
    }

    // 3. položky (oprava selektora na tvoj #tbody)
    const polozky = [];
    document.querySelectorAll('#tbody tr').forEach(tr => {
        const nazovInput = tr.querySelector('.p-name');
        if (nazovInput && nazovInput.value.trim() !== "") {
            polozky.push({
                product_id: tr.dataset.productId || null, // Pridané ID produktu
                nazov: nazovInput.value,
                mnozstvo: tr.querySelector('.p-qty').value.replace(',', '.'),
                cena_mj: tr.querySelector('.p-price').value.replace(',', '.'),
                z1: tr.querySelector('.p-z1').value || 0,
                z2: tr.querySelector('.p-z2').value || 0,
                spolu: tr.querySelector('.p-total').innerText.replace(/\s/g, '').replace(',', '.').replace('€', ''),
                full_data: tr.dataset.fullProduct || null
            });
        }
    });

    // 4. request data (zjednotené s názvami v controllery)
    const data = {
        _token: "{{ csrf_token() }}",
        existujuce_id: idPosielane, // Toto posielame do Laravelu
        prepisat: prepisat,
        zakaznik_meno: menoKlienta,
        nazov_ponuky: document.getElementById('nazov_ponuky').value,
        zlava_zaklad: document.getElementById('z_zaklad').value || 0,
        zlava_objem: document.getElementById('z_objem').value || 0,
        celkova_suma: document.getElementById('grandTotal').innerText.replace(/\s/g, '').replace(',', '.').replace('€', ''),
        polozky: polozky
    };

    // 5. SAVE - použi presne URL z web.php
    axios.post("{{ url('/save-ponuka') }}", data)
        .then(res => {
            if (res.data.success) {
                showToast("Ponuka uložená ✔");
                document.getElementById('existujuce_id').value = res.data.id;
                
                // Otvoríme PDF v novom okne
                

                setTimeout(() => {
                    window.location.href = "{{ url('/archiv') }}";
                }, 600);
            }
        })
    .catch(err => {
        console.log("CELA CHYBA:", err);
         console.log("ODPOVED:", err.response);
        console.log("DATA:", err.response?.data);

        alert("Chyba pri ukladaní: " + (err.response?.data?.message || err.message));
    })
    
    .finally(() => {
            if (btn && btn.tagName === 'BUTTON') {
                btn.disabled = false;
                btn.innerHTML = originalBtnText || "💾 ULOŽIŤ PONUKU";
            }
            unlockUI();
    });
}


function hideAllResults() {
    // Schováme výsledky zákazníka
    const custResults = document.getElementById('customer-results');
    if (custResults) custResults.style.display = 'none';

    // Schováme všetky výsledky produktov v riadkoch
    document.querySelectorAll('.search-results').forEach(box => {
        box.style.display = 'none';
    });
    }

    // Globálne zatváranie našepkávačov pri kliknutí mimo
    document.addEventListener('mousedown', function(e) {
    // Ak kliknutie nebolo na input a ani na výsledky, schováme všetky boxy
    if (!e.target.closest('.p-name') && !e.target.closest('#klient') && !e.target.closest('.search-results')) {
        hideAllResults();
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Tab') {
        hideAllResults();
    }
});


function lockUI() {
    document.getElementById('app-lock').style.display = 'flex';
}

function unlockUI() {
    document.getElementById('app-lock').style.display = 'none';
}

function showToast(message) {
    let toast = document.createElement("div");

    toast.innerText = message;

    toast.style.position = "fixed";
    toast.style.bottom = "20px";
    toast.style.right = "20px";
    toast.style.background = "#28a745";
    toast.style.color = "#fff";
    toast.style.padding = "10px 15px";
    toast.style.borderRadius = "5px";
    toast.style.boxShadow = "0 4px 10px rgba(0,0,0,0.2)";
    toast.style.zIndex = "99999";
    toast.style.fontSize = "13px";

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 2000);
}
</script>
</body>
</html>