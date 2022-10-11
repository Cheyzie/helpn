<x-auth.layout title="Sign Up">
    <form action={{url("/signup")}} method="post" class="auth__form">
        @csrf
        <x-auth.input :errors="$errors" name="name" type="text" autocomplete="username"/>
        <x-auth.input :errors="$errors" name="email" type="text" autocomplete="email"/>
        <x-auth.input :errors="$errors" name="password" type="password" autocomplete="new-password"/>
        <x-auth.input :errors="$errors" name="confirmation" type="password" autocomplete="new-password"/>
        <input type="submit" value="SignUp" class="auth__submit" />
    </form>
    <div class="auth__footer">
        <x-auth.signup.footer/>
    </div>
</x-auth.layout>
