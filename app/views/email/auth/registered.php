{% extends 'email/templates/default.php' %}

{% block content %}
    <p>You are registered!</p>

    <p>Ativate your account using this link: {{ baseUrl }}{{ urlFor('activate') }}?email={{ user.email }}&identifier={{ identifier|url_encode }}</p>
{% endblock %}