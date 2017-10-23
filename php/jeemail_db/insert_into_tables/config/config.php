<?php
    define('DB_HOST', 'localhost');
    define('DB_USER', 'test');
    define('DB_PASS', 'test');
    define('DB_NAME', 'jeemail');
 ?>

 <!-- [BUG] Potentially unwanted behavior if creating a nested label for a parent that does not exist
 Steps to reproduce:
   1) Click on "Create new label" in the label section of the left sidebar
   2) In the new dialogue, enter anything within the first text input.
   3) Check the "Nest label under" checkbox input.
   4) Ignoring the first option element, use the Web Console to change the value attribute of
      any other option element associated with the select element to a label that does not exist.
   5) Select the newly altered option element.
   6) Click the create button.

 Expected result:
  I would expect to be disallowed to take this action and for Gmail to produce a dialogue stating
  that it is unable to nest a label within a label that does not exist.

 Actual result:
   As seen in the picture, a label is created in the format of parent/newLabel, with
   <parent> being the selected nonexistant label and <newLabel> being what was entered
   into the first text input (step #2). -->
