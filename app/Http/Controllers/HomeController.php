<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Connection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

       // return view('home');
       $user = Auth::user();

       // Get suggestions
       $suggestions = User::whereDoesntHave('connections', function($query) use ($user) {
           $query->where('connections.user_id', $user->id)
                 ->orWhere('connections.connected_user_id', $user->id);
       })->get();
       $sugestion_count =$suggestions->count();


       // Get sent requests
       $sentRequests = $user->sentConnections()->where('status', 'requested')->get();
       $send_request_count = $sentRequests->count();
    //    dd($sentRequests);
       // Get received requests
       $receivedRequests = $user->receivedConnections()->where('status', 'withdrawn')->get();
        $recieve_count =  $receivedRequests->count();

       // Get connections
       $connections = $user->connections()->where('status', 'connected')->get();
       $connection_count = $connections->count();
       // dd($connections);


       return view('home', compact('connection_count','send_request_count','recieve_count','suggestions', 'sentRequests', 'receivedRequests', 'connections','sugestion_count'));

    }

    public function getSuggestions()
    {
        $user = Auth::user();
        $suggestions = User::whereNotIn('id', $user->connections()->pluck('connected_user_id'))
            ->whereNotIn('id', $user->sentConnections()->pluck('connected_user_id'))
            ->whereNotIn('id', $user->receivedConnections()->pluck('user_id'))
            ->get();

        return view('connections.suggestions', compact('suggestions'));
    }

    public function connect(Request $request, $id)
    {
        $user = Auth::user();
        $connection = Connection::create([
            'user_id' => $user->id,
            'connected_user_id' => $id,
            'status' => 'requested'
        ]);

        return response()->json($connection);
    }

    public function sentRequests()
    {
        $user = Auth::user();
        $sentRequests = $user->sentConnections()->with('connectedUser')->get();

        return view('connections.sent_requests', compact('sentRequests'));
    }

    public function withdrawRequest(Request $request, $id)
    {
        $user = Auth::user();
        $connection = Connection::where([
            ['user_id', $user->id],
            ['connected_user_id', $id],
        ])->first();
        $connection->status = 'withdrawn';
        $connection->save();

        return response()->json($connection);
    }

    public function receivedRequests()
    {
        $user = Auth::user();
        $receivedRequests = $user->receivedConnections()->with('user')->get();

        return view('connections.received_requests', compact('receivedRequests'));
    }

    public function accept(Request $request, $id)
    {
        $user = Auth::user();
        $connection = Connection::where([
            ['connected_user_id', $user->id],
            ['user_id', $id],
        ])->first();
        $connection->status = 'connected';
        $connection->save();

        return response()->json($connection);
    }

    public function connections()
    {
        $user = Auth::user();
        $connections = $user->connections()->with('connectedUser')->get();

        return view('connections.connections', compact('connections'));
    }

}
