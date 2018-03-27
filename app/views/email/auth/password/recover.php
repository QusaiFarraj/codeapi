{% extends 'email/templates/default.php' %}

{% block content %}
    <p>You requseted a password change.</p>

    <p>Click this link to reset your password: {{ baseUrl }}{{ urlFor('password.reset') }}?email={{ user.email }}&identifier={{ identifier|url_encode }}</p>
{% endblock %}