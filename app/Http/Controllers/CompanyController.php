<?php

namespace App\Http\Controllers;

use App\Company;
use App\Services\YandexMapService;
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

        $params = [
            'geocode' => $data['address'], // адрес (город, улица, номер дома)
            'format'  => 'json', // формат ответа
            'results' => 1, // количество выводимых результатов
            'key'     => config('yandexapi.key'), // ваш api key
        ];

        $response = YandexMapService::getGeoCode($params);

        if (isset($response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
            $coordinates = $response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            $coordinates = explode(' ', $coordinates);
            $data['coordinates'] = json_encode([
                'longitude' => $coordinates[0],
                'latitude' => $coordinates[1]
            ]);
        }

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

        $key = config('yandexapi.key');

        if ($company->coordinates) {
            $company->coordinates = json_decode($company->coordinates);
        }

        return view('companies.show', compact([
            'company',
            'key'
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

        $key = config('yandexapi.key');

        if ($company->coordinates) {
            $company->coordinates = json_decode($company->coordinates);
        }

        return view('companies.edit', compact([
            'company',
            'key'
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

        $params = [
            'geocode' => $data['address'], // адрес (город, улица, номер дома)
            'format'  => 'json', // формат ответа
            'results' => 1, // количество выводимых результатов
            'key'     => config('yandexapi.key'), // ваш api key
        ];

        $response = YandexMapService::getGeoCode($params);

        if (isset($response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'])) {
            $coordinates = $response['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            $coordinates = explode(' ', $coordinates);
            $data['coordinates'] = json_encode([
                'longitude' => $coordinates[0],
                'latitude' => $coordinates[1]
            ]);
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
