@extends('backend.layouts.app')

@section('content')

    <div class="card">
      @php
          $orderCurrency = isset($order->additional_info['invoice']['currency']) ? $order->additional_info['invoice']['currency'] : (config("app.is_america") ? "USD" : "SAR");
      @endphp
        <div class="card-header">
            <h1 class="h2 fs-16 mb-0">{{ translate('Order Details') }}</h1>
        </div>
        <div class="card-body">
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                </div>
                @php
                    $delivery_status = $order->delivery_status;
                    $payment_status = $order->payment_status;
                    $admin_user_id = get_admin()->id;
                @endphp
                @if ($order->seller_id == $admin_user_id || get_setting('product_manage_by_admin') == 1)

                    <!--Assign Delivery Boy-->
                    {{-- @if (addon_is_activated('delivery_boy'))
                        <div class="col-md-3 ml-auto">
                            <label for="assign_deliver_boy">{{ translate('Assign Deliver Boy') }}</label>
                            @if (($delivery_status == 'pending' || $delivery_status == 'confirmed' || $delivery_status == 'picked_up') && auth()->user()->can('assign_delivery_boy_for_orders'))
                                <select class="form-control aiz-selectpicker" data-live-search="true"
                                    data-minimum-results-for-search="Infinity" id="assign_deliver_boy">
                                    <option value="">{{ translate('Select Delivery Boy') }}</option>
                                    @foreach ($delivery_boys as $delivery_boy)
                                        <option value="{{ $delivery_boy->id }}"
                                            @if ($order->assign_delivery_boy == $delivery_boy->id) selected @endif>
                                            {{ $delivery_boy->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ optional($order->delivery_boy)->name }}"
                                    disabled>
                            @endif
                        </div>
                    @endif --}}
                  <div class="col-md-6 row">

                      <div class="col-md-5 m-2 ml-auto">
                          <label for="update_payment_status">{{ translate('Payment Status') }}</label>
                          @if (auth()->user()->can('update_order_payment_status') && $payment_status == 'unpaid')
                              {{-- <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status"> --}}
                              <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity" id="update_payment_status" onchange="confirm_payment_status()">
                                  <option value="unpaid" @if ($payment_status == 'unpaid') selected @endif>
                                      {{ translate('Unpaid') }}
                                  </option>
                                  <option value="paid" @if ($payment_status == 'paid') selected @endif>
                                      {{ translate('Paid') }}
                                  </option>
                              </select>
                          @else
                              <input type="text" class="form-control" value="{{ ucfirst($payment_status) }}" disabled>
                          @endif
                      </div>
                      <div class="col-md-5 m-2 ml-auto">
                          <label for="update_delivery_status">{{ translate('Delivery Status') }}</label>
                          @if (auth()->user()->can('update_order_delivery_status') && $delivery_status != 'delivered' && $delivery_status != 'cancelled')
                              <select class="form-control aiz-selectpicker" data-minimum-results-for-search="Infinity"
                                  id="update_delivery_status">
                                  <option value="processing" @if ($delivery_status == 'processing') selected @endif>
                                      {{ translate('Processing') }}
                                  </option>
                                  <option value="payment" @if ($delivery_status == 'payment') selected @endif>
                                      {{ translate('Payment') }}
                                  </option>
                                  <option value="confirmed" @if ($delivery_status == 'confirmed') selected @endif>
                                      {{ translate('Confirmed') }}
                                  </option>
                                  <option value="picked_up" @if ($delivery_status == 'picked_up') selected @endif>
                                      {{ translate('Picked Up') }}
                                  </option>
                                  <option value="on_the_way" @if ($delivery_status == 'on_the_way') selected @endif>
                                      {{ translate('On The Way') }}
                                  </option>
                                  <option value="delivered" @if ($delivery_status == 'delivered') selected @endif>
                                      {{ translate('Delivered') }}
                                  </option>
                                  <option value="cancelled" @if ($delivery_status == 'cancelled') selected @endif>
                                      {{ translate('Cancel') }}
                                  </option>
                              </select>
                          @else
                              <input type="text" class="form-control" value="{{ $delivery_status }}" disabled>
                          @endif
                      </div>
                      <div class="col-md-5 m-2 ml-auto">
                          <label for="update_tracking_code" title="(Submit => Press Enter)">
                              {{ translate('Tracking Code (optional)') . " "  }} 
                          </label>
                          <input type="text" class="form-control" id="update_tracking_code"
                              value="{{ $order->tracking_code }}">
                      </div>
                      <div class="col-md-5 m-2 ml-auto">
                          <label for="update_shipping_cost" title="(Submit => Press Enter)">
                              {{ translate('Shipping Cost (optional)') . " "  }} 
                          </label>
                          <input type="number" min="0" step="0.01" class="form-control" id="update_shipping_cost"
                              value="{{ $order->additional_info['invoice']['shipping_cost'] ?? 0 }}">
                      </div>
                      <div class="col-md-5 m-2 ml-auto">
                          <label for="update_discount_amount" title="(Submit => Press Enter)">
                              {{ translate('Discount Amount (optional)') . " "  }} 
                          </label>
                          <input type="number" min="0" step="0.01" class="form-control" id="update_discount_amount"
                              value="{{ $order->additional_info['invoice']['discount_amount'] ?? 0 }}">
                      </div>
                    </div>
                @endif

            </div>
            <div class="mb-3">
                @php
                    $removedXML = '<?xml version="1.0" encoding="UTF-8"?>';
                @endphp
                {!! str_replace($removedXML, '', QrCode::size(100)->generate($order->code)) !!}
            </div>
            <div class="row gutters-5">
                <div class="col text-md-left text-center">
                  <h5 title="{{ translate('Shipping Type') }}"> {{ translate('Shipping Type') }} : </h5>
                  {{ $order->shipping_type }}
                  <br>
                  <br>
                  <h5 style="max-width: 500px" title="{{ translate('Additional Info') }}"> {{ translate('Additional Info') }} :</h5>
                  {{ $order?->additional_info['additional_notes'] ?? '' }}
                  <br>
                  <br>
                  <h3> {{ translate('Invoice Address') }} </h3>
                  <address>
                    <strong class="text-main">
                      {{ $order->additional_info['billing']['first_name'] }} {{ $order->additional_info['billing']['last_name'] }}
                    </strong><br>
                    {{ $order->additional_info['billing']['email'] }}<br>
                      {{ $order->additional_info['billing']['phone'] }}<br>
                      {{ $order->additional_info['billing']['address_line_one'] }}, {{ $order->additional_info['billing']['address_line_two'] }}, {{ $order->additional_info['billing']['city'] }}, @if(isset($order->additional_info['billing']['state'])) {{ $order->additional_info['billing']['state'] }} - @endif {{ $order->additional_info['billing']['postal_code'] }}<br>
                      {{ $order->additional_info['billing']['country']['name'] }} <br>
                  </address>
                    @if($order->shipping_type == 'delivery')
                        <h3> {{ translate('Shipping Address') }} </h3>
                        <address>
                            <strong class="text-main">
                                {{ $order->additional_info['shipping']['first_name'] }} {{ $order->additional_info['shipping']['last_name'] }}
                            </strong><br>
                            {{ $order->additional_info['shipping']['email'] }}<br>
                            {{ $order->additional_info['shipping']['phone'] }}<br>
                            {{ $order->additional_info['shipping']['address_line_one'] }}, {{ $order->additional_info['shipping']['address_line_two'] }}, {{ $order->additional_info['shipping']['city'] }}, @if(isset($order->additional_info['shipping']['state'])) {{ $order->additional_info['shipping']['state'] }} - @endif {{ $order->additional_info['shipping']['postal_code'] }}<br>
                            {{ $order->additional_info['shipping']['country']['name'] }}
                        </address>
                    @else
                        <h3> {{ translate('Picket Info') }} </h3>
                        <address>
                            <strong class="text-main">
                                {{ $order->additional_info['pickup']['name'] }}
                            </strong><br>
                            {{ $order->additional_info['pickup']['phone'] }}<br>
                        </address>
                    @endif
                    {{-- @if ($order->manual_payment && is_array(json_decode($order->manual_payment_data, true)))
                        <br>
                        <strong class="text-main">{{ translate('Payment Information') }}</strong><br>
                        {{ translate('Name') }}: {{ json_decode($order->manual_payment_data)->name }},
                        {{ translate('Amount') }}:
                        {{ $orderCurrency }} {{(json_decode($order->manual_payment_data)->amount) }},
                        {{ translate('TRX ID') }}: {{ json_decode($order->manual_payment_data)->trx_id }}
                        <br>
                        <a href="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" target="_blank">
                            <img src="{{ uploaded_asset(json_decode($order->manual_payment_data)->photo) }}" alt=""
                                height="100">
                        </a>
                    @endif --}}
                </div>
                <div class="col-md-4">
                    <table class="ml-auto">
                        <tbody>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order #') }}</td>
                                <td class="text-info text-bold text-right"> {{ $order->code }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Status') }}</td>
                                <td class="text-right">
                                    @if ($delivery_status == 'delivered')
                                        <span class="badge badge-inline badge-success">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @else
                                        <span class="badge badge-inline badge-info">
                                            {{ translate(ucfirst(str_replace('_', ' ', $delivery_status))) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">{{ translate('Order Date') }} </td>
                                <td class="text-right">{{ date('d-m-Y h:i A', $order->date) }}</td>
                            </tr>
                            <tr>
                                <td class="text-main text-bold">
                                    {{ translate('Total amount') }}
                                </td>
                                <td class="text-right">
                                    {{ $orderCurrency }} {{($order->grand_total) }}
                                </td>
                            </tr>
                            {{-- <tr>
                                <td class="text-main text-bold">{{ translate('Payment method') }}</td>
                                <td class="text-right">
                                    {{ translate(ucfirst(str_replace('_', ' ', $order->payment_type))) }}</td>
                            </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <hr class="new-section-sm bord-no">
            <div class="row">
                <div class="col-lg-12 table-responsive">
                    <table class="table-bordered aiz-table invoice-summary table">
                        <thead>
                            <tr class="bg-trans-dark">
                                <th data-breakpoints="lg" class="min-col">#</th>
                                <th width="10%">{{ translate('Photo') }}</th>
                                <th class="text-uppercase">{{ translate('Description') }}</th>
                                <th data-breakpoints="lg" class="text-uppercase">{{ translate('Delivery Type') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Qty') }}
                                </th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-center">
                                    {{ translate('Price') }}</th>
                                <th data-breakpoints="lg" class="min-col text-uppercase text-right">
                                    {{ translate('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderDetails as $key => $orderDetail)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{-- @if ($orderDetail->variation != null && $orderDetail->variation->auction_variation == 0) --}}
                                            <a href="{{ route('react.product', $orderDetail->variation['id']) }}" target="_blank">
                                                {{-- <img height="50" src="{{ uploaded_asset($orderDetail->variation['image']) }}"> --}}
                                                <img height="50" src="{{ $orderDetail->variation['image'] }}">
                                            </a>
                                        {{-- @elseif ($orderDetail->variation != null && $orderDetail->variation->auction_variation == 1)
                                            <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank">
                                                <img height="50" src="{{ uploaded_asset($orderDetail->variation->thumbnail_img) }}">
                                            </a>
                                        @else
                                            <strong>{{ translate('N/A') }}</strong>
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{-- @if ($orderDetail->product != null && $orderDetail->product->auction_product == 0) --}}
                                            <strong>
                                                <a href="{{ route('react.product', $orderDetail->variation['id']) }}" target="_blank"
                                                    class="text-muted" >
                                                    {{ $orderDetail?->variation['name'] }}
                                                </a>
                                            </strong>
                                            <br>
                                            <small title="{{ translate('SKU') }}">
                                                {{ $orderDetail->variation['sku'] }}
                                            </small>
                                            <br>
                                            <small title="{{ translate('barcode') }}">
                                                {{ $orderDetail->variation['barcode'] }}
                                            </small>
                                        {{-- @elseif ($orderDetail->product != null && $orderDetail->product->auction_product == 1)
                                            <strong>
                                                <a href="{{ route('auction-product', $orderDetail->product->slug) }}" target="_blank"
                                                    class="text-muted">
                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                </a>
                                            </strong>
                                        @else
                                            <strong>{{ translate('Product Unavailable') }}</strong>
                                        @endif --}}
                                    </td>
                                    <td>
                                        {{ $order->shipping_type }}
                                    </td>
                                    <td class="text-center">
                                        {{ $orderDetail->quantity * $orderDetail->variation['box_qty'] }}
                                    </td>
                                    <td class="text-center">
                                        {{ $orderCurrency }} {{($orderDetail->variation['price']) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $orderCurrency }} {{($orderDetail->price) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="clearfix float-right">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Sub Total') }} :</strong>
                            </td>
                            <td>
                                {{ $orderCurrency }} {{($order->additional_info['invoice']['cart_total']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Tax') }} :</strong>
                            </td>
                            <td>
                                {{ $orderCurrency }} {{($order->additional_info['invoice']['tax_amount']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Shipping') }} :</strong>
                            </td>
                            <td>
                                {{ $orderCurrency }} {{($order->additional_info['invoice']['shipping_cost']) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('Discount') }} :</strong>
                            </td>
                            <td>
                                {{ $orderCurrency }} {{($order->additional_info['invoice']['discount_amount']) ?? 0}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong class="text-muted">{{ translate('TOTAL') }} :</strong>
                            </td>
                            <td class="text-muted h5">
                                {{ $orderCurrency }} {{($order->grand_total) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="no-print text-right">
                    <a href="{{ route('orders.print', $order->id) }}" type="button" class="btn btn-icon btn-light"><i
                            class="las la-print"></i></a>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('modal')

    <!-- confirm payment Status Modal -->
    <div id="confirm-payment-status" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-3 mb-3 fs-16 fw-700">{{translate('Are you sure you want to change the payment status?')}}</p>
                    <button type="button" class="btn btn-light rounded-2 mt-2 fs-13 fw-700 w-150px" data-dismiss="modal">{{ translate('Cancel') }}</button>
                    <button type="button" onclick="update_payment_status()" class="btn btn-success rounded-2 mt-2 fs-13 fw-700 w-150px">{{translate('Confirm')}}</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    @if ($order->delivery_status == 'pending')
      <script>
          window.onload = function() {
            $('#update_delivery_status').val('processing').trigger('change');
          };
      </script>                        
    @endif
    <script type="text/javascript">
        $('#assign_deliver_boy').on('change', function() {
            var order_id = {{ $order->id }};
            var delivery_boy = $('#assign_deliver_boy').val();
            $.post('{{ route('orders.delivery-boy-assign') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                delivery_boy: delivery_boy
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery boy has been assigned') }}');
            });
        });
        $('#update_delivery_status').on('change', function() {
            const orderStatusOrder = @json($orderStatusOrder);
            const currentOrderStatus = @json($order->delivery_status);
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            if ( orderStatusOrder[status] <= orderStatusOrder[currentOrderStatus]  ) {
                AIZ.plugins.notify('warning', '{{ translate('Can update only to a next status') }}');
                location.reload();
                return;
            }
            $.post('{{ route('orders.update_delivery_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: status
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Delivery status has been updated') }}');
                location.reload();
            });
        });

        // Payment Status Update
        function confirm_payment_status(value){
            $('#confirm-payment-status').modal('show');
        }

        function update_payment_status(){
            $('#confirm-payment-status').modal('hide');
            var order_id = {{ $order->id }};
            $.post('{{ route('orders.update_payment_status') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                status: 'paid'
            }, function(data) {
                $('#update_payment_status').prop('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
                AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
                location.reload();
            });
        }

        $('#update_tracking_code').on('change', function() {
            var order_id = {{ $order->id }};
            var tracking_code = $('#update_tracking_code').val();
            $.post('{{ route('orders.update_tracking_code') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                tracking_code: tracking_code
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order tracking code has been updated') }}');
            });
        });

        $('#update_shipping_cost').on('change', function() {
            var order_id = {{ $order->id }};
            var shipping_cost = $('#update_shipping_cost').val();
            $.post('{{ route('orders.update_shipping_cost') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                shipping_cost: shipping_cost
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order shipping cost has been updated') }}');
                setTimeout(() => location.reload() , 2000);
            });
        });

        $('#update_discount_amount').on('change', function() {
            var order_id = {{ $order->id }};
            var discount_amount = $('#update_discount_amount').val();
            $.post('{{ route('orders.update_discount_amount') }}', {
                _token: '{{ @csrf_token() }}',
                order_id: order_id,
                discount_amount: discount_amount
            }, function(data) {
                AIZ.plugins.notify('success', '{{ translate('Order discount amount has been updated') }}');
                setTimeout(() => location.reload() , 2000);
            });
        });
    </script>
@endsection
