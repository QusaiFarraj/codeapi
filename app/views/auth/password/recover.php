{% extends 'templates/default.php' %}

{% block title %} Recover Password {% endblock %}

{% block content %}

    <form method="post" action="{{ urlFor('password.recover.post') }}">
        <div>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" {% if request.post('email') %}{{ request.post('email') }}{% endif %}>
            {% if errors.has('email') %}{{ errors.first('email') }}{% endif %}
        </div>
        <div>
            <input type="submit" value="Request reset">
        </div>
        <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
    </form>

{% endblock %}