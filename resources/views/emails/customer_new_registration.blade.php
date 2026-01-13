<body>
  <div style="max-width: 600px; margin: auto; padding: 20px; font-family: Arial, sans-serif; background-color: #eee; border-radius: 16px;">
    <h2 style="text-align: center;"> Successful Registration Request</h2>
    <hr>
    <h4> We have successfully received your registration request. </h4>
    <h4> Your registration number is ( {{ isset($user) ?  100000 + $user?->id : '---' }} ). </h4>
    <hr>
    <h4> We will review your request and get back to you within 3-5 business days.</h4>
    <h4>After your account is approved, you will be able to login & start shopping.</h4>
    <hr>
    <p> Best regards,</p>
    <p> The Quality Distribution B2B Team</p>
  </div>
</body> 
