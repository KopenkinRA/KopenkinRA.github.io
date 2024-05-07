<html>
  <head>
    <link rel="stylesheet" href="style.css">
    <title>web1</title>
  </head>
  <body>

    <?php
      if (!empty($persons_messages)) {
        print('<div id="pers_mess"><table>');
        foreach ($persons_messages as $message) {
          print($message);
        }
        print('</table></div>');
      }
      if (!empty($lang_names) && !empty($lang_counts)) {
        print('<table id="langs_mess">
        <tr>');
        foreach ($lang_names as $name) {
          print($name);
        }
        print('</tr><tr>
        ');
        foreach ($lang_counts as $count) {
          print($count);
        }
        print('</tr>
        </table>');
      }
    ?>

    <div id="form" class="cont">
          <form action="admin.php" method="POST">
              <label>LOGIN:<input name="login" placeholder="login"
                <?php if ($errors['login']) {print 'class="error"';} ?>
                value="<?php print $values['login']; ?>" 
              /></label>
              <label> 
              <input name="command" type="radio" value="update">UPDATE
              <input name="command" type="radio" value="delete"?>DELETE
              </label>
              <label>FIO:<input name="name" placeholder="Введите Ваше имя"
                <?php if ($errors['name']) {print 'class="error"';} ?>
                value="<?php print $values['name']; ?>" 
              /></label>
              <label>TEL:<input name="phone" type="tel" placeholder="Ваш номер телефона"
                <?php if ($errors['phone']) {print 'class="error"';} ?>
                value="<?php print $values['phone']; ?>" 
              /></label>
              <label>MAIL:<input name="mail" type="email" placeholder="Ваш email"
                <?php if ($errors['mail']) {print 'class="error"';} ?>
                value="<?php print $values['mail']; ?>" 
              /></label>
              <label>DATE:<input name="ymd" type="date" value="2023-01-01"
                <?php if ($errors['ymd']) {print 'class="error"';} ?>
                value="<?php print $values['ymd']; ?>" 
              /></label>
              <label <?php if ($errors['gender']) {print 'class="error"';} ?> >GENDER  
              <input name="gender" type="radio" <?php if($values['gender'] == 'M'){ print 'checked="checked"'; } ?> value="M">MALE
              <input name="gender" type="radio" <?php if($values['gender'] == 'F'){ print 'checked="checked"'; } ?> value="F"?>FEMALE</label>
              <label>LANGUAGE:<br>
              <select name="lang[]" multiple="multiple">
                  <option value="1">Pascal</option>
                  <option value="2">C</option>
                  <option value="3">C++</option>
                  <option value="4">Java</option>
                  <option value="5">JavaScript</option>
                  <option value="6">PHP</option>
                  <option value="7">Python</option>
                  <option value="8">Haskel</option>
                  <option value="9">Clojure</option>
                  <option value="10">Prolog</option>
                  <option value="11">Scala</option>
              </select></label>
              <label>BIO:<br><textarea name="bio"
                <?php if ($errors['bio']) {print 'class="error"';}?> >
                <?php print $values['bio']; ?></textarea></label>
              <label><input type="submit" value="SUBMIT"/></label>
          </form>
    </div>
  </body>
</html>