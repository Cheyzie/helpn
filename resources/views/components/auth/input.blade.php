<div class="auth__input">
    <input
        value="{{old($name)}}"
        type="{{ $type }}"
        name={{ $name }}
        id="{{ $name }}_input"
        @class(["auth__field", "auth__field_error" => !empty($errorMessage)])
        placeholder={{ ucfirst($name) }}
    />
    @unless(empty($errorMessage))
        <div class="auth__error">{{ $errorMessage }}</div>
    @endunless
</div>
