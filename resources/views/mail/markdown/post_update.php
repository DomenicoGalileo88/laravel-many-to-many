@component('mail::message')
# Post update

Ciao Admin il post {{$post_slug}} è stato modificato.
Confermi la modifica?

@component('mail::button', ['url' => '$post_url'])
Confirm
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent