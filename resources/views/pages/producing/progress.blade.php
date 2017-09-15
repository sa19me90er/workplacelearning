<?php
/**
 * This file (progress.blade.php) was created on 06/29/2016 at 12:19.
 * (C) Max Cassee
 * This project was commissioned by HU University of Applied Sciences.
 */
?>
@extends('layout.HUdefault')
@section('title')
    Voortgang
@stop
@section('content')
    <div class="container-fluid">
        <!-- Internship Info -->
        <div class="row">
            <div class="col-md-11">
                <h3>Weekstaten</h3>
            </div>
            <div class="col-md-1">
                <a class="btn btn-info" role="button" target="_blank" href="{{ route('report-producing-export') }}">Export</a>
            </div>
        </div>
        <div class="row">
            <script>
                window.activities = {!! $activitiesJson !!};
                window.exportTranslatedFieldMapping = {!! $exportTranslatedFieldMapping !!};
            </script>

            <div id="ActivityProducingProcessTable" class="__reactRoot col-md-12"></div>
        </div>
        {{--<div class="row">
        <table class="table blockTable col-md-12">
            <thead class="blue_tile">
                <tr>
                    <td>Datum</td>
                    <td>Omschrijving</td>
                    <td>Tijd (Uren)</td>
                    <td>Werken/leren met</td>
                    <td>Complexiteit</td>
                    <td></td>
                </tr>
            </thead>
            @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
                @foreach(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(15, ($page*15)-15) as $a)
                <tr>
                    <td>{{ date('d-m', strtotime($a->date)) }}</td>
                    <td>{{ $a->description }}</td>
                    <td>{{ $a->getDurationString() }}</td>
                    <td>{{ ucwords($a->description) . (($a->getResourceDetail() != null) ? ": ".$a->getResourceDetail() : "") }}</td>
                    <td>{{ $a->getDifficulty() }}</td>
                    <td><a href="{{route('process-producing-edit', ['id' => $a->lap_id]) }}"><i class="glyphicon glyphicon-pencil" aria-hidden="true"></i></a></td>
                </tr>
                @endforeach
            @endif
        </table>
        </div>
        @if(Auth::user()->getCurrentWorkplace() && Auth::user()->getCurrentWorkplaceLearningPeriod()->hasLoggedHours())
            <div class="row">
                <div class="col-md-6" style="text-align:left;">
                    @if($page>1)
                        <a href="{{ route('progress-producing', ['page' => ($page-1)]) }}">Previous Page</a>
                    @endif
                </div>
                <div class="col-md-6" style="text-align:right;">
                    @if(count(Auth::user()->getCurrentWorkplaceLearningPeriod()->getLastActivity(15, ($page*15))) > 0)
                        <a href="{{ route('progress-producing', ['page' => ($page+1)]) }}">Next Page</a>
                    @endif
                </div>
            </div>
        @endif--}}
    </div>
@stop
