<?php
$title="Памятные места"; // название формы
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<link rel="stylesheet" href="css2/logsig.css">
<body>
	<main>
		<div class="contain">
			<!-- Если авторизован выведет приветствие -->
			<?php if(isset($_SESSION['logged_user'])) : ?>
				<div class="hello"> Привет, <?php echo $_SESSION['logged_user']->login; ?></br></div>
			<!-- Пользователь может нажать выйти для выхода из системы -->
			<a href="logout.php" class="exit">Выйти</a> <!-- файл logout.php создадим ниже -->
				<div class="row">
					<div class="look_posts">
						<a href="posts.php" class="look">Просмотреть все посты</a>	
					</div>
					<!-- Форма регистрации -->
					<div class="form_content">
						<h2>Добавление памятного места</h2>
						<form action="index.php" method="post" enctype="multipart/form-data">
							<input type="text" class=" general_form" name="name" id="name" placeholder="Введите наименование" required><br>
							<input type="text" class=" general_form" name="location" id="location" placeholder="Введите адрес" required><br>
							<input type="text" class=" general_form" name="route" id="route" placeholder="Введите маршрут" required><br>
							<input type="submit" class="form_btn" value="Отправить координаты" onclick="getCordinates()" id='cordinates' name="cordinates" required><br>
							<input type="text" class=" general_form" placeholder="Введите широту" name="latitude" id='latitude' required><br>
    						<input type="text" class=" general_form" placeholder="Введите долготу" name="longitude" id='longitude' required><br>
							<input type="text" class=" general_form" name="description" id="description" placeholder="Введите описание" required><br>
							<input type="text" class=" general_form" name="hashtags" id="hashtags" placeholder="Введите хештэги" required><br>
							<input type="file" class=" general_form" id="file-uploader" placeholder="Загрузите фотографии" name="photo" accept="image/*" required multiple>
							<label id="progress-label" for="progress"></label>
    						<progress id="progress" value="0" max="100"> </progress><br>
							<button class="form_btn" name="save_places" type="submit">Добавить</button>
						</form>
					</div>
			</div>
			</main>
		<script type="text/javascript">
			function getCordinates() {
				navigator.geolocation.getCurrentPosition(position => {
					document.getElementById("latitude").value = position.coords.latitude;
					document.getElementById("longitude").value = position.coords.longitude;
				}, error => {
					document.getElementById("error").innerHTML = error;
				})
			};

			const fileUploader = document.getElementById('file-uploader');
			const feedback = document.getElementById('feedback');
			const progress = document.getElementById('progress');
			const reader = new FileReader();
			fileUploader.addEventListener('change', (event) => {
				const files = event.target.files;
				const file = files[0];
				reader.readAsDataURL(file);
				reader.addEventListener('progress', (event) => {
					if (event.loaded && event.total) {
						const percent = (event.loaded / event.total) * 100;
						progress.value = percent;
						document.getElementById('progress-label').innerHTML = Math.round(percent) + '%';
						if (percent === 100) {
							let msg = `<span style="color:green;">Файл успешно загружен.</span>`;
							feedback.innerHTML = msg;
						}
					}
				});
			});
		</script>
		</body>
			<?php else : ?>		
			<!-- Если пользователь не авторизован выведет ссылки на авторизацию и регистрацию -->
				<div class="main_content">
					<div class="btn">
						<a class="general btn1" href="login.php">Авторизоваться</a><br>
						<a class="general btn2" href="signup.php">Регистрация</a>
					</div>
				</div>

				<div class="alert alert-success" role="alert" style="width: 70%;">
				Посетители нашего сайта вы можете удобным для вас способом показывать, рассказывать про памятные места, а также находить по координатам и карте эту достопримечательность и просматривать добавленные пользователями другие памятные места
</div>

			<?php endif; ?>
		</div>
		<?php require __DIR__ . '/footer.php';
			$data = $_POST;

			if(isset($data['save_places'])) {

			// Регистрируем
			// Создаем массив для сбора ошибок
			$errors = array();

			// Проводим проверки
			// trim — удаляет пробелы (или другие символы) из начала и конца строки
			if(trim($data['name']) == '') {
				$errors[] = "Введите наименование!";
			}

			if(trim($data['location']) == '') {
				$errors[] = "Введите адрес";
			}

			if(trim($data['route']) == '') {
				$errors[] = "Введите маршрут";
			}

			if(trim($data['latitude']) == '') {
				$errors[] = "Введите широту";
			}

			if(trim($data['longitude']) == '') {
				$errors[] = "Введите долготу";
			}

			if(trim($data['description']) == '') {
				$errors[] = "Введите описание";
			}

			if($data['hashtags'] == '') {
				$errors[] = "Введите хештэги";
			}

			if(mb_strlen($data['name']) < 3 || mb_strlen($data['name']) > 50) {
				$errors[] = "Недопустимая длина наименования";
			}

			if (mb_strlen($data['location']) < 3 || mb_strlen($data['location']) > 90){
				$errors[] = "Недопустимая длина адреса";
			}

			if(mb_strlen($data['route']) < 3 || mb_strlen($data['route']) > 90) {
				$errors[] = "Недопустимая длина маршрута";
			}

			if (mb_strlen($data['hashtags']) < 3 || mb_strlen($data['hashtags']) > 50){
				$errors[] = "Недопустимая длина хештэгов";
			}

			if (mb_strlen($data['description']) < 2 || mb_strlen($data['description']) > 200){
				$errors[] = "Недопустимая длина описания";
			}

			if(empty($errors)) {
				$places = R::dispense('places');
				$places->name = $data['name'];
				$places->location = $data['location'];
				$places->route = $data['route'];
				$places->latitude = $data['latitude'];
				$places->longitude = $data['longitude'];
				$places->description = $data['description'];
				$places->hashtags = $data['hashtags'];
				$img_size = 5*1024*1024;
				if(!empty($_FILES['photo']) and $_FILES['photo']['size'] <= $img_size) { 
					$file = $_FILES['photo'];
					$name = $file['name'];
					$path =  "photos\\" . $name;
					if (!move_uploaded_file($file['tmp_name'], $path)) {
						echo '<div style="color: red; align-items:center; fonts-size: 20px ">Произошла ошибка при загрузке фото</div><hr>';
					} else {
						$places->photos = $path;
						R::store($places);
						echo '<div  class="pop-up">Место успешно добавлено!<hr>';
					}
				} else {
					echo '<div style="color: red; align-items:center; fonts-size: 20px ">Произошла ошибка при загрузке фото</div><hr>';
				}
			} else {
				echo '<div style="color: red; align-items:center; fonts-size: 20px ">' . array_shift($errors). '</div><hr>';
			}
			}
		?> 


