@php $headerIsWhite = true; @endphp
@extends('template')
@section('content')
    <main>
        <section class="error">
            <div class="error__container container">
                <div class="error__title">Страница не найдена</div>
                <img class="error__pic" src="/static/images/common/error.png" width="927" height="444" alt="">
                <div class="error__text">Запрашиваемая страница не найдена. Возможно вы сделали опечатку в адресе или страница была перемещена</div>
                <div class="error__action">
                    <a class="button button--primary" href="{{ route('main') }}" title="На главную">
                        <span>На главную</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
@stop
