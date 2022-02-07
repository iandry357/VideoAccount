<?php
    function HTMLcenter($text){
	return "<center>$text</center>";
    }

    function HTMLtag($tag,$attributes=array()){
	    $res="\n<".$tag;
	    foreach($attributes as $k=>$v)
	       $res.=" $k=\"$v\"";
	    $res.=">";
	    return $res;
    }

    /*
     * For self-closing tag
     */
    function HTMLsctag($tag,$attributes=array()){
	    $res="\n<".$tag;
	    foreach($attributes as $k=>$v)
	        $res.=" $k=\"$v\"";
	    $res.="/>";
	    return $res;
    }

    function HTMLclosingtag($tag){
    	return "</$tag>";
    }

    function HTMLforeach($lines){
        $res="";
        foreach($lines as $line)
            $res.=$line;
        return $res;
    }

    function HTMLimg($url,$width,$heigth){
    	     return HTMLsctag("img",array("src"=>$url,"width"=>$width,"heigth"=>$heigth));
    }
    function HTMLlink($link,$text){
        return HTMLtagblock("a",array("href"=>$link),$text);
    }
    
    function HTMLWikipediaPage($title){
        return "http://www.wikipedia.org/wiki/".str_replace(" ","_",$title);

    }
    
    function HTMLlinkNewPage($link,$text){
        return HTMLtagblock("a",array("href"=>$link,"target"=>"_blank"),$text);
    }
    
    function HTMLtable($lines,$attributes=array()){
        return HTMLtagblock("table",$attributes,HTMLforeach($lines));
    }

    function HTMLtr($cols,$attributes=array()){
    	return HTMLtagblock("tr",$attributes,HTMLforeach($cols));
    }

    function HTMLtd($content,$attributes=array()){
    	return HTMLtagblock("td",$attributes,$content);
    }

    function HTMLth($content,$attributes=array()){
    	return HTMLtagblock("th",$attributes,$content);
    }

    function HTMLpre($content){
    	return HTMLtagblock("pre",array(),$content);
    }
    function HTMLtagblock($tag,$attributes,$content){
    	return HTMLtag($tag,$attributes).$content.HTMLclosingtag($tag);
    }

    function HTMLform($action,$method,$content){
    	return HTMLtagblock("form",array("action"=>$action,"method"=>$method),$content);
    }
    function HTMLbr(){
    	return "<br/>";
    }

    /* type: "submit", "text", "email", ... defines the way the <input> element behaves
     * name: used to collect the data
     * value: text that is displayed or returned if we're in a GET form
     * placeholder: text that is displayed within the field but is not user input
     * required: (boolean) if the field must be filled to validate or not
     * classes: for the CSS
     */
    function HTMLinput($type,$name,$value,$placeholder=null,$required=false, $classes=''){
        if ($type == "hidden" or $type == "submit") {
            return HTMLsctag(
                "input",
                array(
                    "type" => $type,
                    "name" => $name,
                    "value" => $value,
                    "class" => $classes
                )
            );
        } else {
            $array = array(
                "type" => $type,
                "name" => $name,
                "value" => $value,
                "placeholder" => $placeholder,
                "class" => $classes
            );
            if ($required) {
                $array['required'] = true;
            }
            return HTMLsctag("input", $array);
        }
    }

    function HTMLp($content,$attributes=array()){
        return HTMLtagblock("p",$attributes,$content);
    }
    function HTMLdiv($content,$attributes=array()){
        return HTMLtagblock("div",$attributes,$content);
    }
    function HTMLi($content,$attributes=array()){
        return HTMLtagblock("i",$attributes,$content);
    }

    function HTMLh1($content){
        return HTMLtagblock("h1",array(),$content);
    }
    function HTMLh2($content){
        return HTMLtagblock("h2",array(),$content);
    }
    function HTMLh3($content){
        return HTMLtagblock("h3",array(),$content);
    }

    function HTMLtextarea($name,$cols,$rows,$content,$placeholder=""){
        return HTMLtagblock("textarea",array(
                                "name"=>$name,
                                "id"=>$name,
                                "placeholder" => $placeholder,
                                "class"=>"form-control",
                                "cols"=>$cols,
                                "rows"=>$rows),
                            $content);
    }

    /**
     * Creates a html <select> element with options retrieved from database
     * @param string $name ⇾ the name to use in DOM, usually "answer" to allow PHP to retrieve the user reply
     * @param array $optionsArray ⇾ an array containing values from the arg1 query, used to fill the option values
     */
    function HTMLselect($name, $optionsArray)
    {
        $selectContent = "";
        foreach ($optionsArray as $option) {
            // $selectContent .= "<option value='" . $option . "'>" . $option . "</option>";
            $selectContent .= HTMLtagblock("option", array("value" => $option['value']), $option['text']);
        }
        return HTMLtagblock("select", array("name" => $name), $selectContent);
    }

    /**
     * Creates a list of radio button using values from $optionsArray
     * @param string $name ⇾ the name to use in DOM, usually "answer" to allow PHP to retrieve the user reply
     * @param array $optionsArray ⇾ an array containing values and their text from the arg1 query, used to fill the radio values & appearance
     * @param boolean $addInput ⇾ true if a open radio button is needed (ie custom answer), false if not
     */
    function HTMLradio($name, $optionsArray, $addInput)
    {
        $radioContent = "";
        foreach ($optionsArray as $option) {
            $radioContent .= HTMLdiv(
                HTMLtag("input", array(
                    "class" => "form-check-input",
                    "type" => "radio",
                    "name" => $name,
                    "id" => htmlspecialchars($option['value']),
                    "value" => htmlspecialchars($option['value'])
                )).HTMLtagblock("label", array(
                    "for" => htmlspecialchars($option['value']),
                    "class" => "form-check-label"
                ), $option['text']),
                array("class" => "form-check")
            );
        }
        if ($addInput) {
            $radioContent .= "<div class='input-group col-6 offset-3'><div class='input-group-prepend'><div class='input-group-text'><input type='radio' name='".$name."' id='customAnswer'></div></div><input type='text' class='form-control' onkeyup='$(\"#customAnswer\").val($(this).val());'></div>";
        }
        return HTMLp($radioContent, array());
    }

    /**
     * Used to create a list of tasks.
     * Creates a div containing a task and its answer possibilities as radio buttons
     * @param string $name ⇾ the name to use in DOM, usually "answer", to which we add the task id to build a JSON on submit
     * @param array $tasks ⇾ an array containing tasks which each contains the task id and the text to display in the div
     * @param array $optionsArray ⇾ an array containing values and their text from the arg2 query, used to fill the radio values & appearance
     */
    function HTMLradiolist($name, $tasks, $optionsArray) {
        $tempStr = "";
        foreach ($tasks as $task) {
            $radioList = "";
            foreach ($optionsArray as $option) {
                // Using user defined class or the default one for the display
                $classes = "";
                if ($option['class'] !== null && strlen($option['class']) > 0) {
                    $classes = "btn btn-".$option['class']." badge badge-".$option['class'];
                } else {
                    $classes = "btn btn-secondary badge badge-secondary";
                }
                // Adding a radio disguised as a button to the possible answers
                $attributesArray = array(
                    "type" => "radio",
                    "id" => $name."-".$task['id'],
                    "name" => $name."-".$task['id'],
                    "value" => htmlspecialchars($option['value']),
                    "data-task-id" => $task['id'], // The expected task id if it was alone
                    "data-feedback" => $option['request_feedback'] // Wheiter or not the user needs to give feedback considering what he chose
                );
                if ($option['request_feedback'] == true) {
                    // The database table in which feedback will be stored
                    $attributesArray["data-target-feedback"] = $option['target_table'];
                }
                $radioList .= HTMLtagblock(
                    "label",
                    array("class" => $classes),
                    HTMLtag("input",
                        $attributesArray
                    ).$option['text']
                );
                // Creating a modal with textarea to retrieve feedback from the user
                if ($option['request_feedback'] == true) {
                    $radioList .= genAnswerFeedbackModal($task['id']);
                }
            }
            $tempStr .= HTMLtagblock("li",
                array(
                    "class" => "list-group-item d-flex justify-content-between align-items-center"
                ),
                HTMLdiv($task['text']).HTMLdiv(
                    $radioList, array(
                        "class" => "btn-group-toggle",
                        "data-toggle" => "buttons"
                    )
                )
            );
        }
        return HTMLtagblock("ul", array("class" => "list-group"), $tempStr);
    }


    /**
     * Creates a list of text where each list element has a bound textarea.
     * Can be used to ask more than 1 question in 1 task.
     * @param string $name ⇾ the name to use in DOM, usually "answer", to which we add the task id to build a JSON on submit
     * @param array $tasks ⇾ an array containing tasks which each contains the task id and the text to display
     */
    function HTMLtextarealist($name, $tasks, $help) {
        $divLeftContent = "";
        $divRightContent = "";
        // Value calculated in order to make the textareas approximately the same height as the list
        $rowNumber = round((count($tasks) * 54 - 38) / 24 + 1, 0, PHP_ROUND_HALF_UP) - ($help ? 2 : 0);
        foreach ($tasks as $task) {
            $divLeftContent .= HTMLtagblock("a",
                array(
                    "class" => "list-group-item list-group-item-action flex-column align-items-start",
                    "role" => "tab",
                    "data-toggle" => "list",
                    "href" => "#tab-".$task['id']
                ),
                HTMLp($task['text'], array("class" => "mb-1"))
            );
            $divRightContent .= HTMLdiv(
                HTMLtagblock("textarea",
                    array(
                        "class" => "form-control".($help ? " mb-2" : ""),
                        "id" => $name."-".$task['id'],
                        "data-task-id" => $task['id'],
                        "rows" => $rowNumber
                    ), ""
                ).($help ? HTMLResourceInputs($task['id']) : ""),
                array(
                    "class" => "tab-pane fade",
                    "id" => "tab-".$task['id']
                )
            );
        }
        $divLeft = HTMLdiv(
            HTMLdiv($divLeftContent,
                array(
                    "class" => "list-group text-left",
                    "role" => "tablist"
                )
            ), array("class" => "col-6")
        );
        $divRight = HTMLdiv(
            HTMLdiv($divRightContent,
                array("class" => "tab-content")
            ), array("class" => "col-6")
        );
        return HTMLdiv($divLeft.$divRight, array("class" => "row"));
    }

    /**
     * Creates cards allowing to choose one answer per question/request.
     * Can be used to ask more than 1 question in 1 task.
     * @param string $name ⇾ the name to use in DOM, usually "answer", to which we add the task id to build a JSON on submit
     * @param array $tasks ⇾ an array containing tasks which each contains the task id and the text to display
     * @param array $optionsArray ⇾ an array containing values and their text from the arg2 query, used to fill the radio values
     */
    function HTMLmultiplecards($name, $tasks, $optionsArray) {
        $toReturn = "";
        $i = 0;
        $content = "";
        foreach ($tasks as $task) {
            $i++;
            $subContent = "";
            foreach ($optionsArray as $option) {
                if ($task['id'] == $option['id']) {
                    $textContent = $option['text'];
                    if (isset($option['help'])) {
                        $textContent = HTMLHelpingResource($option);
                    }
                    $subContent .= HTMLtagblock("li", array(
                            "class" => "list-group-item d-flex justify-content-between align-items-center"
                        ), HTMLdiv($textContent, array("class" => "col-md-10 p-0")).HTMLdiv(
                            HTMLtagblock(
                                "label",
                                array("class" => "btn btn-secondary btn-sm"),
                                HTMLtag("input",
                                    array(
                                        "type" => "radio",
                                        "id" => $name."-".$task['id'],
                                        "name" => $name."-".$task['id'],
                                        "value" => htmlspecialchars($option['value']),
                                        "data-task-id" => $task['id']
                                    )
                                )."Choose"
                        ), array("class" => "btn-group-toggle")
                    ));
                }
            }
            $content .= HTMLdiv(
                HTMLdiv(
                    HTMLdiv($task['text'], array("class" => "card-header")).HTMLtagblock("ul", array("class" => "list-group list-group-flush"), $subContent),
                    array("class" => "card")
                ), array("class" => "col-md-6")
            );
            // We want 2 cards for each row on wide screens
            if ($i % 2 == 0) {
                $toReturn .= HTMLdiv($content."&nbsp;", array("class" => "row text-left"));
                $content = "";
            }
        }
        // In the case where the number of tasks is even, avoid duplicates
        if (strlen($content) > 0) {
            $toReturn .= HTMLdiv($content, array("class" => "row text-left"));
        }
        return $toReturn;
    }

    /**
     * Creates an HTML script tag containing a timer to force the submit
     * @param $unixtime ⇾ unix timestamp of the remaining duration
     */
    function HTMLtimer($unixtime) {
        if ($unixtime < 0) {
            $unixtime = 10;
        }
        $timer = HTMLdiv("Time left : <strong id='countdown'></strong>").HTMLinput("hidden", "unixtime", $unixtime);
        return $timer;
    }


    /**
     * Generate a clickable image and a hidden input that stores the base64 image value.
     * The embedded part is managed by the diagram.js file, as iframe should be created when the page is loaded to reduce load time.
     */
    function HTMLdrawio($image = null) {
        $hiddenInput = HTMLtag("input", array("type" => "hidden", "id" => "diagramData", "name" => "answer"));
        $imageAttributes = array(
            "onclick" => "edit(this);",
            "id" => "diagramImage",
            "class" => "diagramImage",
            "data-default" => ($image === null ? "true" : "false"),
            "src" => ($image === null ? "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMsAAAAVCAYAAADozxpsAAADsXRFWHRteGZpbGUAJTNDbXhmaWxlJTIwbW9kaWZpZWQlM0QlMjIyMDE5LTA4LTIwVDExJTNBMzMlM0E0NC40OTlaJTIyJTIwaG9zdCUzRCUyMnd3dy5kcmF3LmlvJTIyJTIwYWdlbnQlM0QlMjJNb3ppbGxhJTJGNS4wJTIwKFgxMSUzQiUyMExpbnV4JTIweDg2XzY0KSUyMEFwcGxlV2ViS2l0JTJGNTM3LjM2JTIwKEtIVE1MJTJDJTIwbGlrZSUyMEdlY2tvKSUyMENocm9tZSUyRjc0LjAuMzcyOS4xNzIlMjBTYWZhcmklMkY1MzcuMzYlMjBWaXZhbGRpJTJGMi41LjE1MjUuNDglMjIlMjB2ZXJzaW9uJTNEJTIyMTEuMS41JTIyJTIwZXRhZyUzRCUyMlQ0UmtBTzJCUkpZN05fQ0x6TEFyJTIyJTIwcGFnZXMlM0QlMjIxJTIyJTNFJTNDZGlhZ3JhbSUyMGlkJTNEJTIyZGpZUl9aQVZGNWc2STBhV0FZeDYlMjIlMjBuYW1lJTNEJTIyUGFnZS0xJTIyJTNFalpQQmJvTXdESWFmaHVNa0lLTnJqeXZ0dHN0T25iU3pCeTVFQ3dRRnQ2VjklMkJobGlTbEZWYVJka2Y3R1QlMkJQOURvTktxZTNmUWxKODJSeFBFWWQ0RmFoUEU4VklwJTJGdmJnN01FcWpEMG9uTTQ5aWlhdzB4Y1VHQW85NkJ6YldTRlphMGczYzVqWnVzYU1aZ3ljczZkNTJkNmElMkJha05GSGdIZGhtWWUlMkZxdGN5cGxyUGhsNGglMkJvaTNJOE9WcXMlMkZFb0ZZN0ZNMHBhUTI5TU5VdHRBcGM1YThsSFZwV2g2N1VaZGZOJTJGYmc5WHJ4UnpXOUo4RzBmMEk1aUN6cFVabnY0eEtkRGpvMnV2b0VLalBvRGROUSUyQkdna3Z2VGVSU0ZzT01qMXlWVmhrSEVvY05XWCUyQkJuS0FnNWI2eXVhWkElMkJXUWZKaGdrY3lMYmUzcjRCakM1cWpnM3UlMkI2Mk82RWl6NnElMkJDeVRaTTJ3WXlYUmRmZmJKNWVtYXl0elhKSTRrU3ptVW03c2J1b1M3UlZXMSUyQnBXZ3JKSGZtRW1sUXk4UzN5QXRWQ3pIc05Qa2RMWVdWTjE2UHhvSThzZUs2OWVRQ0IyTEVtRTZHRDJzM2Y0M2ElMkZnRSUzRCUzQyUyRmRpYWdyYW0lM0UlM0MlMkZteGZpbGUlM0XmLh3UAAAHtklEQVR4Xu2aBaxlRRKGv8EXWdyXJbhbkCC7ARaX4O5uIbgvLATJ4uziEnQXDe5kcffg7u7usEA+Uh0OTb/b580wj5nkVDLJ5N4+far/rr/qr7pvEJ11CHQItEJgUKtV3aIOgQ4BOrJ0QdAh0BKBjiwtgeqWdQh0ZOlioEOgJQIdWVoC1S3rEBhcsvwFuA2YEHgPuBl4DNi2BaTfAWsBF7ZYOw9wHzAF8FqL9d2SYReB5r33J16GmROVyDIpsCewHPAn4NMgwgnA+eF5TpY5gc+BZ1ucbHglywbAjcMhaYcVv5v33p94aRFSA7MkJ8tUwJ1RLQ4CHgXGAlYEdgYOBfYGcrL0x9vfkywjA9/2x9lYK07vAssDdw/G8/19ZHD9zN8z0H73Omd/7r2/eDXX/1bY/cqHnCxXA7MDM0VFaT5ghloa2BiYtyLDNgT+DvwZeAHYHzgvNmuC5vvPBMw0CwMfZh4mGbYkIHlnA14FdgD0VRsf+FdUwhGiCu4F3Brf3wDcG+9YAhgJGBHYB9gknn8ROBo4uXBLrv0EGB34ErgIWB+YADgcWCwSypNxZqtPX9YLl/8DWwC7AW8Ai7Y4mz4dCawAjAu8BPwTOCvOWPK7hlfJ952AreI+ld3nAnsA+lyymYFTgDniLCbai4G1Q37nMqy2v/F4EjBX3P92oXKU/fpSuuNe2Oiz6mnZuM/NQkVdAfwDOBGYEfgm7uQmH2iSRbAFQsf/XaF2r55lIcDN1wnZYqD/N8hwB9Aki5VqZeCvwFuFdyayXBeVzb7lGGApYOJYfxXwA+CBPwgy6/8MwMtBKhOA4LjPO8CuwJbAqkGu+QH38bMkNZvuKEcl6QKNyuKFa+sB7weoRwD6/FDhLDVcvgCeBrYGngI+Cp96nc0kIVHEQ/9WAf4TiUFVUPK7hlfu+koR4L7DPnUW4PoIquMK5zSmPMcjwKbAaIASXnWyZoEstf1Nbiaz++NuxwROBxZpkC8l+eYd17DZJc5gUjemPJeY+c8WxFjzfN6byfwXZJk7HPpbBHsvvvQii0wfIy4x7SFIj0dgJrJMHkHrXmbEkiWyWBG8IE1iWTUky9jAM8C0wPONDR4ELgEOAK6MKiAhkr0eQJ3a+MwMbSa0UuSWB92sAWqTPD7zHHBZEDvfo4bLZ8CxkbF9droWZzNwRg2ypvdZnQ0Ez5b73WbP3O9RgPGyZCa2+muFzU3VkSr5w/Glycr/r14gS23/BQGTrHcjAZsxkPYr3XENm0SWcYDvY18T9hmNO1gtks8f/L5ZWdKBrAT/K4buzx/2IosZwGC2QpVMsihlPKjVJ8mz0tpElimBV2JB+sz+yqC1dJbMQysZBdIAShcriA4tSiZp3Te3POjMhgaM1dgKkOwa4GvA73Or4WLwbR9B7rP2R7WzTQMcGLLY3tIq5ITSfSRe7nebPXO/xWvfyLZKON9hkrJCGUy5Wa2ddP6xgbOE/qoPstT2V7qdEwnY6qvl++V37JoaNpJl81Ag6Qzev+rAStO8g5940iSLh1OGqdkOLscSqXmqkeX26CtK20gWpdA90R+pQ9XWJSuNjptkURt7aWa+vN9J+wmkGUOZpln1DEwliwHfxvoii1np48YGyjwvVGmZm2TphYs+2RcoWTX1dO1sSoa3QwomGSu29oglsrTZM/fbwFFCmQCs2NoFgP1hiSwmwLODUOle7R+clpYqS23/tF/qGX2/cWg/0awszTt2TQ0byWJM2Jskkyz2oWKnpeTyK7L4pRnfRtuGyulP03RMHWg2V9/19TuLcsPmV+mUTKeUSfYykmXdkCuWaxtjZVrJamQRNGWY0qnZWFsdPLhZMCeL71GGKVNMDMmUhSYLK0NuOVnE5wlAiXBXLBZQhxkG0u6FPWq45GRJkqmvs5ndTRAS89J439QhBW2AS2Sp7Sleudl/2Z85VNHsIeyp/LxEliST1flJhs0XybFEltr+9ibGjZj7Xi1Js77IYhKrYTPEZJksLt/xquXdbGiZ9EJ2jCbZMtWrsgiW4EqQywF7IDPN4iHPmg2+APgOJZvTjtxqZJEQNneTAGtEI2jDa9n2vQZyiSxOnJzmeNm3BPkNOH04pOCHlcsm3mmW0siLUKo6KVMmWF0MUHskg8QEkFsNl5wsPt/rbFZmfXLqJDmVHTbSYmp18nwlv2t45X7bgyk3bfBt1iVhmhTZs+VmD6Jkts9wYGL82Cgr70vTsNr+vtNm+1pgm5g8Hh9ZPw0M8ju26tWwGWKyeHABFmhLr6Nfy6cBfVRMk1xT+wXfkayTCZ93kmEQSRgtn7e7VjDNPpbOprUhixpd35QYAmulkejpLwRKZDHI1eEbAROFTDstnkvNXh4EBqB63ESwTBDUqZtB5DP6btD2+h2mFy4lstTO5h05mPAMvt9JmpMcJzyHAfsFcZp+1/bMzy0xHO+rJtJgxAosvg9EEsyfUVpLXPtgnzEZ2kOmUW9zdNxmf0kpQaaP6iJpJKMJ0pF06Y5r2Ij3EMmwQqLoPuoQ+N0RUPpZLexTNJXEm5Fg/XOoAbHB/duwAXGue0mHQCBgf+jY2AGIZjW1t3bAU+oxhwpwHVmGCqzdpr8xAkpAJa9SXRlvNVHa+dvdgFlHlgGDunvR8I7Aj5f4rDSDRAOwAAAAAElFTkSuQmCC" : $image)
        );
        $baseImage = HTMLtag("img", $imageAttributes);
        return $hiddenInput.$baseImage;
    }


    /**
     * Generate multiple radio buttons components for images
     * @param string $name ⇾ the name to use in DOM, usually "answer" to allow PHP to retrieve the user reply
     * @param array $possibleChoices ⇾ the images retrieved from the database
     */
    function HTMLimagechoice($name, $possibleChoices) {
        $radioContent = "";
        foreach ($possibleChoices as $option) {
            $radioContent .= HTMLdiv(
                HTMLtagblock("label", array(
                    "for" => $option['id'],
                    "class" => "diagramImage"
                ), HTMLtag("input",
                        array(
                            "hidden" => "true",
                            "type" => "radio",
                            "name" => $name,
                            "id" => $option['id'],
                            "value" => $option['id']
                        )
                    )
                    .HTMLtag("img", array("src" => $option['value']))
                )                
            );
        }
        return HTMLp($radioContent);
    }


    /**
     * Create an input group which allow the user to enter a justification (link resource) for his answer
     * @param int $id the id of the task
     */
    function HTMLResourceInputs($id) {
        return HTMLdiv(
            HTMLdiv(HTMLtagblock("span", array("class" => "input-group-text"), "Optional"), array("class" => "input-group-prepend"))
            .HTMLinput("text", "help-link-".$id, "", "Helping ressource (link)", false, "form-control text-left")
            .HTMLinput("text", "help-page-".$id, "", "Page (if exist)", false, "form-control text-left"),
            array("class" => "input-group")
        );
    }

    /**
     * Create a div with the link to the user justification for his answer and the page number (if there is one)
     * @param string $resource the string containing the json object
     */
    function HTMLHelpingResource($resource) {
        $help = json_decode($resource['help']);
        $textContent = HTMLdiv(
            HTMLdiv($resource['text'])
            .HTMLdiv(
                HTMLtagblock(
                    "a",
                    array("href" => $help->link, "target" => "_blank"), "Justification"
                ).(strlen($help->page) > 0 ? " (page ".$help->page.")" : ""),
                array("class" => "text-muted")
            )
        );
        return $textContent;
    }

