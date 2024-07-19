<?php

namespace App\Http\Controllers;

use App\Models\Smtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmtpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()) {
            abort(403, 'Unauthorized access');
        }
        $getSMTPData = Smtp::all();
        return view('smtp.index',compact('getSMTPData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('smtp.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'domain' => 'required',
            'fromname' => 'required',
            'mailfrom' => 'required|email',
            'api' => 'required',
            'type' => 'required',
            'emailtemplatepath' => 'required'
        ]);
        $saveSMTPData = new Smtp();
        $saveSMTPData->name = $request->name;
        $saveSMTPData->domain = $request->domain;
        $saveSMTPData->email = $request->fromname;
        $saveSMTPData->mailfrom = $request->mailfrom;
        $saveSMTPData->api = $request->api;
        $saveSMTPData->type = $request->type;
        $saveSMTPData->emailtemplatepath = $request->emailtemplatepath;
        if($saveSMTPData){
            $saveSMTPData->save();
            return redirect()->route('smtp.index')->with('success', 'SMTP added successfully!');
        }else{
            return redirect()->route('smtp.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Smtp $smtp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Smtp $smtp)
    {
        $editSMTP = Smtp::findOrFail($smtp->id);
        return view('smtp.edit',compact('editSMTP'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Smtp $smtp)
    {
        $request->validate([
            'name' => 'required',
            'domain' => 'required',
            'fromname' => 'required',
            'mailfrom' => 'required|email',
            'api' => 'required',
            'type' => 'required',
            'emailtemplatepath' => 'required'      
        ]);
        $updateSMTPData = Smtp::findOrFail($smtp->id);
        $updateSMTPData->name = $request->name;
        $updateSMTPData->domain = $request->domain;
        $updateSMTPData->email = $request->fromname;
        $updateSMTPData->mailfrom = $request->mailfrom;
        $updateSMTPData->api = $request->api;
        $updateSMTPData->type = $request->type;
        $updateSMTPData->emailtemplatepath = $request->emailtemplatepath;
        if($updateSMTPData){
            $updateSMTPData->update();
            return redirect()->route('smtp.index')->with('success', 'SMTP updated successfully!');
        }else{
            return redirect()->route('smtp.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Smtp $smtp)
    {
        $data = Smtp::find($smtp->id)->delete();
        return redirect(route('smtp.index'))->with('success', 'SMTP Deleted successfully.');
    }

    public function changeStatus(Request $request, $id, $status){
        $changeStat = Smtp::findOrFail($id);
        if($changeStat){
            $changeStat->status = $status;
            $changeStat->update();
            return redirect()->route('smtp.index')->with('success', 'Status updated successfully!');
        }
    }
}
