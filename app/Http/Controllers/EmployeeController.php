<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $employees = Employee::paginate(10);

        return view('employees.index', compact([
            'employees'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $http_referer = $request->server->get('HTTP_REFERER');

        if (strpos($http_referer, $request->path()) === false) {
            session([
                'creation_employee_from' => $http_referer
            ]);
        }

        $company_id = $request->get('company_id');

        return view('employees.create', compact([
            'company_id'
        ]));
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
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:employees'],
            'phone' => ['nullable', 'digits:10', 'unique:employees'],
            'company_id' => ['required', 'numeric', 'max:20'],
        ]);

        $data = $request->except('_token');

        Employee::create($data);

        $url = $request->session()->pull('creation_employee_from', route('employees.index'));

        return redirect($url);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\View\View|void
     */
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return abort(404);
        }

        return view('employees.show', compact([
            'employee'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $company_id = $request->get('company_id');
        $http_referer = $request->server->get('HTTP_REFERER');

        if (strpos($http_referer, $request->path()) === false) {
            session([
                'creation_employee_from' => $http_referer
            ]);
        }

        $employee = Employee::find($id);

        if (!$employee) {
            return abort(404);
        }

        return view('employees.edit', compact([
            'employee',
            'company_id'
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
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:employees,email,'.$id],
            'phone' => ['required', 'digits:10', 'unique:employees,phone,'.$id],
        ]);

        $data = $request->except('_token', '_method');

        $employee = Employee::find($id);
        if (!$employee) {
            return abort(404);
        }

        $employee->update($data);

        $url = $request->session()->pull('creation_employee_from', route('employees.index'));

        return redirect($url);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Employee::destroy($id);

        return back();
    }
}
