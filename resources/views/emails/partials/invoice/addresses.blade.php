@php
  $additionalInfo = $order->additional_info ?? [];
@endphp

<tr>
  <td>
    <div class="addresses-section">
      <!-- Desktop Addresses Container -->
      <table class="addresses-container" cellpadding="0" cellspacing="0">
        <tr>
          <!-- Shipping Address -->
          <td class="address-column" >
            <div class="address-box" style="margin-right: 20px;">
              @if ( $order->shipping_type == "delivery" )
                <h3 class="address-title">Shipped To:</h3>
                <div class="info-grid">
                  <div class="address-value-row">
                    {{ $additionalInfo['shipping']['first_name'] . ' ' . $additionalInfo['shipping']['last_name'] }} 
                  </div>
                  <div class="address-value-row">
                    {{ $additionalInfo['shipping']['phone'] }}
                  </div>
                  <div class="address-value-row">
                    {{$additionalInfo['shipping']['address_line_one'] . ' ' . $additionalInfo['shipping']['address_line_two'] . ', ' . $additionalInfo['shipping']['city'] . ', ' . $additionalInfo['shipping']['state'] . ', ' . $additionalInfo['shipping']['postal_code'] . ', ' . $additionalInfo['shipping']['country']['name']    }}
                  </div>
                  <div class="address-value-row">
                    {{ $additionalInfo['shipping']['email'] }}
                  </div>
                </div>
              @else
                <h3 class="address-title">Picker Info:</h3>
                <div class="info-grid">
                  <div class="address-value-row">
                    {{ $additionalInfo['pickup']['name']  }} 
                  </div>
                  <div class="address-value-row">
                    {{ $additionalInfo['pickup']['phone'] }}
                  </div>
                  <div class="address-value-row" style="color:transparent">
                    ---
                  </div>
                  <div class="address-value-row" style="color:transparent"> 
                    ---
                  </div>
                  <div class="address-value-row" style="color:transparent"> 
                    ---
                  </div>
                </div>
              @endif
            </div>
          </td>
          <!-- Billing Address -->
          <td class="address-column" >
            <div class="address-box" style="margin-left: 20px;">
              <h3 class="address-title">Billed To:</h3>
              <div class="info-grid">
                <div class="address-value-row">
                  {{ $additionalInfo['billing']['first_name'] . ' ' . $additionalInfo['billing']['last_name'] }} 
                </div>
                <div class="address-value-row">
                  {{ $additionalInfo['billing']['phone'] }}
                </div>
                <div class="address-value-row">
                  {{$additionalInfo['billing']['address_line_one'] . ' ' . $additionalInfo['billing']['address_line_two'] . ', ' . $additionalInfo['billing']['city'] . ', ' . $additionalInfo['billing']['state'] . ', ' . $additionalInfo['billing']['postal_code']  . ', ' . $additionalInfo['billing']['country']['name']   }}
                </div>
                <div class="address-value-row">
                  {{ $additionalInfo['billing']['email'] }}
                </div>
              </div>
            </div>
          </td>
        </tr>
      </table>

      <!-- Mobile Address Cards -->
      <div class="mobile-address-cards">
        <!-- Mobile Shipping Address Card -->
        <div class="address-box mobile-address-card">
          @if ( $order->shipping_type == "delivery" )
            <h3 class="address-title">Shipped To:</h3>
            <table>
              <tr>
                <td >
                  {{ $additionalInfo['shipping']['first_name'] . ' ' . $additionalInfo['shipping']['last_name'] }}
                </td>
              </tr>
              <tr>
                <td >
                  {{ $additionalInfo['shipping']['phone'] }}
                </td>
              </tr>
              <tr>
                <td >
                  {{$additionalInfo['shipping']['address_line_one'] . ' ' . $additionalInfo['shipping']['address_line_two'] . ', ' . $additionalInfo['shipping']['city'] . ', ' . $additionalInfo['shipping']['state'] . ', ' . $additionalInfo['shipping']['postal_code'] . ', ' . $additionalInfo['shipping']['country']['name']  }}
                </td>
              </tr>
              <tr>
                <td >
                  {{ $additionalInfo['shipping']['email'] }}
                </td>
              </tr>
            </table>
          @else
            <h3 class="address-title">Picker Info:</h3>
            <table>
              <tr>
                <td >
                  {{ $additionalInfo['pickup']['name'] }}
                </td>
              </tr>
              <tr>
                <td >
                  {{ $additionalInfo['pickup']['phone'] }}
                </td>
              </tr>
              <tr style="color: transparent">
                <td >
                  ---
                </td>
              </tr>
              <tr style="color: transparent">
                <td >
                  ---
                </td>
              </tr>
              <tr style="color: transparent">
                <td >
                  ---
                </td>
              </tr>
            </table>
              
          @endif
        </div>

        <!-- Mobile Billing Address Card -->
        <div class="address-box mobile-address-card">
          <h3 class="address-title">Billed To:</h3>
          <table>
            <tr>
              <td >
                {{ $additionalInfo['billing']['first_name'] . ' ' . $additionalInfo['billing']['last_name'] }}
              </td>
            </tr>
            <tr>
              <td >
                {{ $additionalInfo['billing']['phone'] }}
              </td>
            </tr>
            <tr>
              <td >
                {{$additionalInfo['billing']['address_line_one'] . ' ' . $additionalInfo['billing']['address_line_two'] . ', ' . $additionalInfo['billing']['city'] . ', ' . $additionalInfo['billing']['state'] . ', ' . $additionalInfo['billing']['postal_code'] . ', ' . $additionalInfo['billing']['country']['name']  }}
              </td>
            </tr>
            <tr>
              <td >
                {{ $additionalInfo['billing']['email'] }}
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </td>
</tr>
