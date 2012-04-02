<?php
if(empty($_POST['subject'])||empty($_POST['message']))
    throwError("All fields must be completed");

sendEmail(null, $_POST['subject'], $_POST['message']);
redirect("Thank you for sending us feedback!");