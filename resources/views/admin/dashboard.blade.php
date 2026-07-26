<!DOCTYPE html>
<html>

<head>

<title>
Dashboard Admin MD Farma
</title>


<style>

body {
    font-family: Arial;
    background:#f5f7f9;
}


.container {
    width:90%;
    margin:auto;
}


.card {

    background:white;
    padding:20px;
    margin:15px;
    display:inline-block;
    width:200px;
    border-radius:10px;

}


table {

    width:100%;
    background:white;
    border-collapse:collapse;

}


td,th {

    padding:12px;
    border:1px solid #ddd;

}


a {

    color:#198754;

}

</style>


</head>


<body>


<div class="container">


<h1>
Dashboard Admin Apotek MD Farma
</h1>


<p>
Admin:
{{ session('admin_username') }}
</p>



<div class="card">

<h3>
Total Konsultasi
</h3>

<h2>
{{ $totalConsultation }}
</h2>

</div>



<div class="card">

<h3>
Chat Aktif
</h3>

<h2>
{{ $activeChat }}
</h2>

</div>



<div class="card">

<h3>
Resep
</h3>

<h2>
{{ $resep }}
</h2>

</div>



<div class="card">

<h3>
Non Resep
</h3>

<h2>
{{ $nonResep }}
</h2>

</div>



<hr>


<h2>
Daftar Konsultasi
</h2>


<table>


<tr>

<th>
Nama
</th>

<th>
Umur
</th>

<th>
Jenis
</th>

<th>
Status
</th>

<th>
Aksi
</th>

</tr>



@foreach($consultations as $data)

<tr>

<td>
{{ $data->nama }}
</td>


<td>
{{ $data->umur }}
</td>


<td>
{{ $data->jenis_konsultasi }}
</td>


<td>
{{ $data->status }}
</td>


<td>

<a href="/chat/{{ $data->id }}">
Buka Chat
</a>

</td>


</tr>


@endforeach


</table>



<br>


<a href="/admin/logout">
Logout
</a>


</div>


</body>

</html>