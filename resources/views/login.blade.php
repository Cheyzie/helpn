<x-auth.layout title="Login">
    <form action={{url("/login")}} method="post" class="auth__form">
        @csrf
        <x-auth.input :errors="$errors" name="email" type="text"/>
        <x-auth.input :errors="$errors" name="password" type="password"/>
        <input type="submit" value="Login" class="auth__submit" />
    </form>
    <div class="auth__footer">
        <x-auth.login.footer/>
    </div>
</x-auth.layout>
