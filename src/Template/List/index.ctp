<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">List of Game of Thrones Characters</div>
                  <!-- Table -->
                  <table class="table">
                      <tr>
                          <th>Character</th>
                          <th>Real Name</th>
                      </tr>
                      <?php foreach($characters as $key => $value): ?>
                        <tr>
                          <td><?= $key ?></td><td><?= $value ?></td>
                        </tr>
                      <?php endforeach; ?>
                  </table>
            </div>
        </div>
    </div>
</div>