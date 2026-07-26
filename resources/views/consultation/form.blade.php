<!DOCTYPE html>
<html>
<head>
    <title>Konsultasi Obat - Apotek MD Farma</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background:#f5f7f9;
        }

        .container {
            width:400px;
            margin:40px auto;
            background:white;
            padding:25px;
            border-radius:10px;
        }

        input, select, textarea {
            width:100%;
            padding:10px;
            margin-bottom:15px;
        }

        button {
            width:100%;
            padding:12px;
            background:#198754;
            color:white;
            border:none;
            border-radius:5px;
        }

        h2 {
            text-align:center;
        }
    </style>

</head>

<body>

<div class="container">

<h2>
Konsultasi Obat
</h2>


<form action="/konsultasi" method="POST">

@csrf


<label>
Nama Lengkap
</label>

<input 
type="text" 
name="nama"
required>


<label>
Umur
</label>

<input 
type="number" 
name="umur"
required>


<label>
Nomor HP
</label>

<input 
type="text" 
name="no_hp"
required>


<label>
Jenis Konsultasi
</label>

<select name="jenis_konsultasi">

<option value="resep">
Resep Dokter
</option>

<option value="non_resep">
Non Resep
</option>

</select>


<button>
Mulai Konsultasi
</button>


</form>


</div>


</body>
</html>