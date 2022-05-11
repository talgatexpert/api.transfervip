@component('mail::message')
    # Yeni işbirliği mesajı

    # Address
    {{$item->address}}
    # City
    {{$item->city}}
    # Email
    {{$item->email}}
    # Phone
    {{$item->phone}}
    {{--    @component('mail::button', ['url' => $url])--}}
    {{--        View Order--}}
    {{--    @endcomponent--}}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent