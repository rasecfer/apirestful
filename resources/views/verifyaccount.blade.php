@component('mail::layout')
  @slot('header')
    @component('mail::header', ['url' => config('app.url')])
      Sistema de Administración de Gastos
    @endcomponent
  @endslot
  # Bienvenido {{$user->name}},

  Gracias por crear tu cuenta, para poder utilizarla por favor verifícala usando el siguiente botón:

  @component('mail::button', ['url' => route('verify', $user->verification_token)])
  Verificar Cuenta      
  @endcomponent

  Gracias,

  @slot('footer')
    @component('mail::footer')
      © 2019 Fmassoft. Todos los derechos reservados.
    @endcomponent
  @endslot
@endcomponent