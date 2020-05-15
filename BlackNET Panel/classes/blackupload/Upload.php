<?php
class Upload
{
    # Class attributes
    private $upload_input; # HTML File Input => Example: $_FILES['file']
    private $name_array = []; # Array For File name Protection
    private $ext_array = []; # Array For Extension Protection
    private $mime_array = []; # Array For Mime Types Protection
    private $controller; # Class Controller Path
    private $upload_controller; # File Upload Controller
    private $upload_folder; # Example: /upload
    private $size; # Size limit for protection
    private $use_hash; # Use hashed name insted of the uploaded file name
    private $logs = []; # System Logs Array
    private $overwrite_file; # Enable or Disable file overwriting
    private $files = []; # Array for the all uploaded files informations
    private $max_height; # Image max height
    private $max_width; # Image max width
    private $min_height;
    private $min_width;
    private $message = [
        0 => "File has been uploaded.",
        1 => "Invalid file format.",
        2 => "Failed to get mime type.",
        3 => "File is forbidden.",
        4 => "Exceeded filesize limit.",
        5 => "Please select a file",
        6 => "File already exist.",
        7 => "Failed to move uploaded file.",
        8 => "The uploaded file's height is too large. or The uploaded file's width is too large.",
        9 => "The uploaded file's height is too small. or The uploaded file's width is too small.",
        10 => "The uploaded file is not a valid image.",
        11 => "Opreation does not exist.",
    ]; # Array list of error codes and the messages

    # Class Constructor to initialize attributes
    /*
     *
     * name: __construct
     * @param _FILE $upload_input
     * @param string $upload_folder
     * @param string $controller
     * @param string $upload_controller
     * @param integer $size
     * @param boolean $use_hash
     * @param boolean $overwrite_file
     * @param integer $max_height
     * @param integer $max_width
     * @return null
     *
     */
    public function __construct($upload_input = null, $upload_folder = "upload", $controller = null, $upload_controller = "upload.php", $size = "10 MB", $use_hash = false, $overwrite_file = true, $max_height = null, $max_width = null, $min_height = null, $min_width = null)
    {
        # initialize attributes
        $this->upload_input = $upload_input; # Set file input
        $this->upload_folder = $this->sanitize($upload_folder); # Set upload folder
        $this->controller = $this->sanitize($controller); # Set the class controller folder
        $this->upload_controller = $this->sanitize($upload_controller); # Set the upload controller | Example => upload.php
        $this->size = $this->sizeInBytes($this->sanitize($size)); # Set limit Size
        $this->use_hash = $use_hash; # use hashed name insted of the raw name
        $this->overwrite_file = $overwrite_file; # Set file overwriting to true to enable file overwriting
        $this->max_height = $max_height; # Set Max Height for an image
        $this->max_width = $max_width; # Set Max Width for an image
        $this->min_height = $min_height;
        $this->min_width = $min_width;
    }

    # Function to set upload input when needed
    /*
     *
     * name: setUpload
     * @param _FILE $upload_input
     * @return null
     *
     */
    public function setUpload($upload_input)
    {
        $this->upload_input = $upload_input; # Set the upload input to a new one
    }

    # Function to set the controller when needed
    /*
     *
     * name: setController
     * @param string $controller
     * @return null
     *
     */
    public function setController($controller)
    {
        $this->controller = $this->sanitize($controller); # Set the class controller path
    }

    # Set the upload controller file => Example: upload.php <- the file that contain the upload code
    /*
     *
     * name: setUploadController
     * @param string $upload_controller
     * @return null
     *
     */
    public function setUploadController($upload_controller)
    {
        $this->upload_controller = $this->sanitize($upload_controller); # Sanitize and set the upload controller name
    }

    # Set $use_hash to true or false when needed
    /*
     *
     * name: useHashAsName
     * @param boolean $use_hash
     * @return null
     *
     */
    public function useHashAsName($use_hash = false)
    {
        $this->use_hash = $use_hash;
    }

