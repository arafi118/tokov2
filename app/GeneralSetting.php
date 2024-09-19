<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class GeneralSetting extends Model
{
    use UsesTenantConnection;
protected $fillable =[

        "site_title", "site_logo", "is_rtl", "currency", "currency_position", "staff_access", "date_format", "theme", "developed_by", "invoice_format", "state","tgl_awal_app","tahun_saldo_awal_tahun","bulan_saldo_awal_tahun","tahun_saldo_bulan_lalu","bulan_saldo_bulan_lalu"
    ];
}
