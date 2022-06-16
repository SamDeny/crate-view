@crate/view
===========

*Work in Progress*

Provided TWIG Extensions
------------------------

### TokenParser: `stack` | `push` | `unshift`
*Work in Progress*

```twig
{% stack stack_name %}
    Second Item
{% endstack %}

{% push stack_name %}
    Third Item
{% endpush %}

{% unshift stack_name %}
    First Item
{% endunshift %}
```

### Function: `assets()`
The `assets()` function allows to load all registered elements from a group within a declared asset 
set. This way, you can load all registered stylesheets, link elements and javascripts at once, 
without knowing all their different names.

#### Syntax

**Parameters**
1. `string` The desired asset set name 
2. `string` The desired asset group, declared in the set name.
3. `array` Optional: asset item identifiers to exclude.


**Example 1**

The following example will load all items in the set `set_name` assigned to the `set_group` group.

```twig
{{ assets('set_name', 'set_group') }}
```


**Example 2**

The following example does the same as above, but excludes 2 items:

```twig
{{ assets('set_name', 'set_group', ['@crate/module/css/name.css', '@crate/other_module/css/name.css']) }}
```


### Filter: `asset`
The `asset` filter allows to link single asset files and also define the desired output. The single 
files doesn't need to be declared in the Assets handler itself, but doing it anyways allows declared 
pre-compiler methods to be applied before the file gets embedded into your HTML code.

You can only include files, which are present within the `public` directory of any module. The 
following syntax shows the most basic example (by returning a relative path):

```twig
<link type="text/css" href="{{ '@crate/module/css/stylesheet.css' | asset }}" />
```

This filter can also be used in inline Stylesheet and JavaScript codes like shown below. This time, 
we're using `url` as first parameter, which returns the whole URL including the used protocol and 
current domain:

```twig
<style type="text/css">
    /** Don't forget to quote the string below! */
    .selector {
        background-image: url("{{ '@crate/module/css/stylesheet.css' | asset('url') }}");
    }
</style>
```

The third use-case for the `asset` filter allows to include the whole content of a file instead of 
just a respective URL-linking. This can be used to include vector images, the first example below 
prints the content directly, without any modification:

```twig
<div class="logo">
    {{ '@crate/module/imgs/logo.svg' | asset('content') }}
</div>
```

Additional modifications can be applied as second parameter, multiple modifications can be 
separated by a comma (or by using an array instead of a string). However, modifications are 
currently only supported on the main `content` return method, the following are supported:

- `trim`, trims spaces left and right of the content
- `spaceless`, removes any spaces - HTML-friendly
- `urlencoded`, url-encodes the content
- `base64`, base64-encodes the content
- `md5`, md5-hashes the content
- `sha1`, sha1-hashes the content

```twig
<style type="text/css">
    /** Don't forget to quote the string below! */
    .selector {
        background-image: url("data:image/svg+xml,{{ '@crate/module/imgs/icon.svg' | asset('content', 'spaceless,urlencoded') }}");
    }
    
    .selector-base64 {
        background-image: url("data:image/svg+xml;base64,{{ '@crate/module/imgs/icon.svg' | asset('content', 'spaceless,base64') }}");
    }
</style>
```
