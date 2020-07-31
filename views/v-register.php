<div class="bodyContent">

  <h2>S'inscrire</h2>

  <form action="#" method="post">

    <div class="formElement">
      <label for="regPseudo">Pseudo</label>
      <input type="text" name="regPseudo" value="">
    </div>


    <div class="formElement">
      <label for="regEmail">Adresse e-mail</label>
      <input type="email" name="regEmail" value="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
    </div>

    <div class="formElement">
      <label for="regConfEmail">Adresse e-mail de confirmation</label>
      <input type="email" name="regConfEmail" value="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$">
    </div>

    <div class="formElement">
      <label for="regPassword">Mot de passe</label>
      <input type="password" name="regPassword" value="">
    </div>

    <div class="formElement">
      <label for="regPasswordConf">Mot de passe de confirmation</label>
      <input type="password" name="regPasswordConf" value="">
    </div>

    <div class="formElement">
      <button type="submit" name="btnRegister">S'inscrire</button>
    </div>

  </form>

  <hr>
  <a href="./?action=connect">Déjà inscrit?</a>

</div>
