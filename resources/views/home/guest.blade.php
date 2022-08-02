<x-layout>
    <x-slot:title>Мониторинг групп ВК</x-slot:title>
    <section class="guest">
        <div class="guest-wrapper">
            <h2>Войдите, чтобы начать пользоваться системой</h2>
            <a href="{{ route('auth.redirect') }}" class="guest__auth-link">
                Войти через VK
            </a>
        </div>
    </section>
</x-layout>
