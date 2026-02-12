@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Validasi No BPJS</h3>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form id="bpjs-form" method="POST" action="{{ route('bpjs.validate') }}">
        @csrf
        <div class="form-group">
            <label for="no_bpjs">No BPJS</label>
            <input id="no_bpjs" name="no_bpjs" class="form-control" type="text" maxlength="13" required />
            <div id="no_bpjs_error" class="text-danger mt-1" style="display:none;"></div>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Submit</button>
    </form>

    <script>
        (function(){
            const form = document.getElementById('bpjs-form');
            const input = document.getElementById('no_bpjs');
            const err = document.getElementById('no_bpjs_error');

            function validate(value){
                return /^\d{13}$/.test(String(value).trim());
            }

            form.addEventListener('submit', function(e){
                if(!validate(input.value)){
                    e.preventDefault();
                    err.style.display = 'block';
                    err.textContent = 'Nomor BPJS harus 13 digit angka.';
                    input.focus();
                }
            });
        })();
    </script>
</div>
@endsection
