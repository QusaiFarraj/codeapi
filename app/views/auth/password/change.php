{% extends 'templates/default.php' %}

{% block title %} Change Password {% endblock %}

{% block content %}

    <form method="post" action="{{ urlFor('password.change.post') }}">
        <div>
            <label for="password_old">Old Password</label>
            <input type="password" name="password_old" id="password_old">
            {% if errors.has('password_old') %} {{ errors.first('password_old') }} {% endif %}
        </div>
        <div>
            <label for="password_new">New Password</label>
            <input type="password" name="password_new" id="password_new">
            {% if errors.has('password_new') %} {{ errors.first('password_new') }} {% endif %}
        </div>
        <div>
            <label for="password_confirm">Confirm Password</label>
            <input type="password" name="password_confirm" id="password_confirm">
            {% if errors.has('password_confirm') %} {{ errors.first('password_confirm') }} {% endif %}
        </div>
        <div>
            <input type="submit" value="Change">
        </div>
        <input type="hidden" name="{{ csrf_key }}" value="{{ csrf_token }}">
    </form>

{% endblock %}