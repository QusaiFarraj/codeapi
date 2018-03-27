{% if auth %}
    <p>Hello, {{ auth.getFullNameOrUsername() }}!</p>
    <img src="{{ auth.getAvatarUrl() }}">
{% endif %}
<ul>
    <li><a href="{{ urlFor('home') }}">Home</a></li>

    {% if auth %}
        <li><a href="{{ urlFor('user.profile', {username: auth.username}) }}">Your Profile</a></li>
        <li><a href="{{ urlFor('password.change') }}">Change Password</a></li>
        <li><a href="{{ urlFor('logout') }}">Logout</a></li>
        {% if auth.isAdmin() %}
            <li><a href="{{ urlFor('admin.example') }}">Admin Area</a></li>
        {% endif %}
    {% else %}
        <li><a href="{{ urlFor('register') }}">Register</a></li>
        <li><a href="{{ urlFor('login') }}">Login</a></li>
    {% endif %}
    <li><a href="{{ urlFor('user.all') }}">All User</a></li>
</ul>