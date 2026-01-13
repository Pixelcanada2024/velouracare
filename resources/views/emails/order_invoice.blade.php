<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #
      {{ $order->code }}
    </title>
    @include('emails.partials.invoice.style')
</head>
<body>
    @include('emails.partials.invoice.header', [
      'order' => $order , 
      'message' => $message,
      'info' => $info
      ])

    <div class="container">
        <table width="100%" cellpadding="0" cellspacing="0">
            @include('emails.partials.invoice.order-info'
            ,['order' => $order]
            )
            @include('emails.partials.invoice.addresses'
            ,['order' => $order]
            )
            @include('emails.partials.invoice.products'
            ,['order' => $order]
            )
        </table>
    </div>

    @include('emails.partials.invoice.footer' , [
      'info' => $info,
      'message' => $message
    ])
</body>
</html>
