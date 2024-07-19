<?php

namespace App\Http\Controllers;

use App\Models\Crm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrmController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()) {
            abort(403, 'Unauthorized access');
        }
        $getCRMData = Crm::all();
        return view('crm.index',compact('getCRMData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {        
        return view('crm.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'providerlabel' => 'required',
            'apiendpoint' => 'required|url',
            'apiusername' => 'required',
            'apipassword' => 'required',
            'crmtype' => 'required',           
        ],[
            'providerlabel.required' => 'The Provider field is required.',
            'apiendpoint.required' => 'The API endpoint field is required.',
            'apiusername.required' => 'The API username field is required.',
            'apipassword.required' => 'The API password field is required.',
        ]);
        $saveCRMData = new Crm();
        $saveCRMData->providerlabel = $request->providerlabel;
        $saveCRMData->apiendpoint = $request->apiendpoint;
        $saveCRMData->apiusername = $request->apiusername;
        $saveCRMData->apipassword = $request->apipassword;
        $saveCRMData->crmtype = $request->crmtype;
        if($saveCRMData){
            $saveCRMData->save();
            return redirect()->route('crm.index')->with('success', 'CRM added successfully!');
        }else{
            return redirect()->route('crm.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Crm $crm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Crm $crm)
    {
        $editCRM = Crm::findOrFail($crm->id);
        return view('crm.edit',compact('editCRM'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Crm $crm)
    {
        $request->validate([
            'providerlabel' => 'required',
            'apiendpoint' => 'required|url',
            'apiusername' => 'required',
            'apipassword' => 'required',
            'crmtype' => 'required',           
        ],
        [
            'providerlabel.required' => 'The Provider field is required.',
            'apiendpoint.required' => 'The API endpoint field is required.',
            'apiusername.required' => 'The API username field is required.',
            'apipassword.required' => 'The API password field is required.',
        ]);
        $saveCRMData = Crm::findOrFail($crm->id);
        $saveCRMData->providerlabel = $request->providerlabel;
        $saveCRMData->apiendpoint = $request->apiendpoint;
        $saveCRMData->apiusername = $request->apiusername;
        $saveCRMData->apipassword = $request->apipassword;
        $saveCRMData->crmtype = $request->crmtype;
        if($saveCRMData){
            $saveCRMData->update();
            return redirect()->route('crm.index')->with('success', 'CRM updated successfully!');
        }else{
            return redirect()->route('crm.index')->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crm $crm)
    {
        $data = Crm::find($crm->id)->delete();
        return redirect(route('crm.index'))->with('success', 'CRM Deleted successfully.');
    }

    public function changeStatus(Request $request, $id, $status){
        $changeStat = Crm::findOrFail($id);
        if($changeStat){
            $changeStat->status = $status;
            $changeStat->update();
            return redirect()->route('crm.index')->with('success', 'Status updated successfully!');
        }
    }
}
