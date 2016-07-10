/*
 Language: Blade
 Author: Cupof < http://co.bsnws.net >
 Category: template
 */

function (hljs) {
    var VARIABLE = {
        className: 'variable', begin: '\\$+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*'
    };
    var PREPROCESSOR = {
        className: 'preprocessor', begin: /<\?(php)?|\?>/
    };
    var STRING = {
        className: 'string',
        contains: [hljs.BACKSLASH_ESCAPE, PREPROCESSOR],
        variants: [
            {
                begin: 'b"', end: '"'
            },
            {
                begin: 'b\'', end: '\''
            },
            hljs.inherit(hljs.APOS_STRING_MODE, {illegal: null}),
            hljs.inherit(hljs.QUOTE_STRING_MODE, {illegal: null})
        ]
    };
    var NUMBER = {variants: [hljs.BINARY_NUMBER_MODE, hljs.C_NUMBER_MODE]};
    return {
        case_insensitive: true,
        keywords: 'and true false as or null NULL ' +
        '== === != <> !== < > <= >= <=> ' +
        'if elseif endif ' +
        'for endfor foreach endforeach while endwhile unless endunless ' +
        'inject each show parent extends can endcan lang yield choice empty forelse endforelse' +
        'unless endunless section endsection stop include ',
        contains: [
            hljs.C_LINE_COMMENT_MODE,
            hljs.HASH_COMMENT_MODE,
            hljs.COMMENT(
                '/\\*',
                '\\*/',
                {
                    contains: [
                        {
                            className: 'doctag',
                            begin: '@[A-Za-z]+'
                        },
                        PREPROCESSOR
                    ]
                }
            ),
            hljs.COMMENT(
                '{{--',
                '--}}',
                {
                    contains: [
                        {
                            className: 'doctag',
                            begin: '@[A-Za-z]+'
                        },
                        PREPROCESSOR
                    ]
                }
            ),
            hljs.COMMENT(
                '__halt_compiler.+?;',
                false,
                {
                    endsWithParent: true,
                    keywords: '__halt_compiler',
                    lexemes: hljs.UNDERSCORE_IDENT_RE
                }
            ),
            {
                className: 'string',
                begin: /<<<['"]?\w+['"]?$/, end: /^\w+;?$/,
                contains: [
                    hljs.BACKSLASH_ESCAPE,
                    {
                        className: 'subst',
                        variants: [
                            {begin: /\$\w+/},
                            {begin: /\{\$/, end: /\}/}
                        ]
                    }
                ]
            },
            PREPROCESSOR,
            VARIABLE,
            {
                // swallow composed identifiers to avoid parsing them as keywords
                begin: /(::|->)+[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/
            },
            {
                className: 'function',
                beginKeywords: 'function', end: /[;{]/, excludeEnd: true,
                illegal: '\\$|\\[|%',
                contains: [
                    hljs.UNDERSCORE_TITLE_MODE,
                    {
                        className: 'params',
                        begin: '\\(', end: '\\)',
                        contains: [
                            'self',
                            VARIABLE,
                            hljs.C_BLOCK_COMMENT_MODE,
                            STRING,
                            NUMBER
                        ]
                    }
                ]
            },
            {
                className: 'class',
                beginKeywords: 'class interface', end: '{', excludeEnd: true,
                illegal: /[:\(\$"]/,
                contains: [
                    {beginKeywords: 'extends implements'},
                    hljs.UNDERSCORE_TITLE_MODE
                ]
            },
            {
                beginKeywords: 'namespace', end: ';',
                illegal: /[\.']/,
                contains: [hljs.UNDERSCORE_TITLE_MODE]
            },
            {
                beginKeywords: 'use', end: ';',
                contains: [hljs.UNDERSCORE_TITLE_MODE]
            },
            {
                begin: '=>' // No markup, just a relevance booster
            },
            STRING,
            NUMBER
        ]
    };
}
