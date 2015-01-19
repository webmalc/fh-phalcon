<!DOCTYPE html>
<html lang="ru" ng-app="fh">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="keywords" content="FamilyHelper">
		<meta name="author" content="webmalc@gmail.com">
		<meta name="description" content="Family finance management program">
		<title>{% block title %}FamilyHelper{% endblock %}</title>
		{% block css %}
			{{ assets.outputCss('cdnCss') }}
			{{ assets.outputCss('css') }}
		{% endblock %}
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body>

		<div class="container main-container">
			{% block content %}{% endblock %}
		</div>

		{% block js %}
			{{ assets.outputJs('cdnJs') }}
			{{ assets.outputJs('js') }}
		{% endblock %}
	</body>
</html>