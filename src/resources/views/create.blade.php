@extends('poll::layouts.master')

@section('content')

<h2>Create new poll</h2>

@include('poll::forms.errors')

@include('poll::forms.create')

@endsection