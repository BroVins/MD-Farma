<!DOCTYPE html>
<html>
<head>
    <title>Live Chat Apotek MD Farma</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7f9;
            margin: 0;
        }


        .container {
            width: 700px;
            margin: 30px auto;
        }


        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }


        h1 {
            text-align: center;
            color: #198754;
        }


        .patient p {
            margin: 8px 0;
        }


        .chat-box {

            height: 350px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 15px;
            background: #fafafa;

        }


        .message {

            background: #198754;
            color: white;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            width: fit-content;
            max-width: 70%;

        }


        .admin {

            background: #555;
            margin-left: auto;

        }


        .message img {

            margin-top: 10px;
            border-radius: 5px;

        }


        input[type="text"] {

            width: 70%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #ccc;

        }


        input[type="file"] {

            margin-top: 10px;
            margin-bottom: 10px;

        }


        button {

            padding: 12px 20px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;

        }


        .admin-title {

            color: #555;

        }


        .info {

            font-size: 14px;
            color: #666;

        }

    </style>

</head>


<body>


<div class="container">


<h1>
Live Chat Apotek MD Farma
</h1>



<!-- DATA PASIEN -->

<div class="card patient">

<h3>
Data Pasien
</h3>


<p>
<strong>Nama:</strong>
{{ $consultation->nama }}
</p>


<p>
<strong>Umur:</strong>
{{ $consultation->umur }}
</p>


<p>
<strong>No HP:</strong>
{{ $consultation->no_hp }}
</p>


<p>
<strong>Jenis Konsultasi:</strong>
{{ $consultation->jenis_konsultasi }}
</p>


</div>





<!-- RIWAYAT CHAT -->

<div class="card">


<h3>
Riwayat Chat
</h3>



<div class="chat-box">


@if($consultation->messages->count() == 0)

<p>
Belum ada pesan.
</p>

@endif



@foreach($consultation->messages as $chat)


<div class="message 
@if($chat->sender == 'admin')
admin
@endif
">


<strong>
{{ ucfirst($chat->sender) }}
</strong>


<br>


{{ $chat->message }}



@if($chat->image)

<br>

<img 
src="{{ asset('storage/'.$chat->image) }}"
width="150">

@endif



</div>



@endforeach



</div>


</div>





<!-- FORM PASIEN -->

<div class="card">


<h3>
Kirim Pesan Pasien
</h3>



<form 
action="/chat/{{ $consultation->id }}/send" 
method="POST"
enctype="multipart/form-data">


@csrf


<input 
type="text"
name="message"
placeholder="Tulis pesan..."
>


<br>


<input
type="file"
name="image"
>


<br>


<button type="submit">
Kirim Pesan
</button>


</form>


</div>






<!-- FORM ADMIN -->

<div class="card">


<h3 class="admin-title">
Balasan Apoteker
</h3>



<form 
action="/chat/{{ $consultation->id }}/reply" 
method="POST">


@csrf


<input 
type="text"
name="message"
placeholder="Balas pasien..."
required>



<button type="submit">
Kirim Balasan
</button>


</form>


<p class="info">

Pesan akan dibalas oleh apoteker sesuai jam operasional Apotek MD Farma.

</p>


</div>



</div>


</body>

</html>