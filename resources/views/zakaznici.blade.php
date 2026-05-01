<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Databáza zákazníkov | DÖRKEN</title>
    <style>
:root {
    --dorken: #003399;
    --bg: #f0f2f5;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    margin: 0;
    padding: 0;
    font-size: 13px;
}

.navbar {
    background: var(--dorken);
    color: #fff;
    padding: 8px 0;
}

.navbar-inner {
    max-width: 1500px;
    margin: auto;
    padding: 0 22px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-inner b {
    font-size: 14px;
}

.nav-links a {
    color: #fff;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    font-size: 11px;
    opacity: .8;
}

.nav-links a.active {
    opacity: 1;
    border-bottom: 2px solid white;
    padding-bottom: 2px;
}

.nav-links a:hover {
    opacity: 1;
}

.container {
    max-width: 1550px;
    margin: 12px auto;
    background: white;
    padding: 16px 20px 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.page-title {
    color: var(--dorken);
    font-size: 20px;
    margin: 0 0 12px 0;
}

.add-box {
    background: white;
    border: 1px solid #d1d9e6;
    padding: 0;
    border-radius: 8px;
    margin-bottom: 16px;
    overflow: hidden;
    box-shadow: 0 3px 6px rgba(0,0,0,0.04);
}

.add-header {
    background: var(--dorken);
    color: white;
    padding: 7px 14px;
    font-weight: bold;
    font-size: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.add-header-title {
    letter-spacing: .2px;
}

.add-form {
    padding: 12px 14px 14px;
    background: white;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
}

.section-title {
    margin: 0 0 7px 0;
    font-size: 10px;
    color: var(--dorken);
    border-bottom: 1px solid #e5e8ef;
    padding-bottom: 4px;
    text-transform: uppercase;
}

.field-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 6px;
}

.row-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}

.row-city {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 8px;
}

.row-business {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 8px;
    margin-top: 8px;
}

label {
    font-size: 9px;
    color: #777;
    font-weight: bold;
    display: block;
    margin-bottom: 2px;
}

input {
    width: 100%;
    box-sizing: border-box;
    padding: 4px 6px;
    border: 1px solid #c9ced8;
    border-radius: 3px;
    font-size: 12px;
    height: 26px;
}

input:focus {
    outline: 2px solid rgba(0,51,153,0.18);
    border-color: var(--dorken);
}

.discount-label {
    color: #b00;
}

.discount-input {
    font-weight: bold;
}

.btn-save {
    background: white;
    color: var(--dorken);
    border: none;
    padding: 5px 16px;
    border-radius: 4px;
    cursor: pointer;
    font-weight: bold;
    font-size: 11px;
}

.btn-save:hover {
    background: #eef3ff;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 6px;
}

th {
    background: #343a40;
    color: white;
    padding: 8px 10px;
    text-align: left;
    font-size: 10px;
    text-transform: uppercase;
}

td {
    padding: 8px 10px;
    border-bottom: 1px solid #eee;
    transition: background 0.3s;
}

tr:hover {
    background: #f1f7ff;
}

[contenteditable="true"]:focus {
    outline: 2px solid var(--dorken);
    background: white;
    padding: 4px;
    border-radius: 3px;
}

.badge {
    background: #fffde7;
    border: 1px solid #ffd600;
    padding: 3px 7px;
    border-radius: 3px;
    font-weight: bold;
    color: #856404;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    padding: 8px 10px;
    border-radius: 4px;
    margin-bottom: 10px;
    border: 1px solid #c3e6cb;
}
</style>

    <script>
        document.addEventListener('keydown', function (e) {
            // Kontrola, či stláčame Enter v inpute
            if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                
                // Ak je to submit tlačidlo, necháme ho odoslať formulár
                if (e.target.type === 'submit') return;

                e.preventDefault(); // Zastavíme odoslanie formulára
                
                const form = e.target.form;
                // Získame všetky prvky, ktoré nie sú skryté (vynecháme CSRF a hidden inputy)
                const elements = Array.from(form.elements).filter(el => 
                    el.type !== 'hidden' && 
                    !el.disabled && 
                    el.tagName !== 'FIELDSET'
                );
                
                const index = elements.indexOf(e.target);
                
                // Ak existuje ďalšie pole, skočíme naň, inak odošleme formulár
                if (index > -1 && elements[index + 1]) {
                    elements[index + 1].focus();
                    
                    // Ak je to textové pole, text sa rovno označí pre rýchle prepísanie
                    if (elements[index + 1].select) {
                        elements[index + 1].select();
                    }
                } else {
                    form.submit(); // Ak sme na poslednom poli, odošleme
                }
            }
        });
    </script>


