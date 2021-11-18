<?php 
$title="Форма регистрации"; // название формы
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
?>
<link rel="stylesheet" href="css2/logsig.css">
<link rel="stylesheet" href="css2/index.css">
<body>
	<main>
		<div class="contain">
					<!-- Форма регистрации -->
					<h3>Форма регистрации</h3>
					<form method="post">
						<input type="text" class="form-control general_form" name="login" id="login" placeholder="Введите логин"><br>
						<input type="email" class="form-control general_form" name="email" id="email" placeholder="Введите Email"><br>
						<input type="password" class="form-control general_form" name="password" id="password" placeholder="Введите пароль"><br>
						<input type="password" class="form-control general_form" name="password_2" id="password_2" placeholder="Повторите пароль"><br>
						<button class="form_btn" name="do_signup" type="submit">Зарегистрироваться</button>
					</form>
					<br>
					<p>Если вы зарегистрированы, тогда нажмите <a href="login.php">здесь</a>.</p>
					<p>Вернуться на <a href="index.php">главную</a>.</p>
		</div>
	</main>
</body>
<?php
require __DIR__ . '/footer.php';
$data = $_POST;

// Пользователь нажимает на кнопку "Зарегистрировать" и код начинает выполняться
if(isset($data['do_signup'])) {
	$errors = array();
	// Проводим проверки
	if(trim($data['login']) == '') {
		$errors[] = "Введите логин!";
	}

	if(trim($data['email']) == '') {
		$errors[] = "Введите Email";
	}

	if($data['password'] == '') {
		$errors[] = "Введите пароль";
	}

	if($data['password_2'] == '') {
		$errors[] = "Повторите пароль";
	}

	if($data['password_2'] != $data['password']) {
		$errors[] = "Повторный пароль введен не верно!";
	}
	
	if(mb_strlen($data['login']) < 3 || mb_strlen($data['login']) > 90) {
	    $errors[] = "Недопустимая длина логина";
    }

    if (mb_strlen($data['password']) < 2 || mb_strlen($data['password']) > 20){
	    $errors[] = "Недопустимая длина пароля (от 2 до 20 символов)";
    }
    // проверка на правильность написания Email
    if (!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $data['email'])) {
	    $errors[] = 'Неверно введен е-mail';
    }
	// Проверка на уникальность логина
	if(R::count('users', "login = ?", array($data['login'])) > 0) {
		$errors[] = "Пользователь с таким логином существует!";
	}
	// Проверка на уникальность email
	if(R::count('users', "email = ?", array($data['email'])) > 0) {
		$errors[] = "Пользователь с таким Email существует!";
	}

	if(empty($errors)) {
		// $code_mail = 123;
		// $from = 'mingazov.insaf@inbox.ru';
		// $to = trim($data['email']);
		// $subject = "Подтвердите E-mail";
		// $message = "Ваш код: " . $code_mail;
		// $headers = "From: $from" . "\r\n" . 
		// "Reply-To: $from" . "\r\n" . 
		// "X-Mailer: PHP/" . phpversion();
		// if (mail($to, $subject, $message, $headers)) {
		// 	echo '<div class="success">Код отправлен на указанный E-mail</div><hr>';
			if(isset($data['do_signup'])) {
			// 	if($data['confirm_mail'] === $code_mail) {
					$user = R::dispense('users');
					// добавляем в таблицу записи
					$user->login = $data['login'];
					$user->email = $data['email'];
					// Хешируем пароль
					$user->password = password_hash($data['password'], PASSWORD_DEFAULT);
					// Сохраняем таблицу
					R::store($user);
					echo '<div class="pop-up">Вы успешно зарегистрированы! Можно <a class="pop_up-a" href="login.php">авторизоваться</a>.</div><hr>';
				} else {
					echo '<div class="errors">Код неверный</div><hr>';
				}
	} else {
		echo '<div class="errors">' . array_shift($errors) . '</div><hr>';
	}

}
?> <!-- Подключаем подвал проекта -->