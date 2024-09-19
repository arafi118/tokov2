<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PermissionRole extends Model
{
    use HasFactory,UsesTenantConnection;
    
    protected $table = 'permission_role';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['role_id','permission_id'];
}
