@extends('backend.layout.main')
@section('content')
    <section class="forms">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            <h4>{{ trans('file.Add Adjustment') }}</h4>
                        </div>
                        <div class="card-body">
                            <p class="italic">
                                <small>{{ trans('file.The field labels marked with * are required input fields') }}.</small>
                            </p>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::open([
                                        'route' => 'qty_adjustment.store',
                                        'method' => 'post',
                                        'files' => true,
                                        'id' => 'adjustment-form',
                                    ]) !!}
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Warehouse') }} *</label>
                                                <select required id="warehouse_id" name="warehouse_id"
                                                    class="selectpicker form-control" data-live-search="true"
                                                    data-live-search-style="begins" title="Select warehouse...">
                                                    @foreach ($lims_warehouse_list as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>{{ trans('file.Attach Document') }}</label>
                                                <input type="file" name="document" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    {!! Form::close() !!}
                                    <div class="row mt-5">
                                        <div class="col-md-12">
                                            <h5>{{ trans('file.Order Table') }} *</h5>
                                            <div class="table-responsive mt-3">
                                                <table id="myTable" class="table table-hover order-list">
                                                    <thead>
                                                        <tr>
                                                            <th>{{ trans('file.name') }}</th>
                                                            <th>{{ trans('file.Code') }}</th>
                                                            <th>{{ trans('file.Stock Quantity') }}</th>
                                                            <th>Input SO</th>
                                                            <th>{{ trans('file.Difference') }}</th>
                                                            <th><i class="dripicons-trash"></i></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                    <tfoot class="tfoot active">
                                                        <th colspan="2">{{ trans('file.Total') }}</th>
                                                        <th id="stock-quantity">0</th>
                                                        <th id="input-so">0</th>
                                                        <th id="difference">0</th>
                                                        <th><i class="dripicons-trash"></i></th>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ trans('file.Note') }}</label>
                                                <textarea rows="5" class="form-control" name="note"></textarea>
                                            </div>

                                            <input type="hidden" name="total_qty" id="total-qty" />
                                            <input type="hidden" name="item" id="item" />
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <input type="submit" value="{{ trans('file.submit') }}" class="btn btn-primary"
                                            id="submit-button">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script type="text/javascript">
        $("ul#product").siblings('a').attr('aria-expanded', 'true');
        $("ul#product").addClass("show");
        $("ul#product #adjustment-create-menu").addClass("active");

        var lims_product_array = [];
        var product_code = [];
        var product_name = [];
        var product_qty = [];

        $('.selectpicker').selectpicker({
            style: 'btn-link',
        });

        $('select[name="warehouse_id"]').on('change', function() {
            var id = $(this).val();
            $.get('getproduct/' + id, function(data) {
                lims_product_array = [];
                product_code = data[0];
                product_name = data[1];
                product_qty = data[2];
                product_id = data[3];

                $.each(product_code, function(index) {
                    lims_product_array.push(product_code[index] + ' (' + product_name[index] + ')');

                    var tableData = [
                        product_id[index],
                        product_name[index],
                        product_code[index],
                        product_qty[index],
                    ];

                    setTableData(tableData);
                });
            });
        });

        $("#myTable").on('input', '.input-so', function() {
            rowindex = $(this).closest('tr').index();
            checkQuantity($(this).val() ? $(this).val() : 0, true);
        });

        $("table.order-list tbody").on("click", ".ibtnDel", function(event) {
            rowindex = $(this).closest('tr').index();
            $(this).closest("tr").remove();
            calculateTotal();
        });

        $(window).keydown(function(e) {
            if (e.which == 13) {
                var $targ = $(e.target);
                if (!$targ.is("textarea") && !$targ.is(":button,:submit")) {
                    var focusNext = false;
                    $(this).find(":input:visible:not([disabled],[readonly]), a").each(function() {
                        if (this === e.target) {
                            focusNext = true;
                        } else if (focusNext) {
                            $(this).focus();
                            return false;
                        }
                    });
                    return false;
                }
            }
        });

        $(document).on('click', '#submit-button', (e) => {
            e.preventDefault();

            var rownumber = $('table.order-list tbody tr:last').index();
            if (rownumber < 0) {
                alert("Please insert product to order table!")
            }

            var form = $('#adjustment-form')
            const formData = new FormData(form[0]);

            var tableData = $('table.order-list tbody tr')
            tableData.each((index, item) => {
                var sendInput = true;
                var inputData = {}

                var input = $(item).find('input')
                input.each((indexInput, itemInput) => {
                    var inputName = $(itemInput).attr('name').replace('[]', '')
                    var inputValue = $(itemInput).val()

                    inputData[inputName] = inputValue
                    if (inputName == 'qty' && inputValue == 0) {
                        sendInput = false
                    }
                })

                if (sendInput) {
                    formData.append('input[]', JSON.stringify(inputData))
                }
            })

            var totalQty = $('input[name="total_qty"]').val()
            var item = $('input[name="item"]').val()

            formData.append('total_qty', totalQty)
            formData.append('item', item)

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        window.location.href = '/qty_adjustment'
                    } else {
                        alert('Input SO gagal');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Upload failed:', error);
                }
            });
        })

        function setTableData(data) {
            var newRow = $("<tr>");
            var cols = '';
            cols += '<td>' + data[1] + '</td>';
            cols += '<td>' + data[2] + '</td>';
            cols +=
                '<td><input type="number" readonly class="form-control stock-quantity" name="stock_quantity[]" value="' +
                data[3] + '" required step="any" /></td>';
            cols +=
                '<td><input type="number" class="form-control input-so" name="input_so[]" value="' + data[3] +
                '" required step="any" /></td>';
            cols +=
                '<td><input type="number" readonly class="form-control difference" name="qty[]" value="0" required step="any" /></td>';
            cols +=
                '<td><button type="button" class="ibtnDel btn btn-md btn-danger">{{ trans('file.delete') }}</button></td>';
            cols += '<input type="hidden" class="act-val" name="action[]" value="-"/>';
            cols += '<input type="hidden" class="product-code" name="product_code[]" value="' +
                data[2] + '"/>';
            cols += '<input type="hidden" class="product-id" name="product_id[]" value="' + data[0] + '"/>';

            newRow.append(cols);
            $("table.order-list tbody").append(newRow);
            rowindex = newRow.index();
            calculateTotal();
        }

        function checkQuantity(qty) {
            var table = $('table.order-list tbody tr:nth-child(' + (rowindex + 1) + ')')

            var row_product_code = table.find('td:nth-child(2)').text();
            var stock_quantity = table.find('.stock-quantity').val();
            var pos = product_code.indexOf(row_product_code);
            var difference = parseFloat(qty) - parseFloat(stock_quantity);

            var action = '+'
            if (difference < 0) {
                difference *= -1;
                action = '-';
            }

            table.find('.difference').val(difference);
            table.find('.act-val').val(action);
            calculateTotal();
        }

        function calculateTotal() {
            var total_stock = 0;
            var total_so = 0;
            var total_qty = 0;
            var total_item = 0;
            var tableData = $('table.order-list tbody tr')

            tableData.each((index, item) => {
                var inputStock = $(item).find('input.stock-quantity')
                var inputSO = $(item).find('input.input-so')
                var inputQty = $(item).find('input.difference')

                if (inputQty.val() != 0) {
                    total_qty += parseFloat(inputQty.val());
                    total_item += 1
                }

                total_stock += parseFloat(inputStock.val());
                total_so += parseFloat(inputSO.val());
            })

            $("#stock-quantity").text(total_stock);
            $("#input-so").text(total_so);
            $("#difference").text(total_qty);

            $('input[name="total_qty"]').val(total_qty);
            $('input[name="item"]').val(total_item);
        }
    </script>
@endpush
