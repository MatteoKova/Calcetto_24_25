@extends('layouts.master')

@section('title', trans('messages.title'))

@section('active_home','active')

@section('breadcrumb')
    <li class="breadcrumb-item active" aria-current="page">{{ trans('messages.home') }}</li>
@endsection

@section('body')
        <div class="container-fluid">
            <div class="row">
                <!-- Text -->
                <div class="col-lg-9 col-sm-12">
                    <div class="citazione">
                        <p>{{ $content }}</p>
                    </div>
                </div>

                <!-- Image -->
                <div class="col-lg-3 col-sm-12">
                    <div class="imgBiblio">
                        <img class="img-thumbnail img-responsive border border-5 border-success" src="{{ url('/') }}/img/soccerLove.png">
                    </div>
                </div>
            </div>
        </div>
@endsection