  <footer class="ui footer">
    <div class="ui divided horizontal list">
      <div class="item">
        &copy;&nbsp;2019
      </div>
      <div class="item">
        Bach’s Name Project
      </div>
    </div>
  </footer>
</div>

<!-- JS -->
<script src="<?php echo HTML_PATH ?>assets/js/jquery-3.4.1.min.js"></script>
<script src="<?php echo HTML_PATH ?>assets/js/semantic.min.js"></script>
<!-- <script src="assets/js/navbar-highlight.js"></script> -->

<script>
  $('.ui.dropdown')
    .dropdown();

  // API Settings
  $.fn.api.settings.api = {
    'marcar obra': '<?php echo HTML_PATH ?>assets/php/queries/marcar-obra.php?id_obra={id_obra}',
    'search': '<?php echo HTML_PATH ?>assets/php/queries/search.php?q={query}',
    'afegir relacio': '<?php echo HTML_PATH ?>assets/php/queries/insert-relacio-obra.php',
    'afegir aparicio': '<?php echo HTML_PATH ?>assets/php/queries/insert-aparicio.php',
    'afegir moviment': '<?php echo HTML_PATH ?>assets/php/queries/insert-moviment.php',
    'wiki': 'https://ca.wikipedia.org/w/api.php?action=opensearch&search={query}'
  };

  $('.ui.search')
    .search({
      apiSettings: {
        url: '<?php echo HTML_PATH ?>assets/php/queries/search.php?q={query}'
      },
      fields: {
        results: 'results',
        title: 'title',
        description: 'description',
        url: 'url'
      },
      type: 'category',
      searchFullText: false
    });

  $('.results').css('overflow-y', 'auto')
</script>