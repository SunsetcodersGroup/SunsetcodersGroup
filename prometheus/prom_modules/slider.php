<style>
    #slideshow { 
        margin: 50px auto; 
        position: relative; 
        height: 250px;
        width: 1024px;
        box-shadow: 0 0 20px rgba(0,0,0,0.4); 
    }

    #slideshow > div { 
        position: absolute; 
    }

    .full-green-width
    {
        text-align: center;
        padding: 10px;
        color: white;
        border: 1px #000 solid;
        background-color: #548235;
        margin: 0 auto;
        width: 1024px;
        border-radius: 5px;
    }
</style>

<?php
require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class slider extends SunLibraryModule {

    const ModuleDescription = "Current Basic Slider Module, big plans for updating this.";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }

    public function slider() {

        echo '<table border=0 cellpadding=10 width=50%>';
        echo '<tr><td colspan=2><h2>Slider Panel</h2></td></tr>';
        echo '<tr><td><button><a href="?id=Slider&&moduleID=AddImage">Add New</a></button></td></tr>';
        echo '<tr><td class="headerMenu" colspan=2>Image Name</td></tr>';

        $stmt = $this->objDB->prepare("SELECT sliderID, imageToSlide FROM slider ORDER BY slideOrder");
        $stmt->execute();

        $stmt->bind_result($sliderID, $imageToSlide);

        while ($checkRow = $stmt->fetch()) {

            echo '<tr><td>' . $imageToSlide . '</td><td width=60><a href="?id=Slider&&moduleID=EditImage&&ImageID=' . $sliderID . '">edit</a></td></tr>';
        }

        echo '</table>';
    }

    public function editImage() {

        $imageID = filter_input(INPUT_GET, "ImageID");

        echo '<form action="?id=About&&moduleID=UpdateImage" method="post" enctype="multipart/form-data">';
        echo '<input type="hidden" name="imageID" value="' . $imageID . '">';

        if ($stmt = $this->objDB->prepare("SELECT imageToSlide FROM slider WHERE sliderID=? ")) {

            $stmt->bind_param('i', $imageID);
            $stmt->execute();
            $stmt->bind_result($imageToSlide);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h1>Image Information: </h1></td></tr>';
            echo '<tr><td><img src="../Images/' . $imageToSlide . '"></td></tr>';
            echo '<tr><td><input type="hidden" name="MAX_FILE_SIZE" value="100000" /></td></tr>';
            echo '<tr><td>Choose a replacement image to upload: <br> <input type="file" name="fileToUpload" id="fileToUpload"></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function updateImage() {

        $target_dir = "../Images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $target_filename = basename($_FILES["fileToUpload"]["name"]);

        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";

            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        /*
         * Update Teampanel Database with the new Image information.
         */

        $sliderID = filter_input(INPUT_POST, 'sliderID');
        $contentImageName = $target_filename;
        $contentCode = filter_input(INPUT_POST, 'contentCode');

        $stmt = $this->objDB->prepare("UPDATE slider SET imageToSlide=? WHERE sliderID=?");
        $stmt->bind_param('si', $contentImageName, $sliderID);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Image Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Slider">';
    }

    public function uploadFile() {

        $target_dir = "../Images/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $target_filename = basename($_FILES["fileToUpload"]["name"]);

        $uploadOk = 1;
        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";

            // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        /*
         * Update Teampanel Database with the new Image information.
         */

        $contentImageName = $target_filename;
        $contentCode = filter_input(INPUT_POST, 'contentCode');

        $stmt = $this->objDB->prepare("UPDATE teampanel SET $contentCode=? WHERE teampanelID=1");
        $stmt->bind_param('s', $contentImageName);

        if ($stmt === false) {
            trigger_error($this->objDB->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Image Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Team">';
    }

    public function callToFunction() {
        ?>

        <div class="body-content">
            <div id="slideshow">
        <?php
        $stmt = $this->objDB->prepare("SELECT imageToSlide FROM slider");
        $stmt->execute();

        $stmt->bind_result($imageToSlide);

        while ($checkRow = $stmt->fetch()) {

            echo '<div><img class="animate-fading" src="' . IMAGE_PATH . '/' . $imageToSlide . '" ></div>';
        }
        ?>

            </div>
            <div class="full-green-width">Relax, Indulge, Beautify & Spoil Yourself</div>
        </div>


        <script>
            $("#slideshow > div:gt(0)").hide();

            setInterval(function () {
                $('#slideshow > div:first')
                        .fadeOut(1000)
                        .next()
                        .fadeIn(1000)
                        .end()
                        .appendTo('#slideshow');
            }, 3000);
        </script>
        <?php
    }

    protected function assertTablesExist() {

        $val = mysqli_query($this->objDB, 'select 1 from `slider` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {
            $createTable = $this->objDB->prepare("CREATE TABLE slider (sliderID INT(11) AUTO_INCREMENT PRIMARY KEY, imageToSlide VARCHAR(100) NOT NULL, contentCode VARCHAR(100) NOT NULL)");
            $createTable->execute();
            $createTable->close();
        }
    }

}
