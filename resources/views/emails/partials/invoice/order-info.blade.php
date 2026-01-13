@php
  $additionalInfo = $order->additional_info ;
@endphp
<tr>
  <td>
    <div class="content-section order-info">

      <div>
        <h2 class="section-title">Invoice Number : <span>
          {{ $order->code }}
        </span></h2>

        <div style="margin: 10px 0;">
          <span class="order-info-label">Date : </span>
          <span >
            {{ date('d/m/Y', $order->date) }}
          </span>
        </div>
        <div style="margin: 10px 0;">
          <span class="order-info-label">Order Number : </span>
          <span style="color:#006DFF;text-decoration:underline" >
            #{{ $order->code }}
          </span>
        </div>
        {{-- <div style="margin: 10px 0;">
          <span class="order-info-label">Payment Method : </span>
          <span >
            Credit Card
            {{ strtoupper(str_replace('_', ' ', $order->additionalInfo['payment_method'])) }}
          </span>
        </div> --}}
        <div style="margin: 10px 0;">
          <span class="order-info-label">Shipping Type : </span>
          <span >
            {{ strtoupper($additionalInfo['shipping_type']) }}
          </span>
        </div>
        <div style="margin: 10px 0;">
          <span class="order-info-label">Total Payable Amount : </span>
          <span >
            {{ $additionalInfo['invoice']['currency'] }}{{ $additionalInfo['invoice']['cart_total'] }}
          </span>
        </div>

      </div>

    </div>
  </td>
</tr>
