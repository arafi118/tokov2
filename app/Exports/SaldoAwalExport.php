<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SaldoAwalExport implements FromView
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
        return view('backend.account.format', ['rek'   =>$this->data[0]]);
    }
}
