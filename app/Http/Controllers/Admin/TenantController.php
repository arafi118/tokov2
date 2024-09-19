<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Repositories\HostnameRepository;
use Hyn\Tenancy\Repositories\WebsiteRepository;

class TenantController extends Controller
{
    public function index()
    {
        $data = Hostname::selectRaw('hostnames.id,hostnames.fqdn,websites.uuid,hostnames.created_at')
            ->leftJoin('websites', 'websites.id', '=', 'hostnames.website_id')->paginate(15);

        return view('backend.admin.tenant.index', compact('data'));
    }

    public function getData(Request $req, DataTables $dt)
    {
        $data = Hostname::selectRaw('hostnames.id,hostnames.fqdn,websites.uuid,hostnames.created_at')
            ->leftJoin('websites', 'websites.id', '=', 'hostnames.website_id');

        return $dt->eloquent($data)
            ->addIndexColumn()
            ->filter(function ($query) {
                if (($keyword = request()->input('search')) != '') {

                    $likes = "(LOWER(hostnames.fqdn) LIKE '%" . $keyword . "%' 
                                  OR LOWER(websites.uuid) LIKE '%" . $keyword . "%' 
                                 )";

                    $query->whereRaw($likes);
                }
            }, false)
            ->addcolumn('action', function ($row) use ($req) {
                $button = '<div class="flex items-center">';

                /*$button .= '<a class="btn btn-xs btn-warning" style="text-decoration:none;" href="'.route('admin.tenant.edit',['id'=>$row->id]).'" title="Detail Tenant">
                     <i class="fas fa-list-alt"></i>
                      </a>';*/

                $button .= '<a data-toggle="tooltip" title="Delete" href="#" onclick="deleteRow(\'delete-form-' . $row->id . '\', roleTable)">
                                    <button class="btn btn-xs btn-danger" id="' . $row->id . '">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </a>
                                <form id="delete-form-' . $row->id . '" action="' . route('admin.tenant.delete', ['id' => $row->id]) . '" method="POST" style="display: none;"><input name=_token value=' . csrf_token() . ' type=hidden></form>
                                ';


                $button .= '</div>';

                return $button;
            })->toJson();
    }

    public function create()
    {
        return view('backend.admin.tenant.form');
    }

    public function edit($id)
    {
        $tenant = Website::findOrFail($id);

        return view('backend.admin.tenant.edit_form', compact('tenant'));
    }

    public function update(Request $req, $id)
    {
        try {
            // Make Instance
            $website = Website::find($req->id);

            $website->uuid = $req->nama_tenant;
            app(WebsiteRepository::class)->update($website);

            $response = true;
        } catch (\Exception $e) {
            //\DB::rollback();
            \Log::error("admin.tenant.update: {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        // End Database Transaction
        //\DB::commit();

        // Return Response
        if ($req->ajax()) {
            return response()->json($response);
        } else {
            $req->session()->flash('status', 'Tenant dihapus');

            return redirect()->route('admin.tenant');
        }
    }

    public function store(Request $req)
    {
        // try {
        $rules = [
            'nama_tenant' => 'required|string',

        ];

        $messages = [
            'nama_tenant.required' => 'Nama Tenant Harus Diisi'
        ];

        $this->validate($req, $rules, $messages);

        $fqdn = sprintf('%s.%s', $req->nama_tenant, env('APP_DOMAIN'));

        $cek_exist = Hostname::where('fqdn', $fqdn)->first();

        if ($cek_exist != null) {
            if ($cek_exist->fqdn == $fqdn) {
                $req->session()->flash('status', 'Nama Tenant sudah dipakai');

                return redirect()->back();
            }
        }

        Artisan::call('tenant:create', ['fqdn' => $req->nama_tenant]);

        $seeder = [
            'GeneralSettingSeeder',
            'RoleSeeder',
            'UserSeeder',
            'RekeningSeeder',
            'DummySeeder'
        ];

        $website = Hostname::where('fqdn', $fqdn)->first();

        $data = [];
        foreach ($seeder as $key => $val) {
            $data[] = [
                'seeder' => $val,
                'website' => $website->website_id
            ];

            $cmd = Artisan::call('tenancy:db:seed', ['--class' => $val, '--website_id' => $website->website_id]);
        }

        $req->session()->flash('status', 'Tenant berhasil dibuat');

        return redirect()->route('admin.tenant');
        // } catch (\Exception $e) {
        // \Log::error("admin.tenant.store: {$e->getMessage()}");

        // Response Message
        // $response = false;
        // return abort(500);
        // }

    }

    public function delete(Request $req)
    {
        //\DB::beginTransaction();

        try {
            // Make Instance
            $db = Hostname::find($req->id);

            $hostname = \Hyn\Tenancy\Models\Hostname::where('fqdn', $db->fqdn)->first();
            $website = \Hyn\Tenancy\Models\Website::where('id', $hostname->website_id)->first();

            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);

            $response = true;
        } catch (\Exception $e) {
            //\DB::rollback();
            \Log::error("admin.tenant.delete: {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        // End Database Transaction
        //\DB::commit();

        // Return Response
        if ($req->ajax()) {
            return response()->json($response);
        } else {
            $req->session()->flash('status', 'Tenant dihapus');

            return redirect()->route('admin.tenant');
        }
    }
}
