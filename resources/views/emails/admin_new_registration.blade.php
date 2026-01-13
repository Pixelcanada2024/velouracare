<body>
  <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #eee; border-radius: 16px;">
    <h2 style="text-align: center;"> New Registration Request</h2>
    <hr>
    <h4> 
      A new user has registered.
    </h4>
    <h4>
      Please review the registration details to accept or reject the registration request in 3-5 business days as we told customers.
    </h4>
    <hr>
    <p>
      <strong> Registration Number : </strong> ( {{ isset($user) ?  100000 + $user?->id : '---' }} ) <br>
      <strong> Date : </strong> {{ isset($user) ?  \Carbon\Carbon::parse($user?->created_at)->format('Y-m-d h:i A')  : '---' }} <br>
      <strong> Name : </strong> {{ isset($user) ? $user?->name : '---' }}<br>
      <strong> Company Name : </strong> {{ isset($user?->businessInfo?->company_name  ) ? $user?->businessInfo?->company_name : '---' }}<br>
      <strong> Email : </strong> {{ isset($user) ? $user?->email  : '---'}}<br>
      <strong> Phone : </strong> {{ isset($user) ? $user?->phone : '---' }}<br>
    </p>
    <hr>
    <p>
      You can review the full registration details by clicking the next link :
      <a href="{{ route('customers.index') }}" target="_blank" > Admin's Customers Page </a>
    </p>
    <p> Thank you for your attention!</p>
    <hr>
    <p> Best regards,</p>
    <p> The Quality Distribution B2B Team</p>
  </div>
</body>