{% macro parameter(parameter) %}
{% set string = '* `' ~ parameter.value ~ '`' %}
{% if parameter.options.isa %}
{% set string = string ~ ' **' ~ parameter.options.isa ~ '**' %}
{% endif %}
{% if parameter.options.default or parameter.options.format or parameter.options.except or parameter.options.only %}
{% set comments = [] %}
{% if parameter.options.default %}
{% set comments = comments|merge(['default: `' ~ parameter.options.default|raw ~ '`']) %}
{% endif %}
{% if parameter.options.format %}
{% set comments = comments|merge(['format: `' ~ parameter.options.format|raw ~ '`']) %}
{% endif %}
{% if parameter.options.only %}
{% set comments = comments|merge(['only: `[' ~ parameter.options.only|join(', ') ~ ']`']) %}
{% endif %}
{% if parameter.options.except %}
{% set comments = comments|merge(['except: `[' ~ parameter.options.except|join(', ') ~ ']`']) %}
{% endif %}
{% set string = string ~ ' (' ~ comments|join(', ') ~ ')' %}
{% endif %}
{% if parameter.options.comment %}
{% set string = string ~ ' - ' ~ parameter.options.comment %}
{% endif %}
{{string}}
{% endmacro %}
{% import _self as self %}
{# !! Here are body !! #}
## {{method}} {{path}}
{{document.comment}}

{% if document.parameters %}
### Parameters
{% if document.parameters.getParameter.required is defined %}
#### required
{% for parameter in document.parameters.getParameter.required %}
{{self.parameter(parameter)}}
{% endfor %}
{% endif %}
{% if document.parameters.getParameter.option is defined %}
#### option
{% for parameter in document.parameters.getParameter.option %}
{{self.parameter(parameter)}}
{% endfor %}
{% endif %}
{% if document.parameters.getNote %}
#### :memo: note
{% for note in document.parameters.getNote %}
* {{note}}
{% endfor %}
{% endif %}
{% endif %}

{% if document.examples %}
### Example
{% for example in document.examples %}
{{example.comment}}
{% if example.request is defined %}
#### Request
```
{{example.request.getMethod}} {{example.request.getPath}} 
{% if example.request.getHeaders %}
{% for key, value in example.request.getHeaders %}
{{key}}: {{value}}
{% endfor %}
{% endif %}

{% if example.request.getParameters %}
{{example.request.getParameters|json_encode(constant('JSON_UNESCAPED_UNICODE') b-or constant('JSON_PRETTY_PRINT'))|raw}}
{% endif %}
```
{% endif %}
{% if example.response is defined %}
#### Response
```
{{example.response.code}}
{% if example.response.getHeaders %}
{% for key, value in example.response.getHeaders %}
{{key}}: {{value}}
{% endfor %}
{% endif %}

{% if example.response.getBody %}
{{example.response.getBody|json_encode(constant('JSON_UNESCAPED_UNICODE') b-or constant('JSON_PRETTY_PRINT'))|raw}}
{% endif %}
```
{% endif %}
{% endfor %}
{% endif %}
