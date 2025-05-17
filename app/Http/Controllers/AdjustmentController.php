<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Warehouse;
use App\Product_Warehouse;
use App\Product;
use App\Adjustment;
use App\ProductAdjustment;
use DB;
use App\StockCount;
use App\TbJurnal;
use App\ProductVariant;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdjustmentController extends Controller
{
    public function __construct()
    {
        $this->jurnal = new TbJurnal;
    }

    public function index()
    {
        $role = Role::find(Auth::user()->role_id);
        if ($role->hasPermissionTo('adjustment')) {
            /*if(Auth::user()->role_id > 2 && config('staff_access') == 'own')
                $lims_adjustment_all = Adjustment::orderBy('id', 'desc')->where('user_id', Auth::id())->get();
            else*/
            $lims_adjustment_all = Adjustment::orderBy('id', 'desc')->get();
            return view('backend.adjustment.index', compact('lims_adjustment_all'));
        } else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }

    public function getProduct($id)
    {
        $lims_product_warehouse_data = DB::connection(env('TENANT_DB_CONNECTION'))->table('products')
            ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
            ->whereNull('products.is_variant')
            ->where([
                ['products.is_active', true],
                ['product_warehouse.warehouse_id', $id]
            ])
            ->select('product_warehouse.qty', 'products.code', 'products.name', 'products.id')
            ->get();
        $lims_product_withVariant_warehouse_data = DB::connection(env('TENANT_DB_CONNECTION'))->table('products')
            ->join('product_warehouse', 'products.id', '=', 'product_warehouse.product_id')
            ->whereNotNull('products.is_variant')
            ->where([
                ['products.is_active', true],
                ['product_warehouse.warehouse_id', $id]
            ])
            ->select('products.name', 'product_warehouse.qty', 'product_warehouse.product_id', 'product_warehouse.variant_id')
            ->get();

        $product_code = [];
        $product_name = [];
        $product_qty = [];
        $product_data = [];
        foreach ($lims_product_warehouse_data as $product_warehouse) {
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_warehouse->code;
            $product_name[] = $product_warehouse->name;
            $product_id[] = $product_warehouse->id;
        }

        foreach ($lims_product_withVariant_warehouse_data as $product_warehouse) {
            $product_variant = ProductVariant::select('item_code')->FindExactProduct($product_warehouse->product_id, $product_warehouse->variant_id)->first();
            $product_qty[] = $product_warehouse->qty;
            $product_code[] =  $product_variant->item_code;
            $product_name[] = $product_warehouse->name;
            $product_id[] = $product_warehouse->id;
        }

        $product_data[] = $product_code;
        $product_data[] = $product_name;
        $product_data[] = $product_qty;
        $product_data[] = $product_id;

        return $product_data;
    }

    public function limsProductSearch(Request $request)
    {
        $product_code = explode("(", $request['data']);
        $product_code[0] = rtrim($product_code[0], " ");
        $lims_product_data = Product::where([
            ['code', $product_code[0]],
            ['is_active', true]
        ])->first();
        if (!$lims_product_data) {
            $lims_product_data = Product::join('product_variants', 'products.id', 'product_variants.product_id')
                ->select('products.id', 'products.name', 'products.is_variant', 'product_variants.id as product_variant_id', 'product_variants.item_code')
                ->where([
                    ['product_variants.item_code', $product_code[0]],
                    ['products.is_active', true]
                ])->first();
        }

        $product[] = $lims_product_data->name;
        $product_variant_id = null;
        if ($lims_product_data->is_variant) {
            $product[] = $lims_product_data->item_code;
            $product_variant_id = $lims_product_data->product_variant_id;
        } else
            $product[] = $lims_product_data->code;

        $product[] = $lims_product_data->id;
        $product[] = $product_variant_id;
        return $product;
    }

    public function create()
    {
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        return view('backend.adjustment.create', compact('lims_warehouse_list'));
    }

    public function store(Request $request)
    {
        \DB::beginTransaction();
        try {
            $data = $request->except('document');

            if (isset($data['stock_count_id'])) {
                $lims_stock_count_data = StockCount::find($data['stock_count_id']);
                $lims_stock_count_data->is_adjusted = true;
                $lims_stock_count_data->save();
            }

            $data['reference_no'] = $this->jurnal->notaCounter('stok_opname');
            $document = $request->document;
            if ($document) {
                $documentName = $document->getClientOriginalName();
                $document->move('public/documents/adjustment', $documentName);
                $data['document'] = $documentName;
            }
            $lims_adjustment_data = Adjustment::create($data);

            $productList = $data['input'];
            foreach ($productList as $pro_id) {
                $productData = json_decode($pro_id, true);
                $product_code = $productData['product_code'];
                $qty = $productData['qty'];
                $action = $productData['action'];

                $lims_product_data = Product::find($productData['product_id']);
                if ($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($productData['product_id'], $product_code)->first();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $productData['product_id']],
                        ['variant_id', $lims_product_variant_data->variant_id],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();

                    if ($action == '-') {
                        $lims_product_variant_data->qty -= $qty;
                    } elseif ($action == '+') {
                        $lims_product_variant_data->qty += $qty;
                    }
                    $lims_product_variant_data->save();
                    $variant_id = $lims_product_variant_data->variant_id;
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $productData['product_id']],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();
                    $variant_id = null;
                }

                if ($action == '-') {
                    $lims_product_data->qty -= $qty;
                    $lims_product_warehouse_data->qty -= $qty;
                    $pm = 'minus';
                } elseif ($action == '+') {
                    $lims_product_data->qty += $qty;
                    $lims_product_warehouse_data->qty += $qty;
                    $pm = 'plus';
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();

                $product_adjustment['product_id'] = $productData['product_id'];
                $product_adjustment['variant_id'] = $variant_id;
                $product_adjustment['adjustment_id'] = $lims_adjustment_data->id;
                $product_adjustment['qty'] = $qty;
                $product_adjustment['action'] = $action;
                $pa = ProductAdjustment::create($product_adjustment);

                $fdata = [
                    'id'          => $pa->id,
                    'warehouse_id' => $lims_adjustment_data->warehouse_id,
                    'pm'          => $pm,
                    'created_at'  => $lims_adjustment_data->created_at,
                    'reference_no' => $data['reference_no'],
                    'referensi_id' => $pa->id,
                    'qty'         => $qty,
                    'product_cost' => $lims_product_data->cost
                ];

                $this->sendToJournal($fdata);
            }

            return response()->json([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("stock adjustment store : {$e->getMessage()}");

            // Response Message
            $response = false;
            return response()->json([
                'success' => false
            ]);
        }

        \DB::commit();
    }

    private function sendToJournal(array $data)
    {

        $journal_data = [
            'induk_transaksi'  => 'Stok Opname',
            'cara_bayar'       => null,
            'gudang'           => $data['warehouse_id'],
            'tanggal_transaksi' => $data['created_at'],
            'nomor_transaksi'  => $data['reference_no'],
            'referensi_id'     => $data['id'],
            'memo'             => 'Stok Opname ' . ucfirst($data['pm']),
            'tabel_transaksi'  => 'product_adjustments',
            'details' => [
                [
                    'slug' => 'stok-opname-' . $data['pm'],
                    'label' => 'Stok Opname ' . ucfirst($data['pm']),
                    'nominal' => $data['qty'] * $data['product_cost'],
                    'cara_bayar' => null
                ],


            ]
        ];

        $this->jurnal->simpan($journal_data);
    }

    public function edit($id)
    {
        $lims_adjustment_data = Adjustment::find($id);
        $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
        $lims_warehouse_list = Warehouse::where('is_active', true)->get();
        return view('backend.adjustment.edit', compact('lims_adjustment_data', 'lims_warehouse_list', 'lims_product_adjustment_data'));
    }

    public function update(Request $request, $id)
    {
        \DB::beginTransaction();
        try {
            $data = $request->except('document');
            $document = $request->document;
            if ($document) {
                $documentName = $document->getClientOriginalName();
                $document->move('public/documents/adjustment', $documentName);
                $data['document'] = $documentName;
            }

            $lims_adjustment_data = Adjustment::find($id);
            $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
            $product_id = $data['product_id'];
            $product_variant_id = $data['product_variant_id'];
            $product_code = $data['product_code'];
            $qty = $data['qty'];
            $action = $data['action'];
            $old_product_variant_id = [];
            foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
                $old_product_id[] = $product_adjustment_data->product_id;
                $lims_product_data = Product::find($product_adjustment_data->product_id);
                if ($product_adjustment_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id]
                    ])->first();
                    $old_product_variant_id[$key] = $lims_product_variant_data->id;

                    if ($product_adjustment_data->action == '-') {
                        $lims_product_variant_data->qty += $product_adjustment_data->qty;
                    } elseif ($product_adjustment_data->action == '+') {
                        $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                    }
                    $lims_product_variant_data->save();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                }
                if ($product_adjustment_data->action == '-') {
                    $lims_product_data->qty += $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
                } elseif ($product_adjustment_data->action == '+') {
                    $lims_product_data->qty -= $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();

                if ($product_adjustment_data->variant_id && !(in_array($old_product_variant_id[$key], $product_variant_id))) {
                    $product_adjustment_data->delete();
                } elseif (!(in_array($old_product_id[$key], $product_id)))
                    $product_adjustment_data->delete();
            }

            foreach ($product_id as $key => $pro_id) {
                $lims_product_data = Product::find($pro_id);
                if ($lims_product_data->is_variant) {
                    $lims_product_variant_data = ProductVariant::select('id', 'variant_id', 'qty')->FindExactProductWithCode($pro_id, $product_code[$key])->first();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $pro_id],
                        ['variant_id', $lims_product_variant_data->variant_id],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();
                    //return $action[$key];

                    if ($action[$key] == '-') {
                        $lims_product_variant_data->qty -= $qty[$key];
                    } elseif ($action[$key] == '+') {
                        $lims_product_variant_data->qty += $qty[$key];
                    }
                    $lims_product_variant_data->save();
                    $variant_id = $lims_product_variant_data->variant_id;
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $pro_id],
                        ['warehouse_id', $data['warehouse_id']],
                    ])->first();
                    $variant_id = null;
                }

                if ($action[$key] == '-') {
                    $lims_product_data->qty -= $qty[$key];
                    $lims_product_warehouse_data->qty -= $qty[$key];
                    $pm = 'minus';
                } elseif ($action[$key] == '+') {
                    $lims_product_data->qty += $qty[$key];
                    $lims_product_warehouse_data->qty += $qty[$key];
                    $pm = 'plus';
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();

                $product_adjustment['product_id'] = $pro_id;
                $product_adjustment['variant_id'] = $variant_id;
                $product_adjustment['adjustment_id'] = $id;
                $product_adjustment['qty'] = $qty[$key];
                $product_adjustment['action'] = $action[$key];

                if ($product_adjustment['variant_id'] && in_array($product_variant_id[$key], $old_product_variant_id)) {
                    ProductAdjustment::where([
                        ['product_id', $pro_id],
                        ['variant_id', $product_adjustment['variant_id']],
                        ['adjustment_id', $id]
                    ])->update($product_adjustment);

                    $pa = ProductAdjustment::where([
                        ['product_id', $pro_id],
                        ['variant_id', $product_adjustment['variant_id']],
                        ['adjustment_id', $id]
                    ])->first();
                } elseif ($product_adjustment['variant_id'] === null && in_array($pro_id, $old_product_id)) {
                    ProductAdjustment::where([
                        ['adjustment_id', $id],
                        ['product_id', $pro_id]
                    ])->update($product_adjustment);

                    $pa =  ProductAdjustment::where([
                        ['adjustment_id', $id],
                        ['product_id', $pro_id]
                    ])->first();
                } else {
                    $pa =  ProductAdjustment::create($product_adjustment);
                }
            }

            $lims_adjustment_data->update($data);

            $fdata = [
                'id'          => $pa->id,
                'warehouse_id' => $lims_adjustment_data->warehouse_id,
                'pm'          => $pm,
                'created_at'  => $lims_adjustment_data->created_at,
                'reference_no' => $lims_adjustment_data->reference_no,
                'referensi_id' => $pa->id,
                'qty'         => $qty[$key],
                'product_cost' => $lims_product_data->cost
            ];

            $this->sendToJournal($fdata);
        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error("stock adjustment update : {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        \DB::commit();

        return redirect('qty_adjustment')->with('message', 'Data updated successfully');
    }

    public function deleteBySelection(Request $request)
    {
        $adjustment_id = $request['adjustmentIdArray'];
        foreach ($adjustment_id as $id) {
            $lims_adjustment_data = Adjustment::find($id);
            $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
            foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
                $lims_product_data = Product::find($product_adjustment_data->product_id);
                if ($product_adjustment_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                    if ($product_adjustment_data->action == '-') {
                        $lims_product_variant_data->qty += $product_adjustment_data->qty;
                    } elseif ($product_adjustment_data->action == '+') {
                        $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                    }
                    $lims_product_variant_data->save();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                }
                if ($product_adjustment_data->action == '-') {
                    $lims_product_data->qty += $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
                } elseif ($product_adjustment_data->action == '+') {
                    $lims_product_data->qty -= $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();
                $product_adjustment_data->delete();
            }
            $lims_adjustment_data->delete();

            $this->jurnal->hapus_by_ref($lims_adjustment_data->reference_no);
        }
        return 'Data deleted successfully';
    }

    public function destroy($id)
    {
        \DB::beginTransaction();
        try {
            $lims_adjustment_data = Adjustment::find($id);
            $lims_product_adjustment_data = ProductAdjustment::where('adjustment_id', $id)->get();
            foreach ($lims_product_adjustment_data as $key => $product_adjustment_data) {
                $lims_product_data = Product::find($product_adjustment_data->product_id);
                if ($product_adjustment_data->variant_id) {
                    $lims_product_variant_data = ProductVariant::select('id', 'qty')->FindExactProduct($product_adjustment_data->product_id, $product_adjustment_data->variant_id)->first();
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['variant_id', $product_adjustment_data->variant_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                    if ($product_adjustment_data->action == '-') {
                        $lims_product_variant_data->qty += $product_adjustment_data->qty;
                    } elseif ($product_adjustment_data->action == '+') {
                        $lims_product_variant_data->qty -= $product_adjustment_data->qty;
                    }
                    $lims_product_variant_data->save();
                } else {
                    $lims_product_warehouse_data = Product_Warehouse::where([
                        ['product_id', $product_adjustment_data->product_id],
                        ['warehouse_id', $lims_adjustment_data->warehouse_id]
                    ])->first();
                }
                if ($product_adjustment_data->action == '-') {
                    $lims_product_data->qty += $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty += $product_adjustment_data->qty;
                } elseif ($product_adjustment_data->action == '+') {
                    $lims_product_data->qty -= $product_adjustment_data->qty;
                    $lims_product_warehouse_data->qty -= $product_adjustment_data->qty;
                }
                $lims_product_data->save();
                $lims_product_warehouse_data->save();
                $product_adjustment_data->delete();
            }
            $lims_adjustment_data->delete();
            $this->jurnal->hapus_by_ref($lims_adjustment_data->reference_no);
        } catch (\Exception $e) {
            \Log::error("stock adjustment delete: {$e->getMessage()}");

            // Response Message
            $response = false;
            return abort(500);
        }

        \DB::commit();

        return redirect('qty_adjustment')->with('not_permitted', 'Data deleted successfully');
    }
}
