<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa...</title>
</head>
<body onload="document.forms['esewaParams'].submit()">
    <div style="text-align: center; margin-top: 100px; font-family: sans-serif;">
        <h2>Redirecting to eSewa...</h2>
        <p>Please wait while we transfer you to the secure payment gateway.</p>
        <img src="https://esewa.com.np/common/images/esewa_logo.png" alt="eSewa" style="height: 50px;">
    </div>

    <form name="esewaParams" action="{{ config('gateways.esewa.url') }}" method="POST">
        @foreach($params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
</body>
</html>
