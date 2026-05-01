<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Upraviť zákazníka | DÖRKEN</title>

    <style>
        :root {
            --dorken: #003399;
            --bg: #f0f2f5;
            --success: #28a745;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: var(--bg);
            margin: 0;
            font-size: 13px;
            color: #333;
        }

        .navbar {
            background: var(--dorken);
            color: white;
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
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: bold;
            font-size: 11px;
            opacity: .8;
        }

        .nav-links a:hover,
        .nav-links a.active {
            opacity: 1;
            border-bottom: 2px solid white;
            padding-bottom: 2px;
        }

        .container {
            max-width: 1550px;
            margin: 12px auto;
            background: white;
            padding: 16px 20px 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        .edit-box {
            background: white;
            border: 1px solid #d1d9e6;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 6px rgba(0,0,0,0.04);
        }

        .edit-header {
            background: var(--dorken);
            color: white;
            padding: 8px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .edit-title {
            font-weight: bold;
            font-size: 13px;
        }

       .edit-actions {
            display: flex;
            align-items: center;
        }

        .btn-back {
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 11px;
            opacity: .9;
        }

        .btn-back:hover {
            opacity: 1;
        }

        .btn-update {
            display: none;
        }

        .btn-update:hover {
            background: #eef3ff;
        }

        .edit-form {
            padding: 13px 14px 14px;
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
            gap: 7px;
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
            display: block;
            font-size: 9px;
            font-weight: bold;
            color: #777;
            margin-bottom: 2px;
            text-transform: uppercase;
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
            color: var(--success);
        }

        .discount-input {
            border-color: var(--success);
            text-align: right;
            font-weight: bold;
        }

    .bottom-save {
            text-align: right;
            border-top: 1px solid #e5e8ef;
            padding: 12px 14px;
            background: #fafbfc;
        }

        .btn-update-bottom {
            background: var(--dorken);
            color: white;
            border: none;
            padding: 8px 28px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }

        .btn-update-bottom:hover {
            background: #002a80;
        }

        .form-grid {
            align-items: start;
        }

        .field-grid > div,
        .row-2 > div,
        .row-city > div,
        .row-business > div {
            min-width: 0;
        }

        input {
            display: block;
        }

        .row-business {
            align-items: end;
        }

    </style>
</head>

<body>

<div class="navbar">
    <div class="navbar-inner">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="/ponuka">PONUKA</a>
            <a href="{{ url('/zakaznici') }}" class="active">ZÁKAZNÍCI</a>
            <a href="{{ url('/produkty') }}">PRODUKTY</a>
            <a href="{{ url('/archiv') }}">ARCHÍV</a>
        </div>
    </div>
</div>

<div class="container">

    <div class="edit-box">
            <form action="{{ url('/zakaznici/update/'.$zakaznik->id) }}" method="POST">
                @csrf

                <div class="edit-header">
        <div class="edit-title">✏️ Upraviť zákazníka: {{ $zakaznik->meno }}</div>

        <div class="edit-actions">
            <a href="{{ url('/zakaznici') }}" class="btn-back">⬅ SPÄŤ</a>
        </div>
    </div>

          

    <div class="edit-form">
        <div class="form-grid">

        <div>
        <h4 class="section-title">Identifikácia a sídlo</h4>

        <div class="field-grid">
            <div>
                <label>Názov firmy / Meno *</label>
                <input type="text" name="meno" value="{{ $zakaznik->meno }}" required>
            </div>

            <div class="row-2">
            <div>
                    <label>IČO</label>
                    <input type="text" name="ico" value="{{ $zakaznik->ico }}">
            </div>

                <div>
                    <label>DIČ / IČ DPH</label>
                    <input type="text" name="dic" value="{{ $zakaznik->dic }}">
                </div>
            </div>

            <div>
                <label>Ulica a číslo</label>
                <input type="text" name="ulica" value="{{ $zakaznik->ulica }}">
            </div>

            <div class="row-city">
                <div>
                    <label>Mesto</label>
                    <input type="text" name="mesto" value="{{ $zakaznik->mesto }}">
                </div>

                <div>
                    <label>PSČ</label>
                    <input type="text" name="psc" value="{{ $zakaznik->psc }}">
                </div>
            </div>
        </div>
    </div>

    <div>
                        <h4 class="section-title">Kontakt a obchod</h4>

                        <div class="field-grid">
                            <div>
                                <label>Kontaktná osoba</label>
                                <input type="text" name="kontakt_meno" value="{{ $zakaznik->kontakt_meno }}">
                            </div>

                            <div class="row-2">
                                <div>
                                    <label>Telefón</label>
                                    <input type="text" name="telefon" value="{{ $zakaznik->telefon }}">
                                </div>

                                <div>
                                    <label>E-mail</label>
                                    <input type="email" name="email" value="{{ $zakaznik->email ?? '' }}">
                                </div>
                            </div>
                        </div>

                        <div class="row-business">
                            <div>
                                <label>Typ / Sieť</label>
                                <input type="text" name="typ" value="{{ $zakaznik->typ }}">
                            </div>

                            <div>
                                <label class="discount-label">Základná zľava %</label>
                                <input class="discount-input" type="number" name="default_discount_base" step="0.1" value="{{ $zakaznik->default_discount_base }}">
                            </div>

                            <div>
                                <label class="discount-label">Objemová zľava %</label>
                                <input class="discount-input" type="number" name="default_discount_obj" step="0.1" value="{{ $zakaznik->default_discount_obj }}">
                            </div>
                        </div>

                        <div style="margin-top: 7px;">
                            <label>Interná poznámka</label>
                            <input type="text" name="poznamka" value="{{ $zakaznik->poznamka }}">
                        </div>
                    </div>

                </div>
            </div>
          
            <div class="bottom-save">
    <button type="submit" class="btn-update-bottom">💾 ULOŽIŤ ZMENY</button>
</div>

        </form>
    </div>

</div>

<script>
    document.querySelectorAll('input, textarea').forEach(function (el) {
        el.addEventListener('focus', function () {
            this.select();
        });

        el.addEventListener('click', function () {
            this.select();
        });
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            if (e.target.type === 'submit') return;

            e.preventDefault();

            const form = e.target.form;
            const elements = Array.from(form.elements).filter(el =>
                el.type !== 'hidden' &&
                !el.disabled &&
                el.tagName !== 'FIELDSET'
            );

            const index = elements.indexOf(e.target);

            if (index > -1 && elements[index + 1]) {
                elements[index + 1].focus();

                if (elements[index + 1].select) {
                    elements[index + 1].select();
                }
            } else {
                form.submit();
            }
        }
    });
</script>

</body>
</html>