    # Enable Class Protection [Mime Types, File Extensions, Forbidden Names]
    /*
     *
     * name: enableProtection
     * @param null
     * @return null
     *
     */
    public function enableProtection()
    {

        # Decode JSON and Set Protection Data

        # Enable Level 1 Protection
        $this->name_array = json_decode(
            file_get_contents(
                $this->sanitize(
                    $this->controller . "forbidden.json" # Decode JSON and set the forbidden names array
                )
            )
        );

        # Enable Level 2 Protection
        $this->ext_array = json_decode(
            file_get_contents(
                $this->sanitize(
                    $this->controller . "extension.json" # Decode JSON and set the extension array

                )
            )
        );

        # Enable Level 3 Protection
        $this->mime_array = json_decode(
            file_get_contents(
                $this->sanitize(
                    $this->controller . "mime.json" # Decode JSON and set the mime array
                )
            )
        );
    }

    # Set Forbidden array to a custom list when needed
    /*
     *
     * name: setForbiddenFilter
     * @param array $forbidden_array
     * @return null
     *
     */
    public function setForbiddenFilter($forbidden_array)
    {
        $this->name_array = $forbidden_array; # Custom name filter array
    }

    # Set Extension array to a custom list when needed
    /*
     *
     * name: setExtensionFilter
     * @param array $ext_array
     * @return null
     *
     */
    public function setExtensionFilter($ext_array)
    {
        $this->name_array = $ext_array; # Custom extension filter array
    }

    # Set Mime array to a custom list when needed
    /*
     *
     * name: setMimeFilter
     * @param array $mime_array
     * @return null
     *
     */
    public function setMimeFilter($mime_array)
    {
        $this->name_array = $mime_array; # Custom mime filter array
    }

    # Set file size limit when needed
    /*
     *
     * name: setSizeLimit
     * @param integer $size
     * @return null
     *
     */
    public function setSizeLimit($size)
    {
        # Set file size limit to a new limit
        $this->size = $this->fixIntegerOverflow(
            $this->sizeInBytes(
                $this->sanitize(
                    $size
                )
            )
        );
    }

    # Set upload folder when needed
    /*
     *
     * name: setUploadFolder
     * @param string $folder_name
     * @return null
     *
     */
    public function setUploadFolder($folder_name)
    {
        $this->upload_folder = $this->sanitize($folder_name); # Sanitize and set the upload folder when needed
    }

    # Firewall 1: Check File Extension
    /*
     *
     * name: checkExtension
     * @param null
     * @return true
     *
     */
    public function checkExtension()
    {
        # Check if the file extension is whitelisted
        if (in_array($this->getExtension(), $this->ext_array)) {
            return true; # Return true if the extension is not blacklisted
        } else {
            $this->add_log(null, 1); # Show an error message
        }
    }

    # Function to return the file input extension
    /*
     *
     * name: getExtension
     * @param null
     * @return string
     *
     */
    public function getExtension()
    {
        return strtolower(
            pathinfo(
                $this->getName(),
                PATHINFO_EXTENSION# Get the file extension
            )
        );
    }

    # Firewall 2: Check File Mime Type
    /*
     *
     * name: checkMime
     * @param null
     * @return boolean
     *
     */
    public function checkMime()
    {
        # Get the file mime type using the browser
        $mime = mime_content_type($this->getTempName());

        # Check if the file mime type is whitelisted
        if (in_array($mime, $this->mime_array)) {
            # Check if the browser mime type equals the server mime type
            if ($mime === $this->getMime()) {
                return true; # Return true if the mime type is not blacklisted
            } else {
                $this->add_log(null, 1); # Show an error message
            }
        }
    }

