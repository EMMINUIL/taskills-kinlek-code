<?php
$title="Посты";
require __DIR__ . '/header.php'; // подключаем шапку проекта
require "db.php"; // подключаем файл для соединения с БД
$posts = $_SESSION['places'];
?>
<body>
<main>
<div class="contain">
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
                <form method="POST"><button class="btn-open" style="margin-top: 10px;" type="submit" onclick='next()' name="<?= $id ?>">Перейти</form>
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
  echo $i;
  $last = array_keys($posts)[count($posts)-1];
  for ($i; $i <= $last; $i++) {
    if (isset($data["$i"])) {
      $_SESSION['key'] = $i;
      echo "<meta http-equiv='refresh' content='0; url=post.php'/>";
    }
  }
  require __DIR__ . '/footer.php';
?>