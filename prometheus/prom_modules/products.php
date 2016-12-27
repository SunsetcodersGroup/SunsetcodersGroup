
<style>
    .category-listing
    {
        clear: both;
        float: left;
        width: 10%;
        border: 1px red dotted;
        margin: 15px;
    }
    
    .product-listing
    {
                float: left;
        width: 60%;
        border: 1px red dotted;
                margin: 15px;
    }
    
    .account-information
    {
                       float: right;
        width: 20%;
        border: 1px red dotted;
                margin: 15px;
    }
</style>
<?php
require_once dirname(dirname(__FILE__)) . '/SunLibraryModule.php';

class products extends SunLibraryModule {

    const ModuleDescription = "Product Listing Module";
    const ModuleAuthor = "Sunsetcoders Development Team";
    const ModuleVersion = "0.1";

    function __construct($dbConnection) {
        parent::__construct($dbConnection);
    }

    public function products() {

        /*
         * This is prometheus Administrator output.
         */
    }

    public function editContent() {

        $contentCode = filter_input(INPUT_GET, "ContentID");

        $query = "SELECT $contentCode FROM teampanel WHERE teampanelID=1 ";

        echo '<form method="POST" action="?id=team&&moduleID=UpdateContent">';
        echo '<input type="hidden" name="contentCode" value="' . $contentCode . '">';

        if ($stmt = $this->dbConnection->prepare($query)) {

            $stmt->execute();
            $stmt->bind_result($contentCode);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h1>Content: </h1></td></tr>';
            echo '<tr><td><textarea cols=100 rows=10 name="contentMatter">' . $contentCode . '</textarea></td></tr>';
            echo '<tr><td><input type="submit" name="submit" value="Update"></td></tr>';
        }
        echo '</form>';
    }

    public function editImage() {

        $contentCode = filter_input(INPUT_GET, "ContentID");

        $query = "SELECT $contentCode FROM teampanel WHERE teampanelID=1 ";

        echo '<form action="?id=team&&moduleID=UpdateImage" method="post" enctype="multipart/form-data">';
        echo '<input type="hidden" name="contentCode" value="' . $contentCode . '">';

        if ($stmt = $this->dbConnection->prepare($query)) {

            $stmt->execute();
            $stmt->bind_result($contentCode);
            $stmt->fetch();

            echo '<table border=0 cellpadding=20>';
            echo '<tr><td><h1>Image Information: </h1></td></tr>';
            echo '<tr><td><img src="../Images/' . $contentCode . '"></td></tr>';
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

        $contentImageName = $target_filename;
        $contentCode = filter_input(INPUT_POST, 'contentCode');

        $stmt = $this->dbConnection->prepare("UPDATE teampanel SET $contentCode=? WHERE teampanelID=1");
        $stmt->bind_param('s', $contentImageName);

        if ($stmt === false) {
            trigger_error($this->dbConnection->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Image Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Team">';
    }

    public function updateContent() {

        $contentDescription = filter_input(INPUT_POST, 'contentMatter');
        $contentCode = filter_input(INPUT_POST, 'contentCode');

        $stmt = $this->dbConnection->prepare("UPDATE teampanel SET $contentCode=? WHERE teampanelID=1");
        $stmt->bind_param('s', $contentDescription);

        if ($stmt === false) {
            trigger_error($this->dbConnection->error, E_USER_ERROR);
        }

        $status = $stmt->execute();

        if ($status === false) {
            trigger_error($stmt->error, E_USER_ERROR);
        }
        echo '<font color=black><b>Content Information Updated <br><br> Please Wait!!!!<br>';
        echo '<meta http-equiv="refresh" content="1;url=?id=Team">';
    }

    public function callToFunction() {
        ?>

        <div class="body-content">
            <div class="category-listing">
        <?php
        $categorylisting = $this->objDB->prepare("SELECT productcategory FROM products ");
        $categorylisting->execute();

        $categorylisting->bind_result($productcategory);

        while ($checkRow = $categorylisting->fetch()) {

            echo $productcategory . '<br>';
        }
        $categorylisting->close();
        ?>
            </div><div class="product-listing">
                <?php
                $productListing = $this->objDB->prepare("SELECT productlisting, productdescription, productlistingprice FROM products");
                $productListing->execute();

                $productListing->bind_result($productlisting, $productdescription, $productlistingprice);

                while ($checkRow = $productListing->fetch()) {

                    echo '<div>' . $productlisting . '<br>'.nl2br($productdescription).'<br>'.$productlistingprice.'</div>';
                }
                $productListing->close();
                ?>
            </div><div class="account-information">Account Information Here</div>
        </div>
        <?php
    }

    protected function assertTablesExist() {

        $val = mysqli_query($this->objDB, 'select 1 from `products` LIMIT 1');

        if ($val !== FALSE) {
            
        } else {
            $createTable = $this->objDB->prepare("CREATE TABLE products (productID INT(11) AUTO_INCREMENT PRIMARY KEY, productcategory VARCHAR(100) NOT NULL, producttype VARCHAR(100) NOT NULL, productlisting VARCHAR(100) NOT NULL, productdescription VARCHAR(200) NOT NULL, productlistingprice VARCHAR(100) NOT NULL, productprice VARCHAR(100) NOT NULL)");
            $createTable->execute();
            $createTable->close();
        }
    }

}
