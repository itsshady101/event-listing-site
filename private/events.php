<?php

require_once('init.php');

class Event {

    public $id;
    public $name;
    public $date;
    public $organiser;
    public $description;
    public $city;
    public $location;
    public $image;

    public function __construct($name='', $date='', $organiser='', $description='', $city='', $location='') {
        $this->name = htmlentities($name);
        $this->date = htmlentities($date);
        $this->organiser = htmlentities($organiser);
        $this->description = $description;
        $this->city = htmlentities($city);
        $this->location = htmlentities($location);
    }

    public function upload_image() {
        // Check if the file is img and size.
        if($_FILES['image']['type'] == "image/jpeg" || $_FILES['image']['type'] == "image/png" && ($_FILES['image']['size'] * 0.000000954) < 20) {
            $tmp_file = $_FILES['image']['tmp_name'];
            
            if ($_FILES['image']['type'] == "image/jpeg") {
                $extension = ".jpg";
            } elseif ($_FILES['image']['type'] == "image/png") {
                $extension = ".png";
            }
            
            $target_file = uniqid() . $extension;
            $upload_dir = "../img/events/";

            if(move_uploaded_file($tmp_file, $upload_dir . $target_file)) {
                $this->image = "img/events/" . $target_file;
                return true;
            } else {
                return false;
            }  
        }
        
        $_SESSION['error'] = "Please choose an image only.";
        return false;
    }
    

    public function create() {
        global $db;
        $sql = "INSERT INTO events(name, date, organiser, description, city, location, image) VALUES(
                '$this->name', '$this->date', '$this->organiser', '$this->description', '$this->city', '$this->location', '$this->image'); 	
        ";

        if(mysqli_query($db, $sql)) {
                return true;
        } else {
                echo mysqli_error($db);
                return false;
        }
    }

    static function find_by_id($id) {
        global $db;
        $id = mysqli_real_escape_string($db, $id);

        $sql = "SELECT * FROM events WHERE id='{$id}';";
        if($result = mysqli_query($db, $sql)) {
            $data = mysqli_fetch_assoc($result);
            return $data;
        }
        return false;
    }

}