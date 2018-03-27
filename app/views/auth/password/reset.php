{% extends 'templates/default.php' %}

{% block title %} Reset Password {% endblock %}

{% block content %}
    <form method="post" action="{{ urlFor('password.reset.post') }}?email={{ email }}&identifier={{ identifier|url_encode }}">
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
            {% if errors.has('password') %}{{ errors.first('password')}}{% endif %}
        </div>
        <div>
            <label for="password_confirm">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm">
            {% if errors.has('password_confirm') %}{{ errors.first('password_confirm')}}{% endif %}            
        </div>
        <div>
            <input type="submit" name="Reset">
        </div>
        <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
    </form>
{% endblock %}