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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies'],
            'address' => ['required', 'string', 'max:255'],
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=300,max_height=300',
        ]);

        $data = $request->except('_token');

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

        $company = Company::create($data);

        if($company && $request->hasFile('logo')){
            $url_store = '/uploads/companies/'.$company->id.'/';

            $logo = $request->file('logo');
            $logo_name = 'logo.'. $logo->getClientOriginalExtension();
            $logo_save = public_path($url_store);
            $logo->move($logo_save, $logo_name);

            $company->logo = $url_store . $logo_name;
            $company->save();
        }

        return redirect(route('companies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:companies,email,'.$id],
            'address' => ['required', 'string', 'max:255'],
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=300,max_height=300',
        ]);

        if($request->hasFile('logo')){
            $url_store = '/uploads/companies/'.$id.'/';

            $logo = $request->file('logo');
            $logo_name = 'logo.'. $logo->getClientOriginalExtension();
            $logo_save = public_path($url_store);
            $logo->move($logo_save, $logo_name);

            $data['logo'] =  $url_store . $logo_name;
        }

        $data = $request->except('_token', '_method');

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Company::destroy($id);

        return redirect(route('companies.index'));
    }
}
