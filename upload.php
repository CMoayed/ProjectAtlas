<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if file was uploaded without errors
    if (isset($_FILES["resumeFile"]) && $_FILES["resumeFile"]["error"] == 0) {
        $allowed = [
            "pdf" => "application/pdf",
            "doc" => "application/msword",
            "docx" => "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
        ];
        $filename = $_FILES["resumeFile"]["name"];
        $filetype = $_FILES["resumeFile"]["type"];
        $filesize = $_FILES["resumeFile"]["size"];

        // Verify file extension
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

        // Verify file size - 5MB maximum
        $maxsize = 5 * 1024 * 1024;
        if ($filesize > $maxsize) die("Error: File size is larger than the allowed limit.");

        // Verify MIME type of the file
        if (in_array($filetype, $allowed)) {
            // Ensure upload directory exists
            $uploadPath = "upload/";
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            // Check whether file exists before uploading it
            $newFilePath = $uploadPath . $filename;
            if (file_exists($newFilePath)) {
                echo $filename . " already exists.";
            } else {
                if (move_uploaded_file($_FILES["resumeFile"]["tmp_name"], $newFilePath)) {
                    echo "Your file was uploaded successfully.";
                } else {
                    echo "Error: There was an issue uploading your file.";
                }
            }
        } else {
            echo "Error: There was a problem with your file. Please try again.";
        }
    } else {
        echo "Error: " . $_FILES["resumeFile"]["error"];
    }

    // Capture and print the user's name
    if (isset($_POST['name'])) {
        $name = htmlspecialchars($_POST['name']);
        echo "<br>Name: $name";
    }
}
?>