<body>

<div class="navbar">
        <div class="navbar-inner">
            <b>DÖRKEN</b>
            <div class="nav-links">
                <a href="/ponuka">PONUKA</a>
                <a href="/zakaznici" class="active">ZÁKAZNÍCI</a>
                <a href="/produkty">PRODUKTY</a>
                <a href="/archiv">ARCHÍV</a>
            </div>
        </div>
    
    </div>
</nav>

<div class="container">
    <h1 class="page-title">👥 Správa zákazníkov</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="add-box">
        <form action="{{ url('/zakaznici/store') }}" method="POST">
            @csrf

            <div class="add-header">
                <div class="add-header-title">➕ NOVÝ ZÁKAZNÍK</div>
                <button type="submit" class="btn-save">💾 ULOŽIŤ</button>
            </div>

            <div class="add-form">
                <div class="form-grid">

                    <div>
                        <h4 class="section-title">Identifikácia a sídlo</h4>

                        <div class="field-grid">
                            <div>
                                <label>Názov spoločnosti / Meno *</label>
                                <input type="text" name="meno" required onfocus="this.select()">
                            </div>

                            <div class="row-2">
                                <div>
                                    <label>IČO</label>
                                    <input type="text" name="ico" onfocus="this.select()">
                                </div>
                                <div>
                                    <label>DIČ</label>
                                    <input type="text" name="dic" onfocus="this.select()">
                                </div>
                            </div>

                            <div>
                                <label>Ulica a číslo</label>
                                <input type="text" name="ulica" onfocus="this.select()">
                            </div>

                            <div class="row-city">
                                <div>
                                    <label>Mesto</label>
                                    <input type="text" name="mesto" onfocus="this.select()">
                                </div>
                                <div>
                                    <label>PSČ</label>
                                    <input type="text" name="psc" onfocus="this.select()">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="section-title">Kontakt a obchod</h4>

                        <div class="field-grid">
                            <div>
                                <label>Kontaktná osoba</label>
                                <input type="text" name="kontakt_meno" onfocus="this.select()">
                            </div>

                            <div class="row-2">
                                <div>
                                    <label>Telefón</label>
                                    <input type="text" name="telefon" onfocus="this.select()">
                                </div>
                                <div>
                                    <label>E-mail</label>
                                    <input type="email" name="email" onfocus="this.select()">
                                </div>
                            </div>
                        </div>

                        <div class="row-business">
                            <div>
                                <label>Typ / Sieť</label>
                                <input type="text" name="typ" onfocus="this.select()" placeholder="Klient / Sieť">
                            </div>
                            <div>
                                <label class="discount-label">Zľava Zákl. %</label>
                                <input class="discount-input" type="number" name="default_discount_base" onfocus="this.select()" step="0.1" value="0">
                            </div>
                            <div>
                                <label class="discount-label">Zľava Obj. %</label>
                                <input class="discount-input" type="number" name="default_discount_obj" onfocus="this.select()" step="0.1" value="0">
                            </div>
                        </div>

                        <div style="margin-top: 7px;">
                            <label>Interná poznámka</label>
                            <input type="text" name="poznamka" onfocus="this.select()">
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Meno zákazníka</th>
                <th>IČO</th>
                <th>Mesto</th>
                <th>Zákl. zľava</th>
                <th>Objem. zľava</th>
                <th style="text-align: right;">Akcia</th>
            </tr>
        </thead>
        <tbody>
            @forelse($zakaznici as $z)
                <tr data-id="{{ $z->id }}"
                        data-search="{{ strtolower(
                            ($z->meno ?? '') . ' ' .
                            ($z->ico ?? '') . ' ' .
                            ($z->dic ?? '') . ' ' .
                            ($z->mesto ?? '') . ' ' .
                            ($z->kontakt_meno ?? '') . ' ' .
                            ($z->telefon ?? '') . ' ' .
                            ($z->email ?? '') . ' ' .
                            ($z->typ ?? '')
                        ) }}">
                    <td contenteditable="true" onfocus="selectText(this)" onblur="updateLive(this, 'meno')" onkeydown="checkEnter(event, this)"><strong>{{ $z->meno }}</strong></td>
                    <td contenteditable="true" onfocus="selectText(this)" onblur="updateLive(this, 'ico')" onkeydown="checkEnter(event, this)">{{ $z->ico ?? '' }}</td>
                    <td contenteditable="true" onfocus="selectText(this)" onblur="updateLive(this, 'mesto')" onkeydown="checkEnter(event, this)">{{ $z->mesto ?? '' }}</td>
                    <td contenteditable="true" onfocus="selectText(this)" onblur="updateLive(this, 'default_discount_base')" onkeydown="checkEnter(event, this)">{{ $z->default_discount_base }}</td>
                    <td contenteditable="true" onfocus="selectText(this)" onblur="updateLive(this, 'default_discount_obj')" onkeydown="checkEnter(event, this)">{{ $z->default_discount_obj }}</td>
                    <td style="text-align: right;">
                        <a href="{{ url('/zakaznici/edit/'.$z->id) }}" style="text-decoration:none;" title="Detailná úprava">✏️</a>
                        <button onclick="deleteCustomer({{ $z->id }})" style="background:none; border:none; cursor:pointer; margin-left:8px;" title="Zmazať">🗑️</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center; padding: 20px;">Zatiaľ žiadni zákazníci.</td></tr>
            @endforelse
        </tbody>
    </table>
    //info o filtrovani
    <div id="filterInfo" style="font-size:11px; color:#777; margin-top:6px;"></div>
