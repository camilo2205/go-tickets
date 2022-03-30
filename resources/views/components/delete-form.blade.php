
<form {{ $attributes->merge(["method" => "POST"]) }}
    class="hidden">
    @csrf
    @method('DELETE')
</form>
