<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Email</title>
</head>
<body>
<h1>Test EMAIL</h1>
<p>Hello, {{ $admin->name }}</p>
<h1>Customer List</h1>
@foreach($customers as $customer)
<p>Name: {{ $customer->name }} Email: {{ $customer->email }}</p>
@endforeach
</body>
</html>
