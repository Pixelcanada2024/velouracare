@php
  $additionalInfo = $order->additional_info ?? [];
@endphp
<tr>
  <td>
    <div class="content-section">
      <!-- Web Table -->
      <table class="products-table" style="width: 100%; border-collapse: collapse; border: 1px solid #E0E0E0;">
        <thead>
          <tr style="background-color: #F9FAFB; border-bottom: 1px solid #E5E7EB;">
            <th
              style="text-align: left; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px; max-width: 250px;">
              Product</th>
            <th style="text-align: center; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px;">
              Brand</th>
            <th style="text-align: center; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px;">
              Barcode</th>
            <th style="text-align: center; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px;">
              Unit
              Price</th>
            <th style="text-align: center; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px;">
              QTY</th>
            <th style="text-align: right; padding: 12px 16px; font-weight: 500; color: #374151; font-size: 14px;">
              Total Price</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($order->orderDetails as $orderDetail)
          <?php $variation = (object) $orderDetail->variation; ?>
          <tr style="border-bottom: 1px solid #E5E7EB;">
            <td style="padding: 16px;">
              {{ $variation->name }}
            </td>
            <td style="text-align: center; padding: 16px; color: #374151; font-size: 14px;">
              
              {{ $variation->brand }}
            </td>
            <td style="text-align: center; padding: 16px; color: #374151; font-size: 14px;">
              
              {{ $variation->barcode }}
            </td>
            <td style="text-align: center; padding: 16px; color: #374151; font-size: 14px;">
              
              {{ $additionalInfo['invoice']['currency'] }}{{ number_format($orderDetail->price / $orderDetail->quantity, 2, '.', '') }}
            </td>
            <td style="text-align: center; padding: 16px; color: #374151; font-size: 14px;">
              
              {{ $orderDetail->quantity }}
            </td>
            <td style="text-align: right; padding: 16px; font-weight: 500; color: #111827; font-size: 14px;">
              
              {{ $additionalInfo['invoice']['currency'] }}{{ $orderDetail->price }}
            </td>
          </tr>
          @endforeach

          <!-- Summary Rows -->
          <tr>
            <td colspan="5"
              style="text-align: left; padding: 12px 16px; color: #374151; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              Sub-Total
            </td>
            <td
              style="text-align: right; padding: 12px 16px; color: #111827; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              {{ $additionalInfo['invoice']['currency'] }}{{ $additionalInfo['invoice']['cart_total']  }}
            </td>
          </tr>

          {{-- <tr>
            <td colspan="5"
              style="text-align: left; padding: 12px 16px; color: #374151; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              Shipping Fee
            </td>
            <td
              style="text-align: right; padding: 12px 16px; color: #111827; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              {{ $additionalInfo['invoice']['shipping_cost'] }} 
            </td>
          </tr> --}}

          <tr>
            <td colspan="5"
              style="text-align: left; padding: 12px 16px; color: #374151; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              Tax
            </td>
            <td
              style="text-align: right; padding: 12px 16px; color: #111827; border-bottom: 1px solid #E5E7EB; font-size: 14px;">
              {{ $additionalInfo['invoice']['currency'] }}{{ $additionalInfo['invoice']['tax_amount'] }}
            </td>
          </tr>

          <tr>
            <td colspan="5"
              style="text-align: left; padding: 12px 16px; font-weight: bold; color: #374151; font-size: 14px;">
              Total Payment </td>
            <td style="text-align: right; padding: 12px 16px; font-weight: bold; color: #111827; font-size: 14px;">
              {{ $additionalInfo['invoice']['currency'] }}{{ $order->grand_total }}
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Mobile Cards -->
      <div class="mobile-products">
        @foreach ($order->orderDetails as $orderDetail)
        <?php $variation = (object) $orderDetail->variation; ?>
        <div
          style="background-color: white; border: 1px solid #E5E7EB; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
          <h3 style="font-weight: 600; font-size: 16px; color: #111827; margin-bottom: 12px;">
            {{ $variation->name }}
          </h3>

          <table style="width: 100%; font-size: 14px; color: #374151; border-collapse: collapse;">
            <tr >
              <td style="width: 50%;">Product Name</td>
              <td style="text-align: right;">
                
                {{ $variation->name }}
              </td>
            </tr>
            <tr>
              <td>Brand</td>
              <td style="text-align: right;">
                
                {{ $variation->brand }}
              </td>
            </tr>
            <tr>
              <td>Barcode</td>
              <td style="text-align: right;">
                
                {{ $variation->barcode }}
              </td>
            </tr>
            <tr>
              <td>Unit Price</td>
              <td style="text-align: right;">
                
                {{ $additionalInfo['invoice']['currency'] }}{{ number_format($orderDetail->price / $orderDetail->quantity, 2, '.', '') }}
              </td>
            </tr>
            <tr>
              <td>Qty</td>
              <td style="text-align: right;">
                
                {{ $orderDetail->quantity }}
              </td>
            </tr>
            <tr>
              <td style="font-weight: 600;">Total Price</td>
              <td style="text-align: right; font-weight: 600;">
                
                {{ $additionalInfo['invoice']['currency'] }}{{ $orderDetail->price }}
              </td>
            </tr>
          </table>
        </div>
        @endforeach

        <!-- Mobile Summary -->
        <div style="background-color: #F9FAFB; border-radius: 8px; padding: 24px; margin-top: 24px;">
          <table style="width: 100%; font-size: 14px; color: #374151; border-collapse: collapse;">
            <tr>
              <td style="padding: 10px 0">Sub-Total</td>
              <td style="text-align: right; padding: 10px 0">$
                {{ $additionalInfo['invoice']['cart_total'] }}
              </td>
            </tr>
            {{-- <tr>
              <td style="padding: 10px 0">Shipping Fee</td>
              <td style="text-align: right; padding: 10px 0"> $
                {{ $additionalInfo['invoice']['shipping_cost'] }} 
              </td>
            </tr> --}}

            <tr>
              <td style="padding: 10px 0">Tax</td>
              <td style="text-align: right; padding: 10px 0"> $
                {{ $additionalInfo['invoice']['tax_amount'] }}
              </td>
            </tr>

            <tr>
              <td style="padding: 10px 0">Total Payment </td>
              <td style="text-align: right; padding: 10px 0"> $
                {{ $order->grand_total }}
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </td>
</tr>
