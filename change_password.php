<?php
$title="Смена пароля";
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
?>
<body>
    <main>
        <div class="contain">
            <div class="form_content">
                <h3>Сменить пароль</h3>
                    <form action="change_password.php" method="post" style='margin-bottom: 10px;'>
                        <input type="text" class="form-control general_form" name="login" id="login" placeholder="Введите логин" required><br>
                        <input type="text" class="form-control general_form" name="email" id="email" placeholder="Введите E-mail" required><br>
                        <input type="password" class="form-control general_form" name="password" id="password" placeholder="Введите пароль" required><br>
                        <input type="password" class="form-control general_form" name="password_2" id="password_2" placeholder="Подтвердите пароль" required><br>
                        <button class="form_btn" name="change" type="submit">Авторизоваться</button>
                    </form>
                <a href="login.php" class="exit"><b>Вернуться</b></a>
            </div>
        </div>
    </main>
</body>
<?php
$data = $_POST;
if(isset($data['change'])) {
    $errors = array();
    $user = R::findOne('users', 'login = ?', array($data['login']));

    if($data['login'] == '') {  
        $errors[] = "Введите логин";
    }

    if($data['email'] == '') {
        $errors[] = "Введите E-mail";
    }

    if($data['password'] == '') {   
        $errors[] = "Введите пароль";
    }

    if($data['password_2'] == '') { 
        $errors[] = "Повторите пароль";
    }

    if (mb_strlen($data['email']) < 2 || mb_strlen($data['email']) > 20){ 
        $errors[] = "Недопустимая длина E-mail (от 2 до 20 символов)";
    }

    if($data['password_2'] != $data['password']) {  
        $errors[] = "Повторный пароль введен неверно!";
    }

    if($user) { 
        if($data['email'] === $user->email) {
            // Хешируем пароль
		    $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
            R::store($user);
        } else {
            $errors[] = 'E-mail неверный!';
        }
    } else {    
        $errors[] = 'Пользователь с таким логином не найден!';
    }

    if(!empty($errors)) {   
        echo '<div  class="errors">' . array_shift($errors). '</div><hr>';
    } else {
        header('Location: login.php');
    }
}
require __DIR__ . '/footer.php'; // Подключаем подвал проекта
?>