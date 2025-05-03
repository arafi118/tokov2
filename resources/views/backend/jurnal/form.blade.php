@php
    $opt = '';
    foreach ($lims_account_all as $key) {
        $opt .= '<optgroup label="' . $key->nama . '">';
        foreach ($key->children as $child) {
            $opt .= '<option value="' . $child->kode . '">' . $child->kode . '. ' . $child->nama . '</option>';
        }
        $opt .= '</optgroup>';
    }
@endphp

@extends('backend.layout.main')

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>Input Jurnal</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic"><small>The field labels marked with * are required input fields.</small></p>
                            {!! Form::open(['route' => ['jurnal.store'], 'method' => 'post']) !!}
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Induk Jenis Transaksi</label>
                                        <select required name="induk_transaksi" onchange="jenisTransaksi()"
                                            id="induk_transaksi" class="form-control" title="Pilih induk transaksi...">
                                            @foreach ($induk as $in)
                                                <option value="{{ $in->id }}"
                                                    @if (isset($jurnal)) @if ($jurnal->tb_induk_jenis_transaksi_id == $in->id) selected="" @endif
                                                    @endif>{{ $in->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div id="jt"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="inventaris">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Relasi</label>
                                            <input type="text"
                                                value="{{ isset($inventaris) ? $inventaris->relasi : '' }}" name="relasi"
                                                class="inv form-control" placeholder="Relasi">
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Barang</label>
                                            <input type="text"
                                                value="{{ isset($inventaris) ? $inventaris->nama_barang : '' }}"
                                                name="nama_barang" class="inv form-control" placeholder="Nama Barang">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Unit</label>
                                            <input type="text" value="{{ isset($inventaris) ? $inventaris->unit : '' }}"
                                                name="unit" class="inv form-control" placeholder="Unit">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Harga Satuan</label>
                                            <input type="text" style="text-align: right;"
                                                value="{{ isset($inventaris) ? $inventaris->harga_satuan : '' }}"
                                                name="harga_satuan" class="inv form-control" placeholder="Harga Satuan">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Umur Ekonomis</label>
                                            <input type="number"
                                                value="{{ isset($inventaris) ? round($inventaris->umur_ekonomis) : '' }}"
                                                name="umur_ekonomis" class="inv form-control" placeholder="Umur Ekonomis">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="inv form-control" name="status" id="status">
                                                <option value="baik"
                                                    @if (isset($inventaris)) @if ($inventaris->status == 'baik') selected="" @endif
                                                    @endif>Baik</option>
                                                <option value="rusak"
                                                    @if (isset($inventaris)) @if ($inventaris->status == 'rusak') selected="" @endif
                                                    @endif>Rusak</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Validasi</label>
                                            <input type="text"
                                                value="{{ isset($inventaris) ? $inventaris->tgl_validasi : '' }}"
                                                name="tgl_validasi" id="tgl_validasi" class="inv form-control date"
                                                placeholder="Pilih Tanggal" value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('file.Warehouse') }} *</label>
                                        <input type="hidden" name="jurnal_id"
                                            value="{{ isset($jurnal) ? $jurnal->id : '' }}">
                                        <select required name="warehouse_id" id="warehouse_id"
                                            class="selectpicker form-control" data-live-search="true"
                                            title="Select warehouse...">
                                            @foreach ($lims_warehouse_list as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    @if (isset($jurnal)) @if ($jurnal->warehouse_id == $warehouse->id) selected="" @endif
                                                    @endif>{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('file.Date') }}</label>
                                        <input type="text" name="tgl_transaksi" required class="form-control date"
                                            placeholder="Pilih Tanggal"
                                            value="{{ isset($jurnal) ? $jurnal->tgl_transaksi : '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Nomor Transaksi</label>
                                        <input type="text" name="nomor_transaksi" class="form-control"
                                            placeholder="Nomor Transaksi"
                                            value="{{ isset($jurnal) ? $jurnal->nomor_transaksi : '' }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Memo</label>
                                        <textarea name="memo" class="form-control" required style="min-height: 150px;" placeholder="Memo">{{ isset($jurnal) ? $jurnal->memo : '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button style="float: right;" onclick="cloneAkun()" class="btn btn-success btn-sm"
                                        type="button"><i class="dripicons-plus"></i> Tambah Data</button>
                                    <div class="clearfix"></div>

                                    <table id="akunTable" class="table table-striped" style="margin-top:10px;">
                                        <thead>
                                            <tr>
                                                <th>Debit</th>
                                                <th>Kredit</th>
                                                <th width="20%">Nominal</th>
                                                <th width="30%">Deskripsi</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody_clone">
                                            @if (isset($jurnal))
                                                <?php $no = 0; ?>
                                                @foreach ($details as $det)
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="jurnal_detail_id[]"
                                                                value="{{ $det->id }}">
                                                            <select required class="debit" name="debit[]"
                                                                id="debit_<?php echo $no; ?>">
                                                                <option value="">--Pilih Rekening--</option>
                                                                @foreach ($lims_account_all as $akun)
                                                                    <?php
                                                                    if (isset($akun->depth)) {
                                                                        $depth = str_repeat('-', $akun->depth);
                                                                    } else {
                                                                        $depth = '';
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $akun->kode }}"
                                                                        @if ($akun->kode == $det->debit_kode) selected="" @endif>
                                                                        {{ $depth . '' . $akun->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select required class="kredit" name="kredit[]"
                                                                id="kredit_<?php echo $no; ?>">
                                                                <option value="">--Pilih Rekening--</option>
                                                                @foreach ($lims_account_all as $akun)
                                                                    <?php
                                                                    if (isset($akun->depth)) {
                                                                        $depth = str_repeat('-', $akun->depth);
                                                                    } else {
                                                                        $depth = '';
                                                                    }
                                                                    ?>
                                                                    <option value="{{ $akun->kode }}"
                                                                        @if ($akun->kode == $det->kredit_kode) selected="" @endif>
                                                                        {{ $depth . '' . $akun->nama }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type=""
                                                                value="{{ number_format($det->debit_nominal, 2) }}"
                                                                class="nominal form-control decimal" name="nominal[]"
                                                                id="nominal_0" style="text-align:right;" required></td>
                                                        <td><input type="text" class="form-control" name="deskripsi[]"
                                                                value="{{ $det->deskripsi }}"></td>
                                                        <td style="vertical-align: middle;">
                                                            <button type="button" class="rmv btn btn-sm btn-danger"><i
                                                                    class="dripicons-trash"></i></button>
                                                        </td>
                                                    </tr>
                                                    <?php $no++; ?>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="jurnal_detail_id[]" value="0">
                                                        <select required class="debit" name="debit[]" id="debit_0"
                                                            data-live-search="true">
                                                            <option value="">--Pilih Rekening--</option>
                                                            {!! $opt !!}
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select required class="kredit" name="kredit[]" id="kredit_0"
                                                            data-live-search="true">
                                                            <option value="">--Pilih Rekening--</option>
                                                            {!! $opt !!}
                                                        </select>
                                                    </td>
                                                    <td><input type="text" value="0.00"
                                                            class="nominal form-control decimal" name="nominal[]"
                                                            id="nominal_0" style="text-align:right;" required></td>
                                                    <td><input type="text" class="form-control" name="deskripsi[]"
                                                            value=""></td>
                                                    <td style="vertical-align: middle;">
                                                        <button type="button" class="rmv btn btn-sm btn-danger"><i
                                                                class="dripicons-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="{{ route('jurnal.index') }}"
                                            class="btn btn-danger">{{ trans('file.Cancel') }}</a>
                                        <button type="submit"
                                            class="btn btn-primary">{{ trans('file.submit') }}</button>
                                    </div>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            <?php if(isset($jurnal)): ?>
            jenisTransaksi('<?php echo $details[0]->tb_jenis_transaksi_id; ?>');
            <?php $no = 0; ?>
            <?php foreach($details as $det):?>
            cSelected('<?php echo $det->debit_kode; ?>', '<?php echo $det->kredit_kode; ?>', '<?php echo $no; ?>')
            <?php endforeach ?>
            <?php endif ?>
            $('#inventaris').hide();
            $('body').on('click', '.rmv', function() {
                var countrow = $('.rmv').length;

                if (countrow < 2) {
                    alert('Minimal satu baris');

                    return;
                } else {
                    $(this).closest('tr').remove();
                }
            });
        });

        function jenisTransaksi(id_jenis_transaksi = null) {

            $('#debit_0').val('').change();
            $('#kredit_0').val('').change();
            $.ajax({
                type: 'get',
                data: {
                    'tb_induk_jenis_transaksi_id': $('#induk_transaksi').val()
                },
                url: '{{ route('jurnal.getjenistransaksi') }}',
                success: function(i) {
                    console.log(i);
                    if ($("#induk_transaksi option:selected").text() != 'Input Jurnal') {
                        var data = i.data;
                        var opt =
                            '<label>Jenis Transaksi</label><select onchange="getChild(this)" required name="jenis_transaksi" id="jenis_transaksi" class="form-control"  title="Pilih transaksi...">';

                        opt += '<option value="">- Pilih Jenis Transaksi</option>';
                        for (let i = 0; i < data.length; i++) {
                            var sel = '';

                            if (id_jenis_transaksi != null) {
                                if (id_jenis_transaksi == data[i].id) {
                                    sel = "selected=''";
                                }
                            }
                            opt += '<option data-rekening_debit_kode="' + data[i].rekening_debit_kode +
                                '" data-rekening_kredit_kode="' + data[i].rekening_kredit_kode + '" value="' +
                                data[i].id + '" ' + sel + '>' + data[i].nama + '</option>';
                        }

                        opt += '</select>';

                        $('#jt').show();
                        $('#jt').html(opt);
                        if ($("#induk_transaksi option:selected").text() == 'Pembelian AT dan Inventaris') {
                            $('#inventaris').show();
                            $('.inv').prop('required', true);
                        } else {
                            $('#inventaris').hide();
                            $('.inv').prop('required', false);
                        }
                    } else {

                        if ($("#induk_transaksi option:selected").text() == 'Input Jurnal') {
                            $('#jt').hide();
                            $('#inventaris').hide();
                            $('.inv').prop('required', false);
                        }
                    }
                }
            });
        }

        function getChild(e) {
            console.log($(e).find(':selected').data('rekening_debit_kode'));

            $('#debit_0').val($(e).find(':selected').data('rekening_debit_kode')).change();
            $('#kredit_0').val($(e).find(':selected').data('rekening_kredit_kode')).change();
        }

        function cSelected(d, k, indx) {
            $('#debit_' + indx).val(d).change();
            $('#kredit_' + indx).val(k).change();
        }

        function cloneAkun() {

            var warehouse_id = $('#warehouse_id').val();

            var no = 0;
            var htxl = '';

            no++;

            htxl += '<tr class="baris_' + no + '">';
            htxl += '<td>';
            htxl += '<input type="hidden" name="jurnal_detail_id[]" value="0">';
            htxl += '<select required class="form-control debit" name="debit[]" id="debit_' + no + '">';
            htxl += '<option value="">--Pilih Rekening--</option>';
            htxl += '<?php echo $opt; ?>';
            htxl += '</select>';
            htxl += '</td>';
            htxl += '<td>';
            htxl += '<select required class="form-control kredit" name="kredit[]" id="kredit_' + no + '">';
            htxl += '<option value="">--Pilih Rekening--</option>';
            htxl += '<?php echo $opt; ?>';
            htxl += '</select>';
            htxl += '</td>';
            htxl += '<td>';
            htxl += '<input type="text" value="0" class="nominal form-control decimal" name="nominal[]" id="nominal_' +
                no +
                '" style="text-align:right;" required>';
            htxl += '</td>';
            htxl += '<td><input class="form-control" type="text" name="deskripsi[]" value=""></td>';
            htxl +=
                '<td style="vertical-align: middle;"><button type="button" class="rmv btn btn-sm btn-danger"><i class="dripicons-trash"></i></button></td>';
            htxl += '</tr>';

            $('#tbody_clone').append(htxl);
        }
    </script>
@endpush
