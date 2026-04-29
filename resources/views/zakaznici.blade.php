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

        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; padding: 0; font-size: 13px; }
        
        .navbar { 
            background: var(--dorken); 
            color: white; 
            display: flex; 
            justify-content: center; 
            padding: 12px 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
        }
        
        .navbar-inner {
            width: 100%;
            max-width: 1550px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            opacity: .8;
        }

        .nav-links a:hover { opacity: 1; }
        .nav-links a.active { opacity: 1; border-bottom: 2px solid white; }

        .container { max-width: 1550px; margin: 20px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .add-box { background: #f8f9fa; border: 1px solid #ddd; padding: 20px; border-radius: 6px; margin-bottom: 30px; }
        .btn-save { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #343a40; color: white; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eee; transition: background 0.3s; }
        tr:hover { background: #f1f7ff; }

        /* Štýl pre editovateľnú bunku */
        [contenteditable="true"]:focus {
            outline: 2px solid var(--dorken);
            background: white;
            padding: 5px;
            border-radius: 3px;
        }

        .badge { background: #fffde7; border: 1px solid #ffd600; padding: 3px 7px; border-radius: 3px; font-weight: bold; color: #856404; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        
            
    
    </style>
</head>
    <style>
        :root {
            --dorken: #003399;
            --bg: #f0f2f5;
        }

        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; padding: 0; font-size: 13px; }
        
        .navbar { 
            background: var(--dorken); 
            color: white; 
            display: flex; 
            justify-content: center; 
            padding: 12px 0; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
        }
        
        .navbar-inner {
            width: 100%;
            max-width: 1550px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 25px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            opacity: .8;
        }

        .nav-links a:hover { opacity: 1; }
        .nav-links a.active { opacity: 1; border-bottom: 2px solid white; }

        .container { max-width: 1550px; margin: 20px auto; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        
        .add-box { background: #f8f9fa; border: 1px solid #ddd; padding: 20px; border-radius: 6px; margin-bottom: 30px; }
        .btn-save { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #343a40; color: white; padding: 12px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eee; transition: background 0.3s; }
        tr:hover { background: #f1f7ff; }

        /* Štýl pre editovateľnú bunku */
        [contenteditable="true"]:focus {
            outline: 2px solid var(--dorken);
            background: white;
            padding: 5px;
            border-radius: 3px;
        }

        .badge { background: #fffde7; border: 1px solid #ffd600; padding: 3px 7px; border-radius: 3px; font-weight: bold; color: #856404; }
        .alert-success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 15px; border: 1px solid #c3e6cb; }
        
        
    
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

<nav class="navbar">
    <div class="navbar-inner">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="{{ url('/') }}">CENOVÉ PONUKY</a>
            <a href="{{ url('/produkty') }}">PRODUKTY</a>
            <a href="{{ url('/zakaznici') }}" class="active">ZÁKAZNÍCI</a>
            <a href="{{ url('/archiv') }}">ARCHÍV</a>
        </div>
    </div>
</nav>

<div class="container">
    <h1 style="color:var(--dorken)">👥 Správa zákazníkov</h1>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

   <div class="add-box" style="background: white; border: 1px solid #d1d9e6; padding: 0; border-radius: 8px; margin-bottom: 25px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <div style="background: var(--dorken); color: white; padding: 10px 20px; font-weight: bold; font-size: 13px;">
        ➕ NOVÝ ZÁKAZNÍK
    </div>
        
    <form action="{{ url('/zakaznici/store') }}" method="POST" style="padding: 20px; background: white;">
        @csrf
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            
            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 11px; color: var(--dorken); border-bottom: 1px solid #eee; padding-bottom: 5px;">IDENTIFIKÁCIA A SÍDLO</h4>
                <div style="display: grid; grid-template-columns: 1fr; gap: 8px;">
                    <div>
                        <label style="font-size: 10px; color: #888; font-weight: bold;">Názov spoločnosti / Meno *</label>
                        <input type="text" name="meno" required onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">IČO</label>
                            <input type="text" name="ico" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">DIČ</label>
                            <input type="text" name="dic" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                    </div>
                    <div>
                        <label style="font-size: 10px; color: #888; font-weight: bold;">Ulica a číslo</label>
                        <input type="text" name="ulica" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                    </div>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">Mesto</label>
                            <input type="text" name="mesto" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">PSČ</label>
                            <input type="text" name="psc" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 11px; color: var(--dorken); border-bottom: 1px solid #eee; padding-bottom: 5px;">KONTAKT A OBCHOD</h4>
                <div style="display: grid; grid-template-columns: 1fr; gap: 8px;">
                    <div>
                        <label style="font-size: 10px; color: #888; font-weight: bold;">Kontaktná osoba</label>
                        <input type="text" name="kontakt_meno" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">Telefón</label>
                            <input type="text" name="telefon" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                        <div>
                            <label style="font-size: 10px; color: #888; font-weight: bold;">E-mail</label>
                            <input type="email" name="email" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1.5fr 1fr 1fr; gap: 10px; margin-top: 15px;">
                    <div>
                        <label style="font-size: 10px; color: #888; font-weight: bold;">Typ / Sieť</label>
                        <input type="text" name="typ" onfocus="this.select()" placeholder="Klient / Sieť" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                    </div>
                    <div>
                        <label style="font-size: 10px; color: #b00; font-weight: bold;">Zľava Zákl. %</label>
                        <input type="number" name="default_discount_base" onfocus="this.select()" step="0.1" value="0" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px; font-weight:bold;">
                    </div>
                    <div>
                        <label style="font-size: 10px; color: #b00; font-weight: bold;">Zľava Obj. %</label>
                        <input type="number" name="default_discount_obj" onfocus="this.select()" step="0.1" value="0" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px; font-weight:bold;">
                    </div>
                </div>
                <div style="margin-top: 10px;">
                    <label style="font-size: 10px; color: #888; font-weight: bold;">Interná poznámka</label>
                    <input type="text" name="poznamka" onfocus="this.select()" style="width:100%; padding:5px; border: 1px solid #ccc; border-radius:3px;">
                </div>
            </div>
        </div>

        <div style="margin-top: 20px; text-align: right; border-top: 1px solid #eee; padding-top: 15px;">
            <button type="submit" class="btn-save" style="background: var(--dorken); color: white; border: none; padding: 10px 40px; font-size: 13px; border-radius: 4px; cursor: pointer; font-weight: bold;">
                💾 ULOŽIŤ ZÁKAZNÍKA
            </button>
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
                <tr data-id="{{ $z->id }}">
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



</script>

</body>
</html>