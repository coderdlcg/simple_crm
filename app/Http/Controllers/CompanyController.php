<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $companies = Company::all();

        if ($request->ajax()) {
            $token = csrf_token();

            return datatables()->of($companies)
                ->addColumn('action', function ($row) use ($token) {
                    $html = '<a href="companies/'.$row->id.'" class="btn btn-xs btn-secondary">Show</a> ';
                    $html .= '<a href="companies/'.$row->id.'/edit" class="btn btn-xs btn-secondary">Edit</a> ';
                    $html .= '<a href="#" id="destroy-item-'.$row->id.'" data-url="companies/'.$row->id.'" data-token="'.$token.'" class="btn btn-xs btn-danger" onclick="destroyItem('.$row->id.')">Del</a>';
                    return $html;
                })->toJson();
        }

        return view('companies.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies'],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $data = $request->except('logo', '_token');

        Company::create($data);

        return redirect(route('companies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return abort(404);
        }

        return view('companies.show', compact([
            'company'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Company::find($id);

        if (!$company) {
            return abort(404);
        }

        return view('companies.edit', compact([
            'company'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email,'.$id],
            'address' => ['required', 'string', 'max:255'],
        ]);

        $data = $request->except('logo', '_token', '_method');

        $company = Company::find($id);
        if (!$company) {
            return abort(404);
        }

        $company->update($data);

        return redirect(route('companies.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Company::destroy($id);

        return redirect(route('companies.index'));
    }
}
