<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Archív ponúk | DÖRKEN</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<style>
:root{
    --dorken:#003399;
    --bg:#f8f9fa;
    --border:#dee2e6;
    --danger:#dc3545;
}

body{
    margin:0;
    background:var(--bg);
    font-family:'Segoe UI',sans-serif;
    font-size:13px;
}

.navbar{
    background:var(--dorken);
    color:#fff;
    padding:10px 0;
}

.navbar-inner{
    max-width:1500px;
    margin:auto;
    padding:0 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.nav-links a{
    color:#fff;
    text-decoration:none;
    margin-left:20px;
    font-weight:bold;
    font-size:11px;
    opacity:.8;
}

.nav-links a.active{opacity:1;}

.container{
    max-width:1500px;
    margin:auto;
    padding:25px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

.search{
    padding:8px 12px;
    border:1px solid #ccc;
    border-radius:4px;
    width:260px;
}

.bulk-btn{
    padding:8px 12px;
    background:#fff;
    color:var(--danger);
    border:1px solid #f5c2c7;
    border-radius:4px;
    font-weight:bold;
    cursor:pointer;
    display:none;
    margin-left:10px;
}

.card{
    background:white;
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,.06);
}

table{
    width:100%;
    border-collapse:collapse;
}

thead{
    background:#f1f3f5;
}

th{
    padding:10px;
    font-size:11px;
    text-align:left;
    border-bottom:2px solid #ddd;
    color:var(--dorken);
    cursor:pointer;
    position:relative;
}

td{
    padding:10px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#fafcff;
}

.project{
    color:#666;
    font-size:12px;
}

.price{
    text-align:right;
    font-weight:bold;
    color:#198754;
}

.actions{
    display:flex;
    justify-content:flex-end;
    gap:5px;
}

.btn{
    width:28px;
    height:28px;
    border:1px solid #ccc;
    background:white;
    border-radius:4px;
    cursor:pointer;
    font-size:13px;
}

.btn:hover{
    border-color:var(--dorken);
}

.filter-input{
    width:100%;
    margin-top:6px;
    padding:5px;
    border:1px solid #ccc;
    border-radius:4px;
    font-size:11px;
}
</style>
</head>
<body>

<div class="navbar">
    <div class="navbar-inner">
        <b>DÖRKEN</b>
        <div class="nav-links">
            <a href="{{ url('/ponuka') }}">NOVÁ PONUKA</a>
            <a href="{{ url('/zakaznici') }}">ZÁKAZNÍCI</a>
            <a href="{{ url('/produkty') }}">PRODUKTY</a>
            <a href="{{ url('/archiv') }}" class="active">ARCHÍV</a>
        </div>
    </div>
</div>

<div class="container">

    <div class="header">
        <h2 style="margin:0;color:var(--dorken)">Archív ponúk</h2>

        <div>
            <input type="text" id="globalSearch" class="search" placeholder="Hľadať..." onkeyup="filterRows()">
            <button id="bulkDelete" class="bulk-btn" onclick="deleteSelected()">Vymazať vybrané</button>
        </div>
    </div>

    <div class="card">
        <table id="archiveTable">
            <thead>
                <tr>
                    <th width="35"><input type="checkbox" onclick="toggleAll(this)"></th>
                    <th>ID</th>
                    <th>Dátum</th>
                    <th onclick="addColumnFilter(this,3)">Zákazník</th>
                    <th onclick="addColumnFilter(this,4)">Názov projektu / poznámka</th>
                    <th style="text-align:right;">Suma</th>
                    <th style="text-align:right;">Akcie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ponuky as $p)
                    <tr ondblclick="window.location.href='{{ url('/archiv/detail/' . $p->id) }}'" 
                        style="cursor: pointer;" 
                        title="Dvojklikom otvorte detail">
                        
                        {{-- 1. Checkbox --}}
                        <td><input type="checkbox" class="rowCheck" value="{{ $p->id }}" onchange="updateBulk()"></td>
                        
                        {{-- 2. ID --}}
                        <td>#{{ $p->id }}</td>
                        
                        {{-- 3. Dátum --}}
                        <td>{{ date('d.m.Y', strtotime($p->created_at)) }}</td>
                        
                        {{-- 4. Klient --}}
                        <td>{{ $p->customer_name }}</td>
                        
                        {{-- 5. NÁZOV PROJEKTU (TU JE TA ZMENA) --}}
                        <td class="project" style="padding: 0; min-width: 200px;">
                            <input type="text" 
                                value="{{ $p->title }}" 
                                onchange="updateProjectTitle({{ $p->id }}, this.value)"
                                onclick="event.stopPropagation()" 
                                style="width: 100%; border: none; background: transparent; padding: 10px; font-weight: bold; outline: none; display: block;"
                                onfocus="this.style.background='#fff'; this.style.boxShadow='inset 0 0 3px rgba(0,0,0,0.2)';"
                                onblur="this.style.background='transparent'; this.style.boxShadow='none';">
                        </td>

                        {{-- 6. Cena --}}
                        <td class="price">{{ number_format($p->total_sum, 2, ',', ' ') }} €</td>
                        
                        {{-- 7. Akcie --}}
                        <td>
                            <div class="actions">
                                <a href="{{ url('/archiv/detail/'.$p->id) }}"><button class="btn" onclick="event.stopPropagation()">👁</button></a>
                                <button class="btn" onclick="event.stopPropagation(); deleteSingle({{ $p->id }})">🗑</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function filterRows(){
    let q=document.getElementById('globalSearch').value.toLowerCase();
    document.querySelectorAll('#archiveTable tbody tr').forEach(r=>{
        r.style.display=r.innerText.toLowerCase().includes(q)?'':'none';
    });
}

function addColumnFilter(th,index){
    if(th.querySelector('input')) return;
    th.innerHTML += `<input class="filter-input" onkeyup="filterColumn(${index},this.value)" onclick="event.stopPropagation()">`;
    th.querySelector('input').focus();
}

function filterColumn(index,val){
    val=val.toLowerCase();
    document.querySelectorAll('#archiveTable tbody tr').forEach(r=>{
        let txt=r.cells[index].innerText.toLowerCase();
        r.style.display=txt.includes(val)?'':'none';
    });
}

function toggleAll(m){
    document.querySelectorAll('.rowCheck').forEach(c=>c.checked=m.checked);
    updateBulk();
}

function updateBulk(){
    let count=document.querySelectorAll('.rowCheck:checked').length;
    let btn=document.getElementById('bulkDelete');
    btn.style.display=count?'inline-block':'none';
    btn.innerText='Vymazať vybrané ('+count+')';
}

function deleteSingle(id){
    if(!confirm('Odstrániť ponuku?')) return;

    axios.delete("{{ url('/delete-ponuka') }}/" + id)
    .then(()=>{
        document.getElementById('row-'+id).remove();
    })
    .catch(err=>{
        console.log(err);
        alert('Mazanie zlyhalo');
    });
}

function deleteSelected(){
    let ids = [...document.querySelectorAll('.rowCheck:checked')].map(x => x.value);

    if(ids.length === 0){
        alert('Najprv vyber ponuky');
        return;
    }

    if(!confirm('Odstrániť vybrané ponuky?')) return;

    axios.post("{{ url('/delete-multiple-ponuky') }}", {
        ids: ids,
        _token: document.querySelector('meta[name="csrf-token"]').content
    })
    .then(()=>{
        location.reload();
    })
    .catch(err=>{
        console.log(err);
        alert('Mazanie zlyhalo');
    });
}
</script>

</body>
</html>