<?php
/**
 * Builds response object and returns it.
 * Great for rendering custom responses, such as
 * with component\adapter\view\Json object.
 */
$response = new \stdClass();
$response->status = 'ok';
$response->data = NULL;

// Set overriding status.
if (isset($status))
{
  $response->status = $status;
}

// Set overriding data packet.
if (isset($data))
{
  $response->data = $data;
}

// Note, that any streamed output will be ignored when using component\adapter\view\Json.
// Only returned responses will be output.
// This is not the case if using this view as 'html'
// You can bypass this functionality and output debug data by triggering core default:
// 
// \Core::fault('arg1', 'arg2', ...);
echo "This will be ignored when using JSON template";

return $response;