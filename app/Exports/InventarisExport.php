<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventarisExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct(array $data)
    {
        $this->data[] = $data;
    }

    public function view(): View
    {
      //  dd($this->data[0]);
        return view('backend.laporan_keuangan.inventaris.print',$this->data[0]);
    }
}
