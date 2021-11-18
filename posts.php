<?php
$title="Посты";
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
$posts = R::findAll("places");
?>
<link rel="stylesheet" href="css2/posts.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
<body>
  <main>
  
 
    <div class="contain">
      <form method="POST" class="srh">
        <div class="search " style="margin-top: 100px; margin-bottom: 100px;">
            <td>Поиск:</td>
            <input class="form control me-2" type="text" name="search" placeholder="Искать здесь...">
            <button class="btn-outline-success " type="submit" name="do_search">Поиск</button></td>
        </div>
      </form>
      <h1>Посты</h1>
      <a href="index.php"><button class="form_btn">Вернуться</button></a>
      <table class="table" border='1'>
        <div class="table-form">
        <?php foreach ($posts as $id => $post) : ?>

        



<div class="card_form">
       
              <div class="col">
                <p class="th" scope="col">ID</p>
                <p class="tr"><?= $id ?></p>
              </div>
              <div class="col"> 
                <p class="th" scope="col">Название
                <p class="tr"><?= htmlspecialchars($post['name']) ?></p></p>
              </div> 
              <div class="col"> 
                <p class="th" scope="col">Адрес
                <p class="tr"><?= htmlspecialchars($post['location']) ?></p></p>
              </div>
              <div class="col">   
                <p class="th" scope="col">Маршрут
                <p class="tr"><?= htmlspecialchars($post['route']) ?></p></p>
              </div>
              <div class="col">   
                <p class="th" scope="col">Широта
                <p class="tr"><?= htmlspecialchars($post['latitude']) ?></p></p>
              </div>
              <div class="col">   
                <p class="th" scope="col">Долгота
                <p class="tr"><?= htmlspecialchars($post['longitude']) ?></p></p>
              </div>
              <div class="col">  
                <p class="th" scope="col">Описание
                <p class="tr"><?= htmlspecialchars($post['description']) ?></p></p>
              </div>
              <div class="col">  
                <p class="th" scope="col">Хэштэги
                <p class="tr"><?= htmlspecialchars($post['hashtags']) ?></p></p>
              </div>
              <div class="col">  
                <p class="th" scope="col">Фотографии</p>
                <img src="<?= $post['photos'] ?>" width="200px" alt="Фотография места"/>
              </div>
              <div class="col"> 
                <form method="POST"><button class="btn-open" type="submit" style='margin-top: 20px;' onclick='next()' name="<?= $id ?>">Перейти</form>
              </div>
      
              <br>
              </div>
            <?php endforeach;?>
        </div>   
      </table>
    </div>
  </main>
  <script type="text/javascript">
    function next () {
      location.replace("post.php");
    }
  </script>
</body>
<?php
  $data = $_POST;
  $i = array_keys($posts)[0];
  $last = array_keys($posts)[count($posts)-1];
  for ($i; $i <= $last; $i++) {
    if (isset($data["$i"])) {
      $_SESSION['key'] = $i;
      echo "<meta http-equiv='refresh' content='0; url=post.php'/>";
    }
  }

  if (isset($data['do_search'])) {
    if($data['search'] == '') {
      echo "<meta http-equiv='refresh' content='0; url=posts.php'/>";
    } else {
      $bind = $data['search'];
      $places1 = R::getAll("SELECT * FROM `places` WHERE `name` LIKE :search", ['search' => "%$bind%"]);
      $places2 = R::getAll("SELECT * FROM `places` WHERE `description` LIKE :search", ['search' => "%$bind%"]);
      $places3 = R::getAll("SELECT * FROM `places` WHERE `location` LIKE :search", ['search' => "%$bind%"]);
      $places4 = R::getAll("SELECT * FROM `places` WHERE `hashtags` LIKE :search", ['search' => "%$bind%"]);
      $places_all = array_merge($places1, $places2, $places3, $places4);
      $places = array_unique($places_all);  
      $_SESSION['places'] = $places;
      echo "<meta http-equiv='refresh' content='0; url=some_posts.php'/>";
    }
  }
  require __DIR__ . '/footer.php';
?>