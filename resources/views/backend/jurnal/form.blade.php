@php
    $opt = '';
    $akunJson = json_encode($lims_account_all);
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
                        <div class="card-header text-center">
                            <h4>Input Jurnal</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>
                                    {{ trans('file.The field labels marked with * are required input fields') }}.
                                </small>
                            </p>

                            {!! Form::open(['route' => ['jurnal.store'], 'method' => 'post']) !!}
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('file.Warehouse') }} *</label>
                                        <input type="hidden" name="jurnal_id"
                                            value="{{ isset($jurnal) ? $jurnal->id : '' }}">
                                        <input type="hidden" name="jurnal_detail_id"
                                            value="{{ isset($detail) ? $detail->id : '' }}">
                                        <select required name="warehouse_id" id="warehouse_id"
                                            class="selectpicker form-control" data-live-search="true"
                                            title="Select warehouse...">
                                            @foreach ($lims_warehouse_list as $warehouse)
                                                @php
                                                    $selected = '';
                                                    if (isset($jurnal)) {
                                                        if ($jurnal->warehouse_id == $warehouse->id) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option {{ $selected }} value="{{ $warehouse->id }}">
                                                    {{ $warehouse->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Jenis Transaksi</label>
                                        <select required name="jenis_transaksi" id="jenis_transaksi" class="form-control"
                                            title="Pilih Jenis Transaksi...">
                                            @foreach ($induk->jenisTransaksi as $in)
                                                @php
                                                    $selected = '';
                                                    if (isset($detail)) {
                                                        if ($detail->tb_jenis_transaksi_id == $in->id) {
                                                            $selected = 'selected';
                                                        }
                                                    } else {
                                                        if ($in->id == 95) {
                                                            $selected = 'selected';
                                                        }
                                                    }
                                                @endphp
                                                <option value="{{ $in->id }}" {{ $selected }}>
                                                    {{ $in->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{ trans('file.Date') }}</label>
                                        <input type="text" name="tgl_transaksi" required class="form-control date"
                                            value="{{ date('d-m-Y') }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sumber_dana">Sumber Dana</label>
                                        <select required class="form-control" name="sumber_dana" id="sumber_dana"
                                            data-live-search="true">
                                            <option value="">--Pilih Rekening--</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="disimpan_ke">Disimpan Ke</label>
                                        <select required class="form-control" name="disimpan_ke" id="disimpan_ke"
                                            data-live-search="true">
                                            <option value="">--Pilih Rekening--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="form" class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Keterangan</label>
                                        <textarea name="Keterangan" id="keterangan" class="form-control" required style="min-height: 150px;">{{ isset($detail) ? $detail->deskripsi : '' }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6" id="formRelasi">
                                    <div class="form-group">
                                        <label>Relasi</label>
                                        <input type="text" name="relasi" id="relasi" class="form-control"
                                            value="{{ isset($detail) ? $detail->relasi : '' }}" />
                                    </div>
                                </div>
                                <div class="col-md-6" id="formNominal">
                                    <div class="form-group">
                                        <label>Nominal</label>
                                        <input type="text" name="nominal" id="nominal" required
                                            class="form-control decimal"
                                            value="{{ isset($detail) ? number_format($detail->debit_nominal, 2) : '0.00' }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-3">
                                <a href="/jurnal" class="btn btn-secondary mr-2">Kembali</a>
                                <button class="btn btn-primary" type="submit">
                                    {{ trans('file.submit') }}
                                </button>
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
    <script>
        const akun = JSON.parse(@json($akunJson));

        $(document).on('change', '#jenis_transaksi', (e) => {
            e.preventDefault();

            setAkun();
        })

        $(document).on('change', '#sumber_dana, #disimpan_ke', (e) => {
            var jenisTransaksi = $('#jenis_transaksi').val();
            var kodeDebit = $('#sumber_dana').val();
            var kodeKredit = $('#disimpan_ke').val();

            var akunDebit = akun.find(item => item.kode == kodeDebit);
            var akunKredit = akun.find(item => item.kode == kodeKredit);

            var keterangan = '';
            if (jenisTransaksi == '95') {
                keterangan = 'Dari ' + akunDebit.nama
                if (akunKredit) {
                    keterangan += ' ke ' + akunKredit.nama
                }
            }

            if (jenisTransaksi == '96') {
                if (akunDebit.nama.includes('Kas')) {
                    keterangan = 'Bayar'
                }

                if (akunDebit.nama.includes('Bank')) {
                    keterangan = 'Transfer'
                }

                if (akunKredit) {
                    keterangan += ' ' + akunKredit.nama
                }
            }

            if (jenisTransaksi == '97') {
                keterangan = 'Pemindahan Saldo ' + akunDebit.nama
                if (akunKredit) {
                    keterangan += ' ke ' + akunKredit.nama
                }
            }

            $('#keterangan').val(keterangan);
            setForm(kodeDebit, kodeKredit);
        })

        function setForm(kodeDebit, kodeKredit) {
            var relasi = false;
            if (kodeDebit.includes('1.1.01') && !kodeKredit.includes('1.1.01')) {
                relasi = true;
            }

            if (!kodeDebit.includes('1.1.01') && kodeKredit.includes('1.1.01')) {
                relasi = true;
            }

            if (kodeDebit.includes('1.1.02') && !(kodeKredit.includes('1.1.01') || kodeKredit.includes('1.1.02'))) {
                relasi = true;
            }

            if (!relasi) {
                $('#formRelasi').hide();
                $('#formNominal').attr('class', 'col-md-12');
                $('#formRelasi input').val('');
            } else {
                $('#formRelasi').show();
                $('#formNominal').attr('class', 'col-md-6');
            }
        }

        function setAkun() {
            let jenisTransaksi = $('#jenis_transaksi').val();
            var labelKredit = 'Sumber Dana';
            var labelDebit = 'Disimpan Ke';
            var akunKredit = [];
            var akunDebit = [];

            akun.forEach(item => {
                var level = item.kode.split('.')
                if (jenisTransaksi == '95') {
                    var filterAkunKredit = ['2.1.04.01', '2.1.04.02', '2.1.04.03', '2.1.02.01', '2.1.03.01'];
                    if (
                        (level[0] == '2' || level[0] == '3' || level[0] == '4') &&
                        !(filterAkunKredit.includes(item.kode) || item.kode.includes('4.1.01'))
                    ) {
                        akunKredit.push(item);
                    }

                    if (level[0] == '1') {
                        akunDebit.push(item);
                    }
                }

                if (jenisTransaksi == '96') {
                    if ((level[0] == '1' || level[0] == '2') && !(item.kode.includes('2.1.04'))) {
                        akunKredit.push(item);
                    }

                    if (level[0] == '2' || level[0] == '3' || level[0] == '5') {
                        akunDebit.push(item);
                    }

                    labelKredit = 'Keperluan';
                }

                if (jenisTransaksi == '97') {
                    akunKredit.push(item);
                    akunDebit.push(item);
                }
            });

            var kreditSelect = "{{ isset($detail) ? $detail->kredit_kode : '' }}";
            var debitSelect = "{{ isset($detail) ? $detail->debit_kode : '' }}";

            setSelectOption('sumber_dana', akunKredit, labelKredit, kreditSelect);
            setSelectOption('disimpan_ke', akunDebit, labelDebit, debitSelect);
        }

        function setSelectOption(id, data, label = null, setSelected = null) {
            var select = $('#' + id);
            select.empty();
            select.append('<option value="">--Pilih Rekening--</option>');
            data.forEach(item => {
                var setSelect = (setSelected == item.kode) ? 'selected' : '';
                select.append('<option ' + setSelect + ' value="' + item.kode + '">' + item.kode + '. ' + item
                    .nama +
                    '</option>');
            });

            if (label) {
                $('label[for="' + id + '"]').text(label);
            }

            select.selectpicker('refresh');
        }

        setAkun()
    </script>
@endpush
