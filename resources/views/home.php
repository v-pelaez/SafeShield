<?php require __DIR__ . '/../includes/head.php'; ?>

<?php require __DIR__ . '/../includes/header.php'; ?>
<main>
  <?php
  if(isset($_SESSION["role"])){
    header("Location: /welcome");
   }?>
  <div id="log-box">
    <div id="logo-form">
      <img src="src/seguridad-cibernetica.png" alt="" />
      <h1>SafeShield</h1>
    </div>
    <form action="/" method="post">
      <label for="user"><img src="src/user.png" alt="">Usuario:</label>
      <input type="text" name="user" id="user" placeholder="usuario" value="<?php echo $_COOKIE["rememberUser"] ?? "" ?>">
      <label for="password"><img src="src/lock.png" alt="">Contraseña:</label>
      <input type="password" name="password" id="password" placeholder="*********">
      <div>
        <input type="checkbox" name="remember" id="remember">
        <label for="remeber"> Recordar mi cuenta </label>
      </div>
      <?php
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        login();
      }
      ?>
      <div id="submits">
        <button type="submit" name="entry" id="entry">Entrar</button>
      </div>
    </form>
      <a href="/signin">Registrarse por primera vez</a>
  </div>
</main>
<?php require __DIR__ . '/../includes/footer.php'; ?>