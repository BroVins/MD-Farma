<!DOCTYPE html>
<html>
<head>
    <title>Login Admin - Apotek MD Farma</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f7f9;
        }

        .container {
            width: 350px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #198754;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #198754;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>


<div class="container">


<h2>
Login Admin
</h2>


@if(session('error'))

<p class="error">
{{ session('error') }}
</p>

@endif



<form action="/admin/login" method="POST">

@csrf


<label>
Username
</label>

<input 
type="text"
name="username"
required>



<label>
Password
</label>

<input 
type="password"
name="password"
required>



<button type="submit">
Login
</button>


</form>


</div>


</body>
</html>