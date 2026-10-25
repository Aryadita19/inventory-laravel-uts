@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="card login">
    <h2>Login Inventory</h2>
    <form method="POST" action="{{ route('login.attempt') }}">
        @csrf
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" autofocus>
        <x-field-error name="email" />

        <label for="password">Password</label>
        <input id="password" name="password" type="password">
        <x-field-error name="password" />

        <p><button class="btn" type="submit">Masuk</button></p>
    </form>

    <!-- Bagian Tabel Akun Demo -->
    <table class="table" style="width: 100%; font-size: 14px; text-align: left; margin-top: 20px;">
        <thead>
            <tr>
                <th>Role</th>
                <th>Email</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Admin</td>
                <td>admin@inventory.test</td>
                <td>password</td>
            </tr>
            <tr>
                <td>Staff</td>
                <td>staff@inventory.test</td>
                <td>password</td>
            </tr>
            <tr>
                <td>Customer</td>
                <td>customer@gmail.com</td>
                <td>password</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection