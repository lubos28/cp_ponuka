<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nová ponuka | DÖRKEN</title>
    
 <style>
    /* ZÁKLADNÉ PREMENNÉ */
    :root {
        --dorken: #003399;
        --bg: #f8f9fa;
        --border: #dee2e6;
        --danger: #dc3545;
        --success: #198754;
    }

    body {
        margin: 0;
        background: var(--bg);
        font-family: 'Segoe UI', sans-serif;
        font-size: 13px; /* Zachovaná veľkosť tela */
    }

    /* --- NAVBAR: PRESNE PODĽA ARCHÍVU (PORADIE A VEĽKOSTI) --- */
    .navbar {
        background: var(--dorken);
        color: #fff;
        padding: 10px 0;
    }

    .navbar-inner {
        max-width: 1500px;
        margin: auto;
        padding: 0 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .nav-links a {
        color: #fff;
        text-decoration: none;
        margin-left: 20px;
        font-weight: bold;
        font-size: 11px; /* Zachovaná veľkosť z archívu */
        opacity: .8;
    }

    .nav-links a.active {
    opacity: 1 !important;
    border-bottom: 2px solid white;
    padding-bottom: 2px;
}

    /* --- KONTAJNER A TVOJ OSTATNÝ OBSAH --- */
    .container {
        max-width: 1500px;
        margin: auto;
        padding: 25px;
    }

    /* FIXNÝ ACTION BAR */
    .action-bar-sticky {
        position: sticky;
        top: 0;
        z-index: 1000;
        background: #fff;
        padding: 15px 25px;
        border-bottom: 2px solid var(--dorken);
        margin: -25px -25px 20px -25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 15px rgba(0,0,0,.06);
    }

    /* OSTATNÉ ŠTÝLY (Zostávajú nezmenené pre funkčnosť) */
    .header-box { 
        display: flex; gap: 20px; margin-bottom: 20px; padding: 20px; 
        background: white; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .card {
        background: white; border-radius: 8px; overflow: visible; 
        box-shadow: 0 4px 15px rgba(0,0,0,.06);
    }

    table { width: 100%; border-collapse: collapse; }
    thead { background: #f1f3f5; }
    th { 
        padding: 12px 10px; font-size: 11px; text-align: left; 
        border-bottom: 2px solid #ddd; color: var(--dorken); 
    }
    td { padding: 10px; border-bottom: 1px solid #eee; position: relative; vertical-align: middle; }

    input { 
        padding: 8px; border: 1px solid #ccc; border-radius: 4px; 
        font-size: 13px; width: 100%; box-sizing: border-box;
    }
    input:focus { border-color: var(--dorken); outline: none; }
    label { display: block; font-weight: bold; margin-bottom: 5px; font-size: 11px; color: #555; text-transform: uppercase; }

    .btn-action { padding: 10px 20px; border: 1px solid #ccc; border-radius: 4px; font-weight: bold; cursor: pointer; transition: 0.2s; }
    .btn-save-draft { background: white; color: #333; }
    .btn-pdf { background: var(--danger); color: white; border: none; margin-left: 10px; }
    .btn-add { background: var(--dorken); color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; margin: 20px; }

    .search-results { 
        position: absolute; background: white; border: 1px solid var(--dorken); width: 100%; 
        z-index: 10000; box-shadow: 0 8px 20px rgba(0,0,0,0.15); display: none; 
        max-height: 300px; overflow-y: auto; left: 0; top: 42px; border-radius: 4px;
    }
    .search-item { padding: 10px; cursor: pointer; border-bottom: 1px solid #eee; font-size: 12px; }
    .search-item:hover, .search-item.selected { background: #f0f4ff; }

    .sticky-sum-val { font-size: 24px; font-weight: bold; color: var(--dorken); }
    .p-id-label { color: var(--dorken); font-weight: bold; font-size: 10px; display: block; margin-top: 4px; }
</style>

<div class="navbar">
    <div class="navbar-inner">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="/ponuka" class="active">NOVÁ PONUKA</a>
            <a href="{{ url('/zakaznici') }}">ZÁKAZNÍCI</a>
            <a href="{{ url('/produkty') }}">PRODUKTY</a>
            <a href="{{ url('/archiv') }}">ARCHÍV</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="action-bar-sticky">
        <div>
            <h2 style="margin:0; color:var(--dorken)">Nová cenová ponuka</h2>
        </div>
        <div style="text-align: right; display: flex; align-items: center; gap: 25px;">
            <div>
                <span class="sticky-sum-val">0,00 €</span>
            </div>
            <div>
                <button class="btn-action btn-save-draft">ULOŽIŤ KONCEPT</button>
                <button class="btn-action btn-pdf">GENEROVAŤ PDF</button>
            </div>
        </div>
    </div>
</div>  

<div class="container">
    <div class="action-bar-sticky">
        <div>
            <h2 style="margin:0; color:var(--dorken)">Nová cenová ponuka</h2>
        </div>
        <div style="text-align: right; display: flex; align-items: center; gap: 25px;">
            <div>
                <span class="sticky-sum-val">0,00 €</span>
            </div>
            <div>
                <button class="btn-action btn-save-draft">ULOŽIŤ KONCEPT</button>
                <button class="btn-action btn-pdf">GENEROVAŤ PDF</button>
            </div>
        </div>
    </div>
</div>

    <div class="container">
        <div class="action-bar-sticky">
            <div>
                <h2 style="margin:0; color:var(--dorken)">Nová cenová ponuka</h2>
                <span style="color:#666; font-size:11px;">Vytváranie nového dokumentu</span>
            </div>
            
            <div style="text-align: right; display: flex; align-items: center; gap: 25px;">
                <div>
                    <span style="font-size: 11px; color: #666; display: block; text-transform: uppercase;">Celková suma</span>
                    <span class="sticky-sum-val">0,00 €</span>
                </div>
                <div>
                    <button class="btn-action btn-save-draft">ULOŽIŤ KONCEPT</button>
                    <button class="btn-action btn-pdf">GENEROVAŤ PDF</button>
                </div>
            </div>
        </div>

        <div class="card">
            <table>
                <thead>
                    <tr>
                        <th width="40%">Produkt</th>
                        <th width="10%">Množstvo</th>
                        <th width="15%">Cena/MJ</th>
                        <th width="10%">Zľava %</th>
                        <th width="15%" style="text-align:right">Spolu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" placeholder="Hľadať produkt...">
                            <div class="search-results" style="display:none">
                                <div class="search-item">Delta-Vent S</div>
                                <div class="search-item">Delta-Maxx</div>
                            </div>
                        </td>
                        <td><input type="number" value="1"></td>
                        <td class="col-price">0,00 €</td>
                        <td><input type="number" class="col-discount" value="0"></td>
                        <td class="p-total-val" style="text-align:right">0,00 €</td>
                    </tr>
                </tbody>
            </table>
            <button class="btn-add">+ PRIDAŤ POLOŽKU</button>
        </div>
    </div>

</body>


<script>
// --- IDENTICKÁ LOGIKA AKO V PÔVODNOM SÚBORE ---
let rowCount = 0;
let currentFocus = -1;

function searchZakaznik(input) {
    let term = input.value.trim();
    const resDiv = document.getElementById('zakaznik-results');
    axios.get("{{ url('/search-zakaznici') }}", { params: { term: term } })
    .then(res => {
        resDiv.innerHTML = '';
        if (res.data.length > 0) {
            resDiv.style.display = 'block';
            res.data.forEach(z => {
                const item = document.createElement('div');
                item.className = 'search-item';
                item.innerHTML = `<strong>${z.meno}</strong> <small>(${z.mesto || ''})</small>`;
                item.onclick = function() {
                    document.getElementById('klient_meno').value = z.meno;
                    document.getElementById('def_base').value = z.default_discount_base || 0;
                    document.getElementById('def_obj').value = z.default_discount_obj || 0;
                    applyGlobalDiscounts();
                    resDiv.style.display = 'none';
                };
                resDiv.appendChild(item);
            });
        } else { resDiv.style.display = 'none'; }
    });
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
        <td><input type="number" class="p-discount-base col-discount" value="${document.getElementById('def_base').value}" oninput="calculateRow(${rowCount})"></td>
        <td><input type="number" class="p-discount-obj col-discount" value="${document.getElementById('def_obj').value}" oninput="calculateRow(${rowCount})"></td>
        <td style="text-align:right;"><span class="p-total-val"><span class="p-total">0,00</span> €</span></td>
        <td><button onclick="this.closest('tr').remove(); calculateGrandTotal();" style="color:red; border:none; background:none; cursor:pointer; font-weight:bold;">×</button></td>
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
                item.innerHTML = `<strong>${p.nazov}</strong><br><small>ID: ${kod} | ${p.Rozmer || ''} | <b>${p.cena_mj}€</b></small>`;
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

function saveOffer(type) {
    alert('Ukladám ako ' + type);
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