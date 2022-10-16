---
layout: global.default-layout
title: Package Status
---
<?php

$components = include PROJECT_DATA_ROOT . '/resources/data/components.php';
?>

@extends('global.body')

@section('content')
<div class="container py-5">
    <header class="mb-5">
        <h2>Status</h2>
    </header>
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Component</th>
            <th class="text-end">Status</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($components as $component)
            <tr>
                <td class="text-nowrap">
                    <a class="font-monospace"
                        href="https://github.com/windwalker-io/{{ $component }}"
                        style="text-decoration: none;"
                        target="_blank"
                    >
                        windwalker/{{ $component }}
                    </a>
                </td>
                <td class="text-end text-nowrap">
                    <a href="https://github.com/windwalker-io/{{ $component }}"
                        target="_blank" style="text-decoration: none">
                        <img alt="GitHub" src="https://img.shields.io/github/license/windwalker-io/{{ $component }}?style=flat-square">
                    </a>
                    <a href="https://github.com/windwalker-io/{{ $component }}/actions"
                        target="_blank" style="text-decoration: none">
                        <img alt="GitHub Workflow Status" src="https://img.shields.io/github/workflow/status/windwalker-io/{{ $component }}/PHP%20Composer?label=test&style=flat-square">
                    </a>
                    <a href="https://packagist.org/packages/windwalker/{{ $component }}/stats"
                        target="_blank" style="text-decoration: none">
                        <img alt="Packagist Downloads" src="https://img.shields.io/packagist/dt/windwalker/{{ $component }}?style=flat-square">
                    </a>
                    <a href="https://packagist.org/packages/windwalker/{{ $component }}"
                        target="_blank" style="text-decoration: none">
                        <img alt="Packagist Version" src="https://img.shields.io/packagist/v/windwalker/{{ $component }}?style=flat-square">
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@stop
