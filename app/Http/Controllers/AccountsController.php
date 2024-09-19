<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use App\Account;
use App\TbRekening;
use App\TbJenisTransaksi;
use App\TbKonfigurasiTransaksi;
use App\Payment;
use App\Returns;
use App\ReturnPurchase;
use App\Expense;
use App\Payroll;
use App\MoneyTransfer;
use App\Warehouse;
use DB;
use Excel;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;
use App\Imports\SaldoAwalImport;
use App\Exports\SaldoAwalExport;

class AccountsController extends Controller
{
    protected $catTree    = array();
    protected $countDepth = 0;

    public function indexOld()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('account-index')){
            $lims_account_all = Account::where('is_active', true)->get();
            return view('backend.account.index', compact('lims_account_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('account-index')){
            $lims_account_all = TbRekening::orderBy('kode','asc')->get();
            return view('backend.account.index', compact('lims_account_all'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function getTree($test,$is_array)
    {

        $this->catTree[] = $is_array == 1 ? $test->toArray() : $test;

        foreach ($test->children as $child) {
            $this->getTree($child,$is_array);
        }
    }

    public function depth($parent)
    {
        $cek = TbRekening::where('id',$parent)->first();
        
        if($cek->parent_id !=0){
            
            $this->depth($cek->parent_id);
            $this->countDepth ++;
        }
        
    }


    public function create()
    {
        $dakun    = TbRekening::select('id','parent_id','kode','nama')->where('parent_id',0)->get();
        
        foreach ($dakun as $parent) {

             $this->getTree($parent,1);
        }

        $drop = $this->catTree;
        
        return view('backend.account.form',compact('drop'));
    }

    public function store(Request $req)
    {
        \DB::beginTransaction();

        try {
            if($req->parent_id != 0){
                $this->depth($req->parent_id);
            }
            
            $slug = Str::slug($req->name);

            if($req->account_id !=''){
                $rek = TbRekening::find($req->account_id);
            }else{
                $rek = new TbRekening;
            }
      
            $rek->slug = $slug;
            $rek->nama = $req->name;
            $rek->kode = $req->account_no;
            $rek->parent_id  = $req->parent_id == null ? 0 : $req->parent_id;
            $rek->depth      = $req->parent_id == 0 ? 0 : $this->countDepth + 1;
            $rek->no_rek_bank   = $req->no_rek_bank;
            $rek->atas_nama_rek = $req->atas_nama_rek;
            $rek->save();

            $pid = $req->account_id !='' ? $req->account_id : $rek->id;

            if($req->no_rek_bank !='' && $req->atas_nama_rek !=''){
                $jenis_transaksi = [
                      ['transaksi' =>'Penjualan (HPP)',
                       'debit'=>'bank',
                       'kredit'=>'1.1.03.01'
                      ],
                      ['transaksi'=>'Laba Penjualan',
                        'debit'=>'bank',
                        'kredit'=>'4.1.01.01'
                      ],
                      ['transaksi'=> 'PPN Penjualan',
                       'debit'=>'bank',
                       'kredit'=>'2.1.02.01'
                      ],
                      ['transaksi'=>'Discount Penjualan',
                       'debit'=>'5.1.04.01',
                       'kredit'=>'bank'
                      ],
                      [
                       'transaksi'=>'Ongkos Kirim Penjualan',
                       'debit'=>'5.1.05.02',
                       'kredit'=>'bank'
                      ],
                      [
                      'transaksi'=>'Penjualan (HPP) PO',
                       'debit'=>'bank',
                       'kredit'=>'2.1.01.02'
                      ],
                      ['transaksi'=> 'Laba Penjualan PO',
                       'debit'=>'bank',
                       'kredit'=>'2.1.01.02'
                      ],
                      ['transaksi'=>'PPN Penjualan PO',
                       'debit'=>'bank',
                       'kredit'=>'2.1.02.01'
                      ],
                      ['transaksi'=>'Discount Penjualan PO',
                       'debit'=>'5.1.04.01',
                       'kredit'=>'bank'
                      ],
                      // ['transaksi'=>'Ongkos Kirim Penjualan PO',
                      //  'debit'=>,
                      //  'kredit'=>
                      // ],
                      ['transaksi'=>'Pembelian',
                       'debit'=>'1.1.03.01',
                       'kredit'=>'bank'
                      ],
                      ['transaksi'=>'PPN Pembelian',
                       'debit'=>'5.4.01.01',
                       'kredit'=>'bank'
                      ],
                      ['transaksi'=> 'Discount Pembelian',
                       'debit'=>'bank',
                       'kredit'=>'4.1.01.02'
                      ],
                      ['transaksi'=> 'Ongkos Kirim Pembelian',
                       'debit'=>'5.1.05.02',
                       'kredit'=>'bank'
                      ],
                      ['transaksi'=>'Pembelian PO',
                       'debit'=>'1.1.04.01',
                       'kredit'=>'bank'
                      ],
                      ['transaksi'=>'PPN Pembelian PO',
                       'debit'=>'5.4.01.01',
                       'kredit'=>'bank'
                      ],
                      ['transaksi'=>'Discount Pembelian PO',
                       'debit'=>'bank',
                       'kredit'=>'4.1.01.02'
                      ],
                      // ['transaksi'=> 'Ongkos Kirim Pembelian PO',
                      //  'debit'=>,
                      //  'kredit'=>
                      // ],
                ];

                foreach ($jenis_transaksi as $key => $value) {

                    $jt    = TbJenisTransaksi::where('nama',$value['transaksi'])->first();
                    
                    if($value['debit'] == 'bank'){
                        $rekening_debit_id = $pid;
                    }else{
                        $rekening_debit = TbRekening::where('kode',$value['debit'])->first();
                        $rekening_debit_id = $rekening_debit->id;
                    }

                    if($value['kredit'] == 'bank'){
                        $rekening_kredit_id = $pid;
                    }else{

                        $rekening_kredit = TbRekening::where('kode',$value['kredit'])->first();
                        $rekening_kredit_id = $rekening_kredit->id;
                    }

                    $cek_konf = TbKonfigurasiTransaksi::where('tb_jenis_transaksi_id',$jt->id)
                                                      ->where('cara_bayar','transfer')
                                                      ->where('rekening_debit_id',$rekening_debit_id)  
                                                      ->where('rekening_kredit_id',$rekening_kredit_id)
                                                      ->first();

                    if($cek_konf !=null){
                        $konf = $cek_konf;
                    }else{
                        $konf = new TbKonfigurasiTransaksi;
                    }  
                    
                    $konf->tb_jenis_transaksi_id = $jt->id;
                    $konf->cara_bayar            = 'transfer';
                    $konf->rekening_debit_id     = $rekening_debit_id;
                    $konf->rekening_kredit_id    = $rekening_kredit_id;
                    $konf->save();
                }
            }

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("store accounts : {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        \DB::commit();

        return redirect('accounts')->with('message', 'Account created successfully');
    }

    public function storeOld(Request $request)
    {
        $this->validate($request, [
            'account_no' => [
                'max:255',
                    Rule::unique(env('TENANT_DB_CONNECTION').'.'.'accounts')->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $lims_account_data = Account::where('is_active', true)->first();
        $data = $request->all();
        if($data['initial_balance'])
            $data['total_balance'] = $data['initial_balance'];
        else
            $data['total_balance'] = 0;
        if(!$lims_account_data)
            $data['is_default'] = 1;
        $data['is_active'] = true;
        Account::create($data);
        return redirect('accounts')->with('message', 'Account created successfully');
    }

    public function makeDefault($id)
    {
        $lims_account_data = Account::where('is_default', true)->first();
        $lims_account_data->is_default = false;
        $lims_account_data->save();

        $lims_account_data = Account::find($id);
        $lims_account_data->is_default = true;
        $lims_account_data->save();

        return 'Account set as default successfully';
    }

    public function edit($id)
    {
        $dakun    = TbRekening::select('id','parent_id','kode','nama')->where('parent_id',0)->get();
        $akun = TbRekening::find($id);
        //dd($akun);
        
        foreach ($dakun as $parent) {

             $this->getTree($parent,1);
        }

        $drop = $this->catTree;
        
        return view('backend.account.form',compact('drop','akun'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'account_no' => [
                'max:255',
                    Rule::unique(env('TENANT_DB_CONNECTION').'.'.'accounts')->ignore($request->account_id)->where(function ($query) {
                    return $query->where('is_active', 1);
                }),
            ],
        ]);

        $data = $request->all();
        $lims_account_data = Account::find($data['account_id']);
        if($data['initial_balance'])
            $data['total_balance'] = $data['initial_balance'];
        else
            $data['total_balance'] = 0;
        $lims_account_data->update($data);
        return redirect('accounts')->with('message', 'Account updated successfully');
    }

    public function balanceSheet()
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('balance-sheet')){
            $lims_account_list = Account::where('is_active', true)->get();
            $debit = [];
            $credit = [];
            foreach ($lims_account_list as $account) {
                $payment_recieved = Payment::whereNotNull('sale_id')->where('account_id', $account->id)->sum('amount');
                $payment_sent = Payment::whereNotNull('purchase_id')->where('account_id', $account->id)->sum('amount');
                $returns = DB::connection(env('TENANT_DB_CONNECTION'))->table('returns')->where('account_id', $account->id)->sum('grand_total');
                $return_purchase = DB::connection(env('TENANT_DB_CONNECTION'))->table('return_purchases')->where('account_id', $account->id)->sum('grand_total');
                $expenses = DB::connection(env('TENANT_DB_CONNECTION'))->table('expenses')->where('account_id', $account->id)->sum('amount');
                $payrolls = DB::connection(env('TENANT_DB_CONNECTION'))->table('payrolls')->where('account_id', $account->id)->sum('amount');
                $sent_money_via_transfer = MoneyTransfer::where('from_account_id', $account->id)->sum('amount');
                $recieved_money_via_transfer = MoneyTransfer::where('to_account_id', $account->id)->sum('amount');

                $credit[] = $payment_recieved + $return_purchase + $recieved_money_via_transfer + $account->initial_balance;
                $debit[] = $payment_sent + $returns + $expenses + $payrolls + $sent_money_via_transfer;

                /*$credit[] = $payment_recieved + $return_purchase + $account->initial_balance;
                $debit[] = $payment_sent + $returns + $expenses + $payrolls;*/
            }
            return view('backend.account.balance_sheet', compact('lims_account_list', 'debit', 'credit'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function accountStatement(Request $request)
    {
        $data = $request->all();
        //return $data;
        $lims_account_data = Account::find($data['account_id']);
        $credit_list = new Collection;
        $debit_list = new Collection;
        $expense_list = new Collection;
        $return_list = new Collection;
        $purchase_return_list = new Collection;
        $payroll_list = new Collection;
        $recieved_money_transfer_list = new Collection;
        $sent_money_transfer_list = new Collection;
        
        if($data['type'] == '0' || $data['type'] == '2') {
            $credit_list = Payment::whereNotNull('sale_id')
                            ->where('account_id', $data['account_id'])
                            ->whereDate('created_at', '>=' , $data['start_date'])
                            ->whereDate('created_at', '<=' , $data['end_date'])
                            ->select('payment_reference as reference_no', 'sale_id', 'amount', 'created_at')
                            ->get();

            $recieved_money_transfer_list = MoneyTransfer::where('to_account_id', $data['account_id'])
                                            ->whereDate('created_at', '>=' , $data['start_date'])
                                            ->whereDate('created_at', '<=' , $data['end_date'])
                                            ->select('reference_no', 'to_account_id', 'amount', 'created_at')
                                            ->get();
            $purchase_return_list = ReturnPurchase::where('account_id', $data['account_id'])
                                    ->whereDate('created_at', '>=' , $data['start_date'])
                                    ->whereDate('created_at', '<=' , $data['end_date'])
                                    ->select('reference_no', 'grand_total as amount', 'created_at')
                                    ->get();
        }
        if($data['type'] == '0' || $data['type'] == '1') {
            $debit_list = Payment::whereNotNull('purchase_id')
                            ->where('account_id', $data['account_id'])
                            ->whereDate('created_at', '>=' , $data['start_date'])
                            ->whereDate('created_at', '<=' , $data['end_date'])
                            ->select('payment_reference as reference_no', 'purchase_id', 'amount', 'created_at')
                            ->get();
            $expense_list = Expense::where('account_id', $data['account_id'])
                            ->whereDate('created_at', '>=' , $data['start_date'])
                            ->whereDate('created_at', '<=' , $data['end_date'])
                            ->select('reference_no', 'amount', 'created_at')
                            ->get();
            $return_list = Returns::where('account_id', $data['account_id'])
                            ->whereDate('created_at', '>=' , $data['start_date'])
                            ->whereDate('created_at', '<=' , $data['end_date'])
                            ->select('reference_no', 'grand_total as amount', 'created_at')
                            ->get();
            $payroll_list = Payroll::where('account_id', $data['account_id'])
                            ->whereDate('created_at', '>=' , $data['start_date'])
                            ->whereDate('created_at', '<=' , $data['end_date'])
                            ->select('reference_no', 'amount', 'created_at')
                            ->get();
            $sent_money_transfer_list = MoneyTransfer::where('from_account_id', $data['account_id'])
                                        ->whereDate('created_at', '>=' , $data['start_date'])
                                        ->whereDate('created_at', '<=' , $data['end_date'])
                                        ->select('reference_no', 'to_account_id', 'amount', 'created_at')
                                        ->get();
        }
        $all_transaction_list = new Collection;
        $all_transaction_list = $credit_list->concat($recieved_money_transfer_list)
                                ->concat($debit_list)
                                ->concat($expense_list)
                                ->concat($return_list)
                                ->concat($purchase_return_list)
                                ->concat($payroll_list)
                                ->concat($sent_money_transfer_list)
                                ->sortByDesc('created_at');
        $balance = 0;
        return view('backend.account.account_statement', compact('lims_account_data', 'all_transaction_list', 'balance'));
    }

    public function formatSaldoAwal()
    {   
        $rek = TbRekening::where('depth',3)->get()->toArray();

        return Excel::download(new SaldoAwalExport($rek), 'format-saldo-awal.xlsx');
    }

    public function formSaldoAwal()
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();

        return view('backend.account.import',compact('lims_warehouse_list'));
    }

    public function imporSaldoAwal(Request $req)
    {
      $file = $req->file('file_saldo');

      \DB::beginTransaction();

      try{
        $bulan = $req->input('bulan');
        $tahun = $req->input('tahun');
        $warehouse_id = $req->input('warehouse_id');
        
        $dir   = 'uploads/xls/rekening/';

        if(!file_exists($dir)){
           mkdir($dir,0755,true);
        }

        if($file){
          $extension  = $file->getClientOriginalExtension();

          if(in_array($extension, ['xlsx']) == false){
               
               return redirect()->back()->with('error', 'Ekstensi tidak diperbolehkan');
          }

          $filename   = 'rekening-'.uniqid().'.'.$extension;

          $file->move($dir, $filename);

          Excel::import(new SaldoAwalImport($bulan,$tahun,$warehouse_id), public_path($dir.$filename));

          $filepath = public_path($dir . "/" . $filename);

          if(file_exists($filepath)){

            unlink($filepath);
          }

          \DB::connection(env('TENANT_DB_CONNECTION'))
             ->table('general_settings')->where('id',1)
             ->update(['tahun_saldo_'.$req->jenis_periode_saldo=>$tahun,
                       'bulan_saldo_'.$req->jenis_periode_saldo=>$bulan
                     ]);

          $response = true;
        }
      }catch (\Exception $e) {
      
        \DB::rollback();
        \Log::error("rekening.saldo_awal.import_excel: {$e->getMessage()}");

         return redirect()->back()->with('error', 'Terjadi kesalahan, mohon periksa format dokumen');
      }

      \DB::commit();

      return redirect()->route('accounts.index')->with('message', 'Data Saldo Awal berhasil diupload');
    }

    public function destroy($id)
    {
        $tbrek = TbRekening::find($id);
        
        $child = TbRekening::where('parent_id',$tbrek->id)->get()->count();
       
        if($child > 0){
            TbRekening::where('parent_id',$tbrek->id)->delete();
        }
        $tbrek->delete();

        return redirect('accounts')->with('not_permitted', 'Account deleted successfully!');
        /*if(!env('USER_VERIFIED'))
            return redirect()->back()->with('not_permitted', 'This feature is disable for demo!');
        $lims_account_data = Account::find($id);
        if(!$lims_account_data->is_default){
            $lims_account_data->is_active = false;
            $lims_account_data->save();
            return redirect('accounts')->with('not_permitted', 'Account deleted successfully!');
        }
        else
            return redirect('accounts')->with('not_permitted', 'Please make another account default first!');*/
    }
}
