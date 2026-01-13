<!-- Footer -->
<div style="border-top: #E5E7EB solid 1px;padding-top:10px; margin-top: 10px;">
  <div class="container">
    <p class="footer-message">For all order details, status updates, or printing pdf invoice or excel sheet, please visit this link : 
      <a style="color: #006DFF" href="{{ route('react.dashboard.orders.show', $order->id) }}" target="_blank">{{ route('react.dashboard.orders.show', $order->id)  }}</a> </p>
    <p class="footer-message">If you have any questions regarding this invoice, please contact us at <span
        style="color: #006DFF">{{ $info['email'] }}</span> / <span style="color: #006DFF">{{ $info['phone'] }}</span>.</p>
  </div>
</div>
