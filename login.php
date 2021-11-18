<?php 
$title="Форма авторизации"; // название формы
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
?>
<link rel="stylesheet" href="css2/posts.css">
<link rel="stylesheet" href="css2/index.css">
<body>
	<main>
		<div class="contain">
			<div class="form_content">
				<!-- Форма авторизации -->
				<h3>Форма авторизации</h3>
				<form action="login.php" method="post">
					<input type="text" class="form-control general_form" name="login" id="login" placeholder="Введите логин" required><br>
					<input type="password" class="form-control general_form" name="password" id="pass" placeholder="Введите пароль" required><br>
					<button class="form_btn" name="do_login" type="submit">Авторизоваться</button>
				</form><br>
				<p>Если вы еще не зарегистрированы, тогда нажмите <a href="signup.php">здесь</a>.</p>
				<p>Вернуться на <a href="index.php">главную</a>.</p>
			</div>
		</div>
	</main>
</body>


<?php require __DIR__ . '/footer.php';
$data = $_POST;

// Пользователь нажимает на кнопку "Авторизоваться" и код начинает выполняться
if(isset($data['do_login'])) { 

 // Создаем массив для сбора ошибок
 $errors = array();

 // Проводим поиск пользователей в таблице users
 $user = R::findOne('users', 'login = ?', array($data['login']));

 if($user) {

 	// Если логин существует, тогда проверяем пароль
 	if(password_verify($data['password'], $user->password)) {
 		// Все верно, пускаем пользователя
 		$_SESSION['logged_user'] = $user;
 		
 		// Редирект на главную страницу
        header('Location: index.php');
 	} else {
    
    $errors[] = '<div class="pop-up">Пароль неверный! <a class="pop_up-a" href="change_password.php">Забыли пароль</a>';

 	}

 } else {
 	$errors[] = '<div class="errors">Пользователь с таким логином не найден!';
 }

if(!empty($errors)) {

		echo '' . array_shift($errors). '</div><hr>';

	}

}
?> <!-- Подключаем подвал проекта -->