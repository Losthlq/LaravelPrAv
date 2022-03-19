<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Message;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\Feedback;
use Pavelpage\Censorship\Censor;
use Wkhooy\ObsceneCensorRus;

class TicketController extends Controller
{

    public function index()
    {
        return Ticket::all();
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => 'email:rfc,dns',
                'author' => ['required',
                    Rule::in(['client', 'manager'])],
                'name' => 'required|min:2|max:50',
            ]
        );

        $messageText = $request->input('message');
        ObsceneCensorRus::filterText($messageText);

        $message = new Message();
        $message->content = htmlentities($messageText);
        $message->author = $request->input('author');
        $message->save();

        $ticket = new Ticket();
        $ticket->subject = $message->id;
        $ticket->user_name = $request->input('name');
        $ticket->user_email = $request->input('email');
        $ticket->uid = 'HTX-' . $message->id;

        if ($ticket->save()) {
            //отправка почты не работает так как нет аккаунта
            /*
            $params = [
                'value' => 'Ваш Ticket был создан'
            ];
            Mail::to('example@domain.ru')->send(new Feedback($params));
            */

            return response()->json([
                'status' => true,
                //'result' => $ticket
                'result' => $ticket
            ], 201);
        } else {
            return response()->json([
                'status' => false,
                'result' => null
            ], 401);
        }
    }

    public function show($id)
    {
        return response()->json([
            'status' => false,
            'result' => null
        ], 401);
    }

    public function update(Request $request, $id)
    {
        return response()->json([
            'status'=> false,
            'result' => null
        ], 401);
    }

    public function destroy($id)
    {
        return response()->json([
            'status'=> false,
            'result' => null
        ], 401);
    }

}
