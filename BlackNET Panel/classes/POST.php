<?php
/*
	POST Class
	Class to handles POST Request

	How to use it
	$req = new POST;
	$req->prepare("Folder name","File name",$req->sanitize($data));
	$req->write();
*/
class POST
{
	// Class properties
	private $folder_name;
	private $file_name;
	private $data;

	// A Method to prepare class properties
	public function prepare($folder_name, $file_name, $data)
	{
		$this->folder_name = $folder_name;
		$this->file_name = $file_name;
		$this->data = $data;
	}

	// A Method to sanitize and filter data
	public function sanitize($data)
	{
		$data = trim($data);
		$data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
		$data = filter_var($data, FILTER_SANITIZE_STRING);
		return $data;
	}

	// A Method to write pepared data to a file
	public function write()
	{
		try {
			$data = isset($this->data) ? $this->data : "This is incorrect";
			if ($this->folder_name == "www") {
				$myfile = fopen($this->file_name, "w");
			} else {
				if (!file_exists($this->folder_name) && !is_dir($this->folder_name)) {
					mkdir($this->folder_name);
				}
				$myfile = fopen($this->folder_name . "/" . $this->file_name, "w");
			}
			fwrite($myfile, $data);
			fclose($myfile);

			return true;
		} catch (\Throwable $th) {
			return false;
		}
	}
}
