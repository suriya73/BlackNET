<?php
$blacklist = array('..', '.', "index.php", ".htaccess", "PasswordStealer.dll", "HistoryStealer.dll");

$files = null;

if (file_exists("plugins/")) {
    try {
        $files = scandir("plugins/");
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

?>
<div class="text-center">
  <p class="font-weight-bold">Execute Custom Plugin</p>
</div>

<div class="form-group col-auto">
  <label for="PluginName">Plugin Name</label>
  <select class="form-control" name="PluginName" id="PluginName">
    <?php foreach ($files as $file): ?>
        <?php if (!(in_array($file, $blacklist))): ?>
            <option value="<?php echo $file; ?>"><?php echo $file ?></option>
        <?php endif;?>
    <?php endforeach;?>
  </select>
</div>

<div class="form-group col-auto">
  <label for="ClassName">Class Name</label>
  <input class="form-control" type="input" name="ClassName" />
</div>

<div class="form-group col-auto">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" value="" id="hasoutput">
        <label class="form-check-label" for="hasoutput">
           Plugin has Output
        </label>
    </div>
</div>

<div class="text-center col-auto">
  <button class="btn btn-dark btn-block" name="Form2">Send Command</button>
</div>