/**
 * BSMakeListGroup
 * 
 * Create a Bootstrap ListGroup with as many item as the array, each sub-array providing text and link.
 *
 * @param array $list
 *            an array of 4-strings array, each one providing text and link for each item
 * @param string $active
 *            the text of the active item
 */
function BSMakeListGroup($list, $active,$conn=null,$query=null){
    
    $res='<div class="container">';
    $res.='<div class="list-group">';
    //debug(implode(" ",$list));
    foreach ($list as list($item,$itemid,$link,$detail)){
        $res.='<div class="row">';
        $res.='<div class="col-sm-8">';
        if ($itemid==$active){
            $res.="<a href=\"$link\" class=\"list-group-item list-group-item-action active list-group-item-dark \">".$item."</a>";
        }
        else
            $res.="<a href=\"$link\" class=\"list-group-item list-group-item-action\">".$item."</a>";

        $res.='</div>'; // for col
        $normalizedItem="modal".str_replace(" ", "-", $itemid); // building the id of the modal
        if($detail){
            $res.='<div class="col-sm-4">';
            $res.='<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#'.$normalizedItem.'">';
            $res.="Details $normalizedItem";
            $res.='</button>';
            $res.='</div>';
        }
            
        $res.='</div>'; // for row
        if ($detail){
        $res .= '<!-- Modal -->';
        $res .= '<div class="modal fade" id="'.$normalizedItem.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
        $res .= '  <div class="modal-dialog" role="document">';
        $res .= '     <div class="modal-content">';
        $res .= '        <div class="modal-header">';
        $res .= '           <h5 class="modal-title" id="exampleModalLabel">'.$item.'</h5>';
        $res .= '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        $res .= '           <span aria-hidden="true">&times;</span>';
        $res .= '           </button>';
        $res .= '       </div>';
        $res .= '       <div class="modal-body">';
        if ($_SESSION['project'] == 'SPIPOLL'){

            $sqlModal = "select modal from ArtifactClass where id = 5002 or id = (select classid from Artifact where id = ".$itemid." )    ";
            // $sqlModal = "select modal from ArtifactClass where id =5002";

            $modalStmt = $conn->query($sqlModal);

            $modal=$modalStmt->fetch()["modal"];

            $res .= $modal;

        } else {
            $res .= showQueryAnswerAsString($conn, "Data", $query);
        }

        $res .= '       </div>';
        $res .= '       <div class="modal-footer">';
        $res .= '         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
        $res .= '         <button type="button" class="btn btn-primary">Save changes</button>';
        $res .= '       </div>';
        $res .= '     </div>';
        $res .= '  </div>';
        $res .= '</div>';
        }
    }
    $res .= "</div>";
    

    return $res;
}

