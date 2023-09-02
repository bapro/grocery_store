<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\User;
use App\Notifications\TicketUpdatedNotification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
       //get all tickets unorderly $tickets= Ticket::all();
       // get ticket created by this current user, we can do that due the the relationship created in each table
       $users = Auth()->user();
       //if user is admin give me all tickets, if not all ticket of current user
       $tickets =$users->isAdmin ? Ticket::latest()->get(): $users->tickets;
        return view('ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
     
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id()
            
        ]);

        if($request->file('attachment')){

        $path= Storage::disk('public')->put('users-tickets', $request->file('attachment'));
        $ticket->update(['attachment' => $path]);
        }
//dd($request->file('attachment'));
        return Redirect::route('ticket.index')->with('message_ticket', 'A new ticket has been created.');
        //return response($ticket);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
       
    return view('ticket.show', compact('ticket'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('ticket.edit', compact('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {

        if($ticket->status){
           // $user = User::find($ticket->user_id);
             // this eloquent relationship can be used after creating the relation in each table (User, and Ticket)
            $ticket->user->notify(new TicketUpdatedNotification($ticket));
        //     To see the email that will be send
         // return (new TicketUpdatedNotification($ticket))->toMail($user);
        }
       // dd($ticket->attachment);
        $ticket->update($request->except('attachment'));

        if($request->file('attachment')){
           Storage::disk('public')->delete($ticket->attachment);
           $path= Storage::disk('public')->put('users-tickets', $request->file('attachment'));
           $ticket->update(['attachment' => $path]);
            }
    
 
        return redirect(route('ticket.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect(route('ticket.index'));
    }
}
