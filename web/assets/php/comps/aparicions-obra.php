<?php if ($aparicions = Obra::llistaAparicions($id_obra)) : ?>
  <table class="ui selectable striped collapsing sortable definition table aparicions">
    <thead>
      <tr>
        <th scope="col"></th>
        <th scope="col" class="sorted ascending">Inici</th>
        <th scope="col">Final</th>
        <th scope="col">Veu</th>
        <th scope="col">Transposició</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($aparicions as $aparicio) : ?>
        <tr>
          <td scope="row"><?php echo $aparicio["tipus"] ?></td>
          <td>c.&nbsp;<?php echo $aparicio["compas_inici"] ?>, <?php echo ordinals($aparicio["temps_inici"]) ?> temps</td>
          <td>c.&nbsp;<?php echo $aparicio["compas_final"] ?>, <?php echo ordinals($aparicio["temps_final"]) ?> temps</td>
          <td><?php echo $aparicio["veu"] ?></td>
          <td><?php echo ($aparicio["transposicio"] == 0 ? "Original" : $aparicio["transposicio"]) ?></td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>