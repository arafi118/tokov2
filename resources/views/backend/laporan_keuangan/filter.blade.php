<div class="row">
    <div class="col-md-3">
        <div class="my-2">
            <label class="form-label" for="tahun">Warehouse</label>
            <select required name="warehouse_id" id="warehouse_id" class="selectpicker form-control" data-live-search="true"
                title="Pilih warehouse...">
                @foreach ($lims_warehouse_list as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="my-2">
            <label class="form-label" for="tahun">{{ trans('file.Year') }}</label>
            <select class="form-control" name="tahun" id="tahun">
                <option value="">---</option>
                @for ($i = 2020; $i <= date('Y'); $i++)
                    <option {{ $i == date('Y') ? 'selected' : '' }} value="{{ $i }}">
                        {{ $i }}
                    </option>
                @endfor
            </select>
            <small class="text-danger" id="msg_tahun"></small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="my-2">
            <label class="form-label" for="bulan">{{ trans('file.Month') }}</label>
            <select class="form-control" name="bulan" id="bulan">
                <option value="">---</option>
                <option {{ date('m') == '01' ? 'selected' : '' }} value="01">01. JANUARI</option>
                <option {{ date('m') == '02' ? 'selected' : '' }} value="02">02. FEBRUARI</option>
                <option {{ date('m') == '03' ? 'selected' : '' }} value="03">03. MARET</option>
                <option {{ date('m') == '04' ? 'selected' : '' }} value="04">04. APRIL</option>
                <option {{ date('m') == '05' ? 'selected' : '' }} value="05">05. MEI</option>
                <option {{ date('m') == '06' ? 'selected' : '' }} value="06">06. JUNI</option>
                <option {{ date('m') == '07' ? 'selected' : '' }} value="07">07. JULI</option>
                <option {{ date('m') == '08' ? 'selected' : '' }} value="08">08. AGUSTUS</option>
                <option {{ date('m') == '09' ? 'selected' : '' }} value="09">09. SEPTEMBER</option>
                <option {{ date('m') == '10' ? 'selected' : '' }} value="10">10. OKTOBER</option>
                <option {{ date('m') == '11' ? 'selected' : '' }} value="11">11. NOVEMBER</option>
                <option {{ date('m') == '12' ? 'selected' : '' }} value="12">12. DESEMBER</option>
            </select>
            <small class="text-danger" id="msg_bulan"></small>
        </div>
    </div>

    <div class="col-md-3">
        <div class="my-2">
            <label class="form-label" for="hari">{{ trans('file.Day') }}</label>
            <select class="form-control" name="hari" id="hari">
                <option value="" selected>---</option>
                @for ($j = 1; $j <= 31; $j++)
                    <option value="{{ str_pad($j, 2, '0', STR_PAD_LEFT) }}">{{ str_pad($j, 2, '0', STR_PAD_LEFT) }}</option>
                @endfor
            </select>
            <small class="text-danger" id="msg_hari"></small>
        </div>
    </div>

    <div class="col-md-6">
        <div class="my-2">
            <label class="form-label" for="tahun">{{ trans('file.Report type') }}</label>
            <select required name="jenis_laporan" id="jenis_laporan" class="selectpicker form-control"
                data-live-search="true" title="Pilih laporan...">
                @foreach ($jenis_laporan as $k => $v)
                    <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6" id="jb">
        <div class="my-2">
            <label class="form-label" for="tahun">{{ trans('file.Type of book') }}</label>
            <select required name="jenis_buku_id" id="jenis_buku_id" class="selectpicker form-control"
                data-live-search="true" title="Pilih Jenis Buku...">
                @foreach ($jenis_buku as $jenis)
                    <option value="{{ $jenis->kode }}">{{ $jenis->kode . ' - ' . $jenis->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6" id="invent">
        <div class="my-2">
            <label class="form-label" for="tahun">{{ trans('file.Inventory type') }}</label>
            <select required name="inventaris_id" id="inventaris_id" class="selectpicker form-control"
                data-live-search="true" title="Pilih Jenis Inventaris...">
                @foreach ($inventaris as $in)
                    <option value="{{ $in->id }}">{{ $in->nama }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button class="btn btn-success mr-2" onclick="doFilter('xls')" type="button">
        <i class="dripicons-export"></i> Excel
    </button>
    <button class="btn btn-danger" onclick="doFilter('print')" type="button">
        <i class="dripicons-print"></i> Print
    </button>
</div>
