<?php
require "db.php"; // подключаем файл для соединения с БД
$id = $_SESSION['key'];
$title = "Пост $id";
require __DIR__ . '/header.php'; // подключаем шапку проекта
$post = R::findOne('places', 'id = ?', [$id]);
?>
<link rel="stylesheet" href="css2/posts.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<body>
    <main>
        <table class="table table-borderless" border='1'>
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Название</th>
                    <th scope="col">Адрес</th>
                    <th scope="col">Маршрут</th>
                    <th scope="col">Широта</th>
                    <th scope="col">Долгота</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Хэштэги</th>
                    <th scope="col">Фотографии</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th><?= $id ?></th>
                    <td><?= htmlspecialchars($post['name']) ?></td>
                    <td><?= htmlspecialchars($post['location']) ?></td>
                    <td><?= htmlspecialchars($post['route']) ?></td>
                    <td><?= htmlspecialchars($post['latitude']) ?></td>
                    <td><?= htmlspecialchars($post['longitude']) ?></td>
                    <td><?= htmlspecialchars($post['description']) ?></td>
                    <td><?= htmlspecialchars($post['hashtags']) ?></td>
                    <td><img src="<?= $post['photos'] ?>" width="200px" alt="Фотография места"/></td>
                </tr>
            </tbody>
        </table>

        <button class="form_btn" type="submit" onclick="openChangeForm()">Изменить</button>
        <button class="form_btn" type="submit" onclick="openDeleteForm()">Удалить</button>
        <a href="posts.php"><button class="form_btn">Вернуться</button></a>

        <div class="form-popup" id="changeForm">
            <form style="display: inline-grid;margin: 1rem;" method="POST" enctype="multipart/form-data">
                <label class="general_form" for="name"><b>Название</b>
                <input class="general_input" type="text" placeholder="Введите название" name="name" required></label>
                <label class="general_form" for="location"><b>Адрес</b>
                <input class="general_input" type="text" placeholder="Введите адрес" name="location" required></label>
                <label class="general_form" for="location"><b>Маршрут</b>
                <input class="general_input" type="text" placeholder="Введите маршрут" name="route" required></label>
                <label class="general_form" for="cordinates"><b>Координаты</b>
                <input style="margin: 20px 10px; font-size: 20px" type="submit" value="Отправить координаты" onclick="getCordinates()" id='cordinates' name="cordinates" required><br>
                <input class="general_form" type="text" placeholder="Введите широту" name="latitude" id='latitude' required>
                <input class="general_form" type="text" placeholder="Введите долготу" name="longitude" id='longitude' required></label><br>
                <div id="error" style='color: red; border-color: red; font-weight:bold;'></div>
                <label class="general_form" for="description"><b>Описание</b>
                <input class="general_input" type="text" placeholder="Введите описание" name="description" required></label>     
                <label class="general_form" for="hashtags"><b>Хэштэги</b>
                <input class="general_input" type="text" placeholder="Введите хэштэги" name="hashtags" required></label>
                <label class="general_form" for="psw"><b>Фотографии</b>
                <input class="general_input" type="file" id="file-uploader" placeholder="Загрузите фотографии" name="photos" accept="image/*" required multiple></label>
                <label class="" id="progress-label" for="progress"></label>
                <progress id="progress" value="0" max="100"> </progress><br>
                <button type="submit" class="form_btn" name='change'>Изменить</button>
                <button type="submit" class="form_btn cancel" onclick="closeChangeForm()">Закрыть</button>
            </form>
        </div>

        <div class="form-popup" id="deleteForm">
            <b>Точно удалить?</b>
            <form class="form-container" method="post">
                <input type="submit" class="btn" name="delete" value="Да"/>
                <input type="submit" class="btn cancel" onclick="closeDeleteForm()" value="Нет"/>
            </form>
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
    
    function openChangeForm() {
        document.getElementById("changeForm").style.display = "block";
    };

    function closeChangeForm() {
        document.getElementById("changeForm").style.display = "none";
    };

    function openDeleteForm() {
        document.getElementById("deleteForm").style.display = "block";
    };

    function closeDeleteForm() {
        document.getElementById("deleteForm").style.display = "none";
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
<?php
    $data = $_POST;
    if (isset($data['delete'])) {
        R::trash($post);
        echo "<meta http-equiv='refresh' content='0; url=posts.php'/>";
    }
    if (isset($data['change'])) {
        $post->name = $data['name'];
        $post->location = $data['location'];
        $post->latitude = $data['latitude'];
        $post->longitude = $data['longitude'];
        $post->description = $data['description'];
        $post->hashtags = $data['hashtags'];
        $post->photos = $data['photos'];
        R::store($post);
        echo '<META HTTP-EQUIV="Refresh" Content="0; URL=post.php">';
    }
    if(isset($data['change'])) {
		$errors = array();
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
                echo '<META HTTP-EQUIV="Refresh" Content="; URL=posts.php">';
            }
		} else {
            echo '<div style="color: red; align-items:center; fonts-size: 20px ">' . array_shift($errors). '</div><hr>';
        }
        }
    }
    require __DIR__ . '/footer.php'
?>