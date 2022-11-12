<?php

/**
 * Part of site project.
 *
 * @copyright  Copyright (C) 2022 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

use App\Menu\MenuItem;

return [
    'Core' => [
        'attributes' => new MenuItem(
            title: 'Attributes',
            description: 'PHP8 Attributes decorator component.',
            extra: []
        ),

        'di' => new MenuItem(
            title: 'DI',
            description: 'A powerful PHP Dependency Injection library / IoC Container.',
            extra: []
        ),

        'promise' => new MenuItem(
            title: 'Promise',
            description: 'PHP Promise/A+ library with ES like interface.',
            extra: ['wip' => true]
        ),

        'reactor' => new MenuItem(
            title: 'Reactor',
            description: 'Simple tools to supports event-looping library.',
            extra: ['wip' => true]
        ),
    ],

    'System' => [
        'cache' => new MenuItem(
            title: 'Cache',
            description: 'PSR-6 / PSR-16 compatible cache package.',
            extra: ['wip' => true]
        ),
        'environment' => new MenuItem(
            title: 'Environment',
            description: 'A tool to provider runtime server and browser information.',
            extra: ['wip' => true]
        ),
        'event' => new MenuItem(
            title: 'Event',
            description: 'PSR-14 compatible event dispatchers.',
            extra: ['wip' => true]
        ),
        'filesystem' => new MenuItem(
            title: 'Filesystem',
            description: 'Simple library to provide a fluent interface for file operations.',
            extra: ['wip' => true]
        ),
        'queue' => new MenuItem(
            title: 'Queue',
            description: 'Multiple connection queue management.',
            extra: ['wip' => true]
        ),
        'language' => new MenuItem(
            title: 'Language',
            description: 'I18n library for PHP, support multiple file formats.',
            extra: ['wip' => true]
        ),
    ],

    'HTTP' => [
        'http' => new MenuItem(
            title: 'HTTP',
            description: 'PSR-7 / PSR-15 HTTP message foundation, including Uri, Http client tools.',
            extra: ['wip' => true]
        ),
        'stream' => new MenuItem(
            title: 'Stream',
            description: 'PSR-7 Streaming library.',
            extra: ['wip' => true]
        ),
        'session' => new MenuItem(
            title: 'Session',
            description: 'Object oriented interface to manage PHP sessions.',
            extra: ['wip' => true]
        ),
        'uri' => new MenuItem(
            title: 'Uri',
            description: 'PSR-7 Uri class to manipulate URL data.',
            extra: ['wip' => true]
        ),
    ],

    'Security' => [
        'authentication' => new MenuItem(
            title: 'Authentication',
            description: 'A component to support multiple authenticate gateway.',
            extra: ['wip' => true]
        ),
        'authorization' => new MenuItem(
            title: 'Authorization',
            description: 'Simple ACL component.',
            extra: ['wip' => true]
        ),
        'crypt' => new MenuItem(
            title: 'Crypt',
            description: 'Openssl and libsodium encryption and password hashing adapters for PHP.',
            extra: ['wip' => true]
        ),
        'filter' => new MenuItem(
            title: 'Filter',
            description: 'A set of filter / validate rules.',
            extra: ['wip' => true]
        ),

    ],

    'Database' => [
        'database' => new MenuItem(
            title: 'Database',
            description: 'Simple but powerful DBAL component.',
            extra: ['wip' => true]
        ),
        'query' => new MenuItem(
            title: 'Query',
            description: 'A QueryBuilder component, can use without any framework.',
            extra: ['wip' => true]
        ),
        'orm' => new MenuItem(
            title: 'ORM',
            description: 'An ORM component with DataMapper / Entity pattern.',
            extra: ['wip' => true]
        ),
        'pool' => new MenuItem(
            title: 'Pool',
            description: 'Simple connection pool library.',
            extra: ['wip' => true]
        ),
    ],

    'Data' => [
        'scalars' => new MenuItem(
            title: 'Scalars',
            description: 'PHP scalars objects to enhance data operations.',
            extra: ['wip' => true]
        ),
        'data' => new MenuItem(
            title: 'Data',
            description: 'Provide Collection object and multi-format structured data encode / decode.',
            extra: ['wip' => true]
        ),
    ],

    'HTML & Rendering' => [
        'dom' => new MenuItem(
            title: 'DOM',
            description: 'A DOMDocument wrapper and toolset to build DOM elements.',
            extra: ['wip' => true]
        ),
        'html' => new MenuItem(
            title: 'HTML',
            description: 'A set of HTML element building helpers.',
            extra: ['wip' => true]
        ),
        'form' => new MenuItem(
            title: 'Form',
            description: 'A HTML form builder with multiple field types.',
            extra: ['wip' => true]
        ),
        'edge' => new MenuItem(
            title: 'Edge',
            description: 'A Blade compatible template engine with much extendable interface.',
            extra: ['wip' => true]
        ),
        'renderer' => new MenuItem(
            title: 'Renderer',
            description: 'A multiple template engine adapter, supports Plates / Blade / Mustache / Twig etc.',
            extra: []
        ),
    ],

    'Utilities' => [
        'test' => new MenuItem(
            title: 'Test',
            description: 'Simple test helpers to help unit-testing.',
            extra: ['wip' => true]
        ),
        'utilities' => new MenuItem(
            title: 'Utilities',
            description: 'Some core and sharing classes for Windwalker components.',
            extra: ['wip' => true]
        ),
    ]
];
