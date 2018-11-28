* [{{filepath}}.md]({{filepath}}.md)
{% for content in contents %}
    * [{{content.method}} {{content.path}}]({{filepath}}.md#{{content.method|lower}}-{{content.path|replace({('/'): '', ('{'): '', ('}'): '',})}})
{% endfor %}
