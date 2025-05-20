@extends('components.layouts.app')
@section('content')
<div class="max-w-lg mx-auto mt-8">
    <h1 class="text-2xl font-bold mb-4">Become a Seller</h1>
    @if(session('success'))
        <div class="bg-green-100 p-2 mb-3 rounded">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="bg-red-100 p-2 mb-3 rounded">{{ session('error') }}</div>
    @endif

    @if(isset($application) && $application->status === 'pending')
        <div class="bg-yellow-100 p-2 rounded">Your application is pending. Please wait for admin review.</div>
    @else
    <form action="{{ route('become.seller.apply') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-semibold mb-1">Business Name</label>
            <input type="text" name="business_name" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block font-semibold mb-1">Phone</label>
            <input type="text" name="phone" class="w-full border rounded p-2" required>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded" type="submit">Submit</button>
    </form>
    @endif
</div>
@endsection
