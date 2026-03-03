@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('attendances.index') }}">
        <button>Back to attendances</button>
    </a>
</div>

<div>
    Import CSV file: 
    <form action="{{ route('attendances.csvStore') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file" id="file" accept=".csv, ">
        <input type="submit" value="Submit">
    </form>
    <br>
    <form action="#">

    </form>
</div>
@endsection