@if (session('message'))
<div class="alert alert-success mt-4" role="alert">
    {{ session('message') }}
</div>
@endif