    # Function to get the mime type using the server
    /*
     *
     * name: getMime
     * @param null
     * @return string
     *
     */
    private function getMime()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE); # Open the file to get mime type
        $mtype = finfo_file($finfo, $this->getTempName()); # get mime type and add it to a variable
        if (finfo_close($finfo)) {
            return $mtype; # close the file the and return the mime type
        } else {
            $this->add_log(null, 2); # Show a message error
        }
    }

    # Function that return the uploaded file type
    /*
     *
     * name: getFileType
     * @param null
     * @return string
     *
     */
    public function getFileType()
    {
        return $this->upload_input['type']; # Return the mime type using php default settings
    }

    # Firewall 3: Check File Name is Forbidden
    /*
     *
     * name: checkForbidden
     * @param null
     * @return boolean
     *
     */
    public function checkForbidden()
    {
        # check if a file name is forbidden
        if (!(in_array($this->getName(), $this->name_array))) {
            return true; # Return true if the name is not forbidden
        } else {
            $this->add_log(null, 3); # Show an error message
        }
    }

    # Firewall 4: Check file size limit
    /*
     *
     * name: checkSize
     * @param null
     * @return boolean
     *
     */
    public function checkSize()
    {
        # Check if a file size is less or equal the size limit
        if ($this->getSize() <= $this->size) {
            return true; # Return true if the uploaded passed the size limit test
        } else {
            $this->add_log(null, 4); # Show an error message
        }
    }

    # Return the size of the uploaded file as bytes
    /*
     *
     * name: getSize
     * @param null
     * @return integer
     *
     */
    public function getSize()
    {
        return $this->fixIntegerOverflow($this->upload_input['size']);
    }

    # Function to check if a the HTML input is empty
    /*
     *
     * name: checkIfEmpty
     * @param null
     * @return boolean
     *
     */
    public function checkIfEmpty()
    {
        # check if a the HTML input is empty
        if ($this->upload_input['error'] === UPLOAD_ERR_NO_FILE) {
            $this->add_log(null, 5); # Return false if the input is empty
        } else {
            return true; # Return true if the input has a file
        }
    }

    # Return the name of the uploaded file
    /*
     *
     * name: getName
     * @param null
     * @return string
     *
     */
    public function getName()
    {
        return $this->upload_input['name'];
    }

    # Return the PHP Generated name for the uploaded file
    /*
     *
     * name: getTempName
     * @param null
     * @return string
     *
     */
    public function getTempName()
    {
        return $this->upload_input['tmp_name']; # Return the PHP Generated Temp name
    }

    # Return an "SHA1 Hashed File Name" of the uploaded file
    /*
     *
     * name: hashName
     * @param null
     * @return hash_code
     *
     */
    public function hashName()
    {
        return sha1(
            $this->sanitize( # Sanitize the input
            basename(
                $this->getName()
            )
            )
        ); # Get the file real name using getName() function and hash it using SHA1
    }

    # Get the date of the uploaded file
    /*
     *
     * name: getDate
     * @param null
     * @return timestamp
     *
     */
    public function getDate()
    {
        return filemtime($this->getTempName()); # Get the temp_name using the function getTempName() and return the date
    }

    # Function to upload the file to the server
    /*
     *
     * name: upload
     * @param null
     * @return boolean
     *
     */
    public function upload()
    {
        # check and set the file name depending on $use_hash
        $filename = ($this->use_hash ?
            $this->hashName() . "." . $this->getExtension() :
            $this->getName());
        # Check if OverWrite setting is enabled
        if (!($this->overwrite_file === true)) {
            # Check if the is uploaded to the server
            if ($this->isFile($this->upload_folder . "/" . $filename) === false) {
                # Function to move the file to the upload folder
                if ($this->move_file($filename)) {
                    $this->add_log(null, 0);
                    $this->add_file($this->getJSON());
                    return true;
                }
            } else {
                # Show an error message
                $this->add_log(null, 6);
            }
        } else {
            # Function to move the file to the upload folder
            if ($this->move_file($filename)) {
                $this->add_log(null, 0);
                $this->add_file($this->getJSON());
                return true;
            }
        }

    }

    # Function to move a file to the upload folder
    /*
     *
     * name: move_file
     * @param string $filename
     * @return boolean
     *
     */
    public function move_file($filename)
    {
        if (is_uploaded_file($this->getTempName())) {
            # Move the file to the upload folder
            if (!(move_uploaded_file($this->getTempName(), $this->upload_folder . "/" . $filename))) {
                $this->add_log(null, 7); # Show an error message
                return false;
            } else {
                return true; # Return true if the file is uploaded
            }
        }

    }

    # Fix file input array to make it easy to iterate through it
    /*
     *
     * name: fix_array
     * @param array $file_post
     * @return array
     *
     */
    public function fix_array($file_post)
    {
        $file_array = array(); # An empty array to add the fixed to it
        $file_count = count($file_post['name']); # Count the number of element in the file input
        $file_keys = array_keys($file_post); # get the array keys to loop through it

        # loop through the input and fix it
        for ($i = 0; $i < $file_count; $i++) {
            foreach ($file_keys as $key) {
                # Change the array key placement
                $file_array[$i][$key] = $file_post[$key][$i];
            }
        }
        # Return the fixed array
        return $file_array;
    }

    # Function to create and upload folder and secure it
    /*
     *
     * name: create_upload_folder
     * @param string $folder_name
     * @return null
     *
     */
    public function create_upload_folder($folder_name)
    {
        # Check if a folder exist or not ?
        if (!file_exists($folder_name) && !is_dir($folder_name)) {
            # Create a new dir and set the proper permissions
            @mkdir($this->sanitize($folder_name));
            @chmod($this->sanitize($folder_name), 7770);

            # Protect the folder by adding .htaccess and index.php
            $this->protect_foler($folder_name);
        }
    }

    # Function to potect a folder
    /*
     *
     * name: protect_foler
     * @param string $folder_name
     * @return null
     *
     */
    public function protect_foler($folder_name)
    {
        # Check if .htaccess does not exist then create a new one with proteciton settings
        if (!file_exists($folder_name . "/" . ".htaccess")) {
            $content = "Options -Indexes" . "\n"; #
            $content .= "<Files .htaccess>" . "\n"; #
            $content .= "Order allow,deny" . "\n"; # HTACCESS FILE CONTENT
            $content .= "Deny from all" . "\n"; #
            $content .= "</Files>"; #
            @file_put_contents($this->sanitize($folder_name) . "/" . ".htaccess", $content); # Write the content to the .htaccess file
        }

        # Forbid Access to the upload folder
        if (!file_exists($this->sanitize($folder_name) . "/" . "index.php")) {
            $content = "<?php http_response_code(403); ?>"; # "Enable Forbidden"
            @file_put_contents($this->sanitize($folder_name) . "/" . "index.php", $content); # Write the "Enable Forbidden" Code to a new file
        }
    }

    # Function that helps with input filter and sanitize
    /*
     *
     * name: sanitize
     * @param string $value
     * @return string
     *
     */
    public function sanitize($value)
    {
        # Out-Of-TheBox Filtering and Sanitizing
        $data = trim($value); # Remove White Spaces
        $data = htmlspecialchars($data, ENT_QUOTES, "UTF-8"); # Convert characters to HTML entities
        $data = strip_tags($data); # Strip HTML and PHP Tags
        $data = filter_var($data, FILTER_SANITIZE_STRING); # filters a variable with a string filter
        return $data;
    }

    # Function that format file bytes to a readable format => Example: 7201450 to ( 7.2 MB )
    /*
     *
     * name: formatBytes
     * @param integer $bytes
     * @param integer $precision
     * @return string
     *
     */
    public function formatBytes($bytes, $precision = 2)
    {
        # Out-of-TheBox Byte Convertor
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); # Array to set the current names of storage types
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    # Return any type of readable storage size to bytes Example | 7.2 MB => 7201450
    /*
     *
     * name: sizeInBytes
     * @param integer $size
     * @return integer
     *
     */
    public function sizeInBytes($size)
    {
        $unit = "B";
        $units = array("B" => 0, "K" => 1, "M" => 2, "G" => 3, "T" => 4);
        $matches = array();
        preg_match("/(?<size>[\d\.]+)\s*(?<unit>b|k|m|g|t)?/i", $size, $matches);
        if (array_key_exists("unit", $matches)) {
            $unit = strtoupper($matches["unit"]);
        }
        return (floatval($matches["size"]) * pow(1024, $units[$unit]));
    }

    # Return the files from the upload folder to view them
    /*
     *
     * name: getUploadDirFiles
     * @param null
     * @return array
     *
     */
    public function getUploadDirFiles()
    {
        return scandir($this->upload_folder);
    }

    # Check if a file exist and it is a real file
    /*
     *
     * name: isFile
     * @param string $file_name
     * @return boolean
     *
     */
    public function isFile($file_name)
    {
        $file_name = $this->sanitize($file_name);
        # Check if $file_name is a file and is exists on the server
        if (file_exists($file_name) && is_file($file_name)) {
            return true; # Return true if yes
        } else {
            return false; # Return true if yes
        }
    }

    # Check if a directory exist and it is a real directory
    /*
     *
     * name: isDir
     * @param string $dir_name
     * @return boolean
     *
     */
    public function isDir($dir_name)
    {
        $dir_name = $this->sanitize($dir_name);

        # Check if $dir_name is a directory and is exists on the server
        if (is_dir($dir_name) && file_exists($dir_name)) {
            return true; # Return true if yes
        } else {
            return false; # Return false if no
        }
    }

    # Create a callback function when needed after or before an operation
    /*
     *
     * name: callback
     * @param Function $function
     * @param mixed $args
     * @return function_output
     *
     */
    public function callback($function, $args = null)
    {
        # check if the function is callable
        if (is_callable($function)) {
            # check if $args is an array
            if (is_array($args)) {
                return call_user_func_array($function, $args); # Create a user function with multiple args
            } else {
                return call_user_func($function, $args); # Create a user function with a single args
            }
        }
    }

    # Add a message the system log
    /*
     *
     * name: add_log
     * @param mixed $id
     * @param string $message
     * @return null
     *
     */
    public function add_log($id = null, $message)
    {
        # Check if $id is null
        if ($id === null) {
            array_push($this->logs, $message); # if yes then just push the message
        } else {
            $this->logs[$id] = $message; # if no then add the message to the custom $id
        }
    }

    # Get all logs from system log to view them
    /*
     *
     * name: getLogs
     * @param null
     * @return array
     *
     */
    public function getLogs()
    {
        return $this->logs; # Return the system logs array
    }

    # Get a system log message by an array index id
    /*
     *
     * name: getLog
     * @param mixed $log_id
     * @return string
     *
     */
    public function getLog($log_id)
    {
        return $this->logs[$log_id];
    }

    # Set file overwriting to true or false
    /*
     *
     * name: setFileOverwriting
     * @param boolean $status
     * @return null
     *
     */
    public function setFileOverwriting($status)
    {
        $this->overwrite_file = $status; # Set file overwriting to true or false

    }

    # Set php.ini settings using an array => Example: setINI(["file_uploads"=>1])
    /*
     *
     * name: setINI
     * @param array $ini_settings
     * @return null
     *
     */
    public function setINI($ini_settings)
    {
        # Loop through $ini_settings
        foreach ($ini_settings as $key => $value) {
            ini_set($key, $value); # Set ini_set using $key and $value
        }
    }

    # Ensure correct value for big integers
    /*
     *
     * name: fixIntegerOverflow
     * @param integer $int
     * @return float
     *
     */
    public function fixIntegerOverflow($int)
    {
        if ($int < 0) {
            $int += 2.0 * (PHP_INT_MAX + 1);
        }

        return $int;
    }

    # Get all the uploaded file information in JSON
    /*
     *
     * name: getJSON
     * @param null
     * @return string
     *
     */
    public function getJSON()
    {
        # Return an the informations Array as JSON Encoded string
        return json_encode(
            [
                "message" => $this->getLog(0), # get the first log message
                "filename" => $this->getName(), # get the file name
                "filehash" => $this->hashName(), # get the file hash
                "filesize" => $this->formatBytes($this->getSize()), # get the file size in a human readable format
                "uploaddate" => date("Y/m/d h:i:s A", $this->getDate()), # get the file upload date
                "qrcode" => $this->generateQrCode(), # get the qr code url for the file download link
                "downloadurl" => $this->generateDownloadLink(), # get the uploaded file download link
            ]
        );
    }

    # Function to add a file to the files array
    /*
     *
     * name: add_file
     * @param string $json_string
     * @return null
     *
     */
    public function add_file($json_string)
    {
        array_push($this->files, json_decode($json_string));
    }

    # Function to return all the uploaded files information array
    /*
     *
     * name: get_files
     * @param null
     * @return array
     *
     */
    public function get_files()
    {
        return $this->files;
    }

    # Function to get a log message using a message index id
    /*
     *
     * name: getMessage
     * @param integer $index
     * @return string
     *
     */
    public function getMessage($index)
    {
        return $this->message[$index]; # Return a message from $message array using $index
    }

    # Include Bootstrap CSS
    /*
     *
     * name: includeBootstrap
     * @param null
     * @return string
     *
     */
    public function includeBootstrap()
    {
        # return Bootstrap files using CDN
        return '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" />';
    }

    # Include jQuery Javascript files
    /*
     *
     * name: includeJquery
     * @param null
     * @return string
     *
     */
    public function includeJquery()
    {
        # return jQuery files using CDN
        return '<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" ></script>
				<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" ></script>
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" ></script>';
    }

    # Function to create an upload worker using one line of code and with all firewalls enabled
    # with a size limit of 10MB per uploaded file
    /*
     *
     * name: factory
     * @param array $upload_input
     * @return boolean
     *
     */
    public function factory($upload_input = null, $size_limit = null)
    {
        $this->setUploadFolder("upload"); # set upload folder to "upload"
        $this->setFileOverwriting(true); # Set file overwriting to true
        $this->useHashAsName(false); # Set using hash name to false to use the file real name
        $this->enableProtection(); # Enable class 3 firewall levels
        if ($upload_input === null) {
            $this->setUpload($_FILES['file']); # check if $upload_input is null then set the upload input to $_FILES['file']
        } else {
            $this->setUpload($upload_input); # else set the upload input to your defined input
        }
        # Check the request method is POST
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            # Check all class 5 protection levels
            if ($this->checkIfEmpty()) {
                if ($this->checkSize()) {
                    if (
                        $this->checkForbidden() &&
                        $this->checkExtension() &&
                        $this->checkMime()
                    ) {
                        # Upload the file (:
                        return $this->upload();
                    }
                }
            }
        }
    }

    # Extra Firewall 1: Check an image dimenstions aginst the class dimenstions
    /*
     *
     * name: checkDimenstion
     * @param integer $opreation
     * @return boolean
     *
     */
    public function checkDimenstion($opreation = 2)
    {
        $image_data = getimagesize($this->getTempName());
        $width = $image_date[0];
        $height = $image_data[1];
        switch ($opreation) {
            case 0:
                if ($heights <= $this->max_height) {
                    return true;
                } else {
                    $this->add_log(null, 8);
                }
                break;

            case 1:
                if ($width <= $this->max_width) {
                    return true;
                } else {
                    $this->add_log(null, 8);
                }
                break;

            case 2:
                if ($width <= $this->max_width && $heights <= $this->max_height) {
                    return true;
                } else {
                    $this->add_log(null, 8);
                }
                break;

            case 3:
                if ($heights >= $this->min_height) {
                    return true;
                } else {
                    $this->add_log(null, 9);
                }
                break;

            case 4:
                if ($width >= $this->min_width) {
                    return true;
                } else {
                    $this->add_log(null, 9);
                }
                break;

            case 5:
                if ($width >= $this->min_width && $heights >= $this->min_height) {
                    return true;
                } else {
                    $this->add_log(null, 9);
                }
                break;

            default:
                $this->add_log(null, 11);
                break;
        }
    }

    # Function to set the maximum class image dimenstions to validate them
    /*
     *
     * name: setMaxDimenstion
     * @param integer $height
     * @param integer $width
     * @return null
     *
     */
    public function setMaxDimenstion($height = null, $width = null)
    {
        $this->max_height = $height;
        $this->max_width = $width;
    }

    # Function to set the minimum class image dimenstions to validate them
    /*
     *
     * name: setMinDimenstion
     * @param integer $height
     * @param integer $width
     * @return null
     *
     */
    public function setMinDimenstion($height = null, $width = null)
    {
        $this->min_height = $height;
        $this->min_width = $width;
    }
    # Extra Firewall 2: Function to check if uploaded file is an image
    /*
     *
     * name: isImage
     * @param null
     * @return boolean
     *
     */
    public function isImage()
    {
        if (in_array($this->getMime(), ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'])) {
            return true;
        } else {
            $this->add_log(null, 10);
        }
    }
}
