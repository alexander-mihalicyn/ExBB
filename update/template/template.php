<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $title; ?> :: <?php echo EXBB_VERSION_NAME; ?></title>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="./frontend.js"></script>

	<link rel="stylesheet" href="template/assets/css/style.css" />
</head>
<body>
<div class="b-page">
	<div class="b-installation-form">
		<header class="b-installation-form__header">
			<h1 id="titleContainer"><?php echo $title; ?></h1>
		</header>
		<main class="b-installation-form__data">
			<section class="b-installation-form__content" id="contentContainer">
				<?php echo $content; ?>
			</section>
			<?php if (!empty($stepsList)) : ?>
			<aside class="b-installation-form__steps">
				<ul>
					<?php foreach ($stepsList as $index=>$step) : ?>
					<li id="stepElement<?php echo $index; ?>"><a href="#"><?php echo $step['title']; ?></a></li>
					<?php endforeach; ?>
				</ul>
			</aside>
			<?php endif; ?>
		</main>
	</div>

	<footer class="b-page__footer">
		<div class="b-page__footer_content">
			<a href="http://exbb.info/community/">&copy; ExBB Forum Engine</a>
		</div>
	</footer>
</div>
</body>
</html>