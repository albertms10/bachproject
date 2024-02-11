<div class="ui tiny modal aparicio">
  <i class="close icon"></i>
  <div class="header">
    Afegir aparició
  </div>
  <div class="content">
    <form id="form-aparicions" class="ui form">
      <input type="hidden" name="id_obra" value="<?php echo $id_obra ?>">
      <input type="hidden" name="id_moviment" id="id_moviment">
      <input type="hidden" name="id_usuari" value="<?php echo $_SESSION["id"] ?>">
      <input type="hidden" name="tipus" value="BACH">
      <div class="fields">
        <div class="six wide field">
          <label for="temps_inici">Inici</label>
          <div class="two fields">
            <div class="field">
              <input type="number" id="compas_inici" name="compas_inici" min="1" placeholder="Compàs">
            </div>
            <div class="field">
              <input type="number" id="temps_inici" name="temps_inici" min="1" placeholder="Temps">
            </div>
          </div>
        </div>
        <div class="six wide field">
          <label for="temps_final">Final</label>
          <div class="two fields">
            <div class="field">
              <input type="number" id="compas_final" name="compas_final" min="1" placeholder="Compàs">
            </div>
            <div class="field">
              <input type="number" id="temps_final" name="temps_final" min="1" placeholder="Temps">
            </div>
          </div>
        </div>
        <div class="four wide field">
          <label for="veu">Veu</label>
          <select class="ui fluid search dropdown" id="veu" name="veu">
            <option value="">Veu</option>
            <option value="S">Soprano</option>
            <option value="A">Contralt</option>
            <option value="T">Tenor</option>
            <option value="B">Baix</option>
          </select>
        </div>
      </div>
      <div class="ui center aligned basic segment" style="padding:0">
        <div class="fields" style="position:absolute">
          <div class="field">
            <label for="transposicio">Transposició</label>
            <div class="fields">
              <div class="sixteen wide field">
                <select class="ui fluid mini search dropdown" id="transposicio" name="transposicio" onchange="$('#etiqueta-transposicio').html(this.value.replace('-', '&minus;'))">
                  <option value="+6">Mi</option>
                  <option value="+5">Mi&thinsp;♭</option>
                  <option value="+4">Re</option>
                  <option value="+3">Re&thinsp;♭</option>
                  <option value="+2">Do</option>
                  <option value="+1">Si</option>
                  <option value="0" selected>Si&thinsp;♭</option>
                  <option value="-1">La</option>
                  <option value="-2">La&thinsp;♭</option>
                  <option value="-3">Sol</option>
                  <option value="-4">Sol&thinsp;♭</option>
                  <option value="-5">Fa</option>
                </select>
              </div>
              <div id="etiqueta-transposicio" class="ui sub header" style="margin-top:.5rem; position:absolute; left:6.5rem">0</div>
            </div>
          </div>

        </div>
        <div id="nom-tema" class="theme name">
          <?php
          $notes = Aparicio::mostraNomTema(1);
          foreach ($notes as $nota) : ?>
            <span><?php echo $nota["cromatica_de"] ?></span>
          <?php endforeach ?>
        </div>
      </div>
      <div class="field">
        <label for="comentaris">Comentaris</label>
        <textarea name="comentaris" id="comentaris" rows="3"></textarea>
      </div>
      <button class="ui right floated positive right labeled icon aparicions submit button">
        Afegir
        <i class="plus icon"></i>
      </button>
    </form>
  </div>
  <div class="actions">
  </div>
</div>