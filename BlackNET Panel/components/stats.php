<div class="row">
  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-primary o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fas fa-fw fa-user"></i>
        </div>
        <div class="mr-5">
          <?php echo $client->countClients(); ?> Total Clients!
        </div>
      </div>
      <div class="card-footer text-white clearfix small z-1"></div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-warning o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fab fa-fw fa-usb"></i>
        </div>
        <div class="mr-5">
          <?php echo $client->countClientsByCond("is_usb", "yes"); ?> USB
          Clients!
        </div>
      </div>
      <div class="card-footer text-white clearfix small z-1"></div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-success o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fas fa-fw fa-signal"></i>
        </div>
        <div class="mr-5">
          <?php echo $client->countClientsByCond("status", "Online"); ?> Online
          Clients!
        </div>
      </div>
      <div class="card-footer text-white clearfix small z-1"></div>
    </div>
  </div>

  <div class="col-xl-3 col-sm-6 mb-3">
    <div class="card text-white bg-danger o-hidden h-100">
      <div class="card-body">
        <div class="card-body-icon">
          <i class="fas fa-fw fa-user-slash"></i>
        </div>
        <div class="mr-5">
          <?php echo $client->countClientsByCond("status", "Offline"); ?>
          Offline Clients!
        </div>
      </div>
      <div class="card-footer text-white clearfix small z-1"></div>
    </div>
  </div>
</div>