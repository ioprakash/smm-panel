<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Payment...</title>
</head>
<body>
    <p>Redirecting to Secure Payment Gateway...</p>
    <form id="paymentForm" action="{{ $data['url'] }}" method="{{ $data['method'] }}">
        @foreach($data['fields'] as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
    </form>
    <script>
        document.getElementById('paymentForm').submit();
    </script>
</body>
</html>