/**
 * BSArtifactList
 *
 * List all artifact given in list as a Boostrap Listgroup. For each artifact, add a Modal with the detail of the tuple of the artifact.
 *
 * @param array $list
 *            an array of 4-strings array, each one providing text and link for each item
 * @param string $active
 *            the text of the active item
 * @param $conn a valid PDO connection
 * @param $query the query to display
 * @return the HTML string           
 */

function BSArtifactList($list,$active,$conn=null,$query=null){
    // mnhnDebug(' USER FOR DEBUG '.$_SESSION['id']);
    $res='<div class="container">';
    $res.='<div class="list-group">';
    foreach ($list as list($item,$itemid,$link,$detail)){
        mnhnDebug($item.' - '.$itemid.' - '.$link.' - '.$detail);
        $res.='<div class="row">';
        
        $res.='<div class="col-sm-8">';
        if ($itemid==$active){
            $res.="<a href=\"$link\" class=\"list-group-item list-group-item-action active list-group-item-dark \">".$item."</a>";
        }
        else
            $res.="<a href=\"$link\" class=\"list-group-item list-group-item-action\">".$item."</a>";
            
        $res.='</div>'; // for col
        
        $normalizedItem="modal".str_replace(" ", "-", $itemid); // building the id of the modal
        
        if ( isset($_SESSION['lang']) ){

            if ($_SESSION['lang'] == 'FR'){
                $showDetails = "Afficher détails";
                $refresh = "Charger d'autres photos";
            }elseif ($_SESSION['lang'] == 'EN') {
                $showDetails = "show details";
                $refresh = "Refresh";
            } 
    
        }
        else {
            $showDetails = "show details";
            $refresh = "Refresh";
        }
             
             if($detail){
                $res.='<div class="col-sm-3">';
                // mnhnDebug($normalizedItem);
                $res.='<button type="button" class="btn btn-dark" data-toggle="modal" data-target="#'.$normalizedItem.'">';
                $res.=$showDetails;
                $res.='</button>';
                $res.='</div>';

                $res.='<div class="col-md-1">';
                // mnhnDebug($normalizedItem);
                // $res.='<button type="button"  class="btn btn-dark"  data-toggle="modal">';

                $newlink = $link."&refresh=10&page=1";

                $myArr = ["Résolution si conflit entre vo(tre)(s) réponse(s) et celles des autres","Résolution si conflit avec vo.tre.s réponse.s et celles des autres","Résolution si conflit avec vos réponses et celles des autres"];

                if (!in_array($item, $myArr) ){
                    if ($itemid==$active){
                        $res.="<a href=\"$newlink\" style='height:37px;width:250px' class=\"btn btn-dark\">";
                    }else{
                        $res.="<a href=\"$newlink\" style='height:37px;width:250px' class=\"btn btn-dark disabled\" aria-disabled=\"false\">";
                    }

                    $res.=$refresh. "</a>";
                $res.='</button>';
                $res.='</div>';
                }

                //     if ($itemid==$active){
                //         $res.="<a href=\"$newlink\" style='height:37px;width:250px' class=\"btn btn-dark\">";
                //     }else{
                //         $res.="<a href=\"$newlink\" style='height:37px;width:250px' class=\"btn btn-dark disabled\" aria-disabled=\"false\">";
                //     }
                
                
                 
                // $res.=$refresh. "</a>";
                // $res.='</button>';
                // $res.='</div>';
            }
            
        $res.='</div>'; // for row
        
            if ($detail){
                $res .= '<!-- Modal -->';
                $res .= '<div class="modal fade" id="'.$normalizedItem.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
                $res .= '  <div class="modal-dialog modal-xl" role="document">';
                $res .= '     <div class="modal-content">';
                $res .= '        <div class="modal-header">';
                $res .= '           <h5 class="modal-title" id="exampleModalLabel">'.$item.'</h5>';
                $res .= '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                $res .= '           <span aria-hidden="true">&times;</span>';
                $res .= '           </button>';
                $res .= '       </div>';
                $res .= '       <div class="modal-body">';
                debug("itemid:$itemid");
                $res .= showQueryAnswerAsString($conn, "Details", $query." where id=$itemid");
                $res .= '       </div>';
                $res .= '       <div class="modal-footer">';
                $res .= '         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                //$res .= '         <button type="button" class="btn btn-primary">Save changes</button>';
                $res .= '       </div>';
                $res .= '     </div>';
                $res .= '  </div>';
                $res .= '</div>';
            }
            
    }
    $res .= "</div>";
    
    
    return $res;
    
}
