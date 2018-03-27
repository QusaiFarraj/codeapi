<!DOCTYPE html>
<html>
<head>
    <title>Website | {% block title %}{% endblock %}</title>
</head>
<body>
    {% include 'templates/partials/messages.php' %}
    {% include 'templates/partials/navigation.php' %}
    {% block content %}{% endblock %}
</body>
</html>