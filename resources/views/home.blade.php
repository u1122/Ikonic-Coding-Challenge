@extends('layouts.app')

@section('content')

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"
    integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/helper.js') }}?v={{ time() }}" defer></script>
  <script src="{{ asset('js/main.js') }}?v={{ time() }}" defer></script>

  <div class="container">
    <x-dashboard />
    <x-network_connections :sugestion_count="$sugestion_count" :connection_count="$connection_count" :send_request_count="$send_request_count" :recieve_count="$recieve_count" :sentRequests="$sentRequests"/>
  </div>
@endsection
