<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Systém Cenových Ponúk</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; margin: 0; padding: 0; font-size: 13px; }
        
        /* Navigácia */
        .navbar { background: #343a40; padding: 10px 20px; display: flex; gap: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); position: relative; z-index: 1001; }
        .nav-btn { 
            background: #495057; color: white !important; border: none; padding: 10px 20px; 
            cursor: pointer; border-radius: 4px; font-weight: bold; text-decoration: none; 
            display: inline-block; transition: 0.2s; 
        }
        .nav-btn:hover { background: #6c757d; }
        .nav-btn.active { background: #007bff; }

        .container { max-width: 1550px; margin: 20px auto; background: white; padding: 15px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        /* FIXNÝ PANEL S TLAČIDLAMI (Sticky) */
        .action-bar-sticky {
            position: sticky;
            top: 0;
            z-index: 1000;
            background: #fff;
            padding: 15px;
            border-bottom: 2px solid #007bff;
            margin: -15px -15px 20px -15px; /* Vyrovnanie paddingu kontajnera */
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }

        .page-section { display: none; }
        .page-section.active { display: block; }

        /* Hlavička CP */
        .header-box { display: flex; gap: 20px; margin-bottom: 15px; padding: 15px; background: #f9f9f9; border: 1px solid #eee; align-items: flex-end; border-radius: 5px; }
        .header-box div { flex: 1; }
        
        input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 100%; box-sizing: border-box; font-size: 13px; display: block; }
        input:focus { border-color: #007bff; outline: none; background: #fff; box-shadow: 0 0 5px rgba(0,123,255,0.2); }
        
        label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 11px; text-transform: uppercase; color: #555; }

        /* Tlačidlá akcií */
        .btn-action { padding: 10px 20px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.2s; margin-right: 10px; }
        .btn-save-draft { background: #6c757d; color: white; }
        .btn-save-draft:hover { background: #5a6268; }
        .btn-pdf { background: #dc3545; color: white; }
        .btn-pdf:hover { background: #c82333; }

        .sticky-sum-box { text-align: right; }
        .sticky-sum-label { font-size: 11px; color: #666; display: block; text-transform: uppercase; }
        .sticky-sum-val { font-size: 22px; font-weight: bold; color: #000; }

        /* Tabuľka */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
        th { background: #343a40; color: white; padding: 10px; text-align: left; font-size: 11px; }
        td { padding: 10px; border-bottom: 1px solid #ddd; position: relative; vertical-align: top; }

        .search-results { 
            position: absolute; background: white; border: 2px solid #007bff; width: 100%; 
            z-index: 100000; box-shadow: 0 8px 20px rgba(0,0,0,0.3); display: none; 
            max-height: 350px; overflow-y: auto; left: 0; top: 42px; 
        }
        .search-item { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 12px; line-height: 1.4; color: #333; }
        .search-item:hover, .search-item.selected { background: #d1e7ff; }

        .btn-add { background: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 10px; }
        .p-id-label { color: #007bff; font-weight: bold; display: block; margin-top: 5px; font-size: 11px; min-height: 14px; }
        .hint { font-size: 10px; color: #666; display: block; margin-top: 2px; }
        
        .col-price { background: #f8f9fa; font-weight: bold; text-align: right; }
        .col-discount { background: #fffde7; font-weight: bold; text-align: center; border: 1px solid #ffe082 !important; }
        .p-mj { border: none !important; background: transparent !important; text-align: center; }
    </style>
</head>
<body>

<nav class="navbar">
    <button class="nav-btn active" onclick="showSection('section-cp', this)">📝 Nová ponuka</button>
    <a href="{{ url('/produkty') }}" class="nav-btn">📦 Produkty (Sklad)</a>
    <a href="{{ url('/zakaznici') }}" class="nav-btn">👥 Zákazníci</a>
    <button class="nav-btn" onclick="showSection('section-archiv', this)">📂 Archív</button>
</nav>

<div class="container">
    
    <div id="section-cp" class="page-section active">
        
        <div class="action-bar-sticky">
            <div>
                <button class="btn-action btn-save-draft" onclick="saveOffer('draft')">💾 Uložiť ponuku</button>
                <button class="btn-action btn-pdf" onclick="saveOffer('pdf')">📄 Generovať PDF</button>
            </div>
            <div class="sticky-sum-box">
                <span class="sticky-sum-label">Celková suma bez DPH</span>
                <span class="sticky-sum-val"><span id="grandTotal">0,00</span> €</span>
            </div>
        </div>

        <div class="header-box">
            <div style="flex: 0.6; position: relative;">
                <label>Odberateľ (Hľadaj v databáze alebo vpíš meno)</label>
                <input type="text" id="klient_meno" placeholder="Meno firmy, IČO alebo jednorazový zákazník..." 
                       autocomplete="off" onkeyup="searchZakaznik(this)" onfocus="this.select()">
                <div id="zakaznik-results" class="search-results"></div>
            </div>
            <div style="flex: 0.2;">
                <label>Zákl. zľava %</label>
                <input type="number" id="def_base" value="0" step="0.1" onfocus="this.select()" oninput="applyGlobalDiscounts()">
            </div>
            <div style="flex: 0.2;">
                <label>Objem. zľava %</label>
                <input type="number" id="def_obj" value="0" step="0.1" onfocus="this.select()" oninput="applyGlobalDiscounts()">
            </div>
        </div>

        <table id="ponukaTable">
            <thead>
                <tr>
                    <th style="width: 35%;">Produkt a Rozmer</th>
                    <th style="width: 10%;">Množstvo</th>
                    <th style="width: 5%;">MJ</th>
                    <th style="width: 10%;">Cenník MJ</th>
                    <th style="width: 8%;">Zákl. %</th>
                    <th style="width: 8%;">Objem. %</th>
                    <th style="width: 12%;">Spolu bez DPH</th>
                    <th style="width: 4%;"></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <button class="btn-add" onclick="addRow()">+ Pridať položku (Enter)</button>
    </div>

    <div id="section-archiv" class="page-section">
        <h2>📂 Archív ponúk</h2>
        <p>Zoznam uložených PDF ponúk...</p>
    </div>

</div>

<script>
// --- LOGIKA PRE ZÁKAZNÍKOV ---
function searchZakaznik(input) {
    let term = input.value.trim();
    const resDiv = document.getElementById('zakaznik-results');

    // Hľadá hneď pri prvom znaku alebo prázdnom poli (ak chceš všetkých)
    axios.get("{{ url('/search-zakaznici') }}", { params: { term: term } })
    .then(res => {
        resDiv.innerHTML = '';
        if (res.data.length > 0) {
            resDiv.style.display = 'block';
            res.data.forEach(z => {
                const item = document.createElement('div');
                item.className = 'search-item';
                item.innerHTML = `<strong>${z.meno}</strong> <small>(${z.mesto || ''}) - IČO: ${z.ico || '---'}</small>`;
                item.onclick = function() {
                    document.getElementById('klient_meno').value = z.meno;
                    document.getElementById('def_base').value = z.default_discount_base || 0;
                    document.getElementById('def_obj').value = z.default_discount_obj || 0;
                    applyGlobalDiscounts();
                    resDiv.style.display = 'none';
                };
                resDiv.appendChild(item);
            });
        } else {
            resDiv.style.display = 'none';
        }
    });
}

function saveOffer(type) {
    const klient = document.getElementById('klient_meno').value;
    if(!klient) { alert('Prosím zadajte meno odberateľa.'); return; }
    
    if(type === 'pdf') {
        console.log('Generujem PDF pre: ' + klient);
        // Tu sa neskôr napojí axios.post na generovanie PDF
    } else {
        console.log('Ukladám koncept pre: ' + klient);
    }
    alert('Akcia "' + type + '" bola spustená (zatiaľ v testovacom režime).');
}

// --- LOGIKA PRODUKTOV ---
var rowCount = 0;
var currentFocus = -1;

function showSection(sectionId, btn) {
    document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
    document.getElementById(sectionId).classList.add('active');
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function addRow() {
    rowCount++;
    const tbody = document.querySelector('#ponukaTable tbody');
    const row = document.createElement('tr');
    row.id = `row-${rowCount}`;
    row.innerHTML = `
        <td>
            <div style="position:relative;">
                <input type="text" class="search-input" placeholder="Hľadaj produkt..." autocomplete="off"
                    onfocus="this.select(); searchProduct(null, this, ${rowCount})"
                    onkeyup="searchProduct(event, this, ${rowCount})"
                    onkeydown="handleKeys(event, ${rowCount})">
                <div class="search-results" id="results-${rowCount}"></div>
                <div class="p-id-label" id="id-display-${rowCount}"></div>
            </div>
        </td>
        <td>
            <input type="number" class="p-qty" value="1" step="any" onfocus="this.select()"
                onkeydown="if(event.keyCode==13){event.preventDefault();addRow();}"
                onchange="applyPacking(${rowCount})" oninput="calculateRow(${rowCount})">
            <input type="hidden" class="p-karton" value="1">
            <small class="hint" id="hint-balenie-${rowCount}"></small>
        </td>
        <td><input type="text" class="p-mj" readonly tabindex="-1"></td>
        <td><input type="number" class="p-price-orig col-price" step="0.01" readonly tabindex="-1"></td>
        <td><input type="number" class="p-discount-base col-discount" value="${document.getElementById('def_base').value}" onfocus="this.select()" oninput="calculateRow(${rowCount})"></td>
        <td><input type="number" class="p-discount-obj col-discount" value="${document.getElementById('def_obj').value}" onfocus="this.select()" oninput="calculateRow(${rowCount})"></td>
        <td style="text-align:right; font-weight:bold; padding-top:16px;"><span class="p-total">0,00</span> €</td>
        <td><button onclick="this.closest('tr').remove(); calculateGrandTotal();" style="color:red; border:none; background:none; cursor:pointer; font-weight:bold; font-size:16px;">×</button></td>
    `;
    tbody.appendChild(row);
    row.querySelector('.search-input').focus();
}

function searchProduct(e, input, id) {
    if (e && [38, 40, 13].includes(e.keyCode)) return;
    const div = document.getElementById(`results-${id}`);
    currentFocus = -1;
    let term = input.value.trim();

    axios.get("{{ url('/search-products') }}", { params: { term: term, open_all: 1 } })
    .then(res => {
        div.innerHTML = '';
        let products = Array.isArray(res.data) ? res.data : (res.data.data || []);
        if (products.length > 0) {
            div.style.display = 'block';
            products.forEach((p, index) => {
                const item = document.createElement('div');
                item.className = 'search-item';
                let kod = p.id_vyrobok || p.ID || p.id || '---';
                item.innerHTML = `<strong>${p.nazov}</strong><br><small>ID: ${kod} ${p.Rozmer ? '| ' + p.Rozmer : ''} | Cena: ${p.cena_mj}€</small>`;
                item.onmousedown = (event) => { event.preventDefault(); selectProduct(p, id); };
                div.appendChild(item);
                if (index === 0) currentFocus = 0;
            });
            addActive(div.getElementsByClassName('search-item'));
        } else { div.style.display = 'none'; }
    });
}

function selectProduct(p, id) {
    const r = document.getElementById(`row-${id}`);
    const kod = p.id_vyrobok || p.ID || p.id || '---';
    r.querySelector('.search-input').value = p.nazov + (p.Rozmer ? ' ' + p.Rozmer : '');
    r.querySelector('.p-price-orig').value = parseFloat(p.cena_mj || 0).toFixed(2);
    r.querySelector('.p-mj').value = p.merj || 'ks';
    r.querySelector('.p-karton').value = p.rozmer_balenie || 1;
    document.getElementById(`id-display-${id}`).innerText = "ID: " + kod;
    document.getElementById(`hint-balenie-${id}`).innerText = "Balenie: " + (p.mn_cele_balenie || 1);
    document.getElementById(`results-${id}`).style.display = 'none';
    applyPacking(id);
    r.querySelector('.p-qty').focus();
}

function applyPacking(id) {
    const row = document.getElementById(`row-${id}`);
    const karton = parseFloat(row.querySelector('.p-karton').value) || 1;
    let qtyInput = row.querySelector('.p-qty');
    let qty = parseFloat(qtyInput.value) || 0;
    if (qty > 0 && karton > 1) { qtyInput.value = Math.ceil(qty / karton) * karton; }
    calculateRow(id);
}

function calculateRow(id) {
    const row = document.getElementById(`row-${id}`);
    if (!row) return;
    const qty = parseFloat(row.querySelector('.p-qty').value) || 0;
    const price = parseFloat(row.querySelector('.p-price-orig').value) || 0;
    const d1 = parseFloat(row.querySelector('.p-discount-base').value) || 0;
    const d2 = parseFloat(row.querySelector('.p-discount-obj').value) || 0;
    const total = qty * (price * (1 - (d1 + d2) / 100));
    row.querySelector('.p-total').innerText = total.toLocaleString('sk-SK', { minimumFractionDigits: 2 });
    calculateGrandTotal();
}

function calculateGrandTotal() {
    let sum = 0;
    document.querySelectorAll('.p-total').forEach(el => {
        sum += parseFloat(el.innerText.replace(/\s/g, '').replace(',', '.')) || 0;
    });
    document.getElementById('grandTotal').innerText = sum.toLocaleString('sk-SK', { minimumFractionDigits: 2 });
}

function applyGlobalDiscounts() {
    const dBase = document.getElementById('def_base').value || 0;
    const dObj = document.getElementById('def_obj').value || 0;
    document.querySelectorAll('tr[id^="row-"]').forEach(tr => {
        const id = tr.id.split('-')[1];
        tr.querySelector('.p-discount-base').value = dBase;
        tr.querySelector('.p-discount-obj').value = dObj;
        calculateRow(id);
    });
}

function handleKeys(e, id) {
    const resDiv = document.getElementById(`results-${id}`);
    if (!resDiv || resDiv.style.display === 'none') return;
    const items = resDiv.getElementsByClassName('search-item');
    if (e.keyCode == 40) { e.preventDefault(); currentFocus++; addActive(items); }
    else if (e.keyCode == 38) { e.preventDefault(); currentFocus--; addActive(items); }
    else if (e.keyCode == 13) { 
        e.preventDefault(); 
        if (currentFocus > -1 && items[currentFocus]) {
            items[currentFocus].dispatchEvent(new MouseEvent('mousedown', { bubbles:true }));
        }
    }
}

function addActive(items) {
    if (!items.length) return;
    for (let i = 0; i < items.length; i++) items[i].classList.remove("selected");
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = items.length - 1;
    items[currentFocus].classList.add("selected");
    items[currentFocus].scrollIntoView({ block: "nearest" });
}

document.addEventListener("mousedown", function(e) {
    if (!e.target.classList.contains('search-input') && !e.target.classList.contains('search-item') && e.target.id !== 'klient_meno') {
        document.querySelectorAll('.search-results').forEach(el => el.style.display = 'none');
    }
});

window.onload = addRow;
</script>
</body>
</html>