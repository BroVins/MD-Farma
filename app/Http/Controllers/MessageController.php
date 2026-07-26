<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;


class MessageController extends Controller
{

    // Pesan dari pasien
    public function store(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ]);


        Message::create([

            'consultation_id' => $id,

            'sender' => 'user',

            'message' => $request->message

        ]);


        return redirect('/chat/'.$id);
    }



    // Balasan admin
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required'
        ]);


        Message::create([

            'consultation_id' => $id,

            'sender' => 'admin',

            'message' => $request->message

        ]);


        return redirect('/chat/'.$id);

    }

}