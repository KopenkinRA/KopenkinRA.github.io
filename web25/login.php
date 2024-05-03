<?php
  header('Content-Type: text/html; charset=UTF-8');
  $user = 'u';
  $pass = '';
  $db = new PDO('mysql:host=localhost;dbname=u', $user, $pass, [
    PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
  $session_started = false;
  if (!empty($_COOKIE[session_name()]) && session_start()) {
    $session_started = true;
    if (!empty($_SESSION['login'])) {
      ?>
        <form action="" method="post">
          <input type="submit" name="destroy" value="Выйти" />
          <input type="submit" name="continue" value="Продолжить" />
        </form>
      <?php

      if(!empty($_POST['destroy'])){
        session_destroy();
      }
      header('Location: ./');
      exit();
    }
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(!empty($_COOKIE['login_error'])){
      if($_COOKIE['login_error'] == 'login'){
        print '<div class="error">Несуществующий логин.</div>';
      } else {
        print '<div class="error">Неверный пароль.</div>';
      }
    }
  ?>
    <form action="" method="post">
      <input name="login" />
      <input name="pass" />
      <input type="submit" value="Войти" />
    </form>
  <?php
  } else {
    try {
      $sel_pass = $db->prepare("SELECT pass from users WHERE login=:login");
      $sel_pass->execute(['login' => $_POST['login']]);
      $res_pass = $sel_pass -> fetch();
    } catch(PDOException $e) {
      print('Error : ' . $e->getMessage());
      exit();
    }
    if(!empty($res_pass)){
      if($res_pass['pass'] != $_POST['pass']){
        setcookie('login_error', 'pass');
        header('Location: ./login.php');
        exit();
      } else {
        setcookie('login_error', '', 100000);
      }
    } else {
      setcookie('login_error', 'login');
      header('Location: ./login.php');
      exit();
    }
    if (!$session_started) {
      session_start();
    }
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['uid'] = session_id();
    header('Location: ./login.php');
  }
?>
