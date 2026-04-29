<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0.8cm; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9px; color: #333; line-height: 1.2; }
        .header-table { width: 100%; margin-bottom: 20px; }
        .header-title { font-size: 20px; font-weight: bold; color: #007bff; text-transform: uppercase; }
        .header-info { text-align: right; color: #555; }
        .addresses { width: 100%; margin-bottom: 25px; border-collapse: collapse; }
        .address-box { width: 48%; border: 1px solid #eee; padding: 10px; vertical-align: top; background-color: #fcfcfc; }
        .label-blue { color: #007bff; font-weight: bold; text-transform: uppercase; font-size: 8px; margin-bottom: 5px; display: block; }
        .items-table { width: 100%; border-collapse: collapse; }
        .items-table thead th { text-align: left; padding: 8px 4px; border-bottom: 2px solid #007bff; color: #000; font-size: 8px; text-transform: uppercase; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .product-row td { padding: 8px 4px 0px 4px; vertical-align: top; font-weight: bold; }
        .description-row td { padding: 2px 4px 10px 4px; font-size: 8.5px; color: #666; font-weight: normal; border-bottom: 1px solid #eee; }
        .small-info { font-size: 7px; color: #999; font-weight: normal; display: block; margin-top: 1px; }
        .summary-table { float: right; width: 230px; border-collapse: collapse; margin-top: 20px; }
        .summary-table td { padding: 4px 5px; border-bottom: 1px solid #eee; }
        .total-box { background-color: #007bff; color: white; padding: 10px; border-radius: 3px; margin-top: 5px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-title">Cenová ponuka</td>
            <td class="header-info">Číslo: {{ $ponuka->id }}/2026<br>Dátum: {{ date('d.m.Y', strtotime($ponuka->created_at)) }}</td>
        </tr>
    </table>

    <table class="addresses">
        <tr>
            <td class="address-box">
                <span class="label-blue">Dodávateľ</span>
                <b style="font-size: 10px;">Dörken SK, s.r.o.</b><br>Nádražná 28, 900 28 Ivanka pri Dunaji<br>IČO: 35792892 | IČ DPH: SK2020201898
            </td>
            <td style="width: 4%;"></td>
            <td class="address-box">
                <span class="label-blue">Odberateľ</span>
                <b style="font-size: 10px;">{{ $ponuka->customer_name }}</b><br>Projekt: {{ $ponuka->title }}<br>Platnosť: 14 dní
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="35%">Produkt / Rozmer</th>
                <th width="10%" class="text-right">Množstvo</th>
                <th width="7%" class="text-center">MJ</th>
                <th width="15%" class="text-right">Cena/MJ bez DPH</th>
                <th width="8%" class="text-right">Zľava</th>
                <th width="25%" class="text-right">Spolu bez DPH / s DPH</th>
            </tr>
        </thead>
        <tbody>
@foreach($ponuka->polozky as $item)
@php
    $d = $item->product_data;

    $finalPopis = $d['Popis1'] ?? ($item->popis1 ?? ($item->popis ?? ''));

    $zlavaPercento = ($item->z_zaklad ?? 0) + ($item->z_objekt ?? 0);
    $spoluBezDph = $item->row_total;
    $spoluSdpH = $spoluBezDph * 1.23;
@endphp

<tr class="product-row">
    <td>
        <div style="font-size: 10px; font-weight: bold; text-transform: uppercase;">
            {{ $loop->iteration }}. {{ $item->product_name }}
        </div>

        @if(!empty($d['rozmer']))
            <div style="color:#007bff; font-size: 8px; font-weight: bold; margin-left: 15px;">
                {{ $d['rozmer'] }}
            </div>
        @endif

        <span class="small-info" style="margin-left: 15px;">
            ID výrobku: {{ $d['id_vyrobok'] ?? ($d['id'] ?? '-') }}
        </span>
    </td>

    <td class="text-right">
        {{ number_format($item->quantity, 2, ',', ' ') }}
        <span class="small-info">pal: {{ $d['mn_cele_balenie'] ?? '-' }}</span>
    </td>

    <td class="text-center">{{ $d['merj'] ?? ($item->mj ?? 'ks') }}</td>

    <td class="text-right">
        {{ number_format($item->price_mj, 2, ',', ' ') }} €
        <span class="small-info">s DPH: {{ number_format($item->price_mj * 1.23, 2, ',', ' ') }} €</span>
    </td>

    <td class="text-right">{{ $zlavaPercento }} %</td>

    <td class="text-right">
        {{ number_format($spoluBezDph, 2, ',', ' ') }} €
        <span class="small-info" style="color:#000; font-weight:bold;">
            s DPH: {{ number_format($spoluSdpH, 2, ',', ' ') }} €
        </span>
    </td>
</tr>

{{-- POPIS IBA POD RIADKOM --}}
@if(!empty($finalPopis))
<tr class="description-row">
    <td colspan="6" style="padding-bottom: 10px; color: #555; font-size: 8.5px; border-bottom: 1px solid #eee;">
        {!! nl2br(e($finalPopis)) !!}
    </td>
</tr>
@endif

@endforeach
</tbody>
    </table>

    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td>Spolu bez DPH:</td>
                <td class="text-right">{{ number_format($ponuka->total_sum, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td>DPH (23%):</td>
                <td class="text-right">{{ number_format($ponuka->total_sum * 0.23, 2, ',', ' ') }} €</td>
            </tr>
            <tr>
                <td colspan="2" style="border: none; padding: 0;">
                    <div class="total-box">
                        <table width="100%" style="color: white; border:none;">
                            <tr>
                                <td style="border: none; padding: 0; font-weight: bold; text-transform: uppercase;">Celkom s DPH:</td>
                                <td style="border: none; padding: 0; text-align: right; font-size: 15px; font-weight: bold;">
                                    {{ number_format($ponuka->total_sum * 1.23, 2, ',', ' ') }} €
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>