<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Upraviť zákazníka | DÖRKEN</title>
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

        /* Identický Navbar */
        .navbar {
            background: var(--dorken);
            color: white;
            display: flex;
            justify-content: center;
            padding: 12px 0;
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

        /* Kontajner */
        .container {
            max-width: 1550px;
            margin: 20px auto;
            background: white;
            padding: 25px;
            border-radius: 5px;
            box-shadow: 0 2px 15px rgba(0,0,0,.08);
        }

        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--dorken);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .btn-back {
            text-decoration: none;
            color: #666;
            font-weight: bold;
            font-size: 12px;
        }

        /* Kompaktný formulár */
        .edit-box {
            background: white;
            border: 1px solid #d1d9e6;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        label {
            display: block;
            font-size: 10px;
            font-weight: bold;
            color: #666;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            border-radius: 4px;
            font-size: 13px;
        }

        input:focus {
            border-color: var(--dorken);
            outline: none;
        }

        .btn-update {
            background: var(--success);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }

        .btn-update:hover { background: #218838; }

        .form-row {
            display: grid;
            gap: 15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="navbar-inner">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="{{ url('/') }}">CENOVÉ PONUKY</a>
            <a href="{{ url('/produkty') }}">PRODUKTY</a>
            <a href="{{ url('/zakaznici') }}">ZÁKAZNÍCI</a>
            <a href="{{ url('/archiv') }}">ARCHÍV</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="action-bar">
        <h2 style="margin:0;color:var(--dorken)">✏️ Upraviť údaje: {{ $zakaznik->meno }}</h2>
        <a href="{{ url('/zakaznici') }}" class="btn-back">⬅ SPÄŤ NA ZOZNAM</a>
    </div>

    <div class="edit-box">
        <form action="{{ url('/zakaznici/update/'.$zakaznik->id) }}" method="POST">
            @csrf
            
            <div class="form-row" style="grid-template-columns: 2fr 1fr 1fr 1fr;">
                <div>
                    <label>Názov firmy / Meno *</label>
                    <input type="text" name="meno" value="{{ $zakaznik->meno }}" required>
                </div>
                <div>
                    <label>IČO</label>
                    <input type="text" name="ico" value="{{ $zakaznik->ico }}">
                </div>
                <div>
                    <label>DIČ / IČ DPH</label>
                    <input type="text" name="dic" value="{{ $zakaznik->dic }}">
                </div>
                <div>
                    <label>Typ</label>
                    <input type="text" name="typ" value="{{ $zakaznik->typ }}">
                </div>
            </div>

            <div class="form-row" style="grid-template-columns: 1.5fr 1fr 0.5fr 1fr 1fr; background: #f9f9f9; padding: 15px; border-radius: 4px; border: 1px solid #eee;">
                <div>
                    <label>Ulica a č.</label>
                    <input type="text" name="ulica" value="{{ $zakaznik->ulica }}">
                </div>
                <div>
                    <label>Mesto</label>
                    <input type="text" name="mesto" value="{{ $zakaznik->mesto }}">
                </div>
                <div>
                    <label>PSČ</label>
                    <input type="text" name="psc" value="{{ $zakaznik->psc }}">
                </div>
                <div>
                    <label>Kontakt. osoba</label>
                    <input type="text" name="kontakt_meno" value="{{ $zakaznik->kontakt_meno }}">
                </div>
                <div>
                    <label>Telefón</label>
                    <input type="text" name="telefon" value="{{ $zakaznik->telefon }}">
                </div>
            </div>

            <div class="form-row" style="grid-template-columns: 2fr 1fr 1fr;">
                <div>
                    <label>Interná poznámka</label>
                    <input type="text" name="poznamka" value="{{ $zakaznik->poznamka }}">
                </div>
                <div>
                    <label style="color:var(--success)">Základná zľava %</label>
                    <input type="number" name="default_discount_base" step="0.1" value="{{ $zakaznik->default_discount_base }}" style="border: 1px solid var(--success); text-align: right;">
                </div>
                <div>
                    <label style="color:var(--success)">Objemová zľava %</label>
                    <input type="number" name="default_discount_obj" step="0.1" value="{{ $zakaznik->default_discount_obj }}" style="border: 1px solid var(--success); text-align: right;">
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px; border-top: 1px solid #eee; padding-top: 20px;">
                <button type="submit" class="btn-update">💾 ULOŽIŤ ZMENY</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>