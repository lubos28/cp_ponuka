produkty blade php
aj s uchytenim

<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Sklad | DÖRKEN</title>
    <style>
        :root { --dorken: #003399; --bg: #f8f9fa; --success: #28a745; --danger: #dc3545; }
        
        body { font-family: 'Segoe UI', sans-serif; background: var(--bg); margin: 0; font-size: 13px; overflow: hidden; /* Zakáže skrolovanie celého okna */ }
        
        /* FIXNÁ HORNÁ ČASŤ */
        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--bg);
            z-index: 1000;
        }

        .navbar { background: var(--dorken); color: white; padding: 12px 25px; display: flex; justify-content: space-between; align-items: center; }
        .nav-links a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; opacity: 0.8; }
        
        .container-tools { 
            max-width: 1550px; 
            margin: 0 auto; 
            background: white; 
            padding: 20px 25px 10px 25px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .action-bar { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid var(--dorken); padding-bottom: 15px; margin-bottom: 15px; }
        
        .management-tools { display: flex; align-items: center; gap: 10px; }

        .import-box { 
            background: #f1f3f5; 
            padding: 5px 15px; 
            border-radius: 4px; 
            display: flex; 
            align-items: center; 
            gap: 10px; 
            border: 1px solid #ddd;
        }

        .btn { 
            height: 34px; 
            padding: 0 15px; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer; 
            font-weight: bold; 
            color: white; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .btn-success { background: var(--success); }
        .btn-danger { background: var(--danger); }
        
        .filter-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 2px solid #eee;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        .filter-input:focus { border-color: var(--dorken); outline: none; }

        /* SKROLOVACIA ČASŤ S TABUĽKOU */
        .scrollable-content {
            margin-top: 215px; /* Výška fixnej časti - doladiť podľa potreby */
            height: calc(100vh - 215px);
            overflow-y: auto;
            max-width: 1550px;
            margin-left: auto;
            margin-right: auto;
            background: white;
            padding: 0 25px;
        }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }

        /* FIXNÁ HLAVIČKA TABUĽKY */
        thead th {
            position: sticky;
            top: 0;
            background: #f1f3f5;
            color: var(--dorken);
            z-index: 100;
            border-bottom: 2px solid var(--dorken);
            padding: 12px;
            text-align: left;
        }

        td { padding: 10px; border-bottom: 1px solid #eee; }
        .td-info { text-align: center; width: 40px; }
        
       .info-icon {
    position: relative; /* DÔLEŽITÉ pre správne umiestnenie tooltipu */
    display: inline-flex; 
    align-items: center; 
    justify-content: center;
    color: var(--dorken); 
    cursor: help; 
    font-weight: bold; 
    font-size: 14px;
    background: #eef2f7; 
    width: 24px; 
    height: 24px; 
    border-radius: 50%;
}

.info-icon:hover::after {
    content: attr(data-info); 
    position: absolute; 
    pointer-events: none;
    bottom: 130%; 
    left: 50%; 
    transform: translateX(-50%);
    background: #333; 
    color: #fff; 
    padding: 10px; 
    border-radius: 6px;
    width: 250px; 
    white-space: normal; 
    z-index: 9999; /* Zvýšili sme na maximum, aby bol nad všetkým */
    font-size: 11px; 
    font-weight: normal;
    line-height: 1.4;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    
}
    </style>
</head>
<body>

<div class="fixed-header">
    <div class="navbar">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="{{ url('/ponuka') }}">NOVÁ PONUKA</a>
            <a href="{{ url('/produkty') }}">SKLAD</a>
            <a href="{{ url('/archiv') }}">ARCHÍV</a>  
            </div>
    </div>

    <div class="container-tools">
        <div class="action-bar">
            <h2 style="margin:0; color:var(--dorken)">Sklad produktov</h2>
            <div class="management-tools">
                <form action="{{ url('/produkty/import') }}" method="POST" enctype="multipart/form-data" class="import-box" style="margin:0">
                    @csrf
                    <input type="file" name="excel_file" required style="font-size:11px">
                    <button type="submit" class="btn btn-success">Import</button>
                </form>
                <form action="{{ url('/produkty/reset') }}" method="POST" onsubmit="return confirm('Vymazať sklad?');" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-danger">Vymazať</button>
                </form>
            </div>
        </div>
        <input type="text" id="productFilter" class="filter-input" placeholder="🔍 Rýchle hľadanie (kód, názov, rozmer)...">
    </div>
</div>

<div class="scrollable-content">
    <table id="productsTable">
    <thead>
        <tr>
            <th style="width: 80px;">Kód</th>
            <th>Názov</th>
            <th>Rozmer</th>
            <th style="text-align: right; width: 100px;">Cena MJ</th>
            <th style="text-align: center; width: 50px;">MJ</th>
            <th style="text-align: right; width: 110px;">Množ. v bal.</th>
            <th style="text-align: right; width: 120px;">Balenie (Info)</th>
            <th style="text-align: center; width: 60px;">Info</th>
            <th style="text-align: right; width: 120px;">Cena Balenia</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produkty as $p)
        <tr>
            <td><b>{{ $p->id_vyrobok }}</b></td>
            <td class="s-nazov">{{ $p->nazov }}</td>
            <td class="s-rozmer">{{ $p->Rozmer }}</td>
            <td style="text-align: right;">{{ number_format($p->cena_mj, 2, ',', ' ') }} €</td>
            <td style="text-align: center; color: #666;">{{ $p->merj }}</td>
            <td style="text-align: right; font-weight: bold;">{{ number_format($p->rozmer_balenie, 2, ',', ' ') }}</td>
            <td style="text-align: right; color: #666;"><small>{{ $p->mn_cele_balenie }}</small></td>
            <td class="td-info" style="text-align: center;">
                @if(!empty(trim($p->technicke_info)))
                    <span class="info-icon" data-info="{{ e($p->technicke_info) }}">ⓘ</span>
                @else
                    <span style="color: #eee;">-</span>
                @endif
            </td>
            <td style="color:var(--dorken); font-weight:bold; text-align: right;">
                {{ number_format($p->cena_mj * ($p->rozmer_balenie ?: 1), 2, ',', ' ') }} €
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
</div>

<script>
    document.getElementById('productFilter').addEventListener('keyup', function() {
        let val = this.value.toLowerCase().trim();
        let rows = document.querySelectorAll('#productsTable tbody tr');
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(val) ? "" : "none";
        });
    });
</script>

</body>
</html>