</div>

<script>
// Funkcia na oznacenie celeho textu pri kliknuti
function selectText(element) {
    setTimeout(function() {
        let range = document.createRange();
        range.selectNodeContents(element);
        let sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(range);
    }, 10);
}

// Funkcia na spracovanie Enteru
function checkEnter(e, element) {
    if (e.keyCode === 13) {
        e.preventDefault();
        element.blur(); // Toto spusti onblur (ulozenie)
    }
}

// Funkcia na zive ulozenie cez AJAX
function updateLive(cell, field) {
    let id = cell.parentElement.getAttribute('data-id');
    let value = cell.innerText.trim();

    fetch(`/zakaznici/update-live/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ field: field, value: value })
    })
    .then(response => {
        if(response.ok) {
            cell.style.backgroundColor = '#d4edda';
            setTimeout(() => cell.style.backgroundColor = 'transparent', 500);
        }
    });
}

// Funkcia na zmazanie
        function deleteCustomer(id) {
            if(confirm('Naozaj zmazať zákazníka?')) {
                // Laravel helper -- url() -- zabezpečí správnu cestu aj v podpriečinkoch XAMPP
                let deleteUrl = "{{ url('/zakaznici/delete') }}/" + id;
                //let deleteUrl = window.location.origin + "/projekty/cp-novy/public/zakaznici/delete/" + id;
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        // Vymaže riadok z tabuľky okamžite
                        let row = document.querySelector(`tr[data-id="${id}"]`);
                        if(row) row.remove();
                    } else {
                        alert('Chyba: Server vrátil status ' + response.status);
                    }
                })
                .catch(error => {
                    console.error('Chyba:', error);
                    alert('Nepodarilo sa spojiť so serverom.');
                });
            }

}

//filtrovanie v zadavani
// Formulár nového zákazníka zároveň filtruje existujúcich zákazníkov
const customerForm = document.querySelector('.add-box form');
const customerRows = document.querySelectorAll('tbody tr[data-search]');
const filterInfo = document.getElementById('filterInfo');

function normalizeText(text) {
    return text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .trim();
}

function filterCustomersFromForm() {
    const inputs = customerForm.querySelectorAll('input');

    let searchParts = [];

    inputs.forEach(input => {
        if (
            input.name !== '_token' &&
            input.value.trim() !== '' &&
            input.type !== 'number'
        ) {
            searchParts.push(input.value);
        }
    });

    const searchText = normalizeText(searchParts.join(' '));

    let visibleCount = 0;

    customerRows.forEach(row => {
        const rowText = normalizeText(row.getAttribute('data-search') || '');

        if (searchText === '' || rowText.includes(searchText)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    if (searchText === '') {
        filterInfo.innerText = '';
    } else if (visibleCount === 0) {
        filterInfo.innerText = 'Nenašiel sa žiadny existujúci zákazník – môžeš pridať nového.';
    } else if (visibleCount === 1) {
        filterInfo.innerText = 'Nájdený 1 podobný zákazník.';
    } else {
        filterInfo.innerText = 'Nájdených podobných zákazníkov: ' + visibleCount;
    }
}

customerForm.querySelectorAll('input').forEach(input => {
    input.addEventListener('input', filterCustomersFromForm);
});


</script>

</body>
</html>