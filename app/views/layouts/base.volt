<!DOCTYPE html>
<html lang="ru" ng-app="fh">
	<head>
		{% include 'partials/meta.volt' %}
	</head>
	<body ng-controller="MainController">
		{% block navbar %}{% endblock %}

		<div id="main-container" class="container main-container">
			{% block content %}{% endblock %}
		</div>

		{% include 'partials/scripts.volt' %}
	</body>
